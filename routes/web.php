<?php

use App\Http\Controllers\ExecutiveController;
use App\Http\Controllers\RecordsController;
use Illuminate\Http\Request;
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

//Route::get('/', function () {
//    return view('welcome');
//});


Route::get('/', [ExecutiveController::class, 'index'])->name('index');

Route::get('/generate_refresh_token', [ExecutiveController::class, 'generate_refresh_token'])->name('generate_refresh_token');

Route::post('/add_deals', [RecordsController::class, 'add_deals'])->name('add_deals');

Route::post('/add_task', [RecordsController::class, 'add_task'])->name('add_task');

