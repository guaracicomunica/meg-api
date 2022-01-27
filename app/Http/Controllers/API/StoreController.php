<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassroomResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\StudentSkillNotBoughtResource;
use App\Http\Resources\StudentSkillResource;
use App\Models\Classroom;
use App\Models\User;
use App\Models\UserClassroom;
use App\Models\UserSkill;
use App\Models\Notification;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StoreController extends Controller
{
    /**
     * Create a new TopicController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @throws \Exception
     */
    public function buySkill(int $id)
    {
        try {
            $skill = Skill::with('classroom')->findOrFail($id);

            if(!Auth::user()->isStudentOfClassroom($skill->classroom->id))
            {
                throw new AccessDeniedHttpException();
            }

            $globalUserStatus = Auth::user()->gamefication;

            if($globalUserStatus->coins < $skill->coins)
            {
                return response()->json(['errors' => ['coins' => 'Você não tem moedas suficientes para comprar esta habilidade']], 400);
            }

            DB::beginTransaction();

            $globalUserStatus->coins -= $skill->coins;

            if($globalUserStatus->coins < 0)
            {
                $globalUserStatus->coins = 0;
            }

            $globalUserStatus->save();

            $alreadyUserSkill = UserSkill::
                where('user_id', Auth::user()->id)
                ->where('skill_id', $skill->id)
                ->first();

            if($alreadyUserSkill != null)
            {
                DB::rollBack();
                return response()->json(['errors' => ['skill' => 'Você já comprou esta habilidade']], 400);
            }

             UserSkill::create([
                'user_id' => Auth::user()->id,
                'skill_id' => $skill->id,
            ]);

            DB::commit();

            return response()->json(['message' => 'Habilidade comprada com sucesso']);
        } catch(\Exception $ex)
        {
            DB::rollback();
            throw $ex;
        }
    }

    public function claimSkill(int $id) {
        $skill = Skill::with('classroom')->findOrFail($id);

        $user = Auth::user();

        if(!$user->isStudentOfClassroom($skill->classroom->id))
        {
            throw new AccessDeniedHttpException();
        }

        $userSkill = UserSkill::where('user_id', $user->id)
            ->where('skill_id', $skill->id)
            ->first();

        if($userSkill == null)
        {
            return response()->json(['errors' => ['skill' => 'Antes de reivindicar uma habilidade, você deve comprá-la']], 400);
        }

        if($userSkill->claimed == true)
        {
            return response()->json(['errors' => ['skill' => 'Você já reivindicou esta habilidade']], 400);
        }

        $userSkill->claimed = true;

        $userSkill->save();

        return response()->json(['message' => 'Habilidade reivindicada com sucesso']);

    }


    /**
     * Skills that already bought
     *
     * @return JsonResponse
     */
    public function getStudentSkillsAlreadyBought(): JsonResponse
    {
        $user = Auth::user();

        if(!$user->isStudent()) {
            throw new AccessDeniedHttpException();
        }

        $userSkills = UserSkill::with('skill')->where('user_id', $user->id)->paginate();

        $result = StudentSkillResource::collection($userSkills)->response()->getData();

        return response()->json($result);
    }

    /**
     * Skills that were not bought
     *
     * @return JsonResponse
     */
    public function getStudentSkillsNotBought(): JsonResponse
    {
        $user = Auth::user();

        if(!$user->isStudent()) {
            throw new AccessDeniedHttpException();
        }

        //get skills that were not bought
        $skillsNotBought = DB::select("
            SELECT s.*  FROM `skills` s
            INNER JOIN `classrooms` c ON c.id = s.classroom_id
            INNER JOIN `users_classrooms` uc ON uc.classroom_id = c.id AND uc.user_id = ".Auth::user()->id."
            WHERE s.id
                NOT IN (SELECT us.skill_id FROM `users_skills` us WHERE us.user_id = ".Auth::user()->id.");");


        //get all ids of classrooms that user is in
        $classrooms_id =  UserClassroom::where('user_id', Auth::user()->id)
                    ->groupBy('classroom_id')
                    ->pluck('classroom_id');

        //find this student's classrooms
        $get_classrooms = Classroom::findOrFail($classrooms_id);

        $classrooms = [ 'classrooms' =>
            ClassroomResource::collection($get_classrooms)->response()->getData()->data ?? null ];

        $skills = StudentSkillNotBoughtResource::collection($skillsNotBought)->response()->getData();

        $result = array_merge([ 'skills' => $skills->data ?? null ], $classrooms);

        return response()->json( $result );
    }


    public function getTeacherNotifications(Request $request): JsonResponse
    {
        $user = Auth::user();

        if(!$user->isTeacher()) {
            new AccessDeniedHttpException();
        }

        $notifications = UserSkill::with([
            'claimer',
            'skill',
            'skill.classroom' => function($query) use ($user) {
                $query->where('creator_id', $user->id);
            }
        ])->where('claimed', 1)
            ->paginate($request->get('per_page'));

        $result = NotificationResource::collection($notifications)->response()->getData();

        return response()->json($result);
    }
}
