@extends('layouts.app')
@section('title', 'Input Manifest Penumpang')
@section('breadcrumb', 'Operasional → Shift → Manifest')

@section('content')
<div class="max-w-2xl mx-auto" x-data="manifestForm()">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-asdp-800 to-asdp-600 rounded-2xl px-6 py-5 text-white mb-5">
        <h2 class="font-bold text-lg">📋 Input Manifest Penumpang</h2>
        <p class="text-white/70 text-sm mt-1">
            {{ $tripKapal->kapal->nama_kapal }} — {{ $tripKapal->dermaga->nama_dermaga }} —
            Trip #{{ $tripKapal->trip_ke }}
        </p>
        <p class="text-white/50 text-xs mt-1">
            Shift: {{ $tripKapal->shift->tanggal->isoFormat('D MMMM Y') }}
        </p>
    </div>

    {{-- Live Total --}}
    <div class="bg-white border border-asdp-200 rounded-2xl px-5 py-4 mb-5 flex items-center justify-between shadow-sm">
        <div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Penumpang Manifest</p>
            <p class="text-2xl font-bold text-asdp-800 mt-0.5" x-text="total + ' orang'">0 orang</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-asdp-50 flex items-center justify-center text-2xl">📋</div>
    </div>

    <form method="POST"
          action="{{ route('operasional.manifest.store', $tripKapal) }}"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf

        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                Penumpang Dalam Kendaraan Per Golongan
            </p>
        </div>

        <div class="p-5 grid grid-cols-2 gap-4">
            @foreach([
                ['pnp_dalam_gol_iv_a',  'Gol IV-A (Penumpang)'],
                ['pnp_dalam_gol_iv_b',  'Gol IV-B (Barang)'],
                ['pnp_dalam_gol_v_a',   'Gol V-A (Penumpang)'],
                ['pnp_dalam_gol_v_b',   'Gol V-B (Barang)'],
                ['pnp_dalam_gol_vi_a',  'Gol VI-A (Penumpang)'],
                ['pnp_dalam_gol_vi_b',  'Gol VI-B (Barang)'],
                ['pnp_dalam_gol_vii',   'Gol VII'],
                ['pnp_dalam_gol_viii',  'Gol VIII'],
                ['pnp_dalam_gol_ix',    'Gol IX'],
            ] as [$field, $label])
            <div class="flex items-center gap-3">
                <label class="text-sm text-gray-600 flex-1">{{ $label }}</label>
                <input type="number"
                       name="{{ $field }}"
                       x-model.number="fields.{{ $field }}"
                       @input="calcTotal()"
                       value="{{ old($field, $manifest->$field ?? 0) }}"
                       min="0"
                       class="w-24 border border-gray-300 rounded-xl px-3 py-2 text-sm text-center focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition">
            </div>
            @endforeach
        </div>

        <div class="px-5 pb-5">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Keterangan</label>
            <textarea name="keterangan" rows="2"
                      placeholder="Keterangan tambahan (opsional)..."
                      class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition resize-none">{{ old('keterangan', $manifest->keterangan ?? '') }}</textarea>
        </div>

        <div class="flex gap-3 px-5 pb-5">
            <a href="{{ route('operasional.shift.show', $tripKapal->shift_id) }}"
               class="flex-1 text-center px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                ← Batal
            </a>
            <button type="submit"
                    class="flex-1 bg-asdp-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-800 transition">
                💾 Simpan Manifest
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function manifestForm() {
    return {
        fields: {
            pnp_dalam_gol_iv_a:  {{ old('pnp_dalam_gol_iv_a',  $manifest->pnp_dalam_gol_iv_a  ?? 0) }},
            pnp_dalam_gol_iv_b:  {{ old('pnp_dalam_gol_iv_b',  $manifest->pnp_dalam_gol_iv_b  ?? 0) }},
            pnp_dalam_gol_v_a:   {{ old('pnp_dalam_gol_v_a',   $manifest->pnp_dalam_gol_v_a   ?? 0) }},
            pnp_dalam_gol_v_b:   {{ old('pnp_dalam_gol_v_b',   $manifest->pnp_dalam_gol_v_b   ?? 0) }},
            pnp_dalam_gol_vi_a:  {{ old('pnp_dalam_gol_vi_a',  $manifest->pnp_dalam_gol_vi_a  ?? 0) }},
            pnp_dalam_gol_vi_b:  {{ old('pnp_dalam_gol_vi_b',  $manifest->pnp_dalam_gol_vi_b  ?? 0) }},
            pnp_dalam_gol_vii:   {{ old('pnp_dalam_gol_vii',   $manifest->pnp_dalam_gol_vii   ?? 0) }},
            pnp_dalam_gol_viii:  {{ old('pnp_dalam_gol_viii',  $manifest->pnp_dalam_gol_viii  ?? 0) }},
            pnp_dalam_gol_ix:    {{ old('pnp_dalam_gol_ix',    $manifest->pnp_dalam_gol_ix    ?? 0) }},
        },
        total: 0,
        init() { this.calcTotal(); },
        calcTotal() {
            this.total = Object.values(this.fields).reduce((a, b) => a + (parseInt(b) || 0), 0);
        }
    }
}
</script>
@endpush
