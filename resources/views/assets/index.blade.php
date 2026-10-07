@extends('layouts.app')

@section('title', 'Master Aset')
@section('page-title', 'Master Aset')
@section('page-sub', 'Kelola daftar seluruh aset laboratorium')

@section('content')

{{-- Header + Action --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h2 style="font-size: 1.1rem; font-weight: 700; color: #f1f5f9; margin: 0;">Daftar Aset</h2>
        <p style="font-size: 0.82rem; color: #64748b; margin: 0;">Total {{ $assets->total() }} aset terdaftar</p>
    </div>
    <a href="{{ route('assets.create') }}" class="btn btn-primary-custom d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Tambah Aset
    </a>
</div>

{{-- Filter Bar --}}
<div class="card-dark card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('assets.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label mb-1">Cari Aset</label>
                <div class="input-group" style="border-radius: 10px; overflow: hidden;">
                    <span class="input-group-text" style="background: var(--bg-surface); border-color: var(--border-color); color: #64748b;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control form-control-dark"
                           placeholder="Nama barang, kode, atau kategori..."
                           value="{{ request('search') }}" style="border-left: none;">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Filter Kategori</label>
                <select name="kategori" class="form-select form-select-dark">
                    <option value="">— Semua Kategori —</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                @if(request()->hasAny(['search', 'kategori']))
                    <a href="{{ route('assets.index') }}" class="btn" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #94a3b8; border-radius: 10px;">
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
        @if($assets->isEmpty())
            <div class="text-center py-5" style="color: #475569;">
                <i class="bi bi-box-seam" style="font-size: 3rem;"></i>
                <h5 class="mt-3" style="color: #64748b;">Belum ada aset</h5>
                <p style="font-size: 0.85rem;">Mulai tambahkan aset laboratorium Anda.</p>
                <a href="{{ route('assets.create') }}" class="btn btn-primary-custom">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Aset Pertama
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
                            <th class="text-center">Stok Baik</th>
                            <th class="text-center">Stok Rusak</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assets as $index => $asset)
                            <tr>
                                <td style="color: #475569; font-size: 0.78rem;">{{ $assets->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge badge-soft-primary" style="border-radius: 6px; font-family: monospace; font-size: 0.78rem;">
                                        {{ $asset->kode_barang }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #f1f5f9;">{{ $asset->nama_barang }}</div>
                                    @if($asset->keterangan)
                                        <div style="font-size: 0.72rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                            {{ $asset->keterangan }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-soft-info" style="border-radius: 6px; font-size: 0.75rem;">{{ $asset->kategori }}</span>
                                </td>
                                <td class="text-center">
                                    @if($asset->stock_good == 0)
                                        <span class="fw-700" style="color: #f87171;">0</span>
                                    @elseif($asset->stock_good <= 3)
                                        <span class="stock-good-low">{{ $asset->stock_good }}</span>
                                    @elseif($asset->stock_good <= 10)
                                        <span class="stock-good-mid">{{ $asset->stock_good }}</span>
                                    @else
                                        <span class="stock-good-high">{{ $asset->stock_good }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="{{ $asset->stock_damaged > 0 ? 'stock-damaged' : '' }}" style="{{ $asset->stock_damaged == 0 ? 'color: #475569;' : '' }}">
                                        {{ $asset->stock_damaged }}
                                    </span>
                                </td>
                                <td class="text-center" style="color: #94a3b8; font-weight: 600;">
                                    {{ $asset->total_stock }}
                                </td>
                                <td class="text-center">
                                    @if($asset->stock_good == 0)
                                        <span class="badge badge-soft-danger" style="border-radius: 6px; font-size: 0.72rem;">Habis</span>
                                    @elseif($asset->stock_good <= 3)
                                        <span class="badge badge-soft-warning" style="border-radius: 6px; font-size: 0.72rem;">Kritis</span>
                                    @else
                                        <span class="badge badge-soft-success" style="border-radius: 6px; font-size: 0.72rem;">Tersedia</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <a href="{{ route('assets.show', $asset) }}" class="btn btn-sm" title="Detail"
                                           style="background: rgba(6,182,212,0.1); border: 1px solid rgba(6,182,212,0.2); color: #22d3ee; border-radius: 7px; padding: 0.3rem 0.55rem;">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-sm" title="Edit"
                                           style="background: rgba(79,70,229,0.1); border: 1px solid rgba(79,70,229,0.2); color: #818cf8; border-radius: 7px; padding: 0.3rem 0.55rem;">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Hapus aset \'{{ addslashes($asset->nama_barang) }}\'?\nSemua log terkait juga akan dihapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" title="Hapus"
                                                    style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #f87171; border-radius: 7px; padding: 0.3rem 0.55rem;">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($assets->hasPages())
                <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-top: 1px solid var(--border-color);">
                    <div style="font-size: 0.8rem; color: #64748b;">
                        Menampilkan {{ $assets->firstItem() }}–{{ $assets->lastItem() }} dari {{ $assets->total() }} aset
                    </div>
                    {{ $assets->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection
