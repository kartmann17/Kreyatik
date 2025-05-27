<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date'
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();
        $validated['is_published'] = $request->has('is_published');
        if ($validated['is_published']) {
            $validated['published_at'] = now();
        } else {
            $validated['published_at'] = null;
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $folder = 'images/articles';
            $publicPath = public_path('storage/' . $folder);
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $image->getClientOriginalName());
            $destination = $publicPath . '/' . $imageName;
            $uploadSuccess = move_uploaded_file($image->getPathname(), $destination);
            if ($uploadSuccess) {
                $validated['image'] = 'images/articles/' . $imageName;
            }
        }

        try {
            $article = Article::create($validated);
            return redirect()->route('admin.articles.index')
                ->with('success', 'Article créé avec succès.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de l\'article.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'remove_image' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');
        if ($validated['is_published'] && !$article->published_at) {
            $validated['published_at'] = now();
        } elseif (!$validated['is_published']) {
            $validated['published_at'] = null;
        }

        if ($request->has('remove_image') && $request->remove_image) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
                $validated['image'] = null;
            }
        } elseif ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }

            $image = $request->file('image');
            $folder = 'images/articles';
            $publicPath = public_path('storage/' . $folder);
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $image->getClientOriginalName());
            $destination = $publicPath . '/' . $imageName;
            $uploadSuccess = move_uploaded_file($image->getPathname(), $destination);
            if ($uploadSuccess) {
                $validated['image'] = 'images/articles/' . $imageName;
            }
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // Supprimer l'image si elle existe
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article supprimé avec succès.');
    }

    public function togglePublish(Article $article)
    {
        $article->is_published = !$article->is_published;
        $article->published_at = $article->is_published ? now() : null;
        $article->save();

        return redirect()->route('admin.articles.index')
            ->with('success', $article->is_published ? 'Article publié avec succès.' : 'Article mis en brouillon.');
    }
}
