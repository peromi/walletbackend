<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan; 
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
    return view('welcome');
});

Route::get('/cal', function(){
    $answer = 12000 + floatval(("-346.90"));

    return $answer;
});

Route::get('/migrate', function () {
    Artisan::call('migrate --seed');
    Artisan::call('storage:link');
    // Artisan::call('view:clear');
    // Artisan::call('config:clear');
    // Artisan::call('route:clear');
    return "Database migrate";
});

Route::get('/reset', function () {
    Artisan::call('migrate:fresh');
    Artisan::call('storage:link');
    // Artisan::call('view:clear');
    // Artisan::call('config:clear');
    // Artisan::call('route:clear');
    return "Database reset";
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
