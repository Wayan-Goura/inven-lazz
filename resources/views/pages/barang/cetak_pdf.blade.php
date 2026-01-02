<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        /* Pengaturan Kertas mPDF */
        @page {
            margin: 1.2cm;
            footer: page-footer;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }

        /* Kop Surat */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #1a531d;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .brand-name {
            font-size: 24pt;
            font-weight: bold;
            color: #1a531d;
            letter-spacing: -1px;
        }

        .report-title {
            text-align: right;
            font-size: 14pt;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
        }

        /* Informasi Metadata */
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 8px;
        }

        .meta-table td {
            padding: 8px 12px;
            font-size: 9pt;
            border: none;
        }

        .label { color: #777; width: 15%; }
        .value { font-weight: bold; color: #333; width: 35%; }

        /* Tabel Utama (Spreadsheet Style) */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .main-table th {
            background-color: #1a531d;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            padding: 12px 8px;
            border: 1px solid #144217;
            text-transform: uppercase;
            font-size: 8.5pt;
        }

        .main-table td {
            padding: 10px 8px;
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }

        /* Zebra Stripe */
        .main-table tbody tr:nth-child(even) {
            background-color: #f2f7f2;
        }

        /* Footer Tabel untuk Grand Total */
        .main-table tfoot tr td {
            background-color: #eee;
            font-weight: bold;
            border: 1px solid #ccc;
            padding: 12px 8px;
        }

        /* Utility */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier', monospace; font-weight: bold; color: #2980b9; }

        /* Kotak Ringkasan */
        .summary-wrapper {
            margin-top: 20px;
            width: 40%;
            float: left;
        }

        .summary-box {
            width: 100%;
            border: 2px solid #1a531d;
            background-color: #f0fdf4;
            border-collapse: collapse;
        }

        .summary-box td {
            padding: 8px 12px;
            border-bottom: 1px solid #dcfce7;
        }

        /* Tanda Tangan */
        .sig-container {
            margin-top: 30px;
            width: 30%;
            float: right;
            text-align: center;
        }

        .sig-space { height: 70px; }

        /* Footer Halaman */
        #page-footer {
            text-align: center;
            font-size: 8pt;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <span class="brand-name">LAZZ SYSTEM</span><br>
                <span style="font-size: 9pt; color: #666;">Inventory & Warehouse Management Report</span>
            </td>
            <td class="report-title">
                Laporan Data Barang
            </td>
        </tr>
    </table>

    <table class="meta-table">
        <tr>
            <td class="label">Dicetak oleh</td>
            <td width="5">:</td>
            <td class="value">{{ Auth::user()->name }}</td>
            <td class="label">Tanggal Cetak</td>
            <td width="5">:</td>
            <td class="value">{{ date('d F Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">ID Petugas</td>
            <td>:</td>
            <td class="value">#USR-{{ Auth::user()->id }}</td>
            <td class="label">Status Data</td>
            <td>:</td>
            <td class="value" style="color: #27ae60;">Verified (Original)</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="35px">No</th>
                <th width="100px">Kode Barang</th>
                <th>Deskripsi Barang</th>
                <th width="120px">Kategori</th>
                <th width="100px">Merek</th>
                <th width="90px">Total Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataBarangs as $index => $barang)
            <tr>
                <td class="text-center" style="color: #777;">{{ $index + 1 }}</td>
                <td class="text-center font-mono">{{ $barang->k_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td class="text-center">{{ $barang->category->nama_category ?? '—' }}</td>
                <td class="text-center">{{ $barang->merek }}</td>
                <td class="text-right"><strong>{{ number_format($barang->jml_stok, 0, ',', '.') }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 30px;">Data barang tidak ditemukan dalam database.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">TOTAL AKUMULASI STOK</td>
                <td class="text-right" style="color: #1a531d; font-size: 11pt;">
                    {{ number_format($dataBarangs->sum('jml_stok'), 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div style="width: 100%;">
        <div class="summary-wrapper">
            <h4 style="margin-bottom: 5px; color: #1a531d;">Ringkasan Laporan:</h4>
            <table class="summary-box">
                <tr>
                    <td style="font-size: 9pt;">Total Varian Barang</td>
                    <td class="text-right"><strong>{{ $dataBarangs->count() }} Jenis</strong></td>
                </tr>
                <tr>
                    <td style="font-size: 9pt;">Total Unit Keseluruhan</td>
                    <td class="text-right"><strong>{{ number_format($dataBarangs->sum('jml_stok'), 0, ',', '.') }} Unit</strong></td>
                </tr>
            </table>
        </div>

        <div class="sig-container">
            <p>Ubud, {{ date('d F Y') }}</p>
            <p style="margin-top: 5px;">Petugas Operasional,</p>
            <div class="sig-space"></div>
            <p><strong>( {{ Auth::user()->name }} )</strong></p>
            <p style="font-size: 8pt; color: #666; margin-top: -10px;">Authorized Signature</p>
        </div>
    </div>

    <htmlpagefooter name="page-footer">
        <div id="page-footer">
            Dokumen Inventaris LAZZ SYSTEM - Rahasia Perusahaan <br>
            Halaman {PAGENO} dari {nbpg}
        </div>
    </htmlpagefooter>

</body>
</html>