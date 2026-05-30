<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index()
    {
        $articles = Article::latest()->get();

        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created article.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required',
            'content' => 'required',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $article = new Article();

        $article->title = $request->title;
        $article->content = $request->content;
        $article->user_id = auth()->id();
        $article->slug = Str::slug($request->title, '-');

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $name = Str::slug($request->title) . '_' . time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('images');

            $image->move($destinationPath, $name);

            $article->image = $name;
        }

        $article->save();

        return redirect()
            ->route('articles.index')
            ->with('success', 'Article created successfully.');
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified article.
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title'   => 'required',
            'content' => 'required',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $article->title = $request->title;
        $article->content = $request->content;
        $article->slug = Str::slug($request->title, '-');

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $name = Str::slug($request->title) . '_' . time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('images');

            $image->move($destinationPath, $name);

            $article->image = $name;
        }

        $article->save();

        return redirect()
            ->route('articles.index')
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('success', 'Article deleted successfully.');
    }
}