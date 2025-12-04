<div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-96 max-h-[80vh] overflow-y-auto p-5 rounded-lg shadow-xl">

        <h2 class="text-lg font-semibold mb-3">Edit Barang Keluar</h2>

        <form>
            <input type="hidden" id="edit_id">

            <div class="mb-3">
                <label class="text-sm font-medium">Kode Barang</label>
                <input id="edit_kode" type="text" class="w-full border p-2 rounded">
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Nama Barang</label>
                <input id="edit_nama" type="text" class="w-full border p-2 rounded">
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Merk</label>
                <input id="edit_merk" type="text" class="w-full border p-2 rounded">
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Tanggal</label>
                <input id="edit_tanggal" type="date" class="w-full border p-2 rounded">
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Lokasi</label>
                <select id="edit_lokasi" class="w-full border p-2 rounded">
                    <option value="Ubud">Ubud</option>
                    <option value="Batubulan">Batubulan</option>
                    <option value="Klungkung">Klungkung</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Jumlah</label>
                <input id="edit_jumlah" type="number" class="w-full border p-2 rounded">
            </div>

            <div class="flex justify-end gap-2 mt-3">
                <button type="button" onclick="closeModal('modalEdit')" class="px-3 py-1 bg-gray-400 text-white rounded">Batal</button>
                <button class="px-3 py-1 bg-blue-600 text-white rounded">Update</button>
            </div>
        </form>

    </div>
</div>
