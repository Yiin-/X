<?php

// Route::domain('{account}.overseer.dev')->group(function () {
//     Route::get('register', 'Web\WebController@register');
//     Route::get('login', 'Web\WebController@login');
// });

Route::get('mail', function () {
    $mail = new App\Domain\Mail\InvoiceForClient(App\Domain\Model\Documents\Invoice\Invoice::first());
    Mail::to('stanislovas.janonis@gmail.com')->send($mail);
    return $mail;
});

Route::middleware('auth0')->group(function () {
    Route::name('frontend')->get('{any?}', 'Web\WebController@index');
});
