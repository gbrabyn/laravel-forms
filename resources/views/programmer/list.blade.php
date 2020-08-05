@php
    /** @var $people Illuminate\Database\Eloquent\Collection */
    /** @var $person App\Model\PersonExperience   */
@endphp
@extends('layouts.layoutTutorials')

@section('headTitle')Programming Experience Entries - Laravel @endsection
@section('metaDescription')Edit the results of your dynamic form entries. @endsection

@section('content')
    <h1>Programming Experience Entries</h1>
    <div class="table-wrapper">
        <table>
            <caption>Edit Entries (total: {{ number_format($people->count()) }})</caption>
            <thead>
                <tr>
                    <th>Name</th>
                    <th colspan="2">Laravel Collective Forms</th>
                    <th colspan="2">Laminas / Zend Framework Forms</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td colspan="2">
                        <a href="{{ route('programmer.create', ['locale'=>'en_US']) }}">Add Entry</a>
                    </td>
                    <td colspan="2">

                    </td>
                </tr>
            @foreach($people as $person)
                <tr>
                    <td>{{ $person->fullName }}</td>
                    <td>
                        <a href="{{ route('programmer.edit', ['locale'=>'en_US', 'id'=>$person->id]) }}">English</a>
                    </td>
                    <td>
                        <a href="{{ route('programmer.edit', ['locale'=>'de_DE', 'id'=>$person->id]) }}">German</a>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
