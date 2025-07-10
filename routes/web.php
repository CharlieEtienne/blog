<?php

use App\Enums\MainPages;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Enums\SiteSettings;

Route::get('/' . data_get(SiteSettings::PERMALINKS->get(), MainPages::HOME->value), HomeController::class)
    ->name('home');

Route::get('/' . data_get(SiteSettings::PERMALINKS->get(), MainPages::BLOG->value), [PostController::class, 'index'])
    ->name('posts.index');

Route::get('/' . data_get(SiteSettings::PERMALINKS->get(), MainPages::BLOG->value) . '/{post:slug}', [PostController::class, 'show'])
    ->name('posts.show');

Route::get('/' . data_get(SiteSettings::PERMALINKS->get(), MainPages::CATEGORIES->value), [CategoryController::class, 'index'])
    ->name('categories.index');

Route::get('/' . data_get(SiteSettings::PERMALINKS->get(), MainPages::CATEGORIES->value) . '/{category:slug}', [CategoryController::class, 'show'])
    ->name('categories.show');

Route::get('/' . data_get(SiteSettings::PERMALINKS->get(), MainPages::TAGS->value), [TagController::class, 'index'])
    ->name('tags.index');

Route::get('/' . data_get(SiteSettings::PERMALINKS->get(), MainPages::TAGS->value) . '/{tag:slug}', [TagController::class, 'show'])
    ->name('tags.show');
