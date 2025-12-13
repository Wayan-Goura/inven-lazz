<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        /* CSS KHUSUS UNTUK MPDF */
        body {
            font-family: sans-serif;
            font-size: 11pt;
            margin-top: 25mm; /* Penting karena header sudah disetel */
            margin-bottom: 25mm; /* Penting karena footer sudah disetel */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        h1 {
            text-align: center;
            color: #1A531D; /* Warna hijau gelap */
        }
        .header-content {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <div class="header-content">
        <h1>LAPORAN DATA BARANG LAZZ</h1>
        <P>Tanggal Cetak: {{ date('Y-m-d') }}</P>
        {{-- <P>DARI TANGGAL {{ $fromDate }} SAMPAI TANGGAL {{ $toDate }}</P> --}}
        
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="15%">Kode Barang</th>
                <th width="20%">Nama Barang</th>
                <th width="40%">katagori</th>
                <th width="20%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataBarangs as $index => $barang)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $barang->k_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->category?->nama_category  }}</td>
                    <td>{{ $barang->jml_stok }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="page-break-after: always;"></div>
    
    <h2>Informasi Tambahan (Halaman 2)</h2>
    <p>Ini adalah halaman kedua, mPDF berhasil menangani pemisahan halaman.</p>

</body>
</html>