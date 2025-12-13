<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\Category;
use Illuminate\Http\Request;
use mpdfform;
use Mpdf\Mpdf;



class DataBarangController extends Controller
{
    public function index()
    {
        $dataBarangs = DataBarang::with('category')->paginate(10);

        $categories = Category::all(); 

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
            'merek' => 'required|string|max:255',
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

    public function edit($id)
    {
        $barang = DataBarang::findOrFail($id);// 2. Ambil semua kategori untuk dropdown/select input di form
        $categories = Category::all();// Pass instance DataBarang ($barang) dan daftar kategori ke view
        return view('pages.barang.edit', compact('barang', 'categories'));
    }

    /**
     * Memperbarui DataBarang tertentu di storage.
     * Mengambil Request dan ID barang dari route parameter.
     */
    public function update(Request $request, $id)
    {
        // 1. Ambil data barang berdasarkan ID yang akan diperbarui.
        $barang = DataBarang::findOrFail($id);

        // 2. Validasi untuk update: k_barang harus unik,
        //    tetapi abaikan record dengan ID saat ini ($barang->id)
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'merek' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            // Perhatikan penggunaan $barang->id di sini untuk pengecualian unik
            'k_barang' => 'required|string|unique:data_barangs,k_barang,' . $barang->id,
            'jml_stok' => 'required|integer|min:0',
        ]);

        // 3. Update model instance dengan data yang divalidasi
        $barang->update($validated);

        // 4. Redirect ke halaman index dengan pesan sukses
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

    // generate PDF using mPDF
    public function cetak_pdf()
    {
        $dataBarangs = DataBarang::with('category')->get();

        $data = [
            'dataBarangs' => $dataBarangs,
            'title' => 'Laporan Data Barang',
        ];
        // Render Blade View ke dalam String HTML
        $html = view('pages.barang.cetak_pdf', $data)->render();

        // inisialisasi mpdf

        try {

            // require_once __DIR__ . '/pdf/autoload.php';
            $mpdf = new Mpdf([                
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
            ]);
            $mpdf->SetHeader('Laporan Data Barang||{PAGENO}');
            $mpdf->SetFooter('Generated on {DATE d-m-Y}|Inven Lazz|{PAGENO}');

            //  Load String HTML ke dalam mpdf
            $mpdf->WriteHTML($html);
            $filename = 'laporan_data_barang_' . date('Ymd_His') . '.pdf';
            // Output PDF ke Browser
            $mpdf->Output($filename, 'I');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencetak pdf:' . $e->getMessage());
        }
    }
}