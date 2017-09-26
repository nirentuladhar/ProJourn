@extends('public.master.layout')

@section('css')
    <link rel="stylesheet" href="/css/journals.css">
@endsection


@section('main-content')


<div id="root">
    <div class="grid-x">
        <div class="medium-3 large-3 cell">
            <div class="panel-left">
                <div class="journals">
                    <div class="journal-heading-container">
                        <p class="journal-heading">
                            JOURNALS
                        </p>
                        <a class="new-journal"> New Journal <i class="fa fa-plus-circle" style="padding-left: 4px" aria-hidden="true"></i></a>
                    </div>
        
                    <add-new-journal></add-new-journal>

                    <journal-list></journal-list>

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
                <input type="text" class="search-box" placeholder="Search">
                <fieldset class="small-12 columns">
                    <input id="hidden" type="checkbox"><label for="hidden">Hidden</label>
                    <input id="deleted" type="checkbox"><label for="deleted">Deleted</label>
                </fieldset>
                <div class="grid-x grid-margin-x">
                    <div class="small-6 cell">
                        <label for="date-from"> Date From </label><input id="date-from" type="date">
                    </div>
                    <div class="small-6 cell">
                        <label for="date-upto"> Date Upto </label><input id="date-upto" type="date">
                    </div>
                </div>
                <button class="button"> Search </button>
            </form>

            <hr>
            <add-new-journal-entry></add-new-journal-entry>
                
            <journal-entry-list></journal-entry-list>
{{--  
                <ul>
                    @foreach ($result as $v)
                        <li><a href="#">{{ $v->title }}</a></li>
                    @endforeach
                </ul>  --}}
            </div>
        </div>
        <div class="medium-6 large-6 cell">
            <div class="panel-entry">
                <h1 contenteditable="true">{{ $jev->title }}<br></h1>
                <h6>{{ $jev->updated_at }}</h6>
                <p contenteditable="true">{{ $jev->body }}<br></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('vue-script')
<script src="/js/home.js"></script>
@endsection
