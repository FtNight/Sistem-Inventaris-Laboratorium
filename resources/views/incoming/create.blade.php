@extends('layouts.app')

@section('title', 'Input Barang Masuk')
@section('page-title', 'Input Barang Masuk')
@section('page-sub', 'Catat penerimaan barang dan otomatis update stok')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-7">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('incoming.index') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #94a3b8; border-radius: 8px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #f1f5f9; margin: 0;">Input Barang Masuk</h2>
                <p style="font-size: 0.82rem; color: #64748b; margin: 0;">Stok baik aset akan otomatis bertambah</p>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="d-flex align-items-start gap-3 p-3 mb-4 rounded-3" style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2);">
            <i class="bi bi-info-circle-fill" style="color: #34d399; font-size: 1.1rem; margin-top: 0.1rem;"></i>
            <div>
                <div style="font-size: 0.85rem; font-weight: 600; color: #34d399;">Kalkulasi Otomatis</div>
                <div style="font-size: 0.8rem; color: #64748b; line-height: 1.5;">
                    Saat form ini disimpan, jumlah yang diinput akan <strong style="color: #94a3b8;">otomatis ditambahkan</strong>
                    ke kolom <em>Stok Baik</em> pada master aset yang dipilih.
                </div>
            </div>
        </div>

        <div class="card-dark card">
            <div class="card-header d-flex align-items-center gap-2">
                <div style="width: 32px; height: 32px; background: rgba(16,185,129,0.15); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-arrow-down-circle" style="color: #34d399;"></i>
                </div>
                <span style="font-weight: 600; color: #f1f5f9; font-size: 0.9rem;">Form Penerimaan Barang</span>
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

                <form action="{{ route('incoming.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <div class="col-12">
                            <label for="asset_id" class="form-label">
                                Pilih Barang / Aset <span class="text-danger">*</span>
                            </label>
                            <select id="asset_id" name="asset_id"
                                    class="form-select form-select-dark @error('asset_id') border-danger @enderror"
                                    required onchange="updateStockPreview(this)">
                                <option value="">— Pilih Aset —</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}"
                                            data-stock="{{ $asset->stock_good }}"
                                            data-kode="{{ $asset->kode_barang }}"
                                            {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                        [{{ $asset->kode_barang }}] {{ $asset->nama_barang }}
                                        (Stok Baik: {{ $asset->stock_good }})
                                    </option>
                                @endforeach
                            </select>
                            @error('asset_id')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror

                            {{-- Stock preview --}}
                            <div id="stock-preview" class="mt-2 d-none" style="font-size: 0.8rem; padding: 0.5rem 0.75rem; background: rgba(79,70,229,0.08); border: 1px solid rgba(79,70,229,0.2); border-radius: 8px; color: #94a3b8;">
                                Stok baik saat ini: <span id="current-stock" style="color: #818cf8; font-weight: 600;">0</span> unit
                                &rarr; Setelah input akan menjadi: <span id="new-stock" style="color: #34d399; font-weight: 600;">0</span> unit
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="quantity" class="form-label">
                                Jumlah Masuk <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="quantity" name="quantity"
                                   class="form-control form-control-dark @error('quantity') border-danger @enderror"
                                   placeholder="0"
                                   value="{{ old('quantity', 1) }}"
                                   min="1" required
                                   oninput="updateStockPreview()">
                            @error('quantity')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="tanggal" class="form-label">
                                Tanggal Penerimaan <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="tanggal" name="tanggal"
                                   class="form-control form-control-dark @error('tanggal') border-danger @enderror"
                                   value="{{ old('tanggal', date('Y-m-d')) }}"
                                   required>
                            @error('tanggal')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="source" class="form-label">Sumber Barang</label>
                            <input type="text" id="source" name="source"
                                   class="form-control form-control-dark"
                                   placeholder="Pembelian, Hibah, dll..."
                                   value="{{ old('source') }}"
                                   maxlength="255"
                                   list="source-list">
                            <datalist id="source-list">
                                <option value="Pembelian">
                                <option value="Hibah">
                                <option value="Bantuan Pemerintah">
                                <option value="Donasi">
                                <option value="Penggantian">
                            </datalist>
                        </div>

                        <div class="col-12">
                            <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                            <textarea id="keterangan" name="keterangan"
                                      class="form-control form-control-dark"
                                      rows="2"
                                      placeholder="Catatan tambahan (opsional)..."
                                      maxlength="500">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4 pt-3" style="border-top: 1px solid var(--border-color);">
                        <button type="submit" class="btn btn-primary-custom d-flex align-items-center gap-2" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="bi bi-arrow-down-circle-fill"></i> Simpan & Update Stok
                        </button>
                        <a href="{{ route('incoming.index') }}" class="btn" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #94a3b8; border-radius: 10px; padding: 0.55rem 1.25rem;">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    function updateStockPreview(selectEl) {
        const select   = selectEl || document.getElementById('asset_id');
        const qty      = parseInt(document.getElementById('quantity').value) || 0;
        const selected = select.options[select.selectedIndex];

        if (selected && selected.value) {
            const currentStock = parseInt(selected.dataset.stock) || 0;
            const newStock     = currentStock + qty;

            document.getElementById('current-stock').textContent = currentStock;
            document.getElementById('new-stock').textContent     = newStock;
            document.getElementById('stock-preview').classList.remove('d-none');
        } else {
            document.getElementById('stock-preview').classList.add('d-none');
        }
    }

    // Update preview when quantity changes
    document.getElementById('quantity').addEventListener('input', () => updateStockPreview());

    // Initialize if there's an old value
    window.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('asset_id');
        if (select.value) updateStockPreview(select);
    });
</script>
@endpush
