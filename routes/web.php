<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ProgrammerExperienceController@index')->name('programmer.list');
Route::view('/instructions', 'tutorial.instructions')->name('tutorial.instructions');

Route::group([
    'prefix' => '{locale}/programmer',
    'name' => 'programmer.',
    'where' => ['locale' => '^(en_US|de_DE)$'],
    'middleware' => 'setlocale'
    ], function() {
    
        Route::get('/create', 'ProgrammerExperienceController@add')->name('programmer.create');
        Route::get('/{id}/edit', 'ProgrammerExperienceController@edit')->where(['id'=>'[0-9]+'])->name('programmer.edit');
        Route::post('/', 'ProgrammerExperienceController@store')->name('programmer.store');
        Route::put('/{id}', 'ProgrammerExperienceController@update')->where(['id'=>'[0-9]+'])->name('programmer.update');
});
