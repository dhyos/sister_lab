@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-xl rounded-2xl p-8 mt-10 border border-gray-100">
    <div class="mb-8 border-b pb-5 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Layanan Penyimpanan</h1>
            <p class="text-gray-500 mt-1 text-sm">Silahkan Kelola barang dengan baik</p>
        </div>
        <a href="{{ route('create.view') }}" class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-5 py-2.5 rounded-lg font-semibold shadow hover:scale-105 transition-transform duration-200">
            + Tambah Barang
        </a>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full border-collapse text-sm text-left text-gray-700">
            <thead class="bg-gradient-to-r from-blue-100 to-blue-50 text-gray-800 uppercase text-xs font-semibold tracking-wide">
                <tr>
                    <th class="px-5 py-3 border-b">Gambar</th>
                    <th class="px-5 py-3 border-b">Nama Barang</th>
                    <th class="px-5 py-3 border-b text-center">Merek</th>
                    <th class="px-5 py-3 border-b text-center">Spesifikasi</th>
                    <th class="px-5 py-3 border-b text-center">Tindakan</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-100">
            @foreach($barang as $b)
                <tr class="hover:bg-blue-50 transition-colors duration-200">
                    <td class="px-5 py-3">
                        <img src="" alt="barang" class="w-12 h-12 rounded-lg object-cover shadow">
                    </td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $b['nama_barang'] }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $b['merek'] }}</td>
                    <td class="px-5 py-3 text-center font-semibold text-gray-800">{{ $b['spesifikasi'] }}</td>
                    <td class="px-5 py-3 text-center space-x-2">
                        <a href="{{ route('barang.edit', ['id' => $b -> id_barang]) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Edit</a>
                       <form action="{{ route('barang.destroy', $b->id_barang) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus barang ini?')" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm bg-transparent border-none p-0">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
