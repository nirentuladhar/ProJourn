@extends('public.master.layout')

@section('css')
    <link rel="stylesheet" href="/css/journals.css">
@endsection


@section('main-content')


<div id="xyz">
    <div class="grid-x">
        <div class="medium-3 large-2 cell">
            <div class="panel-left">
                <div class="journals">
                    <div class="journal-heading-container">
                        <p class="journal-heading">
                            JOURNALS
                        </p>
                        <a class="new-journal"> New Journal <i class="fa fa-plus-circle" style="padding-left: 4px" aria-hidden="true"></i></a>
                    </div>
                    <journals></journals>
                </div>



                <a href="/logout"> Logout </a>
                @if (Auth::check())
                    <a class="logged-in-user">
                        {{Auth::user()->firstname}} {{Auth::user()->lastname}}
                    </a>
                @endif
            </div> 
        </div>
        <div class="medium-3 large-3 cell">
            <div class="panel-entries">
                <form>
                    <div style="padding-right:8px; padding-left: 8px;">
                        <input type="text" class="search-box" placeholder="Search">
                    </div>
                    <fieldset class="small-12 columns"  style="display: none;">
                        <input id="hidden" type="checkbox"><label for="hidden">Hidden</label>
                        <input id="deleted" type="checkbox"><label for="deleted">Deleted</label>
                    </fieldset>
                    <div class="grid-x grid-margin-x"  style="display: none;">
                        <div class="small-6 cell">
                            <label for="date-from"> Date From </label><input id="date-from" type="date">
                        </div>
                        <div class="small-6 cell">
                            <label for="date-upto"> Date Upto </label><input id="date-upto" type="date">
                        </div>
                    </div>
                    <button class="button"  style="display: none;"> Search </button>
                </form>
                <hr>
            <journal-entries></journal-entries>
            <hidden-entries></hidden-entries>
            <deleted-entries></deleted-entries>
            </div>
        </div>
        <div class="medium-6 large-6 cell">
            <journal-entry></journal-entry>
            <versions></versions>
            
            {{--  <div class="panel-entry">
                <h1 contenteditable="true">{{ $jev->title }}<br></h1>
                <h6>{{ $jev->updated_at }}</h6>
                <p contenteditable="true">{{ $jev->body }}<br></p>
            </div>  --}}
        </div>
    </div>
</div>
@endsection

@section('vue-script')
<script src="/js/home.js"></script>
@endsection
