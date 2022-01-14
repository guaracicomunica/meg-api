<?php

use App\Http\Controllers\API\ActivityController;
use App\Http\Controllers\API\ClassroomController;
use App\Http\Controllers\API\TopicController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use \App\Http\Controllers\API\UserController;
use \App\Http\Controllers\API\CategoryController;
use \App\Http\Controllers\API\RoleController;
use \App\Http\Controllers\API\PostController;
use \App\Http\Controllers\API\VerifyEmailController;
use \App\Http\Controllers\API\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//auth
Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('verified');
    Route::post('user', [AuthController::class, 'user'])->middleware('verified');
});



// Verify email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend link to verify email
Route::post('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');


//users
Route::group([
    'prefix' => 'users'
], function ($router) {
    Route::get('', [UserController::class, 'index']);
    Route::get('{id}', [UserController::class, 'show']);
    Route::put('{id}', [UserController::class, 'update']);
    Route::delete('{id}', [UserController::class, 'destroy']);
});


//roles
Route::group([
    'prefix' => 'roles'
], function ($router) {
    Route::get('', [RoleController::class, 'index']);
});


//classes
Route::group([
    'prefix' => 'classes'
], function ($router) {
    Route::get('', [ClassroomController::class, 'index']);
    Route::post('', [ClassroomController::class, 'store']);
    Route::post('enrollment', [ClassroomController::class, 'enrollment']);
    Route::post('enrollment/cancellation', [ClassroomController::class, 'enrollmentCancellation']);
    Route::get('{id}/participants', [ClassroomController::class, 'participants']);
    Route::get('{id}/students', [ClassroomController::class, 'students']);
    Route::get('{id}/teachers', [ClassroomController::class, 'teachers']);
    Route::get('{id}', [ClassroomController::class, 'getById']);
});

//posts
Route::group([
    'prefix' => 'posts',
], function ($router) {
    Route::get('', [PostController::class, 'index']);
    Route::get('{id}', [PostController::class, 'show']);
    Route::post('', [PostController::class, 'store']);
});

//activities
Route::group([
    'prefix' => 'activities',
], function ($router) {
    Route::get('', [ActivityController::class, 'index']);
    Route::get('{id}', [ActivityController::class, 'show']);
    Route::post('', [ActivityController::class, 'store']);
    Route::post('delivery', [ActivityController::class, 'delivery']);
    Route::post('grade', [ActivityController::class, 'grade']);
});


//comments
Route::group([
    'prefix' => 'comments',
], function ($router) {
    Route::get('{id}', [CommentController::class, 'index']); //publicos
    Route::get('{id}/privates', [CommentController::class, 'getAllPrivate']); //privados
    Route::post('', [CommentController::class, 'store']);
    Route::delete('{id}',[CommentController::class, 'delete']);
});

//topics
Route::group([
        'prefix' => 'topics' ,
], function ($router) {
    Route::get('', [TopicController::class, 'index']);
    Route::get('{id}', [TopicController::class, 'getOne']);
    Route::get('classroom/{id}', [TopicController::class, 'getByClassroomId']);
    Route::post('', [TopicController::class, 'store']);
});
