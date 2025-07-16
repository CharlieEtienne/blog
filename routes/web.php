<?php

use App\Enums\MainPages;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Enums\SiteSettings;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Define the localized route group
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect' ]
], function() {

        // Home route
        Route::get('' . data_get(SiteSettings::PERMALINKS->get(), MainPages::HOME->value), HomeController::class)->name('home');

        // Blog routes
        Route::prefix(data_get(SiteSettings::PERMALINKS->get(), MainPages::BLOG->value, 'blog'))->group(function () {
            Route::get('', [PostController::class, 'index'])->name('posts.index');
            Route::get('/{post:slug}', [PostController::class, 'show'])->name('posts.show');
        });

        // Categories routes
        Route::prefix(data_get(SiteSettings::PERMALINKS->get(), MainPages::CATEGORIES->value, 'categories'))->group(function () {
            Route::get('', [CategoryController::class, 'index'])->name('categories.index');
            Route::get('/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
        });

        // Tags routes
        Route::prefix(data_get(SiteSettings::PERMALINKS->get(), MainPages::TAGS->value, 'tags'))->group(function () {
            Route::get('', [TagController::class, 'index'])->name('tags.index');
            Route::get('/{tag:slug}', [TagController::class, 'show'])->name('tags.show');
        });

    });
