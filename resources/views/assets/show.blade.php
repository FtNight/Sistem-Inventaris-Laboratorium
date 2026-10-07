@extends('layouts.app')

@section('title', 'Detail Aset — ' . $asset->nama_barang)
@section('page-title', 'Detail Aset')
@section('page-sub', $asset->kode_barang . ' — ' . $asset->nama_barang)

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('assets.index') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #94a3b8; border-radius: 8px;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div class="flex-fill">
        <h2 style="font-size: 1.1rem; font-weight: 700; color: #f1f5f9; margin: 0;">{{ $asset->nama_barang }}</h2>
        <p style="font-size: 0.82rem; color: #64748b; margin: 0;">{{ $asset->kode_barang }} &middot; {{ $asset->kategori }}</p>
    </div>
    <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary-custom d-flex align-items-center gap-2">
        <i class="bi bi-pencil"></i> Edit Aset
    </a>
</div>

<div class="row g-4 mb-4">
    {{-- Stok Baik --}}
    <div class="col-md-4">
        <div class="stat-card green">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
                <span class="badge badge-soft-success" style="border-radius: 8px;">Kondisi Baik</span>
            </div>
            <div class="stat-value">{{ $asset->stock_good }}</div>
            <div class="stat-label">Unit dalam kondisi baik</div>
        </div>
    </div>
    {{-- Stok Rusak --}}
    <div class="col-md-4">
        <div class="stat-card red">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stat-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <span class="badge badge-soft-danger" style="border-radius: 8px;">Kondisi Rusak</span>
            </div>
            <div class="stat-value">{{ $asset->stock_damaged }}</div>
            <div class="stat-label">Unit dalam kondisi rusak</div>
        </div>
    </div>
    {{-- Total --}}
    <div class="col-md-4">
        <div class="stat-card blue">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stat-icon blue"><i class="bi bi-stack"></i></div>
                <span class="badge badge-soft-primary" style="border-radius: 8px;">Total</span>
            </div>
            <div class="stat-value">{{ $asset->total_stock }}</div>
            <div class="stat-label">Total keseluruhan unit</div>
        </div>
    </div>
</div>

{{-- Keterangan --}}
@if($asset->keterangan)
<div class="card-dark card mb-4">
    <div class="card-body d-flex align-items-start gap-3 py-3 px-4">
        <i class="bi bi-info-circle" style="color: #818cf8; font-size: 1.1rem; margin-top: 0.1rem;"></i>
        <div>
            <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem;">Keterangan</div>
            <div style="color: #cbd5e1; font-size: 0.9rem;">{{ $asset->keterangan }}</div>
        </div>
    </div>
</div>
@endif

<div class="row g-4">
    {{-- Riwayat Barang Masuk --}}
    <div class="col-xl-6">
        <div class="card-dark card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 28px; height: 28px; background: rgba(16,185,129,0.15); border-radius: 7px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-arrow-down-circle text-success" style="font-size: 0.85rem;"></i>
                    </div>
                    <span style="font-weight: 600; color: #f1f5f9; font-size: 0.875rem;">Riwayat Barang Masuk</span>
                </div>
                <span class="badge badge-soft-success" style="border-radius: 6px;">{{ $asset->incomingGoodsLogs->count() }} log</span>
            </div>
            <div class="card-body p-0">
                @if($asset->incomingGoodsLogs->isEmpty())
                    <div class="text-center py-4" style="color: #475569; font-size: 0.85rem;">
                        <i class="bi bi-inbox"></i> Belum ada log barang masuk
                    </div>
                @else
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-dark-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Sumber</th>
                                    <th>Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asset->incomingGoodsLogs->sortByDesc('tanggal') as $log)
                                    <tr>
                                        <td style="font-size: 0.82rem; color: #cbd5e1; font-weight: 500;">{{ $log->tanggal->format('d M Y') }}</td>
                                        <td><span class="badge badge-soft-success" style="border-radius: 6px;">+{{ $log->quantity }}</span></td>
                                        <td style="font-size: 0.85rem; color: #f8fafc;">{{ $log->source ?: '-' }}</td>
                                        <td style="font-size: 0.85rem; color: #94a3b8;">{{ $log->user->name ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Riwayat Laporan Kerusakan --}}
    <div class="col-xl-6">
        <div class="card-dark card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 28px; height: 28px; background: rgba(239,68,68,0.15); border-radius: 7px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-exclamation-triangle" style="color: #f87171; font-size: 0.85rem;"></i>
                    </div>
                    <span style="font-weight: 600; color: #f1f5f9; font-size: 0.875rem;">Riwayat Laporan Rusak</span>
                </div>
                <span class="badge badge-soft-danger" style="border-radius: 6px;">{{ $asset->damageReports->count() }} log</span>
            </div>
            <div class="card-body p-0">
                @if($asset->damageReports->isEmpty())
                    <div class="text-center py-4" style="color: #94a3b8; font-size: 0.85rem;">
                        <i class="bi bi-shield-check"></i> Tidak ada laporan kerusakan
                    </div>
                @else
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-dark-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Jenis</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asset->damageReports->sortByDesc('tanggal') as $report)
                                    <tr>
                                        <td style="font-size: 0.82rem; color: #cbd5e1; font-weight: 500;">{{ $report->tanggal->format('d M Y') }}</td>
                                        <td><span class="badge badge-soft-danger" style="border-radius: 6px;">{{ $report->quantity }}</span></td>
                                        <td>
                                            @if($report->jenis_kerusakan === 'rusak')
                                                <span class="badge badge-soft-warning" style="border-radius: 6px; font-size: 0.72rem;">Rusak</span>
                                            @else
                                                <span class="badge badge-soft-danger" style="border-radius: 6px; font-size: 0.72rem;">Hilang</span>
                                            @endif
                                        </td>
                                        <td style="font-size: 0.82rem; color: #94a3b8; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $report->notes ?: '-' }}
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
</div>

@endsection
