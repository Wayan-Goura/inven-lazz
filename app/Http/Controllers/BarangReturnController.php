<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BarangReturn;
use App\Models\Category;
use App\Models\DataBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class BarangReturnController extends Controller
{
    public function index()
    {
        $barangReturn = BarangReturn::with(['dataBarang', 'category', 'user'])->latest()->get();
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
        $return = BarangReturn::findOrFail($id);

        if (auth()->user()->role !== 'super_admin' && $return->is_disetujui) {
            return redirect()->route('kel_barang.b_return.index')
                ->with('error', 'Persetujuan sedang diproses.');
        }

        $validated = $request->validate([
            'barang_id' => 'required|exists:data_barangs,id',
            'category_id' => 'required|exists:categories,id',
            'tanggal_return' => 'required|date',
            'jumlah_return' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',

        ]);

            $return->update([
                'pending_perubahan' => $validated,
                'is_disetujui' => true,
            ]);
            return redirect()
            ->route('kel_barang.b_return.index')
            ->with('success', 'Perubahan data telah diajukan .');
        
    }

    public function destroy($id)
    {
        $return = BarangReturn::findOrFail($id);

        // Jika sudah menunggu persetujuan, tidak boleh ajukan lagi
        if ($return->is_disetujui) {
            return redirect()
                ->route('kel_barang.b_return.index')
                ->with('error', 'Data ini sedang menunggu persetujuan.');
        }

        // AJUKAN HAPUS (BUKAN DELETE LANGSUNG)
        $return->update([
            'pending_perubahan' => [
                'is_delete' => true
            ],
            'is_disetujui' => true,
        ]);

        return redirect()
            ->route('kel_barang.b_return.index')
            ->with('success', 'Penghapusan data telah diajukan.');
    }

    public function cetakPDF()
    {
        // Ambil data yang sama dengan index
        $barangReturn = BarangReturn::with(['dataBarang', 'category', 'user'])->latest()->get();
        $title = "Laporan Barang Return";

        // Render view ke dalam bentuk HTML string
        $html = view('pages.kel_barang.b_return.cetak_return_pdf', compact('barangReturn', 'title'))->render();
        // Inisialisasi mPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => 0,
            'margin_footer' => 5,
            'orientation' => 'P'
        ]);

        // Menulis HTML ke PDF
        $mpdf->WriteHTML($html);

        // Output ke browser (Inline)
        return $mpdf->Output('Laporan_Barang_Return_' . date('YmdHis') . '.pdf', 'I');
    }
}