<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    $user = User::first();
    dd(111,$user);
});
