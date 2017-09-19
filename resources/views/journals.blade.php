@extends('public.master.layout')

@section('css')
    <link rel="stylesheet" href="/css/journals.css">
@endsection


@section('main-content')


<div id="journals">
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
                    <input type="text" v-model="newJournal">
                    <button class="button" @click="addJournal">Add a new journal</button>
                    <ul>
                        @foreach ($journals as $journal)
                            <li> <a href="#"><i class="fa fa-file-o journal-heading-icon" aria-hidden="true"></i> {{ $journal->name }} </a></li>
                        @endforeach
                    </ul>



                    
                    <journals list="{{ $journals }}"></journals>



                <template id="tasks-template">
                    <ul>
                        <li v-for="journal in list_decoded">
                            <a href="#"><i class="fa fa-file-o journal-heading-icon" aria-hidden="true"></i>
                                @{{ journal.name }}
                            </a>
                        </li>
                    </ul>
                </template>
                </div>



                @if (Auth::check())
                    <a class="logged-in-user">
                        {{Auth::user()->firstname}} {{Auth::user()->lastname}}
                    </a>
                @endif
            </div> 
        </div>
        <div class="medium-3 large-3 cell">
            <div class="panel-entries">
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


                <hr>
                <ul>
                    @foreach ($result as $v)
                        <li><a href="#">{{ $v->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="medium-6 large-6 cell">
            <div class="panel-entry">
                {{ $jev->title }}<br>
                {{ $jev->body }}<br>
                {{ $jev->updated_at }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('vue-script')
    Vue.component('journals', {
        template: '#tasks-template',
        props: ['list'],
        computed: {
            list_decoded: function() {
                return JSON.parse(this.list);
            }
        }
    })

    new Vue({
        el: '#journals',
        data:{
            newJournal: '',
            journals: [],
            list: []
        },
        methods: {
            addJournal: function() {
                this.journals.push(this.newJournal);
                alert(this.newJournal);
                this.newJournal='';
            }
        }
    });
@endsection
