<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 1.2cm; footer: page-footer; }
        body { font-family: 'Helvetica', sans-serif; font-size: 9pt; color: #333; line-height: 1.4; }
        .header-table { width: 100%; border-bottom: 3px solid #1a531d; margin-bottom: 20px; padding-bottom: 10px; }
        .brand-name { font-size: 20pt; font-weight: bold; color: #1a531d; }
        .report-title { text-align: right; font-size: 13pt; font-weight: bold; color: #555; text-transform: uppercase; }
        
        .meta-table { width: 100%; margin-bottom: 15px; background-color: #f9f9f9; border: 1px solid #eee; }
        .meta-table td { padding: 5px 10px; border: none; }
        
        .main-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .main-table th { background-color: #1a531d; color: #fff; padding: 10px 5px; border: 1px solid #144217; text-transform: uppercase; font-size: 8pt; }
        .main-table td { padding: 8px 5px; border: 1px solid #dee2e6; vertical-align: middle; word-wrap: break-word; }
        .main-table tbody tr:nth-child(even) { background-color: #f2f7f2; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier', monospace; font-weight: bold; color: #c0392b; }
        .status-return { color: #e67e22; font-weight: bold; }

        .summary-box { width: 40%; float: left; margin-top: 20px; border: 1px solid #1a531d; background-color: #f0fdf4; }
        .sig-container { width: 30%; float: right; text-align: center; margin-top: 20px; }
        #page-footer { text-align: center; font-size: 8pt; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td><span class="brand-name">Lazz Inventory</span><br><small>Inventory Return Report</small></td>
            <td class="report-title">Laporan Barang Return</td>
        </tr>
    </table>

    <table class="meta-table">
        <tr>
            <td width="15%">Pencetak:</td>
            <td width="35%"><strong>{{ Auth::user()->name }}</strong></td>
            <td width="15%">Tanggal Cetak:</td>
            <td width="35%"><strong>{{ date('d F Y') }}</strong></td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="30px">No</th>
                <th width="80px">Tanggal</th>
                <th>Nama Barang</th>
                <th width="90px">Kategori</th>
                <th width="60px">Jumlah</th>
                <th>Alasan / Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @php $totalReturn = 0; @endphp
            @forelse($barangReturn as $i => $return)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($return->getRawOriginal('tanggal_return'))) }}</td>
                    <td>{{ $return->dataBarang->nama_barang }}</td>
                    <td class="text-center">{{ $return->category->nama_category }}</td>
                    <td class="text-center status-return">{{ $return->getRawOriginal('jumlah_return') }}</td>
                    <td>{{ $return->getRawOriginal('deskripsi') }}</td>
                </tr>
                @php $totalReturn += $return->getRawOriginal('jumlah_return'); @endphp
            @empty
                <tr>
                    <td colspan="6" class="text-center">Data tidak tersedia</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL BARANG RETURN</td>
                <td class="text-center">{{ $totalReturn }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary-box">
        <table width="100%" style="padding: 10px;">
            <tr>
                <td>Total Record</td>
                <td class="text-right"><strong>{{ $barangReturn->count() }} Item</strong></td>
            </tr>
            <tr>
                <td>Total Qty Return</td>
                <td class="text-right"><strong>{{ $totalReturn }} Unit</strong></td>
            </tr>
        </table>
    </div>

    <div class="sig-container">
        <p>Bali, {{ date('d F Y') }}</p>
        <div style="height: 60px;"></div>
        <p><strong>( {{ Auth::user()->name }} )</strong></p>
        <hr>
        <small>Staff Gudang</small>
    </div>

    <htmlpagefooter name="page-footer">
        <div id="page-footer">Halaman {PAGENO} dari {nbpg} - Lazz Inventory</div>
    </htmlpagefooter>

</body>
</html>