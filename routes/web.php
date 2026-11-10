<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/report', [ReportController::class, 'index']);
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/filter', [EventController::class, 'filter']);