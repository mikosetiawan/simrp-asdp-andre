@extends('layouts.app')
@section('title', 'Detail Shift — ' . $shift->regu->nama_regu . ' ' . $shift->tanggal->isoFormat('D MMM Y'))
@section('breadcrumb', 'Operasional → Shift → Detail')

@section('content')
<div class="space-y-5">

    {{-- Header Card --}}
    <div class="bg-gradient-to-r from-asdp-900 to-asdp-700 rounded-2xl p-6 text-white">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $shift->status === 'approved' ? 'bg-green-400/30 text-green-100' :
                           ($shift->status === 'submitted' ? 'bg-blue-400/30 text-blue-100' : 'bg-yellow-400/30 text-yellow-100') }}">
                        {{ strtoupper($shift->status) }}
                    </span>
                    <span class="text-white/70 text-sm">Shift #{{ $shift->id }}</span>
                </div>
                <h2 class="text-xl font-bold">{{ $shift->regu->nama_regu }} — {{ $shift->nama_shift }}</h2>
                <p class="text-white/80 text-sm mt-1">
                    {{ $shift->tanggal->isoFormat('dddd, D MMMM Y') }} •
                    {{ substr($shift->jam_mulai, 0, 5) }} – {{ substr($shift->jam_selesai, 0, 5) }}
                </p>
                <p class="text-white/70 text-xs mt-1">
                    Supervisi: {{ $shift->supervisi->name ?? '-' }}
                    @if($shift->kolektor) • Kolektor: {{ $shift->kolektor->name }} @endif
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($shift->isDraft())
                    @can('shift.edit')
                    <a href="{{ route('operasional.shift.edit', $shift) }}"
                       class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-xl text-sm font-medium transition">
                        ✏️ Edit Shift
                    </a>
                    @endcan
                    @can('shift.submit')
                    <form method="POST" action="{{ route('operasional.shift.submit', $shift) }}" class="inline">
                        @csrf
                        <button class="px-4 py-2 bg-green-500 hover:bg-green-400 rounded-xl text-sm font-medium transition">
                            ✅ TUTUP DINAS
                        </button>
                    </form>
                    @endcan
                @endif
                @if($shift->isSubmitted())
                    @can('shift.approve')
                    <form method="POST" action="{{ route('operasional.shift.approve', $shift) }}" class="inline">
                        @csrf
                        <button class="px-4 py-2 bg-green-500 hover:bg-green-400 rounded-xl text-sm font-medium transition">
                            🏆 Approve
                        </button>
                    </form>
                    @endcan
                @endif
            </div>
        </div>

        {{-- Summary KPI --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-5">
            @php
                $totalPend = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->total_pendapatan ?? 0);
                $totalTrip = $shift->tripKapal->sum('jumlah_trip');
                $totalPnp  = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->total_penumpang ?? 0);
                $totalKnd  = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->total_kendaraan ?? 0);
            @endphp
            <div class="bg-white/10 rounded-xl p-3">
                <div class="text-white/60 text-xs mb-1">Total Pendapatan</div>
                <div class="text-white font-bold">Rp {{ number_format($totalPend, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white/10 rounded-xl p-3">
                <div class="text-white/60 text-xs mb-1">Total Trip</div>
                <div class="text-white font-bold">{{ $totalTrip }} Trip</div>
            </div>
            <div class="bg-white/10 rounded-xl p-3">
                <div class="text-white/60 text-xs mb-1">Total Penumpang</div>
                <div class="text-white font-bold">{{ number_format($totalPnp) }} Org</div>
            </div>
            <div class="bg-white/10 rounded-xl p-3">
                <div class="text-white/60 text-xs mb-1">Total Kendaraan</div>
                <div class="text-white font-bold">{{ number_format($totalKnd) }} Unit</div>
            </div>
        </div>
    </div>

    {{-- Action Buttons Sub-Modul --}}
    @if(!$shift->isApproved())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-4">📥 Input Data Shift</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-4 gap-3">
            <a href="{{ route('operasional.trip-kapal.create', $shift) }}"
               class="flex flex-col items-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl hover:border-asdp-400 hover:bg-asdp-50 transition text-center">
                <span class="text-2xl">🚢</span>
                <span class="text-xs font-semibold text-gray-700">Trip Kapal</span>
                <span class="text-[10px] text-gray-500 leading-tight mt-0.5">Jasa sandar di Tagih01</span>
            </a>
            <a href="{{ route('operasional.penjualan-tiket.create', $shift) }}"
               class="flex flex-col items-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl hover:border-asdp-400 hover:bg-asdp-50 transition text-center">
                <span class="text-2xl">🎫</span>
                <span class="text-xs font-semibold text-gray-700">Penjualan Tiket</span>
            </a>
            <a href="{{ route('operasional.limpahan-tiket.create', $shift) }}"
               class="flex flex-col items-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl hover:border-asdp-400 hover:bg-asdp-50 transition text-center">
                <span class="text-2xl">🔄</span>
                <span class="text-xs font-semibold text-gray-700">Limpahan Tiket</span>
            </a>
            <a href="{{ route('operasional.asuransi.create', $shift) }}"
               class="flex flex-col items-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl hover:border-asdp-400 hover:bg-asdp-50 transition text-center">
                <span class="text-2xl">🛡️</span>
                <span class="text-xs font-semibold text-gray-700">Asuransi (Tagih06)</span>
            </a>
            <a href="{{ route('laporan.klaim-roro.pdf', $shift) }}"
               class="flex flex-col items-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl hover:border-red-300 hover:bg-red-50 transition text-center">
                <span class="text-2xl">📄</span>
                <span class="text-xs font-semibold text-gray-700">Cetak Klaim RoRo</span>
            </a>
            <a href="{{ route('laporan.bap.pdf', $shift) }}"
               class="flex flex-col items-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl hover:border-red-300 hover:bg-red-50 transition text-center">
                <span class="text-2xl">📋</span>
                <span class="text-xs font-semibold text-gray-700">Cetak BAP</span>
            </a>
        </div>
    </div>
    @endif

    {{-- Excel Master Table View --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md overflow-hidden font-sans">
        {{-- Excel Ribbon Header --}}
        <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 px-5 py-3 text-white flex items-center justify-between border-b border-emerald-900">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center font-bold text-sm border border-white/20">
                    📊
                </div>
                <div>
                    <h3 class="font-bold text-sm tracking-wide flex items-center gap-2">
                        MASTER EXCEL — DATA TRIP & PENDAPATAN KAPAL (TAGIH01)
                    </h3>
                    <p class="text-[11px] text-emerald-150 text-white/80">Lembar Kerja Rekapitulasi Operasional Shift ASDP Merak</p>
                </div>
            </div>
            @if(!$shift->isApproved())
            <a href="{{ route('operasional.trip-kapal.create', $shift) }}"
               class="px-3.5 py-1.5 bg-white text-emerald-900 hover:bg-emerald-50 font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5">
                <span>➕</span> Input Trip Kapal Baru
            </a>
            @endif
        </div>

        {{-- Excel Spreadsheet Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-slate-800 border-collapse border border-slate-300">
                <thead class="bg-slate-100 text-slate-700 font-semibold uppercase tracking-wider border-b-2 border-slate-300">
                    <tr class="divide-x divide-slate-300 text-center">
                        <th class="px-2 py-2 w-10 bg-slate-200 border border-slate-300">NO</th>
                        <th class="px-3 py-2 border border-slate-300 text-left">NAMA KAPAL</th>
                        <th class="px-3 py-2 border border-slate-300 text-left">DERMAGA</th>
                        <th class="px-2 py-2 border border-slate-300 w-20">JAM TIBA</th>
                        <th class="px-2 py-2 border border-slate-300 w-24">JAM BERANGKAT</th>
                        <th class="px-2 py-2 border border-slate-300 w-16">TRIP KE</th>
                        <th class="px-2 py-2 border border-slate-300 w-16">JML TRIP</th>
                        <th class="px-3 py-2 border border-slate-300 text-right">PENUMPANG</th>
                        <th class="px-3 py-2 border border-slate-300 text-right">KENDARAAN</th>
                        <th class="px-3 py-2 border border-slate-300 text-right bg-emerald-50">TOTAL PENDAPATAN</th>
                        <th class="px-3 py-2 border border-slate-300 text-center">AKSI / OPSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($shift->tripKapal as $trip)
                    <tr class="hover:bg-amber-50/60 divide-x divide-slate-200 transition">
                        <td class="px-2 py-2 text-center text-slate-500 font-mono bg-slate-50 border border-slate-200">{{ $loop->iteration }}</td>
                        <td class="px-3 py-2 font-bold text-slate-800 border border-slate-200">
                            {{ $trip->kapal->nama_kapal ?? '-' }}
                            @if($trip->kapalPengganti)
                            <span class="inline-block text-[10px] bg-orange-100 text-orange-800 px-1.5 py-0.5 rounded font-normal ml-1">
                                Pgn: {{ $trip->kapalPengganti->nama_kapal }}
                            </span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-slate-600 border border-slate-200">{{ $trip->dermaga->nama_dermaga ?? '-' }}</td>
                        <td class="px-2 py-2 text-center font-mono text-slate-700 border border-slate-200">
                            {{ $trip->jam_tiba ? substr($trip->jam_tiba, 0, 5) : '-' }}
                        </td>
                        <td class="px-2 py-2 text-center font-mono text-slate-700 border border-slate-200">
                            {{ $trip->jam_berangkat ? substr($trip->jam_berangkat, 0, 5) : '-' }}
                        </td>
                        <td class="px-2 py-2 text-center font-semibold text-slate-700 border border-slate-200">{{ $trip->trip_ke }}</td>
                        <td class="px-2 py-2 text-center font-semibold text-slate-700 border border-slate-200">{{ $trip->jumlah_trip }}</td>
                        <td class="px-3 py-2 text-right font-mono border border-slate-200">
                            {{ number_format($trip->tagihPelayaran?->total_penumpang ?? 0) }}
                        </td>
                        <td class="px-3 py-2 text-right font-mono border border-slate-200">
                            {{ number_format($trip->tagihPelayaran?->total_kendaraan ?? 0) }}
                        </td>
                        <td class="px-3 py-2 text-right font-mono font-bold border border-slate-200 {{ $trip->tagihPelayaran ? 'text-emerald-800 bg-emerald-50/50' : 'text-amber-600 bg-amber-50/40' }}">
                            @if($trip->tagihPelayaran)
                                Rp {{ number_format($trip->tagihPelayaran->total_pendapatan, 0, ',', '.') }}
                            @else
                                <span class="italic text-[10px]">⚠️ Belum diinput</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center border border-slate-200">
                            <div class="flex items-center justify-center gap-1 flex-wrap">
                                @if(!$shift->isApproved())
                                <a href="{{ route('operasional.trip-kapal.edit', $trip) }}"
                                   class="px-2 py-1 bg-slate-100 text-slate-700 rounded hover:bg-slate-200 border border-slate-300 font-medium text-[11px]">
                                    ✏️ Edit
                                </a>
                                @endif
                                @if(!$trip->tagihPelayaran)
                                <a href="{{ route('operasional.tagih-pelayaran.create', $trip) }}"
                                   class="px-2 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700 font-semibold text-[11px] shadow-sm">
                                    📝 Input Tagih
                                </a>
                                @else
                                <a href="{{ route('operasional.tagih-pelayaran.edit', $trip->tagihPelayaran) }}"
                                   class="px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded hover:bg-blue-100 font-medium text-[11px]">
                                    ✏️ Edit Tagih
                                </a>
                                @endif
                                @if(!$trip->manifest)
                                <a href="{{ route('operasional.manifest.create', $trip) }}"
                                   class="px-2 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded hover:bg-purple-100 font-medium text-[11px]">
                                    📋 Manifest
                                </a>
                                @endif
                                @if(!$shift->isApproved())
                                <form method="POST" action="{{ route('operasional.trip-kapal.destroy', $trip) }}" class="inline"
                                    onsubmit="return confirm('Hapus data trip kapal ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-1 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded transition" title="Hapus">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-4 py-8 text-center text-slate-400 italic bg-slate-50 border border-slate-200">
                            Belum ada data trip kapal pada shift ini. Klik "+ Input Trip Kapal Baru" untuk mulai pengisian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($shift->tripKapal->count() > 0)
                <tfoot class="bg-slate-800 text-white font-bold divide-x divide-slate-700 border-t-2 border-slate-900">
                    <tr>
                        <td colspan="6" class="px-3 py-2.5 text-right uppercase tracking-wider text-[11px] text-slate-300">TOTAL REKAPITULASI SHIFT:</td>
                        <td class="px-2 py-2.5 text-center font-mono bg-slate-900 text-emerald-400">{{ $totalTrip }}</td>
                        <td class="px-3 py-2.5 text-right font-mono">{{ number_format($totalPnp) }}</td>
                        <td class="px-3 py-2.5 text-right font-mono">{{ number_format($totalKnd) }}</td>
                        <td class="px-3 py-2.5 text-right font-mono text-emerald-300 text-xs bg-emerald-950/60">
                            Rp {{ number_format($totalPend, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    @include('operasional.jasa-sandar._tagih03', ['shift' => $shift, 'dermaga' => $dermaga])

</div>
@endsection
