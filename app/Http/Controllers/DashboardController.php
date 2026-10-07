<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\IncomingGoodsLog;
use App\Models\DamageReport;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik ringkasan dari kolom master tabel assets
        $totalAssets      = Asset::count();
        $totalStockGood   = Asset::sum('stock_good');
        $totalStockDamaged = Asset::sum('stock_damaged');
        $totalStock       = $totalStockGood + $totalStockDamaged;

        // Data untuk chart / recent activity
        $recentIncoming = IncomingGoodsLog::with(['asset', 'user'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $recentDamage = DamageReport::with(['asset', 'user'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Aset dengan stok baik terendah (waspada)
        $lowStockAssets = Asset::where('stock_good', '>', 0)
            ->orderBy('stock_good', 'asc')
            ->take(5)
            ->get();

        // Aset dengan stok habis
        $emptyStockCount = Asset::where('stock_good', 0)->count();

        return view('dashboard', compact(
            'totalAssets',
            'totalStockGood',
            'totalStockDamaged',
            'totalStock',
            'recentIncoming',
            'recentDamage',
            'lowStockAssets',
            'emptyStockCount'
        ));
    }
}
