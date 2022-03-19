<?php

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

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::prefix('google')->group(function(){

Route::get('login',[App\Http\Controllers\Auth\LoginController::class, 'googleLogin'])->name('google-login');
Route::any('callback',[App\Http\Controllers\Auth\LoginController::class,'googleCallback'])->name('google-callback');
});


Route::get('/users', [App\Http\Controllers\HomeController::class, 'index'])->name('user.index');
Route::get('/users/list', [App\Http\Controllers\HomeController::class, 'getUsers'])->name('user.getUsers');
Route::get('/users/create', [App\Http\Controllers\HomeController::class, 'create'])->name('user.create');
Route::post('/users/save', [App\Http\Controllers\HomeController::class, 'save'])->name('user.save');
Route::get('/users/edit/{id}', [App\Http\Controllers\HomeController::class, 'edit'])->name('user.edit');
Route::post('/users/update/{id}', [App\Http\Controllers\HomeController::class, 'update'])->name('user.update');
Route::get('/users/delete/{id}', [App\Http\Controllers\HomeController::class, 'delete'])->name('user.delete');

Route::get('/get-states/{countryId}', [App\Http\Controllers\HomeController::class, 'getStates'])->name('states');
Route::get('/get-cities/{stateId}', [App\Http\Controllers\HomeController::class, 'getCities'])->name('cities');


