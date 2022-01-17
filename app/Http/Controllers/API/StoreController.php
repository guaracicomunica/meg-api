<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
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
