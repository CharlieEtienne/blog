<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', HomeController::class)
    ->name('home');

Route::get('/blog', [PostController::class, 'index'])
    ->name('posts.index');

Route::get('/categories', [CategoryController::class, 'index'])
    ->name('categories.index');

Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])
    ->name('categories.show');

Route::get('/tags', [TagController::class, 'index'])
    ->name('tags.index');

Route::get('/tags/{tag:slug}', [TagController::class, 'show'])
    ->name('tags.show');

Route::get('/blog/{post:slug}', [PostController::class, 'show'])
    ->name('posts.show');
