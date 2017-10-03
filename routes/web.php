<?php

// Route::domain('{account}.overseer.dev')->group(function () {
//     Route::get('register', 'Web\WebController@register');
//     Route::get('login', 'Web\WebController@login');
// });

Route::middleware('auth0')->group(function () {
    Route::name('frontend')->get('{any?}', 'Web\WebController@index');
});
