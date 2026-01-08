<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BarangReturn;
use App\Models\Category;
use App\Models\DataBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangReturnController extends Controller
{
    public function index()
    {
        $barangReturn = BarangReturn::with(['barang', 'category', 'user'])->latest()->get();
        return view('pages.kel_barang.b_return.index', compact('barangReturn'));
    }

    public function create()
    {
        $barangs = DataBarang::all();
        $categories = Category::all();
        return view('pages.kel_barang.b_return.create', compact('barangs', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:data_barangs,id',
            'category_id' => 'required|exists:categories,id',
            'tanggal_return' => 'required|date',
            'jumlah_return' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ]);

        BarangReturn::create([
            'barang_id' => $request->barang_id,
            'category_id' => $request->category_id,
            'tanggal_return' => $request->tanggal_return,
            'jumlah_return' => $request->jumlah_return,
            'deskripsi' => $request->deskripsi,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('kel_barang.b_return.index')->with('success', 'Catatan return berhasil disimpan.');
    }

    public function edit($id)
    {
        $return = BarangReturn::findOrFail($id);
        $barangs = DataBarang::all();
        $categories = Category::all();

        return view('pages.kel_barang.b_return.edit', compact('return', 'barangs', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:data_barangs,id',
            'category_id' => 'required|exists:categories,id',
            'tanggal_return' => 'required|date',
            'jumlah_return' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $return = BarangReturn::findOrFail($id);
            
            // Lakukan update
            $return->update([
                'barang_id' => $request->barang_id,
                'category_id' => $request->category_id,
                'tanggal_return' => $request->tanggal_return,
                'jumlah_return' => $request->jumlah_return,
                'deskripsi' => $request->deskripsi,
                'user_id' => Auth::id() ?? $return->user_id,
            ]);

            return redirect()->route('kel_barang.b_return.index')->with('success', 'Catatan return berhasil diperbarui.');
            
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal mengupdate: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $return = BarangReturn::findOrFail($id);
        $return->delete();

        return redirect()->route('kel_barang.b_return.index')->with('success', 'Catatan return berhasil dihapus.');
    }
}