@extends('layouts.app')
@section('title', 'Input Jasa Sandar')
@section('breadcrumb', 'Operasional → Jasa Sandar')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-asdp-900 to-asdp-700 px-6 py-4">
            <h2 class="text-white font-semibold">⚓ Input Jasa Sandar Per Dermaga (TAGIH03)</h2>
            <p class="text-white/70 text-sm mt-1">
                {{ $shift->regu->nama_regu }} — {{ $shift->nama_shift }} — {{ $shift->tanggal->isoFormat('D MMM Y') }}
            </p>
        </div>
        <form method="POST" action="{{ route('operasional.jasa-sandar.store', $shift) }}" class="p-6">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Dermaga</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">Call Sandar</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">Jml Trip</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Tarif JSN/trip</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Est. Jasa Sandar</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($dermaga as $d)
                        @php $ex = $existing[$d->id] ?? null; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $d->nama_dermaga }}
                                <div class="text-xs text-gray-400">{{ $d->kode_dermaga }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" name="data[{{ $d->id }}][call_sandar]"
                                    value="{{ old("data.{$d->id}.call_sandar", $ex?->call_sandar ?? 0) }}"
                                    min="0" class="w-20 border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-2 focus:ring-asdp-500">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" name="data[{{ $d->id }}][jumlah_trip]"
                                    value="{{ old("data.{$d->id}.jumlah_trip", $ex?->jumlah_trip ?? 0) }}"
                                    min="0" class="w-20 border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-2 focus:ring-asdp-500">
                            </td>
                            <td class="px-4 py-3 text-right text-gray-600 text-xs">
                                Rp {{ number_format($d->tarif_jsn_per_trip, 0, ',', '.') }}<br>
                                <span class="text-gray-400">Engker: Rp {{ number_format($d->tarif_engker_per_trip, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-gray-700">
                                @if($ex)
                                Rp {{ number_format($ex->total_jasa_dermaga, 0, ',', '.') }}
                                @else
                                <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" name="data[{{ $d->id }}][keterangan]"
                                    value="{{ old("data.{$d->id}.keterangan", $ex?->keterangan) }}"
                                    placeholder="Opsional" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:ring-2 focus:ring-asdp-500">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end gap-3 mt-5 pt-4 border-t border-gray-100">
                <a href="{{ route('operasional.shift.show', $shift) }}"
                   class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-asdp-800 text-white rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
                    💾 Simpan Jasa Sandar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
