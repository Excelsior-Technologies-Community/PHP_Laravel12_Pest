<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/users', [UserController::class, 'index']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);
Route::post('/users/{id}/restore', [UserController::class, 'restore']);
Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus']);
Route::get('/users/export', [UserController::class, 'export']);

Route::get('/', function () {
    return view('welcome');
});
