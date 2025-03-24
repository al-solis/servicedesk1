<?php

namespace App\Http\Controllers;
use App\Models\TicketType;
use App\Models\KnowledgeBaseArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KnowledgeBaseArticleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $searchTerm = $request->input('search');

        // Start query builder
        $articles = KnowledgeBaseArticle::with('category');

        if ($searchTerm) {
            $articles->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('category', function ($q) use ($searchTerm) {
                        $q->where('description', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        $articles = $articles->paginate(10);

        return view('kb.index', compact('articles', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $categories = TicketType::all();
        return view('kb.create', compact('categories', 'user'));
    }

    public function store(Request $request)
    {
        //dd($request->all());

        $data = request()->validate([
            'title' => 'required',
            'article' => 'required',
            'support_type_id' => 'required',
            'status' => 'required',
        ]);

        KnowledgeBaseArticle::create([
            'title' => $data['title'],
            'content' => $data['article'],
            'category_id' => $data['support_type_id'],
            'status' => $data['status'],
            'created_by' => Auth::id(),
            'created_at' => now(),
        ]);

        //dd($data);

        return redirect()->route('kb.index')->with('success', 'Article created successfully');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $article = KnowledgeBaseArticle::findOrFail($id);
        $categories = TicketType::all();
        return view('kb.edit', compact('article', 'categories', 'user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'article' => 'required|string',
            'support_type_id' => 'required',
            'status' => 'required',
        ]);

        $article = KnowledgeBaseArticle::findOrFail($id);
        $article->update([
            'title' => $request->title,
            'content' => $request->article,
            'status' => $request->status,
            'category_id' => $request->support_type_id,
        ]);

        return redirect()->route('kb.index');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads', 'public');
            return response()->json(['url' => asset("storage/$path")]);
        }
    }
}
