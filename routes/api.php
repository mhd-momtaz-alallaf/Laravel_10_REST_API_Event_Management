<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('events',EventController::class); // the deffirence between Resource and apiResource that apiResource dont add routs for create and edit because the api just for sending data not for views.
Route::apiResource('events.attendees',AttendeeController::class)
    ->scoped()->except(['update']); // scoped means that attendees are always part of the events, when we pass the attendee by Route Model Binding in AttendeeController controller then laravel will get all the parent model (events) too.