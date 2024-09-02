@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

@section('content')
    <form class="header__wrap" action="" method="post">
        @csrf
        <button class="date__change-button" name="prevDate"><</button>
        <input type="hidden" name="displayDate" value="">
        <p class="header__text"></p>
        <button class="date__change-button" name="nextDate">></button>
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
         
             <tr class="table__row">
                <th class=""></th>
                <th class="">{{$works->start->format('H:i:s')}}</th>
                <th class=""></th>
                <th class=""></th>
                <th class=""></th>
             </tr>
           
        </table>
    </div>
   
@endsection
