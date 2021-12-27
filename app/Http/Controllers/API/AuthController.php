<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
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

      //  $this->middleware('verified', ['except' => ['unauthorized', 'register']]);

        $this->emailVerificatoinController = $emailVerificatoinController;
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);

            if(!$token = auth('api')->attempt($credentials)){
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user = User::where('email', $request->input('email'))->first();
            $user->role = $user->withRoleId();

            $result = array_merge(['user'=> $user],$this->respondWithToken($token));

            $this->emailVerificatoinController->verify($request);

            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function logout()
    {
        try {
            auth('api')->logout();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|confirmed|min:6',
                'role' => 'required|numeric|between:2,3'
            ]);

            if($validator->fails()){
                return response()->json(["error" => $validator->errors()->toJson()], 400);
            }

            DB::beginTransaction();

            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            ));

            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $request->role,
            ]);

         //   $this->emailVerificatoinController->sendVerificationEmail(null, $user);
            event(new Registered($user));

            DB::commit();

            $credentials = $request->only(['email', 'password']);

            $token = auth('api')->attempt($credentials);

            $user->role = $user->withRoleId();

            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user,
                'access_token' => $token
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function user()
    {
        try {
            $user = auth('api')->user();
            $user->role = $user->withRoleId();
            return response()->json($user);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 60*60
        ];
    }

    public function unauthorized() {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

}

