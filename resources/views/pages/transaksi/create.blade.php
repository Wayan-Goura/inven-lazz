@extends('layouts.app')
@section('content')

<div class="container-fluid">

    {{-- SweetAlert Notifikasi Sukses/Error dari Session --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#4e73df'
                });
            });
        </script>
    @endif

    <div class="mx-auto" style="max-width: 900px;">
        <div class="card shadow">
            <div class="card-header py-3 d-flex align-items-center bg-white">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-box-open mr-2"></i> Transaksi Barang
                </h6>
            </div>

            {{-- Menambahkan ID pada form agar bisa dipanggil oleh JavaScript --}}
            <form id="formTransaksi" action="{{ route('transaksi.store') }}" method="POST">
                @csrf

                <div class="card-body" style="max-height: calc(100vh - 260px); overflow-y: auto;">
                    <div class="row">

                        {{-- PILIH BARANG --}}
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Pilih Barang <span class="text-danger">*</span>
                                </label>
                                <select 
                                    name="data_barang_id" 
                                    id="data_barang_id" 
                                    class="form-control @error('data_barang_id') is-invalid @enderror"
                                    required
                                >
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach ($barangs as $barang)
                                        <option 
                                            value="{{ $barang->id }}"
                                            data-kode="{{ $barang->k_barang }}"
                                            data-stok="{{ $barang->jml_stok }}"
                                            {{ old('data_barang_id') == $barang->id ? 'selected' : '' }}
                                        >
                                            {{ $barang->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('data_barang_id')
                                    <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- KODE & STOK BARANG (Hanya Baca) --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">Kode Barang</label>
                                <input type="text" id="kode_barang" class="form-control bg-light" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">Stok Tersedia</label>
                                <input type="number" id="stok_barang" class="form-control bg-light" readonly>
                            </div>
                        </div>

                        {{-- JUMLAH --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Jumlah <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="jumlah" 
                                    id="jumlah_input"
                                    min="1"
                                    class="form-control @error('jumlah') is-invalid @enderror"
                                    value="{{ old('jumlah') }}"
                                    required
                                >
                                @error('jumlah')
                                    <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- TANGGAL --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Tanggal Transaksi <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    name="tanggal_transaksi"
                                    class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                                    value="{{ old('tanggal_transaksi') ?? date('Y-m-d') }}"
                                    required
                                >
                                @error('tanggal_transaksi')
                                    <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- TIPE TRANSAKSI --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">Tipe Transaksi <span class="text-danger">*</span></label>
                                <select name="tipe_transaksi" id="select-tipe" class="form-control @error('tipe_transaksi') is-invalid @enderror" required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="masuk" {{ old('tipe_transaksi') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                                    <option value="keluar" {{ old('tipe_transaksi') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                                </select>
                                @error('tipe_transaksi')
                                    <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- KODE TRANSAKSI --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">Kode Transaksi</label>
                                <input type="text" id="kode-transaksi" name="kode_transaksi" 
                                       class="form-control bg-light @error('kode_transaksi') is-invalid @enderror" 
                                       value="{{ old('kode_transaksi') }}" readonly required>
                                @error('kode_transaksi')
                                    <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- LOKASI --}}
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Lokasi <span class="text-danger">*</span>
                                </label>
                                <select 
                                    name="lokasi" 
                                    class="form-control @error('lokasi') is-invalid @enderror"
                                    required
                                >
                                    <option value="">-- Pilih Lokasi --</option>
                                    <option value="Shopee" {{ old('lokasi') == 'Shopee' ? 'selected' : '' }}>Shopee</option>
                                    <option value="Tiktok Shop" {{ old('lokasi') == 'Tiktok Shop' ? 'selected' : '' }}>Tiktok Shop</option>
                                    <option value="Toko Nephew" {{ old('lokasi') == 'Toko Nephew' ? 'selected' : '' }}>Toko Nephew</option>
                                    <option value="Toko Batubulan" {{ old('lokasi') == 'Toko Batubulan' ? 'selected' : '' }}>Toko Batubulan</option>
                                    <option value="Toko Klungkung" {{ old('lokasi') == 'Toko Klungkung' ? 'selected' : '' }}>Toko Klungkung</option>
                                    <option value="Gudang Klungkung" {{ old('lokasi') == 'Gudang Klungkung' ? 'selected' : '' }}>Gudang Klungkung</option>
                                </select>
                                @error('lokasi')
                                    <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer bg-white d-flex justify-content-end border-top">
                    <button type="button" class="btn btn-primary shadow-sm px-4" onclick="confirmTransaksi()">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Fungsi Alert Konfirmasi
function confirmTransaksi() {
    const tipe = document.getElementById('select-tipe').value;
    const barang = document.getElementById('data_barang_id').options[document.getElementById('data_barang_id').selectedIndex].text;
    const jumlah = document.getElementById('jumlah_input').value;

    if (!tipe || !jumlah || document.getElementById('data_barang_id').value == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Harap lengkapi semua field yang berbintang (*)'
        });
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Transaksi',
        html: `Apakah Anda yakin ingin menyimpan transaksi <b>${tipe.toUpperCase()}</b> <br> untuk barang <b>${barang}</b> sebanyak <b>${jumlah}</b>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formTransaksi').submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // --- Bagian 1: Generate Kode Transaksi Otomatis ---
    const selectTipe = document.getElementById('select-tipe');
    const inputKodeTrx = document.getElementById('kode-transaksi');

    if (selectTipe) {
        selectTipe.addEventListener('change', function() {
            let tipe = this.value;

            if (tipe) {
                inputKodeTrx.value = 'Memuat kode...';

                fetch(`{{ route('transaksi.generate-code') }}?type=${tipe}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal memuat data');
                        return response.json();
                    })
                    .then(data => {
                        inputKodeTrx.value = data.code;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        inputKodeTrx.value = 'Gagal memuat kode';
                        Swal.fire('Error', 'Terjadi kesalahan saat mengambil kode transaksi.', 'error');
                    });
            } else {
                inputKodeTrx.value = '';
            }
        });

        if (selectTipe.value && !inputKodeTrx.value) {
            selectTipe.dispatchEvent(new Event('change'));
        }
    }

    // --- Bagian 2: Update Detail Barang saat Pilih Barang ---
    const selectBarang = document.getElementById('data_barang_id');
    const kodeBarang = document.getElementById('kode_barang');
    const stokBarang = document.getElementById('stok_barang');

    if (selectBarang) {
        function updateDetailBarang() {
            const opt = selectBarang.options[selectBarang.selectedIndex];
            
            if (!opt || !opt.value) {
                kodeBarang.value = '';
                stokBarang.value = '';
                return;
            }
            
            kodeBarang.value = opt.getAttribute('data-kode') || '';
            stokBarang.value = opt.getAttribute('data-stok') || '0';
        }

        selectBarang.addEventListener('change', updateDetailBarang);
        
        if(selectBarang.value) updateDetailBarang();
    }
});
</script>
@endsection