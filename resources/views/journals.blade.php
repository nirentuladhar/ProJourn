@extends('public.master.layout')

@section('main-content')


<div id="app">
    <div class="grid-x">
        <div class="medium-3 large-2 cell">
            <div class="panel-journals">
                <ul class="panel-journals__name">
                    <li><a href="#"><img> Engineering Project X </a> </li>
                    <li><a href="#"><img> Secret Revolution 3 </a> </li>
                    @if (Auth::check())
                        <li><a href="#"> {{Auth::user()->firstname}} {{Auth::user()->lastname}}</a></li>
                        <li><a href="/logout"> Logout </a></li>
                    @endif
                </ul>
            </div> 
        </div>
        <div class="medium-3 large-2 cell">
            <div class="panel-entries">

                <example></example>
            
            </div>
        </div>
        <div class="medium-6 large-8 cell">
            <div class="panel-entry">
            
            </div>
        </div>
    </div>
</div>
@endsection
