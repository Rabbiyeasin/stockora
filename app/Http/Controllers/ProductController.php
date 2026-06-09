<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Store;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $products = Product::query()
            ->with(['category', 'store', 'inventory'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('sku', 'LIKE', "%{$search}%");
                });
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', filter_var($request->status, FILTER_VALIDATE_BOOLEAN));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $categories = Category::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only('search', 'category', 'status'),
        ]);
    }

    public function create(): Response
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $stores = Store::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Products/Create', [
            'categories' => $categories,
            'stores' => $stores,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'alert_qty' => 'required|integer|min:0',
            'initial_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        // Generate SKU before creating
        $validated['sku'] = $this->generateSKU();
        $validated['is_active'] = true;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $image->extension();
            $path = $image->storeAs('products', $filename, 'public');
            $validated['image'] = $path;
        }

        $product = Product::create($validated);

        // Create initial inventory
        Inventory::create([
            'product_id' => $product->id,
            'store_id' => $validated['store_id'],
            'quantity' => $validated['initial_stock'],
        ]);

        return redirect()
            ->route('app.products.index', ['client_id' => $request->route('client_id')])
            ->with('success', 'Product created successfully.');
    }

    public function edit(Request $request, string $client_id, Product $product): Response
    {
        $product->load(['category', 'store', 'inventory']);

        $categories = Category::orderBy('name')->get(['id', 'name']);
        $stores = Store::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'stores' => $stores,
        ]);
    }

    
    public function update(Request $request, string $client_id, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'alert_qty' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $image->extension();
            $path = $image->storeAs('products', $filename, 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()
            ->route('app.products.index', ['client_id' => $request->route('client_id')])
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request, string $client_id, Product $product): RedirectResponse
    {
        if ($product->inventory && $product->inventory->quantity > 0) {
            return back()->with('error', 'Cannot delete product with existing stock.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('app.products.index', ['client_id' => $request->route('client_id')])
            ->with('success', 'Product deleted successfully.');
    }

    private function generateSKU(): string
    {
        do {
            $sku = 'PRD-' . strtoupper(Str::random(8));
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }
}