<?php

// routes/web.php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::resource('tasks', TaskController::class);

Route::middleware('auth')->group(function () {
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
});
