<?php

Route::group(array('prefix' => 'api/v1'), function () {
    Route::resource('users', 'UserController');
});

Route::group(array('prefix' => 'api/v1'), function () {
    Route::resource('bunkers', 'BunkerController');
});

Route::group(array('prefix' => 'api/v1'), function () {
    Route::resource('verbindingen', 'VerbindingController');
});

Route::group(array('prefix' => 'api/v1'), function () {
    Route::resource('gemeentes', 'GemeenteController');
});

Route::group(array('prefix' => 'api/v1'), function () {
    Route::resource('deelgemeentes', 'DeelgemeenteController');
});