<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;

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

Route::get('/', function () {
    return redirect()->route('admin.login');
}); 

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin/', 'as' => 'admin.'],function(){

    //Admin Auth Route
    Route::get('login',[AuthController::class,'login']);
    Route::post('login', [AuthController::class, 'authenticate'])->name('login');

    //Admin 
    Route::middleware(['auth'])->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        //User Route
        Route::resource('users', UserController::class);
        //Role Route
        Route::resource('roles', RoleController::class);
        //Permission
        Route::resource('permissions', PermissionController::class);
    
    });

});



