@extends('layouts.app')

@section('title', 'Tambah Aset')
@section('page-title', 'Tambah Aset Baru')
@section('page-sub', 'Input data aset laboratorium baru ke sistem')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('assets.index') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #94a3b8; border-radius: 8px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #f1f5f9; margin: 0;">Tambah Aset Baru</h2>
                <p style="font-size: 0.82rem; color: #64748b; margin: 0;">Isi semua field yang diperlukan</p>
            </div>
        </div>

        <div class="card-dark card">
            <div class="card-header d-flex align-items-center gap-2">
                <div style="width: 32px; height: 32px; background: rgba(79,70,229,0.15); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-box-seam" style="color: #818cf8;"></i>
                </div>
                <span style="font-weight: 600; color: #f1f5f9; font-size: 0.9rem;">Data Aset</span>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert-dark-danger alert mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <strong>Terdapat kesalahan input:</strong>
                        </div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li style="font-size: 0.85rem;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('assets.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label for="kode_barang" class="form-label">
                                Kode Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="kode_barang" name="kode_barang"
                                   class="form-control form-control-dark @error('kode_barang') border-danger @enderror"
                                   placeholder="Contoh: ELK-001"
                                   value="{{ old('kode_barang') }}"
                                   maxlength="50" required>
                            @error('kode_barang')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                            <div style="font-size: 0.73rem; color: #475569; margin-top: 0.3rem;">Kode unik (misal: ELK-001, KOM-002)</div>
                        </div>

                        <div class="col-md-8">
                            <label for="nama_barang" class="form-label">
                                Nama Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="nama_barang" name="nama_barang"
                                   class="form-control form-control-dark @error('nama_barang') border-danger @enderror"
                                   placeholder="Nama lengkap barang/aset"
                                   value="{{ old('nama_barang') }}"
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
                                   placeholder="Contoh: Elektronika, Komputer, Furnitur..."
                                   value="{{ old('kategori') }}"
                                   maxlength="100"
                                   list="kategori-list" required>
                            <datalist id="kategori-list">
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat }}">
                                @endforeach
                                <option value="Elektronika">
                                <option value="Komputer">
                                <option value="Jaringan">
                                <option value="Peralatan Kimia">
                                <option value="Furnitur">
                                <option value="Optik">
                                <option value="Mekanik">
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
                                   placeholder="0"
                                   value="{{ old('stock_good', 0) }}"
                                   min="0" required>
                            @error('stock_good')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="stock_damaged" class="form-label">
                                Stok Kondisi Rusak <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="stock_damaged" name="stock_damaged"
                                   class="form-control form-control-dark @error('stock_damaged') border-danger @enderror"
                                   placeholder="0"
                                   value="{{ old('stock_damaged', 0) }}"
                                   min="0" required>
                            @error('stock_damaged')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea id="keterangan" name="keterangan"
                                      class="form-control form-control-dark @error('keterangan') border-danger @enderror"
                                      rows="3"
                                      placeholder="Deskripsi singkat tentang aset ini (opsional)..."
                                      maxlength="500">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4 pt-3" style="border-top: 1px solid var(--border-color);">
                        <button type="submit" class="btn btn-primary-custom d-flex align-items-center gap-2">
                            <i class="bi bi-floppy2-fill"></i> Simpan Aset
                        </button>
                        <a href="{{ route('assets.index') }}" class="btn" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #94a3b8; border-radius: 10px; padding: 0.55rem 1.25rem;">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection
