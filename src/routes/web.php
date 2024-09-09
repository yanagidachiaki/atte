<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

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

Route::middleware('auth')->group(function () {
    Route::get('/', [AttendanceController::class, 'index']);  //打刻画面
    Route::get('/attendance', [AttendanceController::class, 'checkin'])->name('attendance');  //一覧画面
    Route::get('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('checkin');  //一覧画面
    Route::post('/attendance/start', [AttendanceController::class, 'start'])->name('start'); //勤務開始
    Route::post('/attendance/stop', [AttendanceController::class, 'stop'])->name('stop');//勤務終了
    Route::post('/attendance/restart', [AttendanceController::class, 'restart'])->name('restart'); //休憩開始
    Route::post('/attendance/restend', [AttendanceController::class, 'restend'])->name('restend'); //休憩終了
});
