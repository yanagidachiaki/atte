@php
    // 現在のユーザーの勤務データを取得
    $currentWork = \App\Models\Work::where('user_id', auth()->id())
                                  ->whereDate('start', \Carbon\Carbon::today())
                                  ->first();

    // 現在のユーザーの勤務データと休憩データを取得
    $currentWork = \App\Models\Work::where('user_id', auth()->id())
                                  ->whereDate('start', \Carbon\Carbon::today())
                                  ->first();

    $currentRest = \App\Models\Rest::where('user_id', auth()->id())
                                  ->whereDate('start', \Carbon\Carbon::today())
                                  ->whereNull('stop') // 休憩中かどうか
                                  ->first();
@endphp


@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection
@section('content')
<h2>{{ $name }}さんお疲れ様です!</h2>
<div class="contents">
 
 <div class="contents_button">
       <form class="form__wrap" action="{{ route('start') }}" method="post">
        @csrf
        <div class="form__item">
                <button class="form__item-button" type="submit" name="start_work" {{ $currentWork ? 'disabled' : '' }}>勤務開始</button>
        </div>
         </form>
        <form class="form__wrap" action="{{ route('stop') }}" method="post">
        @csrf
        <div class="form__item">
                <button class="form__item-button" type="submit" name="stop_work" {{ !$currentWork || $currentWork->stop ? 'disabled' : '' }}>勤務終了</button>
        </div>
</form>
<form class="form__wrap" action="{{ route('restart') }}" method="post">
        @csrf
        <div class="form__item">            
                <button class="form__item-button" type="submit" name="start_rest"   @if(!session('work_started') || session('work_stopped') || session('rest_started')) disabled @endif>休憩開始</button>
        </div>  
</form>
<form class="form__wrap" action="{{ route('restend') }}" method="post">
        @csrf
        <div class="form__item">            
                <button class="form__item-button" type="submit" name="end_rest"  @if(!session('work_started') || !session('rest_started')) disabled @endif>休憩終了</button>
        </div>
</form>
  </div> 
</div> 
@endsection