<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\News;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function show(News $news)
    {
        return Inertia::render('NewsDetail', [
            'news' => $news
        ]);
    }
}
