<?php

use App\Http\Controllers\StudentApptController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Models\StudentAppt;

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


Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        // return response()->json($request->user()->toArray());
        return $request->user();
    });

    Route::get('/students', [StudentController::class, 'apiSearch']);
    Route::get('/appts/student', [StudentApptController::class, 'allActiveAppts']);
    
});
