@extends('layouts.app')
@section('title', 'Input Limpahan Tiket')
@section('breadcrumb', 'Operasional → Shift → Limpahan Tiket')

@section('content')
<div class="max-w-5xl mx-auto space-y-5">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-asdp-800 to-asdp-600 rounded-2xl px-6 py-5 text-white">
        <h2 class="font-bold text-lg">🔄 Input Limpahan Tiket (LIMPAHAN)</h2>
        <p class="text-white/70 text-sm mt-1">
            {{ $shift->regu->nama_regu }} — {{ $shift->nama_shift }} —
            {{ $shift->tanggal->isoFormat('D MMMM Y') }}
        </p>
        <p class="text-white/50 text-xs mt-1">
            Limpahan = Tiket Terjual − (Tertagih R1 + R2 + R3)
        </p>
    </div>

    <form method="POST" action="{{ route('operasional.limpahan-tiket.store', $shift) }}">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-asdp-800 text-white">
                            <th class="px-4 py-3 text-left font-medium text-xs uppercase tracking-wide w-28">Jenis Tiket</th>
                            <th class="px-4 py-3 text-center font-medium text-xs uppercase tracking-wide">Terjual</th>
                            <th class="px-4 py-3 text-center font-medium text-xs uppercase tracking-wide">Tertagih R1</th>
                            <th class="px-4 py-3 text-center font-medium text-xs uppercase tracking-wide">Tertagih R2</th>
                            <th class="px-4 py-3 text-center font-medium text-xs uppercase tracking-wide">Tertagih R3</th>
                            <th class="px-4 py-3 text-center font-medium text-xs uppercase tracking-wide">Dilimpahkan</th>
                            <th class="px-4 py-3 text-left font-medium text-xs uppercase tracking-wide">Limpah ke Regu</th>
                            <th class="px-4 py-3 text-left font-medium text-xs uppercase tracking-wide">Ket</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" x-data="limpahanCalc()">
                        @foreach($jenisTiket as $jenis)
                        @php $ex = $existing[$jenis] ?? null; @endphp
                        <tr class="hover:bg-gray-50" x-data="rowCalc('{{ $jenis }}')">
                            <td class="px-4 py-2.5">
                                <span class="px-2 py-0.5 bg-asdp-50 text-asdp-800 rounded text-xs font-bold">
                                    {{ $jenis }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <input type="number"
                                       name="data[{{ $jenis }}][terjual]"
                                       x-model.number="terjual"
                                       @input="calc()"
                                       value="{{ old("data.{$jenis}.terjual", $ex?->terjual ?? 0) }}"
                                       min="0"
                                       class="w-20 border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-2 focus:ring-asdp-500">
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <input type="number"
                                       name="data[{{ $jenis }}][tertagih_regu1]"
                                       x-model.number="r1"
                                       @input="calc()"
                                       value="{{ old("data.{$jenis}.tertagih_regu1", $ex?->tertagih_regu1 ?? 0) }}"
                                       min="0"
                                       class="w-20 border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-2 focus:ring-asdp-500">
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <input type="number"
                                       name="data[{{ $jenis }}][tertagih_regu2]"
                                       x-model.number="r2"
                                       @input="calc()"
                                       value="{{ old("data.{$jenis}.tertagih_regu2", $ex?->tertagih_regu2 ?? 0) }}"
                                       min="0"
                                       class="w-20 border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-2 focus:ring-asdp-500">
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <input type="number"
                                       name="data[{{ $jenis }}][tertagih_regu3]"
                                       x-model.number="r3"
                                       @input="calc()"
                                       value="{{ old("data.{$jenis}.tertagih_regu3", $ex?->tertagih_regu3 ?? 0) }}"
                                       min="0"
                                       class="w-20 border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-2 focus:ring-asdp-500">
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <span x-text="dilimpahkan"
                                      :class="dilimpahkan > 0 ? 'text-orange-600 font-bold' : 'text-gray-400'"
                                      class="text-sm font-semibold"></span>
                            </td>
                            <td class="px-4 py-2.5">
                                <select name="data[{{ $jenis }}][dilimpahkan_ke_regu_id]"
                                        class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-xs focus:ring-2 focus:ring-asdp-500">
                                    <option value="">—</option>
                                    @foreach($regu as $rg)
                                    <option value="{{ $rg->id }}"
                                        {{ old("data.{$jenis}.dilimpahkan_ke_regu_id", $ex?->dilimpahkan_ke_regu_id) == $rg->id ? 'selected' : '' }}>
                                        {{ $rg->nama_regu }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-2.5">
                                <input type="text"
                                       name="data[{{ $jenis }}][keterangan]"
                                       value="{{ old("data.{$jenis}.keterangan", $ex?->keterangan) }}"
                                       placeholder="Opsional"
                                       class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-xs focus:ring-2 focus:ring-asdp-500">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('operasional.shift.show', $shift) }}"
               class="flex-1 text-center px-4 py-3 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                ← Kembali ke Shift
            </a>
            <button type="submit"
                    class="flex-1 bg-asdp-700 text-white px-4 py-3 rounded-xl text-sm font-semibold hover:bg-asdp-800 transition">
                💾 Simpan Limpahan Tiket
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function rowCalc(jenis) {
    return {
        terjual: 0,
        r1: 0, r2: 0, r3: 0,
        dilimpahkan: 0,
        calc() {
            this.dilimpahkan = Math.max(0, this.terjual - this.r1 - this.r2 - this.r3);
        }
    }
}
function limpahanCalc() { return {}; }
</script>
@endpush
