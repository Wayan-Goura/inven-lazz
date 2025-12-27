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
    /**
<<<<<<< HEAD
     * Menampilkan daftar semua catatan return.
     */
    public function index()
    {
        $returns = BarangReturn::with(['barang', 'category', 'user'])->latest()->get();
        return view('barang_return.index', compact('returns'));
    }

    /**
     * Menampilkan form untuk mencatat return baru.
     */
    public function create()
    {
        $barangs = DataBarang::all();
        $categories = Category::all();
        return view('barang_return.create', compact('barangs', 'categories'));
    }

    /**
     * Menyimpan data return ke database (Hanya sebagai catatan).
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:data_barangs,id',
            'category_id' => 'required|exists:categories,id',
            'tanggal_retrun' => 'required|date',
            'jumlah_retrun' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ]);

        // Menyimpan data ke database
        BarangReturn::create([
            'barang_id' => $request->barang_id,
            'category_id' => $request->category_id,
            'tanggal_retrun' => $request->tanggal_retrun,
            'jumlah_retrun' => $request->jumlah_retrun,
            'deskripsi' => $request->deskripsi,
            'user_id' => Auth::id(), // Menggunakan ID pengguna saat ini
        ]);

        return redirect()->route('barang-return.index')->with('success', 'Catatan return berhasil disimpan.');
    }

    /**
     * Menampilkan form untuk mengedit catatan return.
     */
    public function edit($id)
    {
        $return = BarangReturn::findOrFail($id);
        $barangs = DataBarang::all();
        $categories = Category::all();

        return view('barang_return.edit', compact('return', 'barangs', 'categories'));
    }

    /**
     * Memperbarui data catatan return.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:data_barangs,id',
            'category_id' => 'required|exists:categories,id',
            'tanggal_retrun' => 'required|date',
            'jumlah_retrun' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ]);

        $return = BarangReturn::findOrFail($id);

        $return->update([
            'barang_id' => $request->barang_id,
            'category_id' => $request->category_id,
            'tanggal_retrun' => $request->tanggal_retrun,
            'jumlah_retrun' => $request->jumlah_retrun,
            'deskripsi' => $request->deskripsi,
            'user_id' => Auth::id(), 
        ]);

        return redirect()->route('barang-return.index')->with('success', 'Catatan return berhasil diperbarui.');
    }

    /**
     * Menghapus catatan return.
     */
    public function destroy($id)
    {
        $return = BarangReturn::findOrFail($id);
        $return->delete();

        return redirect()->route('barang-return.index')->with('success', 'Catatan return berhasil dihapus.');
    }
}