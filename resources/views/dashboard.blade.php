@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-sub', 'Ringkasan kondisi inventaris laboratorium')

@section('content')

{{-- ═══════════ STAT CARDS ═══════════ --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card blue">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stat-icon blue"><i class="bi bi-box-seam-fill"></i></div>
                <span class="badge badge-soft-primary" style="border-radius: 8px; font-size: 0.7rem;">Total</span>
            </div>
            <div class="stat-value">{{ number_format($totalAssets) }}</div>
            <div class="stat-label">Jenis Aset Terdaftar</div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card green">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
                <span class="badge badge-soft-success" style="border-radius: 8px; font-size: 0.7rem;">Baik</span>
            </div>
            <div class="stat-value">{{ number_format($totalStockGood) }}</div>
            <div class="stat-label">Total Unit Kondisi Baik</div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card red">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stat-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <span class="badge badge-soft-danger" style="border-radius: 8px; font-size: 0.7rem;">Rusak</span>
            </div>
            <div class="stat-value">{{ number_format($totalStockDamaged) }}</div>
            <div class="stat-label">Total Unit Kondisi Rusak</div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card yellow">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stat-icon yellow"><i class="bi bi-stack"></i></div>
                <span class="badge badge-soft-warning" style="border-radius: 8px; font-size: 0.7rem;">Semua</span>
            </div>
            <div class="stat-value">{{ number_format($totalStock) }}</div>
            <div class="stat-label">Total Keseluruhan Unit</div>
        </div>
    </div>
</div>

{{-- ═══════════ MIDDLE ROW ═══════════ --}}
<div class="row g-4 mb-4">

    {{-- Recent Barang Masuk --}}
    <div class="col-xl-6">
        <div class="card-dark card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 32px; height: 32px; background: rgba(16,185,129,0.15); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-arrow-down-circle text-success" style="font-size: 0.9rem;"></i>
                    </div>
                    <span class="fw-600" style="font-size: 0.9rem; color: #f1f5f9;">Barang Masuk Terbaru</span>
                </div>
                <a href="{{ route('incoming.index') }}" class="btn btn-sm" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #34d399; border-radius: 8px; font-size: 0.75rem; padding: 0.3rem 0.75rem;">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentIncoming->isEmpty())
                    <div class="text-center py-5" style="color: #475569;">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0" style="font-size: 0.85rem;">Belum ada data barang masuk</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Sumber</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentIncoming as $log)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 500; color: #f1f5f9;">{{ $log->asset->nama_barang ?? '-' }}</div>
                                            <div style="font-size: 0.72rem; color: #64748b;">{{ $log->asset->kode_barang ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-success" style="border-radius: 6px;">+{{ $log->quantity }} unit</span>
                                        </td>
                                        <td style="color: #94a3b8; font-size: 0.82rem;">{{ $log->source ?: '-' }}</td>
                                        <td style="color: #94a3b8; font-size: 0.82rem;">{{ $log->tanggal->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent Laporan Rusak --}}
    <div class="col-xl-6">
        <div class="card-dark card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 32px; height: 32px; background: rgba(239,68,68,0.15); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-exclamation-triangle" style="color: #f87171; font-size: 0.9rem;"></i>
                    </div>
                    <span class="fw-600" style="font-size: 0.9rem; color: #f1f5f9;">Laporan Rusak Terbaru</span>
                </div>
                <a href="{{ route('damage.index') }}" class="btn btn-sm" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #f87171; border-radius: 8px; font-size: 0.75rem; padding: 0.3rem 0.75rem;">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentDamage->isEmpty())
                    <div class="text-center py-5" style="color: #475569;">
                        <i class="bi bi-shield-check" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0" style="font-size: 0.85rem;">Tidak ada laporan kerusakan</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Jenis</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDamage as $report)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 500; color: #f1f5f9;">{{ $report->asset->nama_barang ?? '-' }}</div>
                                            <div style="font-size: 0.72rem; color: #64748b;">{{ $report->asset->kode_barang ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-danger" style="border-radius: 6px;">{{ $report->quantity }} unit</span>
                                        </td>
                                        <td>
                                            @if($report->jenis_kerusakan === 'rusak')
                                                <span class="badge badge-soft-warning" style="border-radius: 6px;">Rusak</span>
                                            @else
                                                <span class="badge badge-soft-danger" style="border-radius: 6px;">Hilang</span>
                                            @endif
                                        </td>
                                        <td style="color: #94a3b8; font-size: 0.82rem;">{{ $report->tanggal->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ═══════════ BOTTOM ROW ═══════════ --}}
<div class="row g-4">

    {{-- Aset Stok Rendah --}}
    <div class="col-xl-8">
        <div class="card-dark card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 32px; height: 32px; background: rgba(245,158,11,0.15); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-battery-half" style="color: #fbbf24; font-size: 0.9rem;"></i>
                    </div>
                    <span class="fw-600" style="font-size: 0.9rem; color: #f1f5f9;">Aset Stok Rendah</span>
                </div>
                <a href="{{ route('assets.index') }}" class="btn btn-sm" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2); color: #fbbf24; border-radius: 8px; font-size: 0.75rem; padding: 0.3rem 0.75rem;">
                    Master Aset
                </a>
            </div>
            <div class="card-body p-0">
                @if($lowStockAssets->isEmpty())
                    <div class="text-center py-4" style="color: #475569; font-size: 0.85rem;">
                        <i class="bi bi-emoji-smile" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">Semua stok dalam kondisi aman</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Stok Baik</th>
                                    <th>Stok Rusak</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockAssets as $asset)
                                    <tr>
                                        <td>
                                            <span class="badge badge-soft-primary" style="border-radius: 6px; font-family: monospace;">{{ $asset->kode_barang }}</span>
                                        </td>
                                        <td style="font-weight: 500; color: #f1f5f9;">{{ $asset->nama_barang }}</td>
                                        <td>
                                            <span class="badge badge-soft-info" style="border-radius: 6px; font-size: 0.72rem;">{{ $asset->kategori }}</span>
                                        </td>
                                        <td>
                                            @if($asset->stock_good <= 2)
                                                <span class="stock-good-low">{{ $asset->stock_good }}</span>
                                            @elseif($asset->stock_good <= 5)
                                                <span class="stock-good-mid">{{ $asset->stock_good }}</span>
                                            @else
                                                <span class="stock-good-high">{{ $asset->stock_good }}</span>
                                            @endif
                                        </td>
                                        <td class="stock-damaged">{{ $asset->stock_damaged }}</td>
                                        <td>
                                            @if($asset->stock_good <= 2)
                                                <span class="badge badge-soft-danger" style="border-radius: 6px; font-size: 0.72rem;">Kritis</span>
                                            @elseif($asset->stock_good <= 5)
                                                <span class="badge badge-soft-warning" style="border-radius: 6px; font-size: 0.72rem;">Rendah</span>
                                            @else
                                                <span class="badge badge-soft-success" style="border-radius: 6px; font-size: 0.72rem;">Aman</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Summary Info --}}
    <div class="col-xl-4">
        <div class="card-dark card h-100">
            <div class="card-header">
                <span class="fw-600" style="font-size: 0.9rem; color: #f1f5f9;">
                    <i class="bi bi-info-circle me-2" style="color: #818cf8;"></i>Info Sistem
                </span>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background: rgba(79,70,229,0.08); border: 1px solid rgba(79,70,229,0.15);">
                        <div>
                            <div style="font-size: 0.75rem; color: #64748b;">Aset Stok Habis</div>
                            <div style="font-size: 1.4rem; font-weight: 700; color: #f87171;">{{ $emptyStockCount }}</div>
                        </div>
                        <i class="bi bi-dash-circle" style="font-size: 1.5rem; color: #f87171; opacity: 0.6;"></i>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.15);">
                        <div>
                            <div style="font-size: 0.75rem; color: #64748b;">Kondisi Baik (%)</div>
                            <div style="font-size: 1.4rem; font-weight: 700; color: #34d399;">
                                {{ $totalStock > 0 ? round(($totalStockGood / $totalStock) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <i class="bi bi-pie-chart" style="font-size: 1.5rem; color: #34d399; opacity: 0.6;"></i>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15);">
                        <div>
                            <div style="font-size: 0.75rem; color: #64748b;">Kondisi Rusak (%)</div>
                            <div style="font-size: 1.4rem; font-weight: 700; color: #f87171;">
                                {{ $totalStock > 0 ? round(($totalStockDamaged / $totalStock) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <i class="bi bi-exclamation-circle" style="font-size: 1.5rem; color: #f87171; opacity: 0.6;"></i>
                    </div>
                </div>

                <div class="mt-3">
                    <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">Aksi Cepat</div>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('incoming.create') }}" class="btn btn-sm d-flex align-items-center gap-2"
                           style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #34d399; border-radius: 8px; font-size: 0.82rem; padding: 0.5rem 0.75rem;">
                            <i class="bi bi-plus-circle-fill"></i> Input Barang Masuk
                        </a>
                        <a href="{{ route('damage.create') }}" class="btn btn-sm d-flex align-items-center gap-2"
                           style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #f87171; border-radius: 8px; font-size: 0.82rem; padding: 0.5rem 0.75rem;">
                            <i class="bi bi-exclamation-triangle-fill"></i> Laporkan Kerusakan
                        </a>
                        <a href="{{ route('assets.create') }}" class="btn btn-sm d-flex align-items-center gap-2"
                           style="background: rgba(79,70,229,0.1); border: 1px solid rgba(79,70,229,0.2); color: #818cf8; border-radius: 8px; font-size: 0.82rem; padding: 0.5rem 0.75rem;">
                            <i class="bi bi-box-seam-fill"></i> Tambah Aset Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
