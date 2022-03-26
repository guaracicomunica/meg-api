<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\UserStatusGamefication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $this->authorize('viewAny', Auth::user());
            $users = User::paginate(10);
            return response()->json($users);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            $this->authorize('view', $user);
            return response()->json($user);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource about User Status Gamification.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUserStatusGamification($id)
    {
        try {
            if(Auth::user()->id != $id) return response()->json([], 401);

            $userStatusGamification = UserStatusGamefication::where('user_id',$id)->first();

            return response()->json($userStatusGamification);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $this->authorize('update', $user);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|between:2,100',
                'password' => 'sometimes|string|confirmed|min:6',
                'avatar_path' => ['sometimes','file', 'max:2000', 'mimes:png,jpeg,jpg'],
            ]);

            if($request->has('email')){
                return response()->json(['error' => 'O e-mail não pode ser atualizado'], 400);
            }

            if($validator->fails()){
                return response()->json(['error' => $validator->errors()->toJson()], 400);
            }

            if(isset($request->avatar_path))
                $user->uploadAvatar($request->avatar_path);

            $user->update($validator->validated());

            return response()->json([
                'message' => 'User successfully updated',
                'user' => $user,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Update user avatar in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAvatar(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $this->authorize('update', $user);

            $validator = Validator::make($request->all(), [
                'avatar_path' => ['sometimes','file', 'max:2000', 'mimes:png,jpeg,jpg'],
            ]);

            if($validator->fails()){
                return response()->json(['error' => $validator->errors()->toJson()], 400);
            }

            if(isset($request->avatar_path))
                $user->uploadAvatar($request->avatar_path);

            $user->save();

            return response()->json([
                'message' => 'User successfully updated',
                'user' => $user,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            $this->authorize('delete', $user);

            $user->delete();

            return response()->json([
                'message' => 'User successfully deleted',
                'user' => $user,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
