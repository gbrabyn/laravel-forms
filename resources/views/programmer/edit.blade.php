@php

@endphp

@extends('layouts.layoutTutorials')

@section('headTitle')
    Laravel Collective Dynamic Forms Example
@endsection

@push('scripts')
<script src="{{ mix('/build/programmer/edit.js') }}"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    function onSubmit(token) {
        document.getElementById("experience").submit();
    }
</script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{ mix('/build/programmer/edit.css') }}">
@endpush

@section('content')
    <div id="switchLang">
        @languageSwitch()
    </div>
    <header class="main">
        <h1>Laravel Collective Forms Example: @lang('messages.Add Programming Experience')</h1>
        <h2>I.T. Recruitment Agency - Candidate Experience Form</h2>
    </header>
    <p>
        Below is a hypothetical form for Recruitment Agencies to enter their candidates experience. It has been built with the Laravel Collective.
    </p>
    <p>
        Feel free to use the form and test it. Your saved data will be removed after your session has expired.
        Switch languages used in the form using the link to the above right.
    </p>
    @if(!empty($personExperience))
    {!! Form::model($personExperience, [
                    'route'=>['programmer.update', 'id'=>$personExperience->id, 'locale'=>app()->getLocale()], 
                    'method'=>'put', 
                    'id'=>"experience"
        ]) 
    !!}
    @else
    {!! Form::open(['route'=>['programmer.store', 'locale'=>app()->getLocale()], 'method'=>'post', 'id'=>"experience"]) !!}
    @endif    
    @if ($errors->any())
        <p class="errors">@lang('messages.formErrors')</p>
    @endif
    @error('g-recaptcha-response')
        <p class="error">{{ $message }}</p>
    @enderror
    <div
        id="experienceContainer"
        class="taskLayout"
        data-template="@attributeTemplate('programmer._experience', ['k'=>'__index1__', 'countries'=>$countries, 'languages'=>$languages, 'workTypes'=>$workTypes])"
        data-nextkey="{!! nextKey(($personExperience ?? null), 'experience') !!}"
        data-translate-remove-tag="{{ trans('messages.confirmRemoveTag') }}"
        data-translate-remove-job="{{ trans('messages.confirmRemoveJob') }}"
    >
        <div class="row">
            <div class="label-col">
                <label for="fullName">@lang('messages.fullName')</label>
            </div>
            <div class="input-col">
                {!! Form::myInput('text', 'fullName', null, ['id'=>'fullName']) !!}  
            </div>
        </div>
        <div class="row">
            <div class="label-col">
                <label for="email">@lang('messages.email')</label>
            </div>
            <div class="input-col">
                {!! Form::myInput('email', 'email', null, ['id'=>'email']) !!}
            </div>
        </div>
        <div class="row">
            <div class="label-col">
                <label for="address">@lang('messages.address')</label>
            </div>
            <div class="input-col">
                {{ Form::myTextarea('address', null, ['id'=>'address']) }}
            </div>
        </div>
        <div class="row">
            <div class="label-col">
                <label for="countryId">@lang('messages.country')</label>
            </div>
            <div class="input-col">
                {{ Form::mySelect('countryId', $countries, null, ['id'=>'countryId', 'placeholder'=>trans('messages.Select country')]) }}               
            </div>
        </div>
        <div class="row">
            <div class="label-col">
                @lang('messages.programmingLanguages')
            </div>
            <div class="input-col">
                {!! Form::myCheckboxList('languages', $languages, ['class'=>'checkboxList', 'style'=>'column-count:3;']) !!}
            </div>
        </div>
        <div class="row">
            <div class="label-col">
                @lang('messages.additionalProgrammingLanguages')
            </div>
            <div class="input-col">
                @error('additionalLanguages')
                <span class="error msgAbove">{{ $message }}</span><br>
                @enderror
                <table
                    id="additionalLanguages"
                    data-template="{!! attributeTemplate('programmer._list-row', ['name'=>'additionalLanguages.__index__', 'nameAttribute'=>'additionalLanguages[]', 'placeholder'=>trans('messages.Programming Language')]) !!}"
                >
                    <tbody>
                    @foreach(formIterator(($personExperience ?? null), 'additionalLanguages') as $key)
                        @include('programmer._list-row', ['name'=>'additionalLanguages['.$key.']', 'nameAttribute'=>'additionalLanguages[]', 'placeholder'=>trans('messages.Programming Language')])                        
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" align="right">
                                <button type="button" id="additionalLanguageBtn" class="newTag">@lang('messages.Add')</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row">
            <h3>@lang('messages.workExperience')</h3>
        </div>
        @foreach (formIterator(($personExperience ?? null), 'experience') as $key)
            @include('programmer._experience', ['k'=>$key, 'countries'=>$countries, 'languages'=>$languages, 'workTypes'=>$workTypes])
        @endforeach
    </div>
    <div class="tfoot taskLayout">
        <div class="row">
            <div class="label-col">

            </div>
            <div class="input-col align-right">
                <button type="button" id="addExperienceBtn">@lang('messages.addExperience')</button>
            </div>
        </div>
        <div id="saveRow" class="row">
            <div class="label-col">

            </div>
            <div class="input-col align-center">
                <button class="g-recaptcha submitBtn" data-sitekey="{!! env('RECAPTCHA_KEY') !!}" data-callback="onSubmit">
                    @lang('messages.formSave')
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
