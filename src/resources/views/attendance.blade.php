
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance.css?1') }}">

@section('content')
    
    <form class="header__wrap" action="{{ route('checkin') }}" method="GET">
         <button class="date__change-button" name="prevDate" type="submit">&lt;</button>
         <input type="hidden" name="displayDate" value="{{ $displayDate }}">
         <p class="header__text">{{ $displayDate }}</p>
         <button class="date__change-button" name="nextDate" type="submit">&gt;</button>
    </form>

    <div class="table__wrap">
        <table class="attendance__table">
            <tr class="table__row">
                <th class="">名前</th>
                <th class="">勤務開始</th>
                <th class="">勤務終了</th>
                <th class="">休憩時間</th>
                <th class="">勤務時間</th>
            </tr>
           
    @foreach ($works as $work)
       <tr>
        <td>{{ $work->user->name }}</td> <!-- 名前を表示 -->
        <!-- 勤務開始 -->
        <td>{{ \Carbon\Carbon::parse($work->start)->format('H:i:s') }}</td>
        <!-- 勤務終了 -->
        <td>{{ $work->stop ? \Carbon\Carbon::parse($work->stop)->format('H:i:s') : '' }}</td> <!-- 終了時刻がない場合は空白 -->
        <!-- 休憩時間 -->
        <td>{{ sprintf('%02d:%02d:%02d', floor($work->total_rest_seconds / 3600), floor(($work->total_rest_seconds % 3600) / 60), $work->total_rest_seconds % 60) }}</td>
        <!-- 勤務時間 -->
        <td>{{ sprintf('%02d:%02d:%02d', floor($work->total_work_seconds / 3600), floor(($work->total_work_seconds % 3600) / 60), $work->total_work_seconds % 60) }}</td>  
       </tr>
    @endforeach

   
        </table>
    </div>
    
    <!-- Blade File (attendance.blade.php) -->
<div class="pagination__wrap">
    <div class="pagination pagination-lg justify-content-center">
        {{ $works->links() }} <!-- Bootstrap Styled Pagination -->
    </div>
</div>
   
@endsection
