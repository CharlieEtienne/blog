<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\View\View;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(): View
    {
        return view('tags.index', [
            'tags' => Tag::query()
                ->withCount('posts')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function show(Request $request, Tag $tag): View
    {
        return view('tags.show', [
            'tag' => $tag,
            'posts' => $tag
                ->posts()
                ->latest('published_at')
                ->published()
                ->paginate(24),
        ]);
    }
}
