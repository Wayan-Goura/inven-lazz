<h2 class="text-xl font-bold mb-3">Edit Barang Masuk (ID: {{ $id }})</h2>

<form>

    <div class="mb-3">
        <label class="block text-sm font-medium">Kode Barang</label>
        <input type="text" class="w-full border rounded p-2" value="BRG001">
    </div>

    <div class="mb-3">
        <label class="block text-sm font-medium">Nama Barang</label>
        <input type="text" class="w-full border rounded p-2" value="Helm Bogo Retro">
    </div>

    <div class="mb-3">
        <label class="block text-sm font-medium">Merk</label>
        <input type="text" class="w-full border rounded p-2" value="Bogo">
    </div>

    <div class="mb-3">
        <label class="block text-sm font-medium">Tanggal Masuk</label>
        <input type="date" class="w-full border rounded p-2" value="2025-01-10">
    </div>

    <div class="mb-3">
        <label class="block text-sm font-medium">Jumlah</label>
        <input type="number" class="w-full border rounded p-2" value="15">
    </div>

    <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white rounded">
            Batal
        </button>

        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded">
            Update
        </button>
    </div>

</form>
