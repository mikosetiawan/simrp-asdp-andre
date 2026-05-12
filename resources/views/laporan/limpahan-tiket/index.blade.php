{{-- laporan/limpahan-tiket/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Laporan Limpahan Tiket')
@section('breadcrumb', 'Laporan → Limpahan Tiket')

@section('content')
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
            <button type="submit" class="bg-asdp-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
                🔍 Tampilkan
            </button>
            @can('laporan.export')
            <a href="{{ route('laporan.limpahan-tiket.pdf', array_filter(['tanggal' => $tanggal, 'regu_id' => $reguId])) }}"
               class="bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-700 transition">
                📄 Cetak
            </a>
            @endcan
        </form>
        <p class="text-xs text-gray-500 mt-3">Limpahan = Terjual − (Tertagih R1 + R2 + R3), sesuai form input operasional.</p>
    </div>

    @forelse($shifts as $shift)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <span class="font-semibold text-gray-800">
                @if($shift->regu)
                    {{ $shift->regu->kode_regu }} — {{ $shift->regu->nama_regu }}
                @else
                    —
                @endif
                — {{ $shift->nama_shift }}
            </span>
            <span class="text-gray-400 text-sm ml-2">{{ substr($shift->jam_mulai,0,5) }}–{{ substr($shift->jam_selesai,0,5) }}</span>
            <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $shift->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ ucfirst($shift->status) }}
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-600">Jenis tiket</th>
                        <th class="px-3 py-2 text-right text-gray-600">Terjual</th>
                        <th class="px-3 py-2 text-right text-gray-600">Tertagih R1</th>
                        <th class="px-3 py-2 text-right text-gray-600">Tertagih R2</th>
                        <th class="px-3 py-2 text-right text-gray-600">Tertagih R3</th>
                        <th class="px-3 py-2 text-right text-gray-600">Dilimpahkan</th>
                        <th class="px-3 py-2 text-left text-gray-600">Limpah ke regu</th>
                        <th class="px-3 py-2 text-left text-gray-600 min-w-[8rem]">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @php
                        $sTerjual = $sR1 = $sR2 = $sR3 = $sDil = 0;
                    @endphp
                    @foreach($jenisTiket as $jenis)
                    @php
                        $row = $shift->limpahanTiket->firstWhere('jenis_tiket', $jenis);
                        $terjual = (int) ($row?->terjual ?? 0);
                        $r1 = (int) ($row?->tertagih_regu1 ?? 0);
                        $r2 = (int) ($row?->tertagih_regu2 ?? 0);
                        $r3 = (int) ($row?->tertagih_regu3 ?? 0);
                        $dil = (int) ($row?->dilimpahkan ?? 0);
                        $sTerjual += $terjual;
                        $sR1 += $r1;
                        $sR2 += $r2;
                        $sR3 += $r3;
                        $sDil += $dil;
                    @endphp
                    <tr class="hover:bg-gray-50 {{ !$row ? 'text-gray-400' : '' }}">
                        <td class="px-3 py-2 font-medium">
                            <span class="px-2 py-0.5 bg-asdp-50 text-asdp-800 rounded">{{ $jenis }}</span>
                        </td>
                        <td class="px-3 py-2 text-right tabular-nums">{{ number_format($terjual) }}</td>
                        <td class="px-3 py-2 text-right tabular-nums">{{ number_format($r1) }}</td>
                        <td class="px-3 py-2 text-right tabular-nums">{{ number_format($r2) }}</td>
                        <td class="px-3 py-2 text-right tabular-nums">{{ number_format($r3) }}</td>
                        <td class="px-3 py-2 text-right tabular-nums font-semibold {{ $dil > 0 ? 'text-orange-600' : 'text-gray-500' }}">{{ number_format($dil) }}</td>
                        <td class="px-3 py-2 text-gray-700">{{ $row?->dilimpahkanKeRegu?->nama_regu ?? '—' }}</td>
                        <td class="px-3 py-2 text-gray-600 truncate max-w-[14rem]" title="{{ $row?->keterangan ?? '' }}">{{ ($row && $row->keterangan) ? \Illuminate\Support\Str::limit($row->keterangan, 48) : '—' }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold">
                        <td class="px-3 py-2">Total</td>
                        <td class="px-3 py-2 text-right tabular-nums">{{ number_format($sTerjual) }}</td>
                        <td class="px-3 py-2 text-right tabular-nums">{{ number_format($sR1) }}</td>
                        <td class="px-3 py-2 text-right tabular-nums">{{ number_format($sR2) }}</td>
                        <td class="px-3 py-2 text-right tabular-nums">{{ number_format($sR3) }}</td>
                        <td class="px-3 py-2 text-right tabular-nums">{{ number_format($sDil) }}</td>
                        <td class="px-3 py-2" colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-16 text-center">
        <div class="text-5xl mb-3">📭</div>
        <p class="text-gray-500">
            Tidak ada shift untuk tanggal {{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}
            @if($reguId)
                <span class="block mt-1 text-sm">Coba ubah filter regu atau tanggal.</span>
            @endif
        </p>
    </div>
    @endforelse
</div>
@endsection
