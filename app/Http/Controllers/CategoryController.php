<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $categories = Category::query()
            ->with('parent')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'ILIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
            'filters' => $request->only('search'),
        ]);
    }

    public function create(): Response
    {
        $parentCategories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Categories/Create', [
            'parentCategories' => $parentCategories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()
            ->route('app.categories.index', [
                'client_id' => $request->route('client_id')
            ])
            ->with('success', 'Category created successfully.');
    }

    public function edit(Request $request, string $client_id, string $categoryId): Response
    {
        $category = Category::findOrFail($categoryId);

        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Categories/Edit', [
            'category' => $category->load('parent'),
            'parentCategories' => $parentCategories,
            'client_id' => $client_id,
        ]);
    }

    public function update(Request $request, string $client_id, string $categoryId): RedirectResponse
    {
        $category = Category::findOrFail($categoryId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()
            ->route('app.categories.index', [
                'client_id' => $client_id
            ])
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Request $request, string $client_id, string $categoryId): RedirectResponse
    {
        $category = Category::findOrFail($categoryId);

        if ($category->products()->exists()) {
            return back()->with('error', 'Cannot delete category with products.');
        }

        if ($category->children()->exists()) {
            return back()->with('error', 'Cannot delete category with subcategories.');
        }

        $category->delete();

        return redirect()
            ->route('app.categories.index', [
                'client_id' => $client_id
            ])
            ->with('success', 'Category deleted successfully.');
    }
}