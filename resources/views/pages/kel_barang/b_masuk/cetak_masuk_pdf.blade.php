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
        .main-table td { padding: 8px 5px; border: 1px solid #dee2e6; vertical-align: middle; }
        .main-table tbody tr:nth-child(even) { background-color: #f2f7f2; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier', monospace; font-weight: bold; color: #2980b9; }
        .status-plus { color: #27ae60; font-weight: bold; }

        .summary-box { width: 35%; float: left; margin-top: 20px; border: 1px solid #1a531d; background-color: #f0fdf4; }
        .sig-container { width: 30%; float: right; text-align: center; margin-top: 20px; }
        #page-footer { text-align: center; font-size: 8pt; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td><span class="brand-name">LAZZ SYSTEM</span><br><small>Inventory Inbound Report</small></td>
            <td class="report-title">Laporan Barang Masuk</td>
        </tr>
    </table>

    <table class="meta-table">
        <tr>
            <td width="15%">Pencetak:</td>
            <td width="35%"><strong>{{ Auth::user()->name }}</strong></td>
            <td width="15%">Periode:</td>
            <td width="35%"><strong>{{ date('d F Y') }}</strong></td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="30px">No</th>
                <th width="90px">Kode Transaksi</th>
                <th width="80px">Tanggal</th>
                <th>Nama Barang</th>
                <th width="70px">Merek</th>
                <th width="60px">Masuk</th>
                <th width="80px">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @php $totalMasuk = 0; @endphp
            @foreach($transaksis as $item)
                @foreach($item->detailTransaksis as $index => $detail)
                <tr>
                    <td class="text-center">{{ $loop->parent->iteration }}</td>
                    <td class="text-center font-mono">{{ $item->kode_transaksi }}</td>
                    <td class="text-center">{{ date('d/m/y', strtotime($item->tanggal_transaksi)) }}</td>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td class="text-center">{{ $detail->barang->merek }}</td>
                    <td class="text-center status-plus">+{{ $detail->jumlah }}</td>
                    <td class="text-center">{{ $item->lokasi }}</td>
                </tr>
                @php $totalMasuk += $detail->jumlah; @endphp
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="5" class="text-right">TOTAL BARANG MASUK</td>
                <td class="text-center">{{ $totalMasuk }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary-box">
        <table width="100%" style="padding: 10px;">
            <tr><td>Total Transaksi</td><td class="text-right"><strong>{{ $transaksis->count() }}</strong></td></tr>
            <tr><td>Total Qty Masuk</td><td class="text-right"><strong>{{ $totalMasuk }} Unit</strong></td></tr>
        </table>
    </div>

    <div class="sig-container">
        <p>Jakarta, {{ date('d F Y') }}</p>
        <div style="height: 60px;"></div>
        <p><strong>( {{ Auth::user()->name }} )</strong></p>
        <hr>
        <small>Logistics Officer</small>
    </div>

    <htmlpagefooter name="page-footer">
        <div id="page-footer">Halaman {PAGENO} dari {nbpg} - LAZZ System</div>
    </htmlpagefooter>

</body>
</html>