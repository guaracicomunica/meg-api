<?php

use App\Http\Controllers\API\ClassroomController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use \App\Http\Controllers\API\UserController;
use \App\Http\Controllers\API\CategoryController;
use \App\Http\Controllers\API\RoleController;
use \App\Http\Controllers\API\PostController;
use \App\Http\Controllers\API\VerifyEmailController;


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



Route::group([
    'prefix' => 'users'
], function ($router) {
    Route::get('', [UserController::class, 'index']);
    Route::get('{id}', [UserController::class, 'show']);
    Route::put('{id}', [UserController::class, 'update']);
    Route::delete('{id}', [UserController::class, 'destroy']);
});

Route::group([
    'prefix' => 'roles'
], function ($router) {
    Route::get('', [RoleController::class, 'index']);
});

Route::group([
    'prefix' => 'classes'
], function ($router) {
    Route::get('', [ClassroomController::class, 'index']);
    Route::post('', [ClassroomController::class, 'store']);
    Route::post('enrollment', [ClassroomController::class, 'enrollment']);
    Route::get('{id}/participants', [ClassroomController::class, 'participants']);
});


Route::group([
    'prefix' => 'posts',
], function ($router) {
   Route::get('', [PostController::class, 'index']);
   Route::post('activities', [PostController::class, 'storeActivity']);
   Route::post('news', [PostController::class, 'storeNew']);

});
