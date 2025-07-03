<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('categories.index', [
            'categories' => Category::query()
                ->withCount('posts')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function show(Request $request, Category $category): View
    {
        return view('categories.show', [
            'category' => $category,
            'posts' => $category
                ->posts()
                ->latest('published_at')
                ->published()
                ->paginate(24),
        ]);
    }
}
