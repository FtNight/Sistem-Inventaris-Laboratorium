<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Tampilkan daftar semua aset.
     */
    public function index(Request $request)
    {
        $query = Asset::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $assets    = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $kategoris = Asset::distinct()->pluck('kategori');

        return view('assets.index', compact('assets', 'kategoris'));
    }

    /**
     * Form tambah aset baru.
     */
    public function create()
    {
        $kategoris = Asset::distinct()->pluck('kategori');
        return view('assets.create', compact('kategoris'));
    }

    /**
     * Simpan aset baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang'   => 'required|string|max:50|unique:assets,kode_barang',
            'nama_barang'   => 'required|string|max:255',
            'kategori'      => 'required|string|max:100',
            'stock_good'    => 'required|integer|min:0',
            'stock_damaged' => 'required|integer|min:0',
            'keterangan'    => 'nullable|string|max:500',
        ]);

        Asset::create($validated);

        return redirect()->route('assets.index')
            ->with('success', "Aset '{$validated['nama_barang']}' berhasil ditambahkan.");
    }

    /**
     * Tampilkan detail aset.
     */
    public function show(Asset $asset)
    {
        $asset->load([
            'incomingGoodsLogs.user',
            'damageReports.user',
        ]);

        return view('assets.show', compact('asset'));
    }

    /**
     * Form edit aset.
     */
    public function edit(Asset $asset)
    {
        $kategoris = Asset::distinct()->pluck('kategori');
        return view('assets.edit', compact('asset', 'kategoris'));
    }

    /**
     * Simpan perubahan aset.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'kode_barang'   => 'required|string|max:50|unique:assets,kode_barang,' . $asset->id,
            'nama_barang'   => 'required|string|max:255',
            'kategori'      => 'required|string|max:100',
            'stock_good'    => 'required|integer|min:0',
            'stock_damaged' => 'required|integer|min:0',
            'keterangan'    => 'nullable|string|max:500',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')
            ->with('success', "Aset '{$asset->nama_barang}' berhasil diperbarui.");
    }

    /**
     * Hapus aset.
     */
    public function destroy(Asset $asset)
    {
        $nama = $asset->nama_barang;
        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', "Aset '{$nama}' berhasil dihapus.");
    }
}
