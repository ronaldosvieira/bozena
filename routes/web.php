<?php

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

Route::get('/', function () {
    return \Illuminate\Support\Facades\Redirect::route('tournament.index');
});

Route::post('tournament/{tournament}/activate', [
    'as' => 'tournament.activate',
    'uses' => 'TournamentController@activate'
]);

Route::post('tournament/{tournament}/match/{match}/start', [
    'as' => 'tournament.match.start',
    'uses' => 'MatchController@start'
]);

Route::post('tournament/{tournament}/match/{match}/end', [
    'as' => 'tournament.match.end',
    'uses' => 'MatchController@end'
]);

Route::get('tournament/{tournament}/match/{match}/goal/add', [
    'as' => 'tournament.match.goal.store',
    'uses' => 'MatchController@addGoal'
]);

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    Route::resource('tournament', 'TournamentController');
    Route::resource('player', 'PlayerController');
});
