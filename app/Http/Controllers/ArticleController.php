<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(){
        $articles = Article::latest()->paginate(5);
        return view ('articles.index', compact('articles'));
    }

    public function create (){
        return view ('articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required',
            'content' => 'required',
            'image'   => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $article = new Article();

        $article->title = $request->title;
        $article->content = $request->content;
        $article->user_id = auth()->id();
        $article->slug = Str::slug($request->title, '-');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = Str::slug($request->title) . '_' . time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('/images');
            $imagePath = $destinationPath . '/' . $name;

            $image->move($destinationPath, $name);

            $article->image = $name;
        }

        $article->save();

        return redirect()->route('articles.index')
                        ->with('success', 'Article created successfully.');
    }

    
}
