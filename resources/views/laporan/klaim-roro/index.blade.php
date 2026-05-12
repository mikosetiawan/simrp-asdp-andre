{{-- laporan/klaim-roro/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Klaim RoRo')
@section('breadcrumb', 'Laporan → Klaim RoRo')
@section('content')
@php
    $pdfQuery = array_filter([
        'kapal_id' => $kapalId,
        'dermaga_id' => $dermagaId,
    ], fn ($v) => $v !== null && $v !== '');
    $klaimRowNo = $klaimRowNoBase;
@endphp
<div class="space-y-5">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                    class="border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Regu</label>
                <select name="regu_id" class="border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 min-w-[12rem]">
                    <option value="">Semua Regu</option>
                    @foreach($regu as $r)
                    <option value="{{ $r->id }}" {{ $reguId == $r->id ? 'selected' : '' }}>{{ $r->kode_regu }} — {{ $r->nama_regu }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. data</label>
                <input type="number" name="nomor_data" value="{{ $nomorData }}"
                    min="1" step="1" placeholder="Semua"
                    class="border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 w-28"
                    title="Nomor urut baris (kolom No), mengikuti semua regu. Kosongkan untuk menampilkan semua.">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kapal</label>
                <select name="kapal_id" class="border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 min-w-[11rem]">
                    <option value="">Semua Kapal</option>
                    @foreach($kapals as $k)
                    <option value="{{ $k->id }}" {{ (int) $kapalId === (int) $k->id ? 'selected' : '' }}>{{ $k->nama_kapal }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dermaga</label>
                <select name="dermaga_id" class="border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 min-w-[11rem]">
                    <option value="">Semua Dermaga</option>
                    @foreach($dermagas as $d)
                    <option value="{{ $d->id }}" {{ (int) $dermagaId === (int) $d->id ? 'selected' : '' }}>{{ $d->kode_dermaga }} — {{ $d->nama_dermaga }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-asdp-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
                🔍 Tampilkan
            </button>
        </form>
    </div>

    @forelse($shifts as $shift)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div>
                <span class="font-semibold text-gray-800">
                    @if($shift->regu)
                        {{ $shift->regu->kode_regu }} — {{ $shift->regu->nama_regu }}
                    @else
                        -
                    @endif
                    — {{ $shift->nama_shift }}
                </span>
                <span class="text-gray-400 text-sm ml-2">{{ substr($shift->jam_mulai,0,5) }}–{{ substr($shift->jam_selesai,0,5) }}</span>
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $shift->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ ucfirst($shift->status) }}
                </span>
            </div>
            <a href="{{ route('laporan.klaim-roro.pdf', $shift) }}{{ count($pdfQuery) ? '?' . http_build_query($pdfQuery) : '' }}"
               class="bg-red-600 text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-red-700 transition">
                📄 Cetak PDF
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-center text-gray-600 w-10">No</th>
                        <th class="px-3 py-2 text-left text-gray-600">Kapal</th>
                        <th class="px-3 py-2 text-left text-gray-600">Dermaga</th>
                        <th class="px-3 py-2 text-right text-gray-600">Trip</th>
                        <th class="px-3 py-2 text-right text-gray-600">EKB-D</th>
                        <th class="px-3 py-2 text-right text-gray-600">EKB-L</th>
                        <th class="px-3 py-2 text-right text-gray-600">EKB-A</th>
                        <th class="px-3 py-2 text-right text-gray-600">Gol IV-A</th>
                        <th class="px-3 py-2 text-right text-gray-600">Gol IV-B</th>
                        <th class="px-3 py-2 text-right text-gray-600">Total Pnp</th>
                        <th class="px-3 py-2 text-right text-gray-600">Total Knd</th>
                        <th class="px-3 py-2 text-right text-gray-600">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($shift->tripKapal as $trip)
                    @php $tp = $trip->tagihPelayaran; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-center tabular-nums text-gray-600">{{ ++$klaimRowNo }}</td>
                        <td class="px-3 py-2 font-medium">{{ $trip->kapal->nama_kapal ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $trip->dermaga->nama_dermaga ?? '-' }}</td>
                        <td class="px-3 py-2 text-right">{{ $trip->jumlah_trip }}</td>
                        <td class="px-3 py-2 text-right">{{ $tp?->jml_pnp_ekb_d ?? '-' }}</td>
                        <td class="px-3 py-2 text-right">{{ $tp?->jml_pnp_ekb_l ?? '-' }}</td>
                        <td class="px-3 py-2 text-right">{{ $tp?->jml_pnp_ekb_a ?? '-' }}</td>
                        <td class="px-3 py-2 text-right">{{ $tp?->gol_iv_a ?? '-' }}</td>
                        <td class="px-3 py-2 text-right">{{ $tp?->gol_iv_b ?? '-' }}</td>
                        <td class="px-3 py-2 text-right font-medium">{{ number_format($tp?->total_penumpang ?? 0) }}</td>
                        <td class="px-3 py-2 text-right font-medium">{{ number_format($tp?->total_kendaraan ?? 0) }}</td>
                        <td class="px-3 py-2 text-right font-bold text-gray-800">
                            Rp {{ number_format($tp?->total_pendapatan ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-16 text-center">
        <div class="text-5xl mb-3">📭</div>
        <p class="text-gray-500">
            Tidak ada data shift untuk tanggal {{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}
            @if($kapalId || $dermagaId || $reguId || $nomorData)
                <span class="block mt-1 text-sm">Coba ubah filter tanggal, regu, no. data, kapal, atau dermaga.</span>
            @endif
        </p>
    </div>
    @endforelse
</div>
@endsection
