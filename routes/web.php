<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MqttController;

Route::get('/', function () {
    return view('welcome');
});