@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Riwayat Barang Masuk')
@section('page-sub', 'Log semua penerimaan barang laboratorium')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h2 style="font-size: 1.1rem; font-weight: 700; color: #f1f5f9; margin: 0;">Barang Masuk</h2>
        <p style="font-size: 0.82rem; color: #64748b; margin: 0;">Total {{ $logs->total() }} transaksi tercatat</p>
    </div>
    <a href="{{ route('incoming.create') }}" class="btn btn-primary-custom d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Input Barang Masuk
    </a>
</div>

{{-- Filter --}}
<div class="card-dark card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('incoming.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Cari Barang</label>
                <div class="input-group">
                    <span class="input-group-text" style="background: var(--bg-surface); border-color: var(--border-color); color: #64748b;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control form-control-dark"
                           placeholder="Nama atau kode barang..."
                           value="{{ request('search') }}" style="border-left: none;">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control form-control-dark" value="{{ request('dari') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control form-control-dark" value="{{ request('sampai') }}">
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                @if(request()->hasAny(['search', 'dari', 'sampai']))
                    <a href="{{ route('incoming.index') }}" class="btn" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #94a3b8; border-radius: 10px;">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card-dark card">
    <div class="card-body p-0">
        @if($logs->isEmpty())
            <div class="text-center py-5" style="color: #475569;">
                <i class="bi bi-arrow-down-circle" style="font-size: 3rem;"></i>
                <h5 class="mt-3" style="color: #64748b;">Belum ada data barang masuk</h5>
                <p style="font-size: 0.85rem;">Mulai catat penerimaan barang laboratorium.</p>
                <a href="{{ route('incoming.create') }}" class="btn btn-primary-custom">
                    <i class="bi bi-plus-lg me-2"></i>Input Pertama
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-dark-custom mb-0">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th class="text-center">Jumlah Masuk</th>
                            <th>Sumber / Keterangan</th>
                            <th>Dicatat Oleh</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $index => $log)
                            <tr>
                                <td style="color: #94a3b8; font-size: 0.8rem; font-weight: 500;">{{ $logs->firstItem() + $index }}</td>
                                <td>
                                    <a href="{{ route('assets.show', $log->asset_id) }}"
                                       style="color: #a5b4fc; text-decoration: none; font-family: monospace; font-size: 0.82rem; font-weight: 600;">
                                        {{ $log->asset->kode_barang ?? '-' }}
                                    </a>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #f8fafc;">{{ $log->asset->nama_barang ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-soft-info" style="border-radius: 6px; font-size: 0.75rem;">
                                        {{ $log->asset->kategori ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-success" style="border-radius: 6px; font-size: 0.82rem; padding: 0.35rem 0.65rem;">
                                        +{{ $log->quantity }} unit
                                    </span>
                                </td>
                                <td>
                                    @if($log->source)
                                        <div style="font-size: 0.85rem; color: #e2e8f0; font-weight: 500;">{{ $log->source }}</div>
                                    @endif
                                    @if($log->keterangan)
                                        <div style="font-size: 0.78rem; color: #94a3b8;">{{ Str::limit($log->keterangan, 50) }}</div>
                                    @endif
                                    @if(!$log->source && !$log->keterangan)
                                        <span style="color: #64748b; font-size: 0.82rem;">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size: 0.85rem; color: #cbd5e1;">{{ $log->user->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <div style="font-size: 0.85rem; color: #f1f5f9; font-weight: 500;">{{ $log->tanggal->format('d M Y') }}</div>
                                    <div style="font-size: 0.75rem; color: #94a3b8;">{{ $log->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('incoming.destroy', $log) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus log barang masuk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" title="Hapus Log"
                                                style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #f87171; border-radius: 7px; padding: 0.3rem 0.55rem;">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-top: 1px solid var(--border-color);">
                    <div style="font-size: 0.8rem; color: #64748b;">
                        Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} log
                    </div>
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection
