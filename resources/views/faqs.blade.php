@extends('public.master.layout')

@section('css')
    <link rel="stylesheet" href="/css/journals.css">
@endsection


@section('main-content')
<div  style="background: #EEE">
<div class="grid-container">
    <div class="grid-x">
        <div class=" small-12 large-8 cell" style="background: white">
            <div style="background: #ff671f; padding-left:16px; padding-right:16px; padding-bottom: 8px;">
                <a href="/" style="padding-top:16px; display: block; color: white; text-decoration: underline"> Journal Engine </a>
                <h2 style="color: white; font-weight: bold; padding-top: 36px; ">Frequently Asked Questions</h2>
            </div>
            <br>
            <div style="padding: 16px; padding-bottom: 64px;">
            <p>
                <b>What is Journal Engine? </b> <br>
                Journal Engine is a web-based professional journal keeping application that keeps immutable records of all entries. Journal Engine is designed to be easy and efficient to use, allowing users to keep a transparent and trustworthy record of professional activities without the hassle of writing.
            </p>
            <p>
                <b>Can Journal Engine protect me in court? </b> <br>
                Journal Engine provides complete transparency about any changes made to the journal and provides a history of all updates. All entries within the system will be immutable, meaning they cannot be modified or deleted. Any modifications made, such as editing, updating, or deleting an entry, will not delete the original entry from the system, but hide it unless there is a specific request to reveal the full history of the entry. Thus users will not be able to permanently delete their history, and administrators will have access to all deleted files, providing true transparency within the journal. This will make the journal a viable method of defence in the court of law.
            </p>

            <p>
                <b>How to add a journal? </b><br>
                1. Click ‘New Journal’ on the left pane of the screen. <br>
                2. Type in your journal name. <br>
                3. Click ‘Add a new journal’ button.
            </p>
            <p>
                <b>How to add a new entry? </b><br>
                1. Select one of the journals first. <br>
                2. Click ‘New Entry’ button. <br>
                3. Type in your entry title and click ‘Add an entry’ button. <br>
                4. Click on your newly created journal entry, add a description then, press save.
            </p>
            <p>
                <b>How to update an entry? </b><br>
                1. Click on the entry you would like to update. <br>
                2. Make the changes and click save.
            </p>
            <p>
                <b>How do you hide or delete an entry? </b><br>
                1. Click on the entry you would like to hide or delete. <br>
                2. Click hide or delete button.
            </p>
            <p>
                <b>How do you unhide an entry? </b><br>
                1. Click on the ‘hidden’ button in the middle pane. <br>
                2. Select the entry you wish to unhide and press unhide button. <br>
            </p>
            <p>
                <b>How do you access your hidden/deleted entries? </b><br>
                1. Select the journal first, a list of entry should be displayed in the middle pane. <br>
                2. Select ‘Hidden’ or ‘Deleted’ tab to access its associated entries. <br>
            </p>
            <p>
                <b>How do you search for an entry? </b><br>
                1. Type the term you wish to search in the search box in the middle pane and press ‘Search’. <br>
                2. To include hidden or deleted entries, check hidden or deleted checkboxes. <br>
                3. To include only entries of a particular date, add your date to only ‘Date From’. <br>
                4. To search between a particular date range, add ‘Date from’ and ‘Date upto’. <br>
            </p>
            <br>
            <br>
            <br>
            <h3> Contact Us </h3>
            <p>
                Carlos Miguel Dela Cruz - <em>carlos.delacruz@student.uts.edu.au </em><br>
                Dean Mai - <em>deanmai1996@gmail.com</em><br>
                Niren Tuladhar - <em>niren.r.tuladhar@student.uts.edu.au</em> <br>
                Peter Chen - <em>chen371287536@gmail.com </em><br>
                Travyse Nguyen - <em>travyse.t.nguyen@student.uts.edu.au</em>
            </p>
            </div>
        </div>
    </div>
</div>
</div>
@endsection