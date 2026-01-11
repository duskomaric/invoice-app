<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Company;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Company $company)
    {
        $articles = $company->articles()->latest()->paginate(20);

        return view('app.articles.index', compact('company', 'articles'));
    }

    public function create(Company $company)
    {
        return view('app.articles.create', compact('company'));
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'tax_category' => 'nullable|string|max:50',
            'type' => 'required|string',
            'is_active' => 'boolean',
            'prices' => 'nullable|array',
        ]);

        $article = $company->articles()->create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'unit' => $validated['unit'] ?? null,
            'tax_category' => $validated['tax_category'] ?? null,
            'type' => $validated['type'],
            'is_active' => $validated['is_active'] ?? true,
            'prices_meta' => $validated['prices'] ?? [],
        ]);

        return redirect()->route('app.articles.show', [$company, $article])
            ->with('success', 'Article created.');
    }

    public function show(Company $company, Article $article)
    {
        return view('app.articles.show', compact('company', 'article'));
    }

    public function edit(Company $company, Article $article)
    {
        return view('app.articles.edit', compact('company', 'article'));
    }

    public function update(Request $request, Company $company, Article $article)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'tax_category' => 'nullable|string|max:50',
            'type' => 'required|string',
            'is_active' => 'boolean',
            'prices' => 'nullable|array',
        ]);

        $article->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'unit' => $validated['unit'] ?? null,
            'tax_category' => $validated['tax_category'] ?? null,
            'type' => $validated['type'],
            'is_active' => $validated['is_active'] ?? true,
            'prices_meta' => $validated['prices'] ?? [],
        ]);

        return redirect()->route('app.articles.show', [$company, $article])
            ->with('success', 'Article updated.');
    }

    public function destroy(Company $company, Article $article)
    {
        $article->delete();

        return redirect()->route('app.articles.index', $company)
            ->with('success', 'Article deleted.');
    }
}
