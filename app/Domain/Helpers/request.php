<?php

if (!function_exists('current_company')) {
    function current_company() {
        if (request()->header('X-Current-Company') && auth()->check()) {
            return auth()->user()->companies()->find(
                request()->header('X-Current-Company')
            );
        }
        return null;
    }
}