<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(){
        $barang = Barang::all();
        return view('layanan_penyimpanan', compact('barang'));
    }

    public function create(){
        return view('create');
    }

    public function storeadd(Request $request)
    {
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('barang', 'public');
        }
        Barang::create([
            'nama_barang' => $request->nama_barang,
            'merek' => $request->merek,
            'spesifikasi' => $request->spesifikasi,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id); 
        return view('edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $barang->nama_barang = $request->nama_barang;
        $barang->merek = $request->merek;
        $barang->spesifikasi = $request->spesifikasi;

        if ($request->hasFile('foto')) {
            $barang->foto = $request->file('foto')->store('barang', 'public');
        }

        $barang->save();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate!');
    }

    public function destroy($id)
{
    $barang = Barang::findOrFail($id);

    $barang->delete();
    
    return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
}

}
