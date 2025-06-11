<?php

namespace App\Http\Controllers;
use App\Models\KnowledgeBaseArticle;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        // Get the search term from the request
        $searchTerm = $request->input('search');

        $articles = KnowledgeBaseArticle::with(['user', 'category'])
            ->where('status', 'Active')
            ->when($searchTerm, function ($query, $searchTerm) {
                return $query->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('category', function ($query) use ($searchTerm) {
                        return $query->where('description', 'like', '%' . $searchTerm . '%');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('articles.article-index', compact('articles', 'user'));
    }

    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $articles = KnowledgeBaseArticle::findorFail($id);
        return view('articles.article-show', compact('articles', 'user'));
    }

}
