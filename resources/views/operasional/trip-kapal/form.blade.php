@extends('layouts.app')
@php
    $isEdit = isset($tripKapal);
    $formAction = $isEdit
        ? route('operasional.trip-kapal.update', $tripKapal)
        : route('operasional.trip-kapal.store', $shift);
@endphp
@section('title', $isEdit ? 'Ubah Data Trip & Tagih Kapal' : 'Tambah Data Trip & Tagih Kapal')
@section('breadcrumb', 'Operasional → Shift → Trip Kapal & Tagih01')

@section('content')
<div class="max-w-5xl mx-auto" x-data="unifiedTripForm()" x-init="init()">

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md overflow-hidden mb-5">
        <div class="bg-gradient-to-r from-asdp-900 via-asdp-800 to-asdp-700 px-6 py-4 text-white">
            <h2 class="font-bold text-lg flex items-center gap-2">
                <span>🚢</span> {{ $isEdit ? '✏️ Ubah Data Trip & Tagih Pelayaran (Tagih01)' : '➕ Input Tambah Data Trip Kapal & Tagih Pelayaran' }}
            </h2>
            <p class="text-white/80 text-xs mt-1">
                Regu: <strong>{{ $shift->regu->nama_regu }}</strong> | Shift: <strong>{{ $shift->nama_shift }}</strong> | Tanggal: <strong>{{ $shift->tanggal->isoFormat('D MMMM Y') }}</strong>
            </p>
        </div>

        {{-- Live Calculation Summary Bar --}}
        <div class="bg-emerald-50/80 border-b border-emerald-200 px-6 py-3.5 grid grid-cols-2 sm:grid-cols-4 gap-4 text-slate-800">
            <div>
                <div class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wide">Pend. Penumpang</div>
                <div class="text-emerald-950 font-bold text-sm font-mono" x-text="'Rp ' + formatRupiah(pendapatanPnp)">Rp 0</div>
            </div>
            <div>
                <div class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wide">Pend. Kendaraan</div>
                <div class="text-emerald-950 font-bold text-sm font-mono" x-text="'Rp ' + formatRupiah(pendapatanKnd)">Rp 0</div>
            </div>
            <div>
                <div class="text-[11px] font-semibold text-slate-600 uppercase tracking-wide">Total Volume</div>
                <div class="text-slate-900 font-bold text-xs font-mono" x-text="totalPnp + ' Pnp | ' + totalKnd + ' Knd'">0 Pnp | 0 Knd</div>
            </div>
            <div class="bg-emerald-600 text-white rounded-xl p-2 text-right shadow-sm">
                <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-150">TOTAL PENDAPATAN</div>
                <div class="text-white font-extrabold text-base font-mono" x-text="'Rp ' + formatRupiah(totalPendapatan)">Rp 0</div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ $formAction }}" class="space-y-6">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif
        <input type="hidden" name="tarif_id" value="{{ $tarif->id }}">

        {{-- BAGIAN 1: DATA OPERASIONAL TRIP KAPAL --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 bg-asdp-900 text-white flex items-center justify-between">
                <h3 class="font-semibold text-sm flex items-center gap-2">
                    <span>1️⃣</span> Data Operasional Trip Kapal
                </h3>
                <span class="text-xs bg-white/10 px-2.5 py-1 rounded-full text-white/80">Informasi Pelayaran</span>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kapal <span class="text-red-500">*</span></label>
                        <select name="kapal_id" @change="onKapalChange($event)" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 @error('kapal_id') border-red-400 @enderror">
                            <option value="">-- Pilih Kapal --</option>
                            @foreach($kapal as $k)
                            <option value="{{ $k->id }}"
                                    data-existing-trips="{{ $tripCounts[$k->id] ?? 0 }}"
                                    {{ (int) old('kapal_id', $isEdit ? $tripKapal->kapal_id : null) === (int) $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kapal }} ({{ number_format($k->grt) }} GRT)
                                @if(isset($tripCounts[$k->id]) && $tripCounts[$k->id] > 0)
                                    [Sudah {{ $tripCounts[$k->id] }} trip]
                                @endif
                            </option>
                            @endforeach
                        </select>
                        @error('kapal_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kapal Pengganti (Opsional)</label>
                        <select name="kapal_pengganti_id" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                            <option value="">-- Tidak Ada Pengganti --</option>
                            @foreach($kapal as $k)
                            <option value="{{ $k->id }}" {{ (int) old('kapal_pengganti_id', $isEdit ? $tripKapal->kapal_pengganti_id : null) === (int) $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kapal }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dermaga <span class="text-red-500">*</span></label>
                        <select name="dermaga_id" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 @error('dermaga_id') border-red-400 @enderror">
                            <option value="">-- Pilih Dermaga --</option>
                            @foreach($dermaga as $d)
                            <option value="{{ $d->id }}" {{ (int) old('dermaga_id', $isEdit ? $tripKapal->dermaga_id : null) === (int) $d->id ? 'selected' : '' }}>
                                {{ $d->nama_dermaga }} ({{ $d->kode_dermaga }})
                            </option>
                            @endforeach
                        </select>
                        @error('dermaga_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Trip <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_trip" x-ref="jumlahTrip" value="{{ old('jumlah_trip', $isEdit ? $tripKapal->jumlah_trip : 1) }}" min="1"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 @error('jumlah_trip') border-red-400 @enderror">
                        @error('jumlah_trip')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trip Ke-</label>
                        <input type="number" name="trip_ke" x-ref="tripKe" value="{{ old('trip_ke', $isEdit ? $tripKapal->trip_ke : 1) }}" min="1"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Tiba (Kedatangan)</label>
                        <input type="time" name="jam_tiba" value="{{ old('jam_tiba', $isEdit && $tripKapal->jam_tiba ? substr($tripKapal->jam_tiba, 0, 5) : '') }}"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Berangkat (Keberangkatan)</label>
                        <input type="time" name="jam_berangkat" value="{{ old('jam_berangkat', $isEdit && $tripKapal->jam_berangkat ? substr($tripKapal->jam_berangkat, 0, 5) : '') }}"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Trip (Opsional)</label>
                    <textarea name="keterangan" rows="2" placeholder="Catatan operasional trip..."
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 resize-none">{{ old('keterangan', $isEdit ? $tripKapal->keterangan : '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- BAGIAN 2: DATA PENUMPANG (TAGIH01) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 bg-blue-900 text-white flex items-center justify-between">
                <h3 class="font-semibold text-sm flex items-center gap-2">
                    <span>2️⃣</span> Data Penumpang (Tagih01)
                </h3>
                <span class="text-xs bg-white/10 px-2.5 py-1 rounded-full text-white/80">Jumlah Tiket Penumpang</span>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach([
                    ['jml_pnp_ekb_d', 'EKB-D (Dewasa)', 'ekb_dewasa'],
                    ['jml_pnp_ekb_l', 'EKB-L (Lansia)', 'ekb_lansia'],
                    ['jml_pnp_ekb_a', 'EKB-A (Anak < 5th)', 'ekb_anak'],
                ] as [$name, $label, $tarifKey])
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $label }}
                        <span class="text-xs text-blue-600 font-semibold block">
                            Rp {{ number_format($tarif->$tarifKey, 0, ',', '.') }}/org
                        </span>
                    </label>
                    <input type="number" name="{{ $name }}" x-model.number="{{ $name }}" @input="hitung()"
                        value="{{ old($name, $tagih->$name ?? 0) }}" min="0"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 font-mono font-semibold">
                </div>
                @endforeach
            </div>
        </div>

        {{-- BAGIAN 3: DATA KENDARAAN (TAGIH01) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 bg-amber-800 text-white flex items-center justify-between">
                <h3 class="font-semibold text-sm flex items-center gap-2">
                    <span>3️⃣</span> Data Kendaraan per Golongan (Tagih01)
                </h3>
                <span class="text-xs bg-white/10 px-2.5 py-1 rounded-full text-white/80">Gol I s/d Gol IX</span>
            </div>
            <div class="p-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
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
                        <span class="text-xs text-amber-700 font-semibold block">Rp {{ number_format($tarif->$tarifKey, 0, ',', '.') }}</span>
                    </label>
                    <input type="number" name="{{ $name }}" x-model.number="{{ $name }}" @input="hitung()"
                        value="{{ old($name, $tagih->$name ?? 0) }}" min="0"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 font-mono font-semibold">
                </div>
                @endforeach
            </div>
        </div>

        {{-- BAGIAN 4: JASA SANDAR & TAMBAT (TAGIH03) --}}
        @include('operasional.jasa-sandar._embed_tagih03', ['dermaga' => $dermaga, 'jasaExisting' => $jasaExisting])

        {{-- ACTION BUTTONS --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('operasional.shift.show', $shift) }}"
               class="px-5 py-3 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 text-white rounded-xl text-sm font-bold hover:from-emerald-700 hover:to-emerald-600 transition shadow-lg flex items-center gap-2">
                <span>💾</span> {{ $isEdit ? 'Simpan Perubahan Data Trip & Tagih' : 'Simpan Lengkap Data Trip & Tagih Pelayaran' }}
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

function unifiedTripForm() {
    return {
        isEdit: {{ $isEdit ? 'true' : 'false' }},
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
        pendapatanPnp: 0, pendapatanKnd: 0, totalPendapatan: 0, totalPnp: 0, totalKnd: 0,

        init() { this.hitung(); },

        onKapalChange(e) {
            if (this.isEdit) return;
            const select = e.target;
            const selectedOpt = select.options[select.selectedIndex];
            const existing = parseInt(selectedOpt.getAttribute('data-existing-trips') || '0', 10);
            const nextTrip = existing + 1;
            if (this.$refs.jumlahTrip) this.$refs.jumlahTrip.value = nextTrip;
            if (this.$refs.tripKe) this.$refs.tripKe.value = nextTrip;
        },

        hitung() {
            this.pendapatanPnp = (this.jml_pnp_ekb_d * TARIF.ekb_dewasa) + (this.jml_pnp_ekb_l * TARIF.ekb_lansia) + (this.jml_pnp_ekb_a * TARIF.ekb_anak);
            this.pendapatanKnd = (this.gol_i*TARIF.gol_i)+(this.gol_ii*TARIF.gol_ii)+(this.gol_iii*TARIF.gol_iii)+(this.gol_iv_a*TARIF.gol_iv_a)+(this.gol_iv_b*TARIF.gol_iv_b)+(this.gol_v_a*TARIF.gol_v_a)+(this.gol_v_b*TARIF.gol_v_b)+(this.gol_vi_a*TARIF.gol_vi_a)+(this.gol_vi_b*TARIF.gol_vi_b)+(this.gol_vii*TARIF.gol_vii)+(this.gol_viii*TARIF.gol_viii)+(this.gol_ix*TARIF.gol_ix);
            this.totalPendapatan = this.pendapatanPnp + this.pendapatanKnd;
            this.totalPnp = this.jml_pnp_ekb_d + this.jml_pnp_ekb_l + this.jml_pnp_ekb_a;
            this.totalKnd = this.gol_i + this.gol_ii + this.gol_iii + this.gol_iv_a + this.gol_iv_b + this.gol_v_a + this.gol_v_b + this.gol_vi_a + this.gol_vi_b + this.gol_vii + this.gol_viii + this.gol_ix;
        },

        formatRupiah(v) { return Number(v).toLocaleString('id-ID'); }
    };
}
</script>
@endpush
