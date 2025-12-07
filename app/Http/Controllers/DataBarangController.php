<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\Category; // ⬅️ JANGAN LUPA IMPORT
use Illuminate\Http\Request;

class DataBarangController extends Controller
{
    public function index()
    {
        $dataBarangs = DataBarang::with('category')->paginate(10);

        $categories = Category::all(); // ← INI YANG WAJIB

        return view('pages.barang.index', compact('dataBarangs', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        $categories = Category::all();
        return view('pages.barang.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'k_barang' => 'required|string|unique:data_barangs,k_barang',
            'jml_stok' => 'required|integer|min:0',
        ]);

        DataBarang::create($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(DataBarang $dataBarang)
    // {
    //     return view('barang.show', compact('dataBarang'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataBarang $barang)
    {
        // Typically, you need to pass categories to the edit view for a dropdown/select input
        $categories = Category::all();
        return view('pages.barang.edit', compact('barang', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataBarang $barang)
    {
        // Validation for update: k_barang must be unique, but ignore the current record's ID
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'k_barang' => 'required|string|unique:data_barangs,k_barang,' . $barang->id,
            'jml_stok' => 'required|integer|min:0',
        ]);

        // Update the model instance with validated data
        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataBarang $barang)
    {
        // Delete the model instance
        $barang->delete();

        // Redirect back to the index page with a success message
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}