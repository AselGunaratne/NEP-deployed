<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Reporting\Http\Controllers\ReportingController;
use General\Http\Controllers\GeneralController;

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

Route::get('/home', [GeneralController::class, 'pending'])->middleware('auth','verified');

Route::get('/home/main', function () {
    return view('home');
});
Route::get('/search', [UserController::class, 'search']);

Route::get('/markAsRead', function(){
    auth()->user()->unreadNotifications->markAsRead();
    return redirect()->back();
});

//CHART ROUTES
Route::get('/get-user-chart-data',[ReportingController::class, 'getMonthlyUserData']);
Route::get('/get-processItem-formType-chart-data',[ReportingController::class, 'getProcessItemFormTypeData']);