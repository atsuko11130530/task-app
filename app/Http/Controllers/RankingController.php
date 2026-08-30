<?php

// app/Http/Controllers/RankingController.php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\View\View;

class RankingController extends Controller
{
    public function index(): View
    {
        $rankedTags = Tag::withCount('tasks')
            ->has('tasks')
            ->orderByDesc('tasks_count')
            ->take(10)
            ->get();

        return view('ranking.index', compact('rankedTags'));
    }
}
