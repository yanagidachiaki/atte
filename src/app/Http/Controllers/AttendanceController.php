<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rest;
use App\Models\Work;
use Carbon\Carbon;

class AttendanceController extends Controller
{
      public function index()
{
  return view('index');
}
      public function checkin()
{
  return view ('attendance');
}

      public function start(Request $request)
      {
        // 現在の時間を取得
        $currentTime = Carbon::now();

       // 勤怠開始時間を保存
        $works = Work::create([
        'start' => $currentTime,
        'user_id' => auth()->id(), // 現在ログインしているユーザーのID
    ]);

       return view('attendance', compact('works'));
      }
}