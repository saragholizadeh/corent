<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RegulationController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Home\ShowCategoryController;

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

Route::prefix('admin')->group(function(){

    Route::get('/' , [DashboardController::class , 'index'])->name('dashboard');

    Route::resource('category', CategoryController::class);

    Route::resource('subcategory' ,SubCategoryController::class);

    Route::resource('post', PostController::class);

    Route::resource('regulation', RegulationController::class);

});

Route::get('/home' , [HomeController::class , 'index'])->name('home');


Route::get('/category/{id}' , [ShowCategoryController::class , 'index'])->name('show_category');
