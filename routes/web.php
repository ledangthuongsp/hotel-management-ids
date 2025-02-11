<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingpage/landingpage');
});
Route::get('/login', function()
{
    return view('login.login');
}); 