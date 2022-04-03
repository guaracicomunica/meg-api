<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Handlers\EnrollClassroomHandler;
use App\Http\Handlers\EnrollmentCancellationHandler;
use App\Http\Handlers\GetAllClassroomHandler;
use App\Http\Handlers\GetByIdClassroomHandler;
use App\Http\Handlers\GetParticipantsClassroomHandler;
use App\Http\Handlers\GetStudentsClassroomHandler;
use App\Http\Handlers\GetTeachersClassroomHandler;
use App\Http\Handlers\ManageClassroomHandler;
use App\Http\Requests\EnrollClassroomRequest;
use App\Http\Requests\EnrollmentCancellationRequest;
use App\Http\Requests\ManageClassroomRequest;
use App\Http\Resources\RankingResource;
use App\Models\Level;
use App\Models\Skill;
use App\Models\UserClassroom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ClassroomController extends Controller
{
    /**
     * Create a new ClassroomController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /****
     * Get all classrooms
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $classes = GetAllClassroomHandler::handle($request);
        return response()->json($classes);
    }

    /****
     * Manage classroom - create and update as draft or not
     * @param ManageClassroomRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(ManageClassroomRequest $request) : JsonResponse
    {
        ManageClassroomHandler::handle($request->all());
        return response()->json([
            'message' => 'Classroom successfully managed'
        ], 201);
    }

    /***
     * Make an enrollment - user get in a classroom
     * @param EnrollClassroomRequest $request
     * @return JsonResponse
     */
    public function enrollment(EnrollClassroomRequest $request) : JsonResponse
    {
        EnrollClassroomHandler::handle($request->all());
        return response()->json([
            'message' => 'Enrollment successfully done',
        ], 201);
    }

    /***
     * @param EnrollmentCancellationRequest $request
     * @return JsonResponse
     */
    public function enrollmentCancellation(EnrollmentCancellationRequest $request): JsonResponse
    {
        EnrollmentCancellationHandler::handle($request->all());
        return response()->json([
            'message' => 'User is no longer participant of classroom',
        ]);
    }


    /****
     * Get all participants of a classroom
     * @param int $id
     * @return JsonResponse
     */
    public function participants(int $id) : JsonResponse
    {
        $result = GetParticipantsClassroomHandler::handle($id);
        return response()->json($result);
    }

    /****
     * Get all students of a classroom
     * @param int $id
     * @return JsonResponse
     */
    public function students(int $id) : JsonResponse
    {
        $result = GetStudentsClassroomHandler::handle($id);
        return response()->json($result);
    }

    /****
     * Get all teachers of a classroom
     * @param int $id
     * @return JsonResponse
     */
    public function teachers(int $id) : JsonResponse
    {
        $result = GetTeachersClassroomHandler::handle($id);
        return response()->json($result);
    }

    /****
     * Get classroom by id
     * @param int $id
     * @return JsonResponse
     */
    public function getById(int $id): JsonResponse
    {
        $result = GetByIdClassroomHandler::handle($id);
        return response()->json($result);
    }

    /****
     * Delete Skill of classroom
     * @param int $id
     * @return JsonResponse
     */
    public function removeSkill(int $classroom_id, int $id): JsonResponse
    {
        //validations to do this action
        if(!Auth::user()->isTeacherOfClassroom($classroom_id)) return response()
            ->json(['message' => 'É necessário ser professor da turma para realizar essa ação'], 401);

        $skill = Skill::findOrFail($id);

        $skill->delete();

        return response()->json(['message' => 'A habilidade foi removida da turma']);
    }


    /****
     * Delete Level of classroom
     * @param int $id
     * @return JsonResponse
     */
    public function removeLevel(int $classroom_id, int $id): JsonResponse
    {
        //validations to do this action
        if(!Auth::user()->isTeacherOfClassroom($classroom_id)) return response()
                ->json(['message' => 'É necessário ser professor da turma para realizar essa ação'], 401);

        $level = Level::findOrFail($id);

        $level->delete();

        return response()->json(['message' => 'A habilidade foi removida da turma']);
    }


        /****
     * Ranking of classroom
     * @param int $id
     * @return JsonResponse
     */
    public function ranking(Request $request, int $id): JsonResponse
    {
        //validations to do this action
        if(!Auth::user()->isMemberOfClassroom($id)) return response()
                ->json(['message' => 'É necessário ser membro da turma para realizar essa ação'], 403);


        $data = UserClassroom::
        with(['user', 'level'])
        ->whereHas('user', function($query) {
            $query->where('users.role_id', 3);
        })
        ->where('classroom_id', $id)
        ->orderBy('xp','desc')
        ->paginate($request->per_page);

        $result = RankingResource::collection($data)->response()->getData();

        return response()->json($result);
    }
}
