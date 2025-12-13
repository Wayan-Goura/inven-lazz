<h5 class="mb-3 text-gray-800 font-weight-bold">
    Edit Barang Masuk (ID: {{ $id }})
</h5>

<form>

    <div class="row">

        <!-- KODE BARANG -->
        <div class="col-md-6 mb-3">
            <label>Kode Barang *</label>
            <input type="text"
                   class="form-control"
                   value="BRG001">
        </div>

        <!-- NAMA BARANG -->
        <div class="col-md-6 mb-3">
            <label>Nama Barang *</label>
            <input type="text"
                   class="form-control"
                   value="Helm Bogo Retro">
        </div>

        <!-- MERK -->
        <div class="col-md-6 mb-3">
            <label>Merk *</label>
            <input type="text"
                   class="form-control"
                   value="Bogo">
        </div>

        <!-- KATEGORI -->
        <div class="col-md-6 mb-3">
            <label>Kategori *</label>
            <select class="form-control">
                <option value="">-- Pilih Kategori --</option>
                <option value="Helm" selected>Helm</option>
                <option value="Aksesoris">Aksesoris</option>
                <option value="Oli">Oli</option>
            </select>
        </div>

        <!-- TANGGAL -->
        <div class="col-md-6 mb-3">
            <label>Tanggal Masuk *</label>
            <input type="date"
                   class="form-control"
                   value="2025-01-10">
        </div>

        <!-- JUMLAH -->
        <div class="col-md-6 mb-3">
            <label>Jumlah *</label>
            <input type="number"
                   class="form-control"
                   value="15">
        </div>

    </div>

    <!-- BUTTON -->
    <div class="text-right mt-3">
        <button type="button"
                onclick="closeModal()"
                class="btn btn-secondary btn-sm">
            Batal
        </button>

        <button type="button"
                class="btn btn-primary btn-sm">
            Update
        </button>
    </div>

</form>
