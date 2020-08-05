@php

@endphp

@extends('layouts.layoutTutorials')

@section('headTitle')
    Laravel - how to build dynamic forms tutorial
@endsection
@section('metaDescription')Get base understanding of building and validating (server-side) forms where JS is used to add and remove fields. Have dynamic elements on top of dynamic elements and more. @endsection

@section('content')
<header class="main">
    <h1>How to Build Dynamic Forms in Laravel</h1>
</header>
<p>
    Get a base understanding of how to build complex, dynamic forms in Laravel. By "Dynamic Form" we mean a form
    where fields and blocks of fields get added using JavaScript. See the 
    <a href="{{ route('programmer.create', ['locale'=>'en_US']) }}">example dynamic form here</a>. 
    You will be shown how to not only build the form but also how to validate it with complex validation rules.
</p>
<h3>Features of the <a href="{{ route('programmer.create', ['locale'=>'en_US']) }}">example form</a></h3>
<ul>
    <li>
        You can add as many "Addtional Languages" and "Jobs" as you want until you reach the 'max_input_vars' 
        limit set in the php.ini. This is a limit for how many form fields can be submitted to the server
        and is usually set to the default of 1,000. Changing this limit in the php.ini is quite straight forward.
    </li>
    <li>
        Each dynamically added job has within it a dynamic section "Additional Languages Used". 
        You can add and remove "Additional Languages Used" as you like.
    </li>
    <li>
        Sophisticated validation rules are applied to the dynamically added experience (Jobs) section 
        Example: <br>
        - there must be entries in either the "Languages Used" or "Additional Languages Used" 
        section or both. <br>
        - "end date" must be on or after the "start date". <br>
        "Full Name" must be unique for all the people you have submitted.
    </li>
    <li>
        The form is multi-lingual. There is a button to switch between the English and German language version.
        Field labels, error messages and JavaScript "confirm" messages all appear in the appropriate language.
        <br>
        NOTE about dates fields. The HTML5 date element &lt;input type="date"&gt; is being used to input dates.
        Most browsers format the displayed date using the browsers languages settings. For this reason when
        you switch language you will not see the date format change.
        The submitted date is always in the same format yyyy-mm-dd. 
    </li>
</ul>

<h3>Learning</h3>
<p>
    Play around with the <a href="{{ route('programmer.create', ['locale'=>'en_US']) }}">example form</a> 
    and get a feel for how it behaves. Test what errors messages you can get out of it. It is OK to save data as it will 
    be deleted after your session is over.
</p>
<p>
    Get an overview of some of the <a href="{{ route('tutorial.techniques') }}">basic techniques</a> 
    used in building the form and see <a href="{{ route('tutorial.instructions') }}">detailed instructions here</a>.
    You can find the entire code for this website at <a href="https://github.com/gbrabyn/laravel-forms">GitHub</a>
</p>

@endsection