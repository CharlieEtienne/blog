<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;
use Illuminate\View\View;

class Comments extends Component
{
    public int $postId;

    public function render() : View
    {
        return view('livewire.comments', [
            'comments' => Comment::query()
                ->where('post_id', $this->postId)
                ->whereNull('parent_id')
                ->paginate(30),
            'commentsCount' => Comment::query()
                ->where('post_id', $this->postId)
                ->count(),
        ]);
    }
}
