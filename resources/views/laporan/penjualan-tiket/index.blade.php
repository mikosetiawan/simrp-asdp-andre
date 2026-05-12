{{-- laporan/penjualan-tiket/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Laporan Penjualan Tiket')
@section('breadcrumb', 'Laporan → Penjualan Tiket')

@section('content')
@php
    $kndFields = [
        'knd_gol_i','knd_gol_ii','knd_gol_iii','knd_gol_iv_a','knd_gol_iv_b',
        'knd_gol_v_a','knd_gol_v_b','knd_gol_vi_a','knd_gol_vi_b',
        'knd_gol_vii','knd_gol_viii','knd_gol_ix',
    ];
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
            <button type="submit" class="bg-asdp-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
                🔍 Tampilkan
            </button>
            @can('laporan.export')
            <a href="{{ route('laporan.penjualan-tiket.pdf', array_filter(['tanggal' => $tanggal, 'regu_id' => $reguId])) }}"
               class="bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-700 transition">
                📄 Cetak
            </a>
            @endcan
        </form>
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
                        <th class="px-2 py-2 text-left text-gray-600 whitespace-nowrap">Pos</th>
                        <th class="px-2 py-2 text-right text-gray-600">EKB-D</th>
                        <th class="px-2 py-2 text-right text-gray-600">EKB-L</th>
                        <th class="px-2 py-2 text-right text-gray-600">EKB-A</th>
                        <th class="px-2 py-2 text-right text-gray-600">I</th>
                        <th class="px-2 py-2 text-right text-gray-600">II</th>
                        <th class="px-2 py-2 text-right text-gray-600">III</th>
                        <th class="px-2 py-2 text-right text-gray-600">IV-A</th>
                        <th class="px-2 py-2 text-right text-gray-600">IV-B</th>
                        <th class="px-2 py-2 text-right text-gray-600">V-A</th>
                        <th class="px-2 py-2 text-right text-gray-600">V-B</th>
                        <th class="px-2 py-2 text-right text-gray-600">VI-A</th>
                        <th class="px-2 py-2 text-right text-gray-600">VI-B</th>
                        <th class="px-2 py-2 text-right text-gray-600">VII</th>
                        <th class="px-2 py-2 text-right text-gray-600">VIII</th>
                        <th class="px-2 py-2 text-right text-gray-600">IX</th>
                        <th class="px-2 py-2 text-right text-gray-600">Σ Pnp</th>
                        <th class="px-2 py-2 text-right text-gray-600">Σ Knd</th>
                        <th class="px-2 py-2 text-right text-gray-600">Pendapatan</th>
                        <th class="px-2 py-2 text-left text-gray-600 min-w-[6rem]">Ket</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @php
                        $tPnp = $tKnd = $tPend = 0;
                    @endphp
                    @foreach($posList as $pos)
                    @php
                        $p = $shift->penjualanTiket->firstWhere('pos_penjualan', $pos);
                        $sumKnd = $p
                            ? collect($kndFields)->sum(fn ($f) => (int) ($p->$f ?? 0))
                            : 0;
                        $rowPnp = $p ? $p->total_pnp : 0;
                        $rowPend = $p ? (int) ($p->total_pendapatan_penjualan ?? 0) : 0;
                        $tPnp += $rowPnp;
                        $tKnd += $sumKnd;
                        $tPend += $rowPend;
                    @endphp
                    <tr class="hover:bg-gray-50 {{ !$p ? 'text-gray-400' : '' }}">
                        <td class="px-2 py-2 font-medium whitespace-nowrap">{{ $pos }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->pnp_ekb_d ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->pnp_ekb_l ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->pnp_ekb_a ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->knd_gol_i ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->knd_gol_ii ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->knd_gol_iii ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->knd_gol_iv_a ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->knd_gol_iv_b ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->knd_gol_v_a ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->knd_gol_v_b ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->knd_gol_vi_a ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->knd_gol_vi_b ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->knd_gol_vii ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->knd_gol_viii ?? 0) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($p?->knd_gol_ix ?? 0) }}</td>
                        <td class="px-2 py-2 text-right font-medium tabular-nums">{{ number_format($rowPnp) }}</td>
                        <td class="px-2 py-2 text-right font-medium tabular-nums">{{ number_format($sumKnd) }}</td>
                        <td class="px-2 py-2 text-right font-semibold tabular-nums">Rp {{ number_format($rowPend, 0, ',', '.') }}</td>
                        <td class="px-2 py-2 text-gray-600 truncate max-w-[10rem]" title="{{ $p->keterangan ?? '' }}">{{ ($p && $p->keterangan) ? \Illuminate\Support\Str::limit($p->keterangan, 40) : '—' }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold">
                        <td class="px-2 py-2">Total shift</td>
                        <td class="px-2 py-2 text-right tabular-nums" colspan="15"></td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($tPnp) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">{{ number_format($tKnd) }}</td>
                        <td class="px-2 py-2 text-right tabular-nums">Rp {{ number_format($tPend, 0, ',', '.') }}</td>
                        <td class="px-2 py-2"></td>
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
