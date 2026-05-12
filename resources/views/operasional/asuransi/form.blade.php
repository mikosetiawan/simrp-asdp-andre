@extends('layouts.app')
@section('title', 'Input Data Asuransi')
@section('breadcrumb', 'Operasional → Shift → Asuransi (Tagih06)')

@section('content')
<div class="max-w-3xl mx-auto" x-data="asuransiForm()">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-asdp-800 to-asdp-600 rounded-2xl px-6 py-5 text-white mb-5">
        <h2 class="font-bold text-lg">🛡️ Input Produksi Asuransi (Tagih06)</h2>
        <p class="text-white/70 text-sm mt-1">
            {{ $shift->regu->nama_regu }} — {{ $shift->nama_shift }} —
            {{ $shift->tanggal->isoFormat('D MMMM Y') }}
        </p>
    </div>

    {{-- Live total bar --}}
    <div class="grid grid-cols-3 gap-4 mb-5">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-1">Total JR</p>
            <p class="text-lg font-bold text-asdp-800" x-text="'Rp ' + fmt(totalJr)">Rp 0</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-1">Total JP</p>
            <p class="text-lg font-bold text-asdp-800" x-text="'Rp ' + fmt(totalJp)">Rp 0</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-2xl shadow-sm p-4 text-center">
            <p class="text-xs text-green-600 uppercase tracking-wide font-medium mb-1">Total Asuransi</p>
            <p class="text-lg font-bold text-green-800" x-text="'Rp ' + fmt(totalJr + totalJp)">Rp 0</p>
        </div>
    </div>

    <form method="POST" action="{{ route('operasional.asuransi.store', $shift) }}" class="space-y-5">
        @csrf

        {{-- Jasa Raharja (JR) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 bg-blue-50 border-b border-blue-100 flex items-center justify-between">
                <h3 class="font-semibold text-blue-900">🔵 Jasa Raharja (JR)</h3>
                @if($tarif)
                <span class="text-xs text-blue-500">Tarif JR: Rp {{ number_format($tarif->asuransi_jr_pnp, 0, ',', '.') }}/orang</span>
                @endif
            </div>
            <div class="p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Penumpang</p>
                <div class="grid grid-cols-3 gap-4 mb-5">
                    @foreach([
                        ['jr_pnp_dewasa','jr_pnp_dewasa','Dewasa'],
                        ['jr_pnp_lansia','jr_pnp_lansia','Lansia'],
                        ['jr_pnp_anak',  'jr_pnp_anak',  'Anak'],
                    ] as [$xfield, $name, $label])
                    <div>
                        <label class="block text-xs text-gray-600 mb-1.5">{{ $label }}</label>
                        <input type="number" name="{{ $name }}"
                               x-model.number="{{ $xfield }}"
                               @input="calcJr()"
                               value="{{ old($name, $asuransi->$name ?? 0) }}"
                               min="0"
                               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm text-center focus:ring-2 focus:ring-asdp-500">
                    </div>
                    @endforeach
                </div>

                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Kendaraan per Golongan</p>
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                    @foreach([
                        ['jr_knd_gol_i',   'jr_knd_gol_i',   'Gol I'],
                        ['jr_knd_gol_ii',  'jr_knd_gol_ii',  'Gol II'],
                        ['jr_knd_gol_iii', 'jr_knd_gol_iii', 'Gol III'],
                        ['jr_knd_gol_iv',  'jr_knd_gol_iv',  'Gol IV'],
                        ['jr_knd_gol_v',   'jr_knd_gol_v',   'Gol V'],
                        ['jr_knd_gol_vi',  'jr_knd_gol_vi',  'Gol VI'],
                        ['jr_knd_gol_vii', 'jr_knd_gol_vii', 'Gol VII'],
                        ['jr_knd_gol_viii','jr_knd_gol_viii','Gol VIII'],
                        ['jr_knd_gol_ix',  'jr_knd_gol_ix',  'Gol IX'],
                    ] as [$xfield, $name, $label])
                    <div>
                        <label class="block text-xs text-gray-600 mb-1.5">{{ $label }}</label>
                        <input type="number" name="{{ $name }}"
                               x-model.number="{{ $xfield }}"
                               @input="calcJr()"
                               value="{{ old($name, $asuransi->$name ?? 0) }}"
                               min="0"
                               class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs text-center focus:ring-2 focus:ring-asdp-500">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Jasa Penumpang (JP) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 bg-orange-50 border-b border-orange-100 flex items-center justify-between">
                <h3 class="font-semibold text-orange-900">🟠 Jasa Penumpang (JP)</h3>
                @if($tarif)
                <span class="text-xs text-orange-500">Tarif JP: Rp {{ number_format($tarif->asuransi_jp_pnp, 0, ',', '.') }}/orang</span>
                @endif
            </div>
            <div class="p-5 grid grid-cols-2 gap-4">
                @foreach([
                    ['jp_pnp_dewasa','jp_pnp_dewasa','Penumpang Dewasa'],
                    ['jp_pnp_lansia','jp_pnp_lansia','Penumpang Lansia'],
                ] as [$xfield, $name, $label])
                <div>
                    <label class="block text-xs text-gray-600 mb-1.5">{{ $label }}</label>
                    <input type="number" name="{{ $name }}"
                           x-model.number="{{ $xfield }}"
                           @input="calcJp()"
                           value="{{ old($name, $asuransi->$name ?? 0) }}"
                           min="0"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm text-center focus:ring-2 focus:ring-asdp-500">
                </div>
                @endforeach
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <a href="{{ route('operasional.shift.show', $shift) }}"
               class="flex-1 text-center px-4 py-3 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                ← Kembali ke Shift
            </a>
            <button type="submit"
                    class="flex-1 bg-asdp-700 text-white px-4 py-3 rounded-xl text-sm font-semibold hover:bg-asdp-800 transition">
                💾 Simpan Data Asuransi
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const TARIF_JR = {{ $tarif?->asuransi_jr_pnp ?? 3600 }};
const TARIF_JP = {{ $tarif?->asuransi_jp_pnp ?? 1400 }};

function asuransiForm() {
    return {
        // JR penumpang
        jr_pnp_dewasa: {{ old('jr_pnp_dewasa', $asuransi->jr_pnp_dewasa ?? 0) }},
        jr_pnp_lansia: {{ old('jr_pnp_lansia', $asuransi->jr_pnp_lansia ?? 0) }},
        jr_pnp_anak:   {{ old('jr_pnp_anak',   $asuransi->jr_pnp_anak   ?? 0) }},
        // JR kendaraan
        jr_knd_gol_i:    {{ old('jr_knd_gol_i',    $asuransi->jr_knd_gol_i    ?? 0) }},
        jr_knd_gol_ii:   {{ old('jr_knd_gol_ii',   $asuransi->jr_knd_gol_ii   ?? 0) }},
        jr_knd_gol_iii:  {{ old('jr_knd_gol_iii',  $asuransi->jr_knd_gol_iii  ?? 0) }},
        jr_knd_gol_iv:   {{ old('jr_knd_gol_iv',   $asuransi->jr_knd_gol_iv   ?? 0) }},
        jr_knd_gol_v:    {{ old('jr_knd_gol_v',    $asuransi->jr_knd_gol_v    ?? 0) }},
        jr_knd_gol_vi:   {{ old('jr_knd_gol_vi',   $asuransi->jr_knd_gol_vi   ?? 0) }},
        jr_knd_gol_vii:  {{ old('jr_knd_gol_vii',  $asuransi->jr_knd_gol_vii  ?? 0) }},
        jr_knd_gol_viii: {{ old('jr_knd_gol_viii', $asuransi->jr_knd_gol_viii ?? 0) }},
        jr_knd_gol_ix:   {{ old('jr_knd_gol_ix',   $asuransi->jr_knd_gol_ix   ?? 0) }},
        // JP penumpang
        jp_pnp_dewasa: {{ old('jp_pnp_dewasa', $asuransi->jp_pnp_dewasa ?? 0) }},
        jp_pnp_lansia: {{ old('jp_pnp_lansia', $asuransi->jp_pnp_lansia ?? 0) }},

        totalJr: 0,
        totalJp: 0,

        init() { this.calcJr(); this.calcJp(); },

        calcJr() {
            const pnp = (this.jr_pnp_dewasa + this.jr_pnp_lansia + this.jr_pnp_anak) * TARIF_JR;
            const knd = (this.jr_knd_gol_i + this.jr_knd_gol_ii + this.jr_knd_gol_iii +
                         this.jr_knd_gol_iv + this.jr_knd_gol_v + this.jr_knd_gol_vi +
                         this.jr_knd_gol_vii + this.jr_knd_gol_viii + this.jr_knd_gol_ix) * TARIF_JR;
            this.totalJr = pnp + knd;
        },

        calcJp() {
            this.totalJp = (this.jp_pnp_dewasa + this.jp_pnp_lansia) * TARIF_JP;
        },

        fmt(v) {
            return Number(v).toLocaleString('id-ID');
        }
    }
}
</script>
@endpush
