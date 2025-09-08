<?php

use Illuminate\Support\Facades\Route;

// SPA entry: serve the same view for all non-API routes
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');
