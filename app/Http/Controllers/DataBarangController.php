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