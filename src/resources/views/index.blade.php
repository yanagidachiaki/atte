@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
 <form class="form__wrap" action="{{ route('start') }}" method="post">
        @csrf
        <div class="form__item">
                <button class="form__item-button" type="submit" name="start_work">勤務開始</button>
                <button class="form__item-button" type="submit" name="start_work" disabled>勤務開始</button>
          
        </div>
        <div class="form__item">
                <button class="form__item-button" type="submit" name="end_work">勤務終了</button>
                <button class="form__item-button" type="submit" name="end_work" disabled>勤務終了</button>
        
        </div>
        <div class="form__item">          
                <button class="form__item-button" type="submit" name="start_rest">休憩開始</button>
                <button class="form__item-button" type="submit" name="start_rest" disabled>休憩開始</button>
            
        </div>
        <div class="form__item">            
                <button class="form__item-button" type="submit" name="end_rest">休憩終了</button>
                <button class="form__item-button" type="submit" name="end_rest" disabled>休憩終了</button>
            
        </div>
@endsection