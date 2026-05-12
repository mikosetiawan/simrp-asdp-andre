@extends('layouts.app')
@section('title', 'Input Tagih Pelayaran — ' . $tripKapal->kapal->nama_kapal)
@section('breadcrumb', 'Operasional → Shift → Tagih Pelayaran')

@section('content')
<div class="max-w-5xl mx-auto" x-data="tagihForm()" x-init="init()">

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-5">
        <div class="bg-gradient-to-r from-asdp-900 to-asdp-700 px-6 py-4">
            <h2 class="text-white font-semibold">📋 Form Input Tagih Pelayaran (Tagih01)</h2>
            <p class="text-white/70 text-sm mt-1">
                {{ $tripKapal->kapal->nama_kapal }} — {{ $tripKapal->dermaga->nama_dermaga }} —
                Trip #{{ $tripKapal->trip_ke }} ({{ $tripKapal->jumlah_trip }} trip) —
                {{ $tripKapal->shift->tanggal->isoFormat('D MMM Y') }}
            </p>
        </div>
        {{-- Live Calculation Bar --}}
        <div class="bg-asdp-50 border-b border-asdp-100 px-6 py-3 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <div class="text-xs text-asdp-600 font-medium">Pendapatan Penumpang</div>
                <div class="text-asdp-900 font-bold text-sm" x-text="'Rp ' + formatRupiah(pendapatanPnp)">Rp 0</div>
            </div>
            <div>
                <div class="text-xs text-asdp-600 font-medium">Pendapatan Kendaraan</div>
                <div class="text-asdp-900 font-bold text-sm" x-text="'Rp ' + formatRupiah(pendapatanKnd)">Rp 0</div>
            </div>
            <div>
                <div class="text-xs text-green-600 font-medium">Total Penumpang</div>
                <div class="text-green-800 font-bold text-sm" x-text="totalPnp + ' orang'">0 orang</div>
            </div>
            <div>
                <div class="text-xs text-orange-600 font-medium">TOTAL PENDAPATAN</div>
                <div class="text-orange-800 font-bold text-lg" x-text="'Rp ' + formatRupiah(totalPendapatan)">Rp 0</div>
            </div>
        </div>
    </div>

    <form method="POST"
        action="{{ isset($tagih->id) ? route('operasional.tagih-pelayaran.update', $tagih) : route('operasional.tagih-pelayaran.store', $tripKapal) }}"
        class="space-y-5">
        @csrf
        @if(isset($tagih->id)) @method('PUT') @endif
        <input type="hidden" name="tarif_id" value="{{ $tarif->id }}">

        {{-- Penumpang --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3 bg-blue-50 border-b border-blue-100">
                <h3 class="font-semibold text-blue-900">👥 Data Penumpang</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach([
                    ['jml_pnp_ekb_d', 'EKB-D (Dewasa)', 'ekb_dewasa'],
                    ['jml_pnp_ekb_l', 'EKB-L (Lansia)', 'ekb_lansia'],
                    ['jml_pnp_ekb_a', 'EKB-A (Anak < 5th)', 'ekb_anak'],
                ] as [$name, $label, $tarifKey])
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $label }}
                        <span class="text-xs text-gray-400 font-normal">
                            (Rp {{ number_format($tarif->$tarifKey, 0, ',', '.') }}/org)
                        </span>
                    </label>
                    <input type="number" name="{{ $name }}" x-model.number="{{ $name }}" @input="hitung()"
                        value="{{ old($name, $tagih->$name ?? 0) }}" min="0"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500">
                </div>
                @endforeach
            </div>
        </div>

        {{-- Kendaraan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3 bg-orange-50 border-b border-orange-100">
                <h3 class="font-semibold text-orange-900">🚗 Data Kendaraan (Golongan)</h3>
            </div>
            <div class="p-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach([
                    ['gol_i',    'Gol I (Sepeda)',       'gol_i'],
                    ['gol_ii',   'Gol II (Motor <500)',  'gol_ii'],
                    ['gol_iii',  'Gol III (Motor ≥500)', 'gol_iii'],
                    ['gol_iv_a', 'Gol IV-A (Penumpang)', 'gol_iv_a'],
                    ['gol_iv_b', 'Gol IV-B (Barang)',    'gol_iv_b'],
                    ['gol_v_a',  'Gol V-A (Penumpang)',  'gol_v_a'],
                    ['gol_v_b',  'Gol V-B (Barang)',     'gol_v_b'],
                    ['gol_vi_a', 'Gol VI-A (Penumpang)', 'gol_vi_a'],
                    ['gol_vi_b', 'Gol VI-B (Barang)',    'gol_vi_b'],
                    ['gol_vii',  'Gol VII',               'gol_vii'],
                    ['gol_viii', 'Gol VIII',              'gol_viii'],
                    ['gol_ix',   'Gol IX',                'gol_ix'],
                ] as [$name, $label, $tarifKey])
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        {{ $label }}
                        <span class="text-xs text-gray-400 block">Rp {{ number_format($tarif->$tarifKey, 0, ',', '.') }}</span>
                    </label>
                    <input type="number" name="{{ $name }}" x-model.number="{{ $name }}" @input="hitung()"
                        value="{{ old($name, $tagih->$name ?? 0) }}" min="0"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500">
                </div>
                @endforeach
            </div>
        </div>

        @include('operasional.jasa-sandar._embed_tagih03', ['dermaga' => $dermaga, 'jasaExisting' => $jasaExisting])

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('operasional.shift.show', $tripKapal->shift_id) }}"
               class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-6 py-2.5 bg-asdp-800 text-white rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
                💾 Simpan Tagih01 &amp; Jasa Sandar (TAGIH03)
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const TARIF = {
    ekb_dewasa: {{ $tarif->ekb_dewasa }}, ekb_lansia: {{ $tarif->ekb_lansia }}, ekb_anak: {{ $tarif->ekb_anak }},
    gol_i: {{ $tarif->gol_i }}, gol_ii: {{ $tarif->gol_ii }}, gol_iii: {{ $tarif->gol_iii }},
    gol_iv_a: {{ $tarif->gol_iv_a }}, gol_iv_b: {{ $tarif->gol_iv_b }},
    gol_v_a: {{ $tarif->gol_v_a }},   gol_v_b: {{ $tarif->gol_v_b }},
    gol_vi_a: {{ $tarif->gol_vi_a }}, gol_vi_b: {{ $tarif->gol_vi_b }},
    gol_vii: {{ $tarif->gol_vii }},   gol_viii: {{ $tarif->gol_viii }}, gol_ix: {{ $tarif->gol_ix }},
};

function tagihForm() {
    return {
        jml_pnp_ekb_d: {{ old('jml_pnp_ekb_d', $tagih->jml_pnp_ekb_d ?? 0) }},
        jml_pnp_ekb_l: {{ old('jml_pnp_ekb_l', $tagih->jml_pnp_ekb_l ?? 0) }},
        jml_pnp_ekb_a: {{ old('jml_pnp_ekb_a', $tagih->jml_pnp_ekb_a ?? 0) }},
        gol_i: {{ old('gol_i', $tagih->gol_i ?? 0) }},
        gol_ii: {{ old('gol_ii', $tagih->gol_ii ?? 0) }},
        gol_iii: {{ old('gol_iii', $tagih->gol_iii ?? 0) }},
        gol_iv_a: {{ old('gol_iv_a', $tagih->gol_iv_a ?? 0) }},
        gol_iv_b: {{ old('gol_iv_b', $tagih->gol_iv_b ?? 0) }},
        gol_v_a: {{ old('gol_v_a', $tagih->gol_v_a ?? 0) }},
        gol_v_b: {{ old('gol_v_b', $tagih->gol_v_b ?? 0) }},
        gol_vi_a: {{ old('gol_vi_a', $tagih->gol_vi_a ?? 0) }},
        gol_vi_b: {{ old('gol_vi_b', $tagih->gol_vi_b ?? 0) }},
        gol_vii: {{ old('gol_vii', $tagih->gol_vii ?? 0) }},
        gol_viii: {{ old('gol_viii', $tagih->gol_viii ?? 0) }},
        gol_ix: {{ old('gol_ix', $tagih->gol_ix ?? 0) }},
        pendapatanPnp: 0, pendapatanKnd: 0, totalPendapatan: 0, totalPnp: 0,
        init() { this.hitung(); },
        hitung() {
            this.pendapatanPnp = (this.jml_pnp_ekb_d * TARIF.ekb_dewasa) + (this.jml_pnp_ekb_l * TARIF.ekb_lansia) + (this.jml_pnp_ekb_a * TARIF.ekb_anak);
            this.pendapatanKnd = (this.gol_i*TARIF.gol_i)+(this.gol_ii*TARIF.gol_ii)+(this.gol_iii*TARIF.gol_iii)+(this.gol_iv_a*TARIF.gol_iv_a)+(this.gol_iv_b*TARIF.gol_iv_b)+(this.gol_v_a*TARIF.gol_v_a)+(this.gol_v_b*TARIF.gol_v_b)+(this.gol_vi_a*TARIF.gol_vi_a)+(this.gol_vi_b*TARIF.gol_vi_b)+(this.gol_vii*TARIF.gol_vii)+(this.gol_viii*TARIF.gol_viii)+(this.gol_ix*TARIF.gol_ix);
            this.totalPendapatan = this.pendapatanPnp + this.pendapatanKnd;
            this.totalPnp = this.jml_pnp_ekb_d + this.jml_pnp_ekb_l + this.jml_pnp_ekb_a;
        },
        formatRupiah(v) { return Number(v).toLocaleString('id-ID'); }
    };
}
</script>
@endpush
