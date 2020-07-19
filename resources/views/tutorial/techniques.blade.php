@php

@endphp

@extends('layouts.layoutTutorials')

@section('headTitle')
    Techniques for building Dynamic Forms.
@endsection
@push('css')
    <link rel="stylesheet" href="{{ mix('/build/tutorial.css') }}">
@endpush

@section('content')
<header class="main">
    <h1>Techniques for building Dynamic Forms</h1>
</header>
<h3>General Principles Used to Build <a href="{{ route('programmer.create', ['locale'=>'en_US']) }}">this Form</a></h3>
<h4>Form Arrays</h4>
<p>PHP automatically converts form fields with square brackets in the name into an array.
    So if you have two fields submitted with names 'language[]="English"' and 'language[]="German"'
    then PHP will convert them into a language array '[0=>"English", 1=>"German"]'. Also
    'job[startDate]="2020-07-01"' and 'job[endDate]="2020-07-31"' becomes an array belonging to
    key "job" with an array value of ['startData'=>'2020-07-01', 'endDate'=>'2020-07-31'].
</p>
<h4>Not all form fields get submitted</h4>
<p>Most form fields such as &lt;input type="text"&gt;, &lt;select&gt;, &lt;textarea&gt; 
    appear in your forms submitted data even if you do not enter anything into them. Other
    fields such as checkboxes and radio buttons do not appear if they have not been checked (or selected).
    The same goes for any dynamic fields that have been totally removed.
</p>
<p>
    It can be easiest sometimes to just let PHP create the array keys (i.e. field 
    names end with '[]') for a block of dynamic fields. Example:
</p>
<pre>
&lt;div class="row"&gt;
    &lt;div class="input-col"&gt;
        &lt;input type="text" name="fullName[]"&gt;
    &lt;/div&gt;
    &lt;div class="input-col"&gt;
        &lt;input type="email" name="email[]"&gt;
    &lt;/div&gt;
&lt;/div&gt;
</pre>
<p>When processing the submitted form you can just loop through "fullName" and use the key to
find the matching email address. However this is not the way to go when using a checkbox, 
radio input or having dynamic input within that row. For example this would be a mistake:
</p>
<pre>
&lt;div class="row"&gt;
    &lt;div class="input-col"&gt;
        &lt;input type="text" name="fullName[]"&gt;
    &lt;/div&gt;
    &lt;div class="input-col"&gt;
        &lt;input type="email" name="email[]"&gt;
    &lt;/div&gt;
    &lt;div class="input-col"&gt;
        &lt;input type="checkbox" name="qualified[]" value="1"&gt;
    &lt;/div&gt;
&lt;/div&gt;
</pre>
<p>
    If you had 3 of the above rows dynamically added to a form and only had the "qualified[]" checkbox
    checked on the last row when submitting then when processing
    the form data the qualified[] array has only one entry. If you loop through the "fullName" 
    array and use its key to find the matching checkbox value then it would appear the 
    first row had the qualified checkbox checked. This brings erroneous results and is not the way to proceed.
</p>
<p>A better solution would be:</p>
<pre>
&lt;div class="row"&gt;
    &lt;div class="input-col"&gt;
        &lt;input type="text" name="person[---integer key goes here---][fullName]"&gt;
    &lt;/div&gt;
    &lt;div class="input-col"&gt;
        &lt;input type="email" name="person[---integer key goes here---][email]"&gt;
    &lt;/div&gt;
    &lt;div class="input-col"&gt;
        &lt;input type="hidden" name="person[---integer key goes here---][qualified]" value="0"&gt;
        &lt;input type="checkbox" name="person[---integer key goes here---][qualified]" value="1"&gt;
    &lt;/div&gt;
&lt;/div&gt;
</pre>
<p>where each field in a row shares the same integer key and each row has its own unique integer key.</p>
<p>
    It is easy for PHP to come up with a unique integer key for each row but when JavaScript creates 
    a new row it must not use an integer key that has already been used by PHP to create the previous rows.
    For this reason we get PHP to write a data attribute with the lowest integer value that the 
    JavaScript can use when creating a row. E.g. &lt;div data-nextkey="6"&gt;
</p>
<p>In the example above, the value from the &lt;input type="hidden"&gt; gets overwritten by the checkbox with the same
name attribute when the checkbox is checked.
</p>

<h4>PHP and JavaScript will use the same view templates</h4>
<p>
    Dynamic elements or blocks need to be written to the screen by both PHP (when a form is 
    retrieved from the database or has been submitted but contains errors) and JavaScript (when 
    you want to add more of the elements or blocks). 
</p>
<p>
    So that you don't need to write the same snippet of HTML in two places (in PHP and in JavaScript)
    we will write one version in PHP that gets stored in an HTML 'data' attribute so that JavaScript
    can retrieve a copy.<br>
    Example: &lt;div data-template="... HTML template goes here ..."&gt;
</p>
<h4>Forms can be displayed in 3 states</h4>
<p>
    The form can be displayed in 3 states. These are:
</p>
<ul>
    <li>Empty</li>
    <li>Populated from database</li>
    <li>Contains errors and is displayed using data from the submitted form. This is usually data from 
        $_POST or $_GET but in Laravels case is usually data added to a session. 
    </li>
</ul>
<p>To simplify looping through arrays of dynamic data in the Laravel blade template we will write a function 
    that provides the right array of data according to the state the form is in.
</p>
@endsection