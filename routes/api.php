<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/get-token', [App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class, 'getToken'])->name('getToken');
// Route::middleware(['auth'])->group(function () {

    Route::post('/get-token-all', [App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class, 'getTokenData'])->name('getTokenData');
    Route::post('/send-notification', [App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class, 'sendNotification'])->name('send.notification');

// });

Route::prefix('v1')->group(function(){
    Route::post('login',[App\Http\Controllers\AuthController::class,'login']);
    Route::post('register', [App\Http\Controllers\AuthController::class,'register']);
});