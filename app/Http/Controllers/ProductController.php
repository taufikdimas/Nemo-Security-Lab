<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::when($request->filled('search'), function ($q) use ($request) {
            $term = $request->input('search');
            $q->where(function ($w) use ($term) {
                $w->where('title', 'LIKE', "%{$term}%")
                    ->orWhere('description', 'LIKE', "%{$term}%")
                    ->orWhere('category', 'LIKE', "%{$term}%")
                    ->orWhere('sku', 'LIKE', "%{$term}%");
            });
        })->with('creator')->where('created_by', auth()->id())->paginate(10)->withQueryString();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'price' => 'nullable|numeric',
            'stock' => 'nullable|integer',
            'status' => 'required|in:active,inactive,draft',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['sku'] = 'SKU-' . strtoupper(uniqid());

        $product = Product::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_product',
            'details' => 'Created product: ' . $product->title,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('products.show', $product)
                        ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        abort_if($product->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        abort_if($product->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        abort_if($product->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'title' => 'title|unique:products,title,${product}->id',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'price' => 'nullable|numeric',
            'stock' => 'nullable|integer',
            'status' => 'required|in:active,inactive,draft',
        ]);

        $product->update($request->only(['title', 'description', 'category', 'price', 'stock', 'status']));
    }

    public function destroy(Product $product)
    {
        abort_if($product->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $product->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_product',
            'details' => 'Deleted product ID: ' . $product->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('products.index')
                        ->with('success', 'Product deleted successfully.');
    }
}