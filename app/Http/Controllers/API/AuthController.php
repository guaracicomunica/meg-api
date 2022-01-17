<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\UserRole;
use App\Models\UserStatusGamefication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    protected $emailVerificatoinController;

    public function __construct(EmailVerificationController $emailVerificatoinController)
    {
        $this->middleware(
            'auth:api',
            ['except' => ['unauthorized', 'login', 'register']]
        );

        $this->emailVerificatoinController = $emailVerificatoinController;
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->only(['email', 'password']);

            if(!$token = auth('api')->attempt($credentials)){
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $this->emailVerificatoinController->verify($request);

            $user = User::where('email', $request->input('email'))->first();

            $user->role = $user->role_id;

            $result = array_merge(['user'=> $user],$this->respondWithToken($token));

            return response()->json($result);
        } catch (\Throwable $e) {

            return response()->json(['error' => $e->getMessage()],
                $e->getCode() != 2002 && $e->getCode() != 0
                    ? $e->getCode()
                    : 500);
        }
    }

    /**
     * @throws ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|confirmed|min:6',
                'role' => 'required|numeric|between:2,3',
                'avatar' => 'sometimes|file|mimes:jpeg,png,svg'
            ]);

            if($validator->fails()){
                return response()->json(["error" => $validator->errors()->toJson()], 400);
            }

            DB::beginTransaction();

            $user = User::create(array_merge(
                $validator->validated(),
                [
                    'password' => bcrypt($request->password),
                    'role_id' => $request->role,
                ]
            ));

            if(isset($request->avatar))
                $user->uploadAvatar($request->avatar);

            if($user->isStudent())
            {
                UserStatusGamefication::firstOrCreate(['user_id' => $user->id]);
            }

            event(new Registered($user));

            DB::commit();

            $credentials = $request->only(['email', 'password']);

            $token = auth('api')->attempt($credentials);

            $user->role = $user->role_id;

            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user,
                'access_token' => $token
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function user(): JsonResponse
    {
        $user = auth('api')->user();
        $user->role = $user->role_id;
        return response()->json($user);
    }

    protected function refreshToken(): JsonResponse
    {
        $token = auth('api')->refresh();
        $result = $this->respondWithToken($token);
        return response()->json($result);
    }

    protected function respondWithToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()
        ];
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function unauthorized(): JsonResponse
    {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
