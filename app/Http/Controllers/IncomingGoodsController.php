<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\IncomingGoodsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomingGoodsController extends Controller
{
    /**
     * Tampilkan riwayat barang masuk.
     */
    public function index(Request $request)
    {
        $query = IncomingGoodsLog::with(['asset', 'user']);

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('asset', function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->where('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->where('tanggal', '<=', $request->sampai);
        }

        $logs = $query->orderByDesc('tanggal')->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('incoming.index', compact('logs'));
    }

    /**
     * Form input barang masuk.
     */
    public function create()
    {
        $assets = Asset::orderBy('nama_barang')->get();
        return view('incoming.create', compact('assets'));
    }

    /**
     * Simpan data barang masuk dan update stok asset.
     * 
     * Logika kalkulasi:
     * - Catat log barang masuk
     * - Tambahkan quantity ke stock_good pada tabel assets (master stok)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id'    => 'required|exists:assets,id',
            'quantity'    => 'required|integer|min:1',
            'source'      => 'nullable|string|max:255',
            'tanggal'     => 'required|date',
            'keterangan'  => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Buat log barang masuk
            IncomingGoodsLog::create([
                'asset_id'   => $validated['asset_id'],
                'user_id'    => auth()->id() ?? 1, // fallback jika belum auth
                'quantity'   => $validated['quantity'],
                'source'     => $validated['source'],
                'tanggal'    => $validated['tanggal'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // 2. Tambahkan ke stock_good pada master aset (kalkulasi otomatis)
            Asset::where('id', $validated['asset_id'])
                ->increment('stock_good', $validated['quantity']);
        });

        $asset = Asset::find($validated['asset_id']);

        return redirect()->route('incoming.index')
            ->with('success', "Barang masuk '{$asset->nama_barang}' sejumlah {$validated['quantity']} berhasil dicatat. Stok baik sekarang: {$asset->fresh()->stock_good}.");
    }

    /**
     * Hapus log barang masuk (tidak mempengaruhi stok karena stok di master).
     */
    public function destroy(IncomingGoodsLog $incoming)
    {
        $nama = $incoming->asset->nama_barang ?? 'Aset';
        $incoming->delete();

        return redirect()->route('incoming.index')
            ->with('success', "Log barang masuk untuk '{$nama}' berhasil dihapus.");
    }
}
