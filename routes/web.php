<?php

/**
 * Serve a front-end application.
 */
Route::middleware('web-auth')->group(function () {
    /**
     * Landing page
     */
    Route::name('landing-page')->domain(env('APP_DOMAIN'))->get('/', 'Web\LandingPageController@index');

    /**
     * Application
     */
    Route::name('user.confirmation')->get('email-confirmation/{token}', 'Web\WebController@confirmUser');
    Route::name('demo')->get('demo', 'Web\WebController@demo');
    Route::name('frontend-application')->get('{any?}', 'Web\WebController@serveApplication');
});
