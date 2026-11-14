<?php


use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\HomeHeroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);

Route::post('/contact', [ContactController::class, 'store']);

Route::get('/hero', [HomeHeroController::class, 'show']);

Route::post('/hero', [HomeHeroController::class, 'store']);


