@extends('layouts.app')

@section('title', 'Laporan Rusak/Hilang')
@section('page-title', 'Laporan Barang Rusak / Hilang')
@section('page-sub', 'Riwayat semua laporan kerusakan dan kehilangan aset')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h2 style="font-size: 1.1rem; font-weight: 700; color: #f1f5f9; margin: 0;">Laporan Kerusakan</h2>
        <p style="font-size: 0.82rem; color: #64748b; margin: 0;">Total {{ $reports->total() }} laporan tercatat</p>
    </div>
    <a href="{{ route('damage.create') }}" class="btn d-flex align-items-center gap-2"
       style="background: linear-gradient(135deg, #ef4444, #dc2626); border: none; color: #fff; border-radius: 10px; padding: 0.55rem 1.25rem; font-weight: 600; font-size: 0.875rem;">
        <i class="bi bi-plus-lg"></i> Laporkan Kerusakan
    </a>
</div>

{{-- Filter --}}
<div class="card-dark card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('damage.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
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
                <label class="form-label mb-1">Jenis</label>
                <select name="jenis" class="form-select form-select-dark">
                    <option value="">— Semua —</option>
                    <option value="rusak" {{ request('jenis') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="hilang" {{ request('jenis') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                </select>
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
                @if(request()->hasAny(['search', 'jenis', 'dari', 'sampai']))
                    <a href="{{ route('damage.index') }}" class="btn" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #94a3b8; border-radius: 10px;">
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
        @if($reports->isEmpty())
            <div class="text-center py-5" style="color: #475569;">
                <i class="bi bi-shield-check" style="font-size: 3rem; color: #34d399;"></i>
                <h5 class="mt-3" style="color: #64748b;">Tidak ada laporan kerusakan</h5>
                <p style="font-size: 0.85rem;">Semua aset dalam kondisi baik!</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-dark-custom mb-0">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Jenis</th>
                            <th>Catatan</th>
                            <th>Dilaporkan Oleh</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $index => $report)
                            <tr>
                                <td style="color: #475569; font-size: 0.78rem;">{{ $reports->firstItem() + $index }}</td>
                                <td>
                                    <a href="{{ route('assets.show', $report->asset_id) }}"
                                       style="color: #818cf8; text-decoration: none; font-family: monospace; font-size: 0.82rem;">
                                        {{ $report->asset->kode_barang ?? '-' }}
                                    </a>
                                </td>
                                <td>
                                    <div style="font-weight: 500; color: #f1f5f9;">{{ $report->asset->nama_barang ?? '-' }}</div>
                                    <div style="font-size: 0.72rem; color: #475569;">{{ $report->asset->kategori ?? '' }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-danger" style="border-radius: 6px; font-size: 0.82rem; padding: 0.35rem 0.65rem;">
                                        {{ $report->quantity }} unit
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($report->jenis_kerusakan === 'rusak')
                                        <span class="badge badge-soft-warning" style="border-radius: 6px; padding: 0.35rem 0.65rem;">
                                            <i class="bi bi-tools me-1"></i>Rusak
                                        </span>
                                    @else
                                        <span class="badge badge-soft-danger" style="border-radius: 6px; padding: 0.35rem 0.65rem;">
                                            <i class="bi bi-question-circle me-1"></i>Hilang
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size: 0.82rem; color: #94a3b8;">
                                        {{ $report->notes ? Str::limit($report->notes, 60) : '—' }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.82rem; color: #94a3b8;">{{ $report->user->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <div style="font-size: 0.85rem; color: #cbd5e1;">{{ $report->tanggal->format('d M Y') }}</div>
                                    <div style="font-size: 0.72rem; color: #475569;">{{ $report->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('damage.destroy', $report) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus log laporan ini?')">
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

            @if($reports->hasPages())
                <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-top: 1px solid var(--border-color);">
                    <div style="font-size: 0.8rem; color: #64748b;">
                        Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari {{ $reports->total() }} laporan
                    </div>
                    {{ $reports->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection
