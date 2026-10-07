@extends('layouts.app')

@section('title', 'Laporkan Barang Rusak/Hilang')
@section('page-title', 'Laporkan Kerusakan')
@section('page-sub', 'Input laporan barang rusak atau hilang')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-7">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('damage.index') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #94a3b8; border-radius: 8px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #f1f5f9; margin: 0;">Laporkan Kerusakan / Kehilangan</h2>
                <p style="font-size: 0.82rem; color: #64748b; margin: 0;">Stok baik akan otomatis berkurang sesuai jumlah yang dilaporkan</p>
            </div>
        </div>

        {{-- Warning Info Box --}}
        <div class="d-flex align-items-start gap-3 p-3 mb-4 rounded-3" style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);">
            <i class="bi bi-exclamation-triangle-fill" style="color: #f87171; font-size: 1.1rem; margin-top: 0.1rem;"></i>
            <div>
                <div style="font-size: 0.85rem; font-weight: 600; color: #f87171;">Kalkulasi Otomatis</div>
                <div style="font-size: 0.8rem; color: #64748b; line-height: 1.6;">
                    • Jenis <strong style="color: #fbbf24;">Rusak</strong>: Stock Baik berkurang, Stock Rusak bertambah<br>
                    • Jenis <strong style="color: #f87171;">Hilang</strong>: Stock Baik berkurang saja (barang tidak ada)
                </div>
            </div>
        </div>

        <div class="card-dark card">
            <div class="card-header d-flex align-items-center gap-2">
                <div style="width: 32px; height: 32px; background: rgba(239,68,68,0.15); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-exclamation-triangle" style="color: #f87171;"></i>
                </div>
                <span style="font-weight: 600; color: #f1f5f9; font-size: 0.9rem;">Form Laporan Kerusakan</span>
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

                <form action="{{ route('damage.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <div class="col-12">
                            <label for="asset_id" class="form-label">
                                Pilih Barang / Aset <span class="text-danger">*</span>
                            </label>
                            <select id="asset_id" name="asset_id"
                                    class="form-select form-select-dark @error('asset_id') border-danger @enderror"
                                    required onchange="updatePreview(this)">
                                <option value="">— Pilih Aset —</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}"
                                            data-stock="{{ $asset->stock_good }}"
                                            data-damaged="{{ $asset->stock_damaged }}"
                                            {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                        [{{ $asset->kode_barang }}] {{ $asset->nama_barang }}
                                        (Stok Baik: {{ $asset->stock_good }})
                                    </option>
                                @endforeach
                            </select>
                            @error('asset_id')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror

                            {{-- Preview --}}
                            <div id="stock-preview" class="mt-2 d-none p-3 rounded-3" style="background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.15);">
                                <div class="row g-2 text-center" style="font-size: 0.8rem;">
                                    <div class="col-4">
                                        <div style="color: #64748b;">Stok Baik Saat Ini</div>
                                        <div id="current-good" style="font-size: 1.1rem; font-weight: 700; color: #34d399;">—</div>
                                    </div>
                                    <div class="col-4 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-arrow-right" style="color: #475569; font-size: 1rem;"></i>
                                    </div>
                                    <div class="col-4">
                                        <div style="color: #64748b;">Stok Baik Setelah</div>
                                        <div id="new-good" style="font-size: 1.1rem; font-weight: 700; color: #f87171;">—</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="quantity" class="form-label">
                                Jumlah Barang <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="quantity" name="quantity"
                                   class="form-control form-control-dark @error('quantity') border-danger @enderror"
                                   placeholder="0"
                                   value="{{ old('quantity', 1) }}"
                                   min="1" required
                                   oninput="updatePreview()">
                            @error('quantity')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="jenis_kerusakan" class="form-label">
                                Jenis Laporan <span class="text-danger">*</span>
                            </label>
                            <select id="jenis_kerusakan" name="jenis_kerusakan"
                                    class="form-select form-select-dark @error('jenis_kerusakan') border-danger @enderror"
                                    required onchange="updatePreview()">
                                <option value="rusak" {{ old('jenis_kerusakan', 'rusak') == 'rusak' ? 'selected' : '' }}>
                                    🔧 Rusak
                                </option>
                                <option value="hilang" {{ old('jenis_kerusakan') == 'hilang' ? 'selected' : '' }}>
                                    ❓ Hilang
                                </option>
                            </select>
                            @error('jenis_kerusakan')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="tanggal" class="form-label">
                                Tanggal Kejadian <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="tanggal" name="tanggal"
                                   class="form-control form-control-dark @error('tanggal') border-danger @enderror"
                                   value="{{ old('tanggal', date('Y-m-d')) }}"
                                   required>
                            @error('tanggal')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">Catatan Kerusakan</label>
                            <textarea id="notes" name="notes"
                                      class="form-control form-control-dark @error('notes') border-danger @enderror"
                                      rows="3"
                                      placeholder="Jelaskan kondisi kerusakan atau kronologi kehilangan..."
                                      maxlength="500">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="mt-1" style="font-size: 0.78rem; color: #f87171;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4 pt-3" style="border-top: 1px solid var(--border-color);">
                        <button type="submit" class="btn d-flex align-items-center gap-2"
                                style="background: linear-gradient(135deg, #ef4444, #dc2626); border: none; color: #fff; border-radius: 10px; padding: 0.55rem 1.25rem; font-weight: 600; font-size: 0.875rem; transition: all 0.2s;"
                                onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(239,68,68,0.35)'"
                                onmouseout="this.style.transform=''; this.style.boxShadow=''">
                            <i class="bi bi-exclamation-triangle-fill"></i> Simpan Laporan
                        </button>
                        <a href="{{ route('damage.index') }}" class="btn" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #94a3b8; border-radius: 10px; padding: 0.55rem 1.25rem;">
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
    function updatePreview(selectEl) {
        const select   = selectEl || document.getElementById('asset_id');
        const qty      = parseInt(document.getElementById('quantity').value) || 0;
        const selected = select.options[select.selectedIndex];

        if (selected && selected.value) {
            const currentGood = parseInt(selected.dataset.stock) || 0;
            const newGood     = Math.max(0, currentGood - qty);

            document.getElementById('current-good').textContent = currentGood + ' unit';
            document.getElementById('new-good').textContent     = newGood + ' unit';
            document.getElementById('stock-preview').classList.remove('d-none');

            // Warn if quantity exceeds stock
            const qtyInput = document.getElementById('quantity');
            if (qty > currentGood) {
                qtyInput.style.borderColor = '#ef4444';
                qtyInput.style.boxShadow   = '0 0 0 3px rgba(239,68,68,0.2)';
            } else {
                qtyInput.style.borderColor = '';
                qtyInput.style.boxShadow   = '';
            }
        } else {
            document.getElementById('stock-preview').classList.add('d-none');
        }
    }

    document.getElementById('quantity').addEventListener('input', () => updatePreview());
    document.getElementById('jenis_kerusakan').addEventListener('change', () => updatePreview());

    window.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('asset_id');
        if (select.value) updatePreview(select);
    });
</script>
@endpush
