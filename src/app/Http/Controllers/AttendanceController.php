<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Rest;
use App\Models\Work;
use Carbon\Carbon;

class AttendanceController extends Controller
{
      public function index()
            {
               $name = Auth::user()->name;
               return view ('index', ['name' => $name]);
            }
           
      public function start(Request $request)
            {
                // 現在の時間を取得
                 $currentTime = Carbon::now();
                 $userId = auth()->id();

                // すでに勤務を開始しているか確認（同じ日に1回のみ）
                 $existingWork = Work::where('user_id', $userId)
                            ->whereDate('start', $currentTime->toDateString())
                            ->first();

                if ($existingWork) {
                 return redirect()->back();
                }

                // 勤怠開始時間を保存
                Work::create([
                'start' => $currentTime,
                'user_id' => $userId,
                ]);

                 // 勤務開始セッションをセット
                session(['work_started' => true, 'work_stopped' => false, 'rest_started' => false]);

                return redirect()->back();
      }


public function stop(Request $request)
{
    // 現在の時間を取得
    $currentTime = Carbon::now();
    $userId = auth()->id();

    // その日の勤務開始があるか確認
    $existingWork = Work::where('user_id', $userId)
                        ->whereDate('start', $currentTime->toDateString())
                        ->whereNull('stop') // stopがまだ登録されていない場合のみ
                        ->first();

    // 勤怠終了時間を保存 
    if ($existingWork) {
        $existingWork->update(['stop' => $currentTime]);
    }

    // 未終了の休憩がある場合は終了処理を追加
    $existingRest = Rest::where('user_id', $userId)
                        ->whereNull('stop') // 休憩終了していない
                        ->first();

    if ($existingRest) {
        // 休憩終了時間を保存し、休憩時間の計算（1分単位で繰り上げ）
        $restStart = Carbon::parse($existingRest->start);
        $restDurationSeconds = $currentTime->diffInSeconds($restStart);
    
        // 1分未満なら1分に繰り上げる
        $restDurationMinutes = ceil($restDurationSeconds / 60) * 60;

        $existingRest->update([
            'stop' => $currentTime,
            'total' => ($existingRest->total ?? 0) + $restDurationSeconds,
        ]); // 累計休憩時間を追加
    }

    // 勤務終了セッションをセット
    session(['work_started' => false, 'work_stopped' => true]);

    return redirect()->back();
}


       public function checkin(Request $request)
      {
       // 初期値は今日の日付
    $displayDate = $request->input('displayDate') ?? Carbon::today()->format('Y-m-d');
    $date = Carbon::parse($displayDate);

    // 前日、翌日ボタンが押された時の日付処理
    if ($request->has('prevDate')) {
        $date = $date->subDay();  // 前の日付へ
    } elseif ($request->has('nextDate')) {
        $date = $date->addDay();  // 次の日付へ
    }

    // 勤怠データを取得（日付でフィルタリング）
    $worksQuery = Work::whereDate('start', $date)
                      ->orWhereDate('stop', $date);

    // 勤怠データをページネーションで取得
     $works = $worksQuery->simplePaginate(5);  // 1ページに5件表示

    // 勤務時間と休憩時間の計算
    foreach ($works as $work) {
        // 勤務時間の計算
        if ($work->stop && $work->start) {
            $workStart = Carbon::parse($work->start);
            $workEnd = Carbon::parse($work->stop);
            $workDurationSeconds = $workStart->diffInSeconds($workEnd);
        } else {
            // 勤務終了時刻がない場合の勤務時間（途中）
            $workStart = Carbon::parse($work->start);
            $workEnd = Carbon::now(); // 勤務中の場合、現在時刻を終了時間として扱う
            $workDurationSeconds = $workStart->diffInSeconds($workEnd);
        }

        // 休憩時間の計算
        $totalRestSeconds = 0; // 初期化

        // その日の休憩時間を合計
        $rests = Rest::where('user_id', $work->user_id)
                     ->whereDate('start', $date)
                     ->get();
        
        foreach ($rests as $rest) {
            $restEndTime = $rest->stop ? Carbon::parse($rest->stop) : Carbon::now(); // 休憩終了が未設定なら現在時刻を使用
            $totalRestSeconds += $restEndTime->diffInSeconds(Carbon::parse($rest->start));
        }

        // 勤務時間を計算（勤務時間 - 休憩時間）
        $work->total_work_seconds = $workDurationSeconds - $totalRestSeconds;

        // 休憩時間を秒単位に変換してセット
        $work->total_rest_seconds = $totalRestSeconds;
    }

    // 勤務開始・終了が1日1回かどうかのチェック
    $userId = auth()->id();
    $hasStartedWorkToday = Work::where('user_id', $userId)
                                ->whereDate('start', $date)
                                ->exists();
    $hasStoppedWorkToday = Work::where('user_id', $userId)
                                ->whereDate('stop', $date)
                                ->exists();

    return view('attendance', compact('works', 'date'))
           ->with('displayDate', $date->format('Y-m-d'))
           ->with('hasStartedWorkToday', $hasStartedWorkToday)
           ->with('hasStoppedWorkToday', $hasStoppedWorkToday);
      }     

    public function restart() // 休憩開始ボタンを打刻
{
    $currentTime = Carbon::now();
    $userId = auth()->id();

    // 勤務が開始されているかを確認
    if (!session('work_started') || session('work_stopped')) {
        return redirect()->back();
    }

    // 休憩がすでに開始されていないか確認
    $existingRest = Rest::where('user_id', $userId)
                    ->whereNull('stop') // 休憩が終了していない
                    ->first();

    if ($existingRest) {
        return redirect()->back();
    }

    // 休憩開始時間を保存
    Rest::create([
        'start' => $currentTime,
        'user_id' => $userId,
        'total' => 0, // 初回の休憩開始時には total を 0 に設定
    ]);

    // 休憩開始ボタンが押された状態をセッションに保存
    session(['rest_started' => true]);

    return redirect()->back();
}
 

      public function restend()  //休憩終了ボタンを打刻
      {
          $currentTime = Carbon::now();
          $userId = auth()->id();

        // 休憩開始済みで、終了していないものを取得
        $existingRest = Rest::where('user_id', $userId)
                            ->whereNull('stop') // 休憩終了していない
                            ->first();

          if (!$existingRest) {
          return redirect()->back();
        }

         // 休憩終了時間を保存し、休憩時間の計算（1分単位で繰り上げ）
         $restStart = Carbon::parse($existingRest->start);
         $restDurationSeconds = $currentTime->diffInSeconds($restStart);
    
         // 1分未満なら1分に繰り上げる
         $restDurationMinutes = ceil($restDurationSeconds / 60)*60 ;

         $existingRest->update([
        'stop' => $currentTime,
        'total' => ($existingRest->total ?? 0) + $restDurationSeconds, ]);// 累計休憩時間を追加
    
          
        // セッション情報をクリア
         session(['rest_started' => false]);

        return redirect()->back();
        }
}