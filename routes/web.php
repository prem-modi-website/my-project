<?php

use App\Repositories\DashboardRepository;
use App\Services\DashboardService;
use App\Services\AuthServices;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/',[App\Http\Controllers\AuthController::class,'index'])->name('login');
Route::post('/authenticate',[App\Http\Controllers\AuthController::class,'authenticate'])->name('authenticate');
Route::get('/logout',[App\Http\Controllers\AuthController::class,'logout'])->name('logout');

Route::get('/dashboard',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class , 'dashboard'])->name('Dashboard');

Route::middleware(['auth'])->group(function () {
    
    /* Use for super admin and admin start */
    Route::middleware('can:SuperAdminAndAdmin')->group(function () {
        Route::get('/dashboard',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class , 'dashboard'])->name('Dashboard');
        
        // Route::post('setting', 'Backend\Superadmin\UserController@setting')->name('setting');
        // Route::get('settingpage', 'Backend\Superadmin\UserController@settingpage')->name('settingpage');

        Route::get('/monthly-stats/{id}',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class , 'chart'])->name('chart');
        
        Route::get('/chart/{id}',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class , 'chartDifference'])->name('chartDifference');
        
        Route::get('/scan-statistics/{id}',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'dynamictable'])->name('dynamictable');
        // Route::get('/maintables/{id}','DashboardController@index')->name('maintables');
        
        Route::get('/export-scan-records/{id}',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'export'])->name('export');
        
        Route::post('import', [App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'import'])->name('import');
        
        Route::get('view', [App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'listview'])->name('listview');

    });
        
    /* Use for super admin and admin end */

    /* Use for super admin and developer start */
    
    Route::middleware('can:SuperadminAndDeveloper')->group(function () {
        Route::get('/dashboard',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'dashboard'])->name('Dashboard');
        Route::get('/monthly-stats/{id}',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'chart'])->name('chart');
        
        Route::get('/chart/{id}',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'chartDifference'])->name('chartDifference');
        
        
        Route::get('/scan-statistics/{id}',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'dynamictable'])->name('dynamictable');
        // Route::get('/maintables/{id}','DashboardController@index')->name('maintables');
        
        Route::get('/export-scan-records/{id}',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'export'])->name('export');
        
        Route::post('import', [App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'import'])->name('import');
        
        Route::get('view', [App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'listview'])->name('listview');
    
       
        
    });
    /* Use for super admin and developer end */
    Route::middleware('can:Superadmin')->group(function () {       
        // Route::get('/commmonblade',function(){
        //     return view('common.common1');
        // });
        Route::get('common1', [App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'main'])->name('main');

        Route::get('deactivate/{id}', [App\Http\Controllers\Backend\Superadmin\UserController::class ,'deactivate'])->name('deactivate');
        
        Route::resource('usermanagement', App\Http\Controllers\Backend\Superadmin\UserController::class);
        
    });
    
    Route::get('/dashboard',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class , 'dashboard'])->name('Dashboard');
    Route::post('/update-data',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class , 'updatedata'])->name('UpdateData');
    Route::get('/monthly-stats/{id}',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'chart'])->name('chart');
        
    Route::get('/chart/{id}',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'chartDifference'])->name('chartDifference');
    
    
    Route::get('/scan-statistics/{id}',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'dynamictable'])->name('dynamictable');
    // Route::get('/maintables/{id}','DashboardController@index')->name('maintables');
    
    Route::get('/export-scan-records/{id}',[App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'export'])->name('export');

    
    Route::post('import', [App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'import'])->name('import');
    
    Route::get('view', [App\Http\Controllers\Backend\Admin\Dashboard\DashboardController::class ,'listview'])->name('listview');

    Route::get('settingpage', [App\Http\Controllers\Backend\Superadmin\UserController::class ,'settingpage'])->name('settingpage');
    
    Route::post('setting', [App\Http\Controllers\Backend\Superadmin\UserController::class ,'setting'])->name('setting');
    
    Route::post('/save-token', [App\Http\Controllers\Backend\SuperAdmin\Migration\MigrationUtilityController::class, 'saveToken'])->name('save-token');

    Route::get('migration', [App\Http\Controllers\Backend\SuperAdmin\Migration\MigrationUtilityController::class,'create'])->name('migration-create');
    // Route::get('migration', 'Backend\SuperAdmin\Migration\MigrationUtilityController@create')->name('migration-create');
    Route::post('migration-store',[App\Http\Controllers\Backend\SuperAdmin\Migration\MigrationUtilityController::class,'store'])->name('migration.store');

});
Route::fallback(function () {
    return view('error.404');
})->name('notfound');


Route::get('/common',function(){
    return view('backend.admin.dashboard.dashboard');
});

Route::get('/data', function () {
    $details = "Mansi yes";
    \Log::info("check");
    event(new \App\Events\SendMessage($details));
    dd('Event Run Successfully.');
});