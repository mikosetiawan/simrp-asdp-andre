@extends('layouts.app')
@section('title', 'Jasa Sandar & Tambat (TAGIH03) — ' . $shift->regu->nama_regu)
@section('breadcrumb', 'Operasional → Shift → Jasa Sandar & Tambat')

@section('content')
@php
    $exMap = $shift->jasaSandar->keyBy('dermaga_id');
    $rupiah = fn ($v) => ((float) $v) > 0 ? 'Rp ' . number_format((float) $v, 0, ',', '.') : 'Rp -';
    $totalEngker = $shift->jasaSandar->sum('pendapatan_engker');
    $totalTambat = $shift->jasaSandar->sum('pendapatan_jsn');
    $totalJasa   = $shift->jasaSandar->sum('total_jasa_dermaga');
@endphp

<div class="max-w-5xl mx-auto space-y-5 font-sans">

    {{-- Header Card --}}
    <div class="bg-gradient-to-r from-asdp-900 via-asdp-800 to-asdp-700 rounded-2xl p-6 text-white shadow-md">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/15 text-white border border-white/20">
                        TAGIH03
                    </span>
                    <span class="text-white/70 text-xs">Shift #{{ $shift->id }}</span>
                </div>
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <span>⚓</span> Ringkasan Jasa Sandar &amp; Masa Tambat
                </h2>
                <p class="text-white/80 text-sm mt-1">
                    {{ $shift->regu->nama_regu }} — Shift {{ $shift->nama_shift }} ({{ $shift->tanggal->isoFormat('dddd, D MMMM Y') }})
                </p>
            </div>
            <a href="{{ route('operasional.shift.show', $shift) }}"
               class="px-4 py-2 bg-white/15 hover:bg-white/25 border border-white/20 rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
                <span>⬅️</span> Kembali ke Shift
            </a>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-5">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3.5 border border-white/15">
                <div class="text-white/70 text-xs mb-1 font-medium">Total Sandar Engker</div>
                <div class="text-white font-bold text-base font-mono">{{ $rupiah($totalEngker) }}</div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3.5 border border-white/15">
                <div class="text-white/70 text-xs mb-1 font-medium">Total Sandar Masa Tambat</div>
                <div class="text-white font-bold text-base font-mono">{{ $rupiah($totalTambat) }}</div>
            </div>
            <div class="bg-emerald-500/30 backdrop-blur-sm rounded-xl p-3.5 border border-emerald-300/40">
                <div class="text-emerald-100 text-xs mb-1 font-bold uppercase tracking-wider">Total Pendapatan Dermaga</div>
                <div class="text-white font-extrabold text-lg font-mono">{{ $rupiah($totalJasa) }}</div>
            </div>
        </div>
    </div>

    {{-- Detailed Sections --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-6">
        
        {{-- Section 5: Engker --}}
        <div>
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-asdp-100 text-asdp-800 text-xs flex items-center justify-center font-bold">5</span>
                    Jasa Sandar dari Engker
                </h4>
                <span class="text-xs text-slate-500">Kalkulasi per Call Sandar</span>
            </div>
            <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-xs">
                <table class="w-full text-xs text-slate-800 border-collapse">
                    <thead class="bg-slate-100 text-slate-700 font-semibold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-left">Sandar di Dermaga</th>
                            <th class="px-4 py-3 text-center w-28">Call Sandar</th>
                            <th class="px-4 py-3 text-right w-44">Pendapatan Engker</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($dermaga as $d)
                            @php $ex = $exMap[$d->id] ?? null; @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2.5 font-medium text-slate-800">- {{ strtoupper($d->nama_dermaga) }}</td>
                                <td class="px-4 py-2.5 text-center font-mono font-semibold text-slate-700">{{ $ex?->call_sandar ?? 0 }}</td>
                                <td class="px-4 py-2.5 text-right font-mono font-semibold text-slate-900">{{ $rupiah($ex?->pendapatan_engker ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-asdp-900 text-white font-bold">
                        <tr>
                            <td colspan="2" class="px-4 py-3 uppercase tracking-wider text-[11px]">Total Sandar Engker</td>
                            <td class="px-4 py-3 text-right font-mono text-xs text-emerald-300">{{ $rupiah($totalEngker) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Section 6: Masa Tambat --}}
        <div>
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2">
                <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-asdp-100 text-asdp-800 text-xs flex items-center justify-center font-bold">6</span>
                    Jasa Sandar Masa Tambat
                </h4>
                <span class="text-xs text-slate-500">Kalkulasi per Jumlah Trip Sandar</span>
            </div>
            <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-xs">
                <table class="w-full text-xs text-slate-800 border-collapse">
                    <thead class="bg-slate-100 text-slate-700 font-semibold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-left">Dermaga</th>
                            <th class="px-4 py-3 text-center w-28">Total Trip</th>
                            <th class="px-4 py-3 text-right w-44">Pendapatan Masa Tambat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($dermaga as $d)
                            @php $ex = $exMap[$d->id] ?? null; @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2.5 font-medium text-slate-800">- {{ strtoupper($d->nama_dermaga) }}</td>
                                <td class="px-4 py-2.5 text-center font-mono font-semibold text-slate-700">{{ $ex?->jumlah_trip ?? 0 }}</td>
                                <td class="px-4 py-2.5 text-right font-mono font-semibold text-slate-900">{{ $rupiah($ex?->pendapatan_jsn ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-asdp-900 text-white font-bold">
                        <tr>
                            <td colspan="2" class="px-4 py-3 uppercase tracking-wider text-[11px]">Total Sandar Masa Tambat</td>
                            <td class="px-4 py-3 text-right font-mono text-xs text-emerald-300">{{ $rupiah($totalTambat) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
