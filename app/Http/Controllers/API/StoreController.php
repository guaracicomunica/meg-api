<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\ClaimedSkill;
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
    public function buySkill(Request $request)
    {
        try {
            if(!Auth::user()->isStudentOfClassroom($request->get('classroom_id')))
            {
                throw new AccessDeniedHttpException();
            }

            $skill = Skill::with('classroom')->findOrFail($request->get('skill_id'));

            if($skill->classroom_id != $request->get('classroom_id')) {
                return response()->json(['errors' => ["skill" => "A turma {$skill->classroom->name} não contém esta habilidade"]], 400);
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

            $alreadyClaimedSkill = ClaimedSkill::
                where('user_id', Auth::user()->id)
                ->where('skill_id', $skill->id)
                ->first();

            if($alreadyClaimedSkill != null)
            {
                DB::rollBack();
                return response()->json(['errors' => ['skill' => 'Você já comprou esta habilidade']], 400);
            }

             ClaimedSkill::create([
                'user_id' => Auth::user()->id,
                'skill_id' => $skill->id,
            ]);

            DB::commit();

            return response()->json(['message' => 'Habilidade requerida com sucesso']);
        } catch(\Exception $ex)
        {
            DB::rollback();
            throw $ex;
        }
    }

    public function getTeacherNotifications(Request $request): JsonResponse
    {
        $user = Auth::user();

        if(!$user->isTeacher()) {
            new AccessDeniedHttpException();
        }

        $notifications = ClaimedSkill::with([
            'claimer',
            'skill',
            'skill.classroom' => function($query) use ($user) {
                $query->where('creator_id', $user->id);
            }
        ])->paginate($request->get('per_page'));

        $result = NotificationResource::collection($notifications)->response()->getData();

        return response()->json($result);
    }
}
