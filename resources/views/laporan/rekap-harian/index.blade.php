@extends('layouts.app')
@section('title', 'Rekap Harian')
@section('breadcrumb', 'Laporan → Rekap Harian')

@section('content')
<div class="space-y-5">

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                    class="border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Regu</label>
                <select name="regu_id" class="border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                    <option value="">Semua Regu</option>
                    @foreach($regus as $rg)
                        <option value="{{ $rg->id }}" {{ $regu_id == $rg->id ? 'selected' : '' }}>{{ $rg->nama_regu }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-asdp-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
                🔍 Tampilkan
            </button>
            @can('laporan.export')
            <a href="{{ route('laporan.rekap-harian.pdf', ['tanggal' => $tanggal, 'regu_id' => $regu_id]) }}"
               class="bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-700 transition">
                📄 Export PDF
            </a>
            <a href="{{ route('laporan.rekap-harian.excel', ['tanggal' => $tanggal, 'regu_id' => $regu_id]) }}"
               class="bg-green-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700 transition">
                📊 Export Excel
            </a>
            <button type="button" onclick="window.print()" class="bg-gray-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-700 transition">
                🖨️ Print
            </button>
            @endcan
        </form>
    </div>

    {{-- KPI --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center">
            <div class="text-2xl mb-2">💰</div>
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Total Pendapatan</div>
            <div class="text-xl font-bold text-gray-800">Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center">
            <div class="text-2xl mb-2">🚢</div>
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Total Trip</div>
            <div class="text-xl font-bold text-gray-800">{{ number_format($data['total_trip']) }} Trip</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center">
            <div class="text-2xl mb-2">👥</div>
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Total Penumpang</div>
            <div class="text-xl font-bold text-gray-800">{{ number_format($data['total_penumpang']) }} Org</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center">
            <div class="text-2xl mb-2">🚗</div>
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Total Kendaraan</div>
            <div class="text-xl font-bold text-gray-800">{{ number_format($data['total_kendaraan']) }} Unit</div>
        </div>
    </div>

    {{-- Detail per Regu --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">
                📊 Rekap Per Shift — {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}
            </h3>
        </div>
        @if(count($data['per_regu']) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-asdp-900 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Regu</th>
                        <th class="px-4 py-3 text-left font-medium">Shift</th>
                        <th class="px-4 py-3 text-left font-medium">Jam</th>
                        <th class="px-4 py-3 text-left font-medium">Supervisi</th>
                        <th class="px-4 py-3 text-right font-medium">Trip</th>
                        <th class="px-4 py-3 text-right font-medium">Penumpang</th>
                        <th class="px-4 py-3 text-right font-medium">Kendaraan</th>
                        <th class="px-4 py-3 text-right font-medium">Pend. Penumpang</th>
                        <th class="px-4 py-3 text-right font-medium">Pend. Kendaraan</th>
                        <th class="px-4 py-3 text-right font-medium">Total Pendapatan</th>
                        <th class="px-4 py-3 text-center font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($data['per_regu'] as $item)
                    @php
                        $shift = $item['shift'];
                        $pendPnp = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->pendapatan_penumpang ?? 0);
                        $pendKnd = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->pendapatan_kendaraan ?? 0);
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-asdp-800">{{ $shift->regu->nama_regu ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $shift->nama_shift }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ substr($shift->jam_mulai, 0, 5) }}–{{ substr($shift->jam_selesai, 0, 5) }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $shift->supervisi->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ $item['trip'] }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($item['penumpang']) }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($item['kendaraan']) }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($pendPnp, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($pendKnd, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-800">Rp {{ number_format($item['pendapatan'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $shift->status === 'approved' ? 'bg-green-100 text-green-700' :
                                   ($shift->status === 'submitted' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($shift->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-asdp-900 text-white font-bold">
                    <tr>
                        <td colspan="4" class="px-4 py-3">TOTAL</td>
                        <td class="px-4 py-3 text-right">{{ $data['total_trip'] }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($data['total_penumpang']) }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($data['total_kendaraan']) }}</td>
                        <td colspan="2" class="px-4 py-3"></td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="py-16 text-center">
            <div class="text-5xl mb-3">📭</div>
            <p class="text-gray-500">Tidak ada data shift untuk tanggal {{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
