<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\DamageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DamageReportController extends Controller
{
    /**
     * Tampilkan riwayat laporan barang rusak/hilang.
     */
    public function index(Request $request)
    {
        $query = DamageReport::with(['asset', 'user']);

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('asset', function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        // Filter jenis kerusakan
        if ($request->filled('jenis')) {
            $query->where('jenis_kerusakan', $request->jenis);
        }

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->where('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->where('tanggal', '<=', $request->sampai);
        }

        $reports = $query->orderByDesc('tanggal')->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('damage.index', compact('reports'));
    }

    /**
     * Form pelaporan barang rusak/hilang.
     */
    public function create()
    {
        // Hanya tampilkan aset yang masih memiliki stok baik > 0
        $assets = Asset::where('stock_good', '>', 0)->orderBy('nama_barang')->get();
        return view('damage.create', compact('assets'));
    }

    /**
     * Simpan laporan kerusakan dan update stok asset.
     * 
     * Logika kalkulasi:
     * - Catat log kerusakan
     * - Kurangi stock_good sejumlah quantity
     * - Tambahkan stock_damaged sejumlah quantity (khusus jenis 'rusak')
     * - Jika 'hilang', stock_good dikurangi saja (barang tidak ada)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id'         => 'required|exists:assets,id',
            'quantity'         => 'required|integer|min:1',
            'jenis_kerusakan'  => 'required|in:rusak,hilang',
            'notes'            => 'nullable|string|max:500',
            'tanggal'          => 'required|date',
        ]);

        // Cek apakah stok cukup
        $asset = Asset::findOrFail($validated['asset_id']);

        if ($asset->stock_good < $validated['quantity']) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity' => "Stok baik tidak mencukupi. Stok baik saat ini: {$asset->stock_good} unit."
                ]);
        }

        DB::transaction(function () use ($validated, $asset) {
            // 1. Buat log laporan kerusakan
            DamageReport::create([
                'asset_id'        => $validated['asset_id'],
                'user_id'         => auth()->id() ?? 1,
                'quantity'        => $validated['quantity'],
                'jenis_kerusakan' => $validated['jenis_kerusakan'],
                'notes'           => $validated['notes'] ?? null,
                'tanggal'         => $validated['tanggal'],
            ]);

            // 2. Kurangi stock_good (berlaku untuk rusak & hilang)
            $asset->decrement('stock_good', $validated['quantity']);

            // 3. Jika RUSAK: tambah ke stock_damaged (barang masih ada, hanya rusak)
            //    Jika HILANG: tidak ditambah ke stock_damaged (barang benar-benar hilang)
            if ($validated['jenis_kerusakan'] === 'rusak') {
                $asset->increment('stock_damaged', $validated['quantity']);
            }
        });

        return redirect()->route('damage.index')
            ->with('success', "Laporan kerusakan '{$asset->nama_barang}' sejumlah {$validated['quantity']} berhasil dicatat.");
    }

    /**
     * Hapus log laporan (tidak mempengaruhi stok master).
     */
    public function destroy(DamageReport $damage)
    {
        $nama = $damage->asset->nama_barang ?? 'Aset';
        $damage->delete();

        return redirect()->route('damage.index')
            ->with('success', "Log laporan kerusakan untuk '{$nama}' berhasil dihapus.");
    }
}
