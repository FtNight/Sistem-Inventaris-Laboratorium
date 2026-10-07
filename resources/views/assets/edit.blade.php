@extends('layouts.app')

@section('title', 'Edit Aset — ' . $asset->nama_barang)
@section('page-title', 'Edit Aset')
@section('page-sub', 'Perbarui informasi aset: ' . $asset->nama_barang)

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('assets.index') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #94a3b8; border-radius: 8px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #f1f5f9; margin: 0;">Edit Aset</h2>
                <p style="font-size: 0.82rem; color: #64748b; margin: 0;">{{ $asset->kode_barang }} — {{ $asset->nama_barang }}</p>
            </div>
        </div>

        <div class="card-dark card">
            <div class="card-header d-flex align-items-center gap-2">
                <div style="width: 32px; height: 32px; background: rgba(79,70,229,0.15); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-pencil-square" style="color: #818cf8;"></i>
                </div>
                <span style="font-weight: 600; color: #f1f5f9; font-size: 0.9rem;">Edit Data Aset</span>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert-dark-danger alert mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <strong>Terdapat kesalahan:</strong>
                        </div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li style="font-size: 0.85rem;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('assets.update', $asset) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label for="kode_barang" class="form-label">
                                Kode Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="kode_barang" name="kode_barang"
                                   class="form-control form-control-dark @error('kode_barang') border-danger @enderror"
                                   value="{{ old('kode_barang', $asset->kode_barang) }}"
                                   maxlength="50" required>
                            @error('kode_barang')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label for="nama_barang" class="form-label">
                                Nama Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="nama_barang" name="nama_barang"
                                   class="form-control form-control-dark @error('nama_barang') border-danger @enderror"
                                   value="{{ old('nama_barang', $asset->nama_barang) }}"
                                   maxlength="255" required>
                            @error('nama_barang')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="kategori" class="form-label">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="kategori" name="kategori"
                                   class="form-control form-control-dark @error('kategori') border-danger @enderror"
                                   value="{{ old('kategori', $asset->kategori) }}"
                                   maxlength="100"
                                   list="kategori-list" required>
                            <datalist id="kategori-list">
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat }}">
                                @endforeach
                            </datalist>
                            @error('kategori')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="stock_good" class="form-label">
                                Stok Kondisi Baik <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="stock_good" name="stock_good"
                                   class="form-control form-control-dark @error('stock_good') border-danger @enderror"
                                   value="{{ old('stock_good', $asset->stock_good) }}"
                                   min="0" required>
                            @error('stock_good')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                            <div style="font-size: 0.73rem; color: #475569; margin-top: 0.3rem;">
                                ⚠️ Ubah manual jika perlu koreksi stok
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="stock_damaged" class="form-label">
                                Stok Kondisi Rusak <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="stock_damaged" name="stock_damaged"
                                   class="form-control form-control-dark @error('stock_damaged') border-danger @enderror"
                                   value="{{ old('stock_damaged', $asset->stock_damaged) }}"
                                   min="0" required>
                            @error('stock_damaged')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea id="keterangan" name="keterangan"
                                      class="form-control form-control-dark"
                                      rows="3"
                                      maxlength="500">{{ old('keterangan', $asset->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4 pt-3" style="border-top: 1px solid var(--border-color);">
                        <button type="submit" class="btn btn-primary-custom d-flex align-items-center gap-2">
                            <i class="bi bi-floppy2-fill"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('assets.index') }}" class="btn" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #94a3b8; border-radius: 10px; padding: 0.55rem 1.25rem;">
                            Batal
                        </a>
                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="ms-auto"
                              onsubmit="return confirm('Hapus aset ini? Semua log terkait juga akan dihapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn d-flex align-items-center gap-2"
                                    style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #f87171; border-radius: 10px; padding: 0.55rem 1rem;">
                                <i class="bi bi-trash3-fill"></i> Hapus Aset
                            </button>
                        </form>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection
