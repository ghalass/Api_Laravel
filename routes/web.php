<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $routeCollection = Route::getRoutes();
    return view('welcome', [
        'routeCollection' => $routeCollection
    ]);
});
