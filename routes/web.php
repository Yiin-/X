<?php

/**
 * Serve a front-end application.
 */
Route::middleware('web-auth')->group(function () {
    Route::name('frontend-application')->get('{any?}', 'Web\WebController@serveApplication');
});
