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
                    <journals></journals>
                </div>



                @if (Auth::check())
                <div class="logged-in-user">
                    <p><i class="fa fa-user" aria-hidden="true"></i> {{Auth::user()->firstname}} {{Auth::user()->lastname}}  </p>
                    <a href="/logout" class="logout-button"> Logout <i class="fa fa-sign-out" aria-hidden="true"></i> </a>
                </div>
                @endif
            </div> 
        </div>
        <div class="medium-3 large-3 cell">
            <div class="panel-entries">
            <search style="padding-left: 8px; padding-right: 8px"></search>
                <hr>
            <journal-entries></journal-entries>
            <hidden-entries></hidden-entries>
            <deleted-entries></deleted-entries>
            </div>
        </div>
        <div class="medium-6 large-6 cell">
        <div style="margin-left: 20px; max-width: 600px; display: flex; margin-left: auto; margin-right: auto; flex-wrap:wrap; overflow: scroll; height: 100vh;">
            <journal-entry style="width: 100%"></journal-entry>
            <versions style="width: 100%"></versions>
        </div>
        </div>
    </div>
</div>
@endsection

@section('vue-script')
<script src="/js/home.js"></script>
@endsection
