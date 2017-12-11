<?php

/**
 * Serve a front-end application.
 */
Route::middleware('web-auth')->group(function () {
    // Pdf
    Route::prefix('storage')->group(function () {
        Route::prefix('html')->group(function () {
            Route::get('/{id}', 'Documents\StorageController@genHtml');
        });
        Route::prefix('pdf')->group(function () {
            Route::get('/{id}/{document}', 'Documents\StorageController@pdf');
        });
    });

    /**
     * Landing page
     */
    Route::name('landing-page')->domain(env('APP_DOMAIN'))->get('/', 'Web\LandingPageController@index');

    /**
     * Application
     */
    Route::name('user.confirmation')->get('email-confirmation/{token}', 'Web\WebController@confirmUser');
    Route::name('user.accept-invitation')->get('accept-invitation/{token}', 'Web\WebController@acceptInvitation');
    Route::name('demo')->get('demo', 'Web\WebController@demo');
    Route::name('frontend-application')->get('{any?}', 'Web\WebController@application')->where('any', '.*');
});
