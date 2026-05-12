@extends('layouts.app')
@section('title', 'Input Penjualan Tiket')
@section('breadcrumb', 'Operasional → Shift → Penjualan Tiket')

@section('content')
<div class="max-w-5xl mx-auto space-y-5">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-asdp-800 to-asdp-600 rounded-2xl px-6 py-5 text-white">
        <h2 class="font-bold text-lg">🎫 Input Penjualan Tiket Terpadu (JUAL01)</h2>
        <p class="text-white/70 text-sm mt-1">
            {{ $shift->regu->nama_regu }} — {{ $shift->nama_shift }} —
            {{ $shift->tanggal->isoFormat('D MMMM Y') }}
        </p>
        @if($tarif)
        <p class="text-white/50 text-xs mt-1">Tarif aktif: {{ $tarif->nama_tarif }}</p>
        @endif
    </div>

    <form method="POST" action="{{ route('operasional.penjualan-tiket.store', $shift) }}">
        @csrf

        @foreach($posList as $pos)
        @php $ex = $existing[$pos] ?? null; @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                <span class="w-8 h-8 rounded-lg bg-asdp-100 text-asdp-800 flex items-center justify-center text-sm font-bold">
                    {{ $loop->index + 1 }}
                </span>
                <h3 class="font-semibold text-gray-800">Pos: {{ $pos }}</h3>
                @if($ex)
                <span class="ml-auto text-xs text-green-600 font-medium bg-green-50 px-2.5 py-1 rounded-full">
                    ✓ Sudah diinput
                </span>
                @endif
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 gap-6">

                    {{-- Penumpang --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">👥 Penumpang</p>
                        <div class="space-y-2.5">
                            @foreach([
                                ['pnp_ekb_d','EKB-D Dewasa'],
                                ['pnp_ekb_l','EKB-L Lansia'],
                                ['pnp_ekb_a','EKB-A Anak'],
                            ] as [$field, $label])
                            <div class="flex items-center gap-3">
                                <label class="text-sm text-gray-600 w-28 flex-shrink-0">{{ $label }}</label>
                                <input type="number"
                                       name="data[{{ $pos }}][{{ $field }}]"
                                       value="{{ old("data.{$pos}.{$field}", $ex?->$field ?? 0) }}"
                                       min="0"
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm text-center focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition">
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kendaraan --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">🚗 Kendaraan (Golongan)</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach([
                                ['knd_gol_i','Gol I'],['knd_gol_ii','Gol II'],
                                ['knd_gol_iii','Gol III'],['knd_gol_iv_a','Gol IV-A'],
                                ['knd_gol_iv_b','Gol IV-B'],['knd_gol_v_a','Gol V-A'],
                                ['knd_gol_v_b','Gol V-B'],['knd_gol_vi_a','Gol VI-A'],
                                ['knd_gol_vi_b','Gol VI-B'],['knd_gol_vii','Gol VII'],
                                ['knd_gol_viii','Gol VIII'],['knd_gol_ix','Gol IX'],
                            ] as [$field, $label])
                            <div class="flex items-center gap-2">
                                <label class="text-xs text-gray-500 w-16 flex-shrink-0">{{ $label }}</label>
                                <input type="number"
                                       name="data[{{ $pos }}][{{ $field }}]"
                                       value="{{ old("data.{$pos}.{$field}", $ex?->$field ?? 0) }}"
                                       min="0"
                                       class="flex-1 border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <input type="text"
                           name="data[{{ $pos }}][keterangan]"
                           value="{{ old("data.{$pos}.keterangan", $ex?->keterangan) }}"
                           placeholder="Keterangan pos {{ $pos }} (opsional)..."
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition">
                </div>
            </div>
        </div>
        @endforeach

        {{-- Actions --}}
        <div class="flex gap-3">
            <a href="{{ route('operasional.shift.show', $shift) }}"
               class="flex-1 text-center px-4 py-3 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                ← Kembali ke Shift
            </a>
            <button type="submit"
                    class="flex-1 bg-asdp-700 text-white px-4 py-3 rounded-xl text-sm font-semibold hover:bg-asdp-800 transition">
                💾 Simpan Semua Penjualan Tiket
            </button>
        </div>
    </form>
</div>
@endsection
