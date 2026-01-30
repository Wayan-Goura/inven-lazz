<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $categories = Category::query();

        $perPage = $request->get('per_page', 10);
        if ($perPage === 'all') {
            $perPage = $categories->count();
        }

        $categories = $categories->paginate((int) $perPage)->withQueryString();

        return view('pages.kel_barang.catagory.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.kel_barang.catagory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_category' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('kel_barang.catagory.index')->with('success', 'Category berhasil ditambahkan.');   
    }

    /**
     * Display the specified resource.
     */
    // public function show(Category $category)
    // {
        
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('pages.kel_barang.catagory.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nama_category' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('kel_barang.catagory.index')->with('success', 'Category berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('kel_barang.catagory.index')->with('success', 'Category berhasil dihapus.');
    }
}   
