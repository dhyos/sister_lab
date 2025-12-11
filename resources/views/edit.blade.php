@extends('layouts.app')

<form action="{{ route('barang.update', $barang->id_barang) }}" method="POST" class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8 space-y-6">
    @csrf
    @method('PUT')
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Edit Data Barang</h2>
    <div>
        <label class="block text-gray-700 font-semibold mb-2">Nama Barang</label>
        <input type="text" name="nama_barang" placeholder="Masukkan nama barang"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
    </div>

    <div>
        <label class="block text-gray-700 font-semibold mb-2">Merek</label>
        <input type="text" name="merek" placeholder="Masukkan merek"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
    </div>

    <div>
        <label class="block text-gray-700 font-semibold mb-2">Spesifikasi</label>
        <textarea name="spesifikasi" placeholder="Masukkan spesifikasi barang"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 h-24 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"></textarea>
    </div>

    <div>
        <label class="block text-gray-700 font-semibold mb-2">Foto</label>
        <input type="file" name="foto"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
    </div>

    <div class="text-right">
        <a href="{{ route('barang.index') }}" class="bg-gradient-to-r from-yellow-600 to-yellow-500 text-black font-semibold px-6 py-2 rounded-lg shadow hover:scale-105 transform transition-transform duration-200">Kembali</a>
        <button type="submit"
            class="bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold px-6 py-2 rounded-lg shadow hover:scale-105 transform transition-transform duration-200">
            Simpan
        </button>
    </div>
</form>
