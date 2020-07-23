@php
    /** @var $k int */
@endphp
<fieldset class="experience">
    <h4>
        @lang('messages.Job')
    </h4>
    <div class="row">
        <div class="label-col">

        </div>
        <div class="input-col align-right">
            <button class="removeJob" type="button">@lang('messages.Remove Job')</button>
        </div>
    </div>
    <div class="row">
        <div class="label-col">
            @lang('messages.companyName')
        </div>
        <div class="input-col">
            {!! Form::myInput('text', 'experience['.$k.'][companyName]') !!}
        </div>
    </div>
    <div class="row">
        <div class="label-col">
            @lang('messages.officeLocation')
        </div>
        <div class="input-col">
            {!! Form::myTextarea('experience['.$k.'][officeLocation]') !!}
        </div>
    </div>
    <div class="row">
        <div class="label-col">
            @lang('messages.officeCountry')
        </div>
        <div class="input-col">
            {!! Form::mySelect('experience['.$k.'][officeCountryId]', $countries, null, ['placeholder'=>trans('messages.Select country')]) !!}
        </div>
    </div>
    <div class="row">
        <div class="label-col">
            @lang('messages.period')
        </div>
        <div class="input-col dateRange">
            <div class="table-cell">
                {!! Form::myInput('date', 'experience['.$k.'][startDate]', null, ['placeholder'=>trans('messages.start')]) !!}
            </div>
            <div class="table-cell">
                @lang('messages.to')
            </div>
            <div class="table-cell">
                {!! Form::myInput('date', 'experience['.$k.'][finishDate]', null, ['placeholder'=>trans('messages.finish')]) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="label-col">
            @lang('messages.type')
        </div>
        <div class="input-col">
            {!! Form::myRadioList('experience['.$k.'][type]', $workTypes, ['class'=>'checkboxListHorizontal']) !!}
        </div>
    </div>
    <div class="row">
        <div class="label-col">
            @lang('messages.programmingLanguagesUsed')
        </div>
        <div class="input-col">
            {!! Form::myCheckboxList('experience['.$k.'][languagesUsed]', $languages, ['class'=>'checkboxList', 'style'=>'column-count:3;']) !!}
        </div>
    </div>
    <div class="row">
        <div class="label-col">
            @lang('messages.Additional Programming Languages Used')
        </div>
        <div class="input-col">
            @error('experience.'.$k.'.additionalLanguagesUsed')
            <span class="error msgAbove">{{ $message }}</span><br>
            @enderror
            <table
                class="resultSet hoverRows"
                data-template="{!! attributeTemplate('programmer._list-row', ['name'=>'experience.'.$k.'.additionalLanguagesUsed.__index2__', 'nameAttribute'=>'experience['.$k.'][additionalLanguagesUsed][]', 'placeholder'=>trans('messages.Programming Language')]) !!}"
            >
                <tbody>
                @foreach (formIterator(($personExperience ?? null), 'experience.'.$k.'.additionalLanguagesUsed') as $key2)
                    @include('programmer._list-row', ['name'=>'experience['.$k.'][additionalLanguagesUsed]['.$key2.']', 'nameAttribute'=>'experience['.$k.'][additionalLanguagesUsed][]', 'placeholder'=>trans('messages.Programming Language')])
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" align="right">
                        <button type="button" class="additionalFrameworkBtn">@lang('messages.Add')</button>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</fieldset>
