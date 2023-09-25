<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

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

Route::controller(LoginController::class)->group(function() {
    Route::get('/','login_form');
    Route::post('login','login');
    Route::get('logout','logout');
});

Route::middleware(['admin'])->group(function() {
    Route::controller(ProductController::class)->group(function() {
        Route::prefix('product')->group(function() {
            Route::get('list','list');
            Route::get('detail/{id}','detail');
            Route::get('create','create');
            Route::post('create/process','create_process');
            Route::get('restock/{id}','restock');
            Route::post('restock/{id}/process','restock_process');
            Route::get('edit/{id}','edit');
            Route::get('delete-photo/{id}','del_photo');
            Route::post('edit/{id}/process','edit_process');
            Route::delete('delete/{id}','delete');  
        });
    });
    Route::prefix('category')->controller(CategoryController::class)->group(function() {
        Route::get('list','list');
        Route::get('create','create');
        Route::post('create/process','create_process');
        Route::get('edit/{id}','edit');
        Route::post('edit/{id}/process','edit_process');
        Route::delete('delete/{id}','delete');
    });
    Route::prefix('user')->controller(UserController::class)->group(function() {
        Route::get('list','list');
        Route::get('create','create');
        Route::post('create/process','create_process');
        Route::get('edit/{id}','edit');
        Route::post('edit/{id}/process','edit_process');
        Route::delete('delete/{id}','delete');
    });
    Route::prefix('admin')->controller(AdminController::class)->group(function() {
        Route::get('list','list');
        Route::get('create','create');
        Route::post('create/process','create_process');
        Route::get('edit/{id}','edit');
        Route::post('edit/{id}/process','edit_process');
        Route::delete('delete/{id}','delete');
    });
    Route::controller(ProfileController::class)->group(function() {
        Route::get('profile','profile');
        Route::get('edit-profile','edit_profile');
        Route::post('edit-profile/process','edit_profile_process');
        Route::get('change-password','change_password');
        Route::post('change-password/process','change_password_process');
    });
});