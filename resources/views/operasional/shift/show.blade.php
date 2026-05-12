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

    {{-- Trip Kapal Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">🚢 Data Trip Kapal (Tagih01)</h3>
            @if(!$shift->isApproved())
            <a href="{{ route('operasional.trip-kapal.create', $shift) }}"
               class="text-sm text-asdp-700 font-medium hover:underline">+ Tambah Trip</a>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kapal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Dermaga</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Trip</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Penumpang</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Kendaraan</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Pendapatan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($shift->tripKapal as $trip)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $trip->kapal->nama_kapal ?? '-' }}
                            @if($trip->kapalPengganti)
                            <span class="text-xs text-orange-600 ml-1">(Pgn: {{ $trip->kapalPengganti->nama_kapal }})</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $trip->dermaga->nama_dermaga ?? '-' }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ $trip->jumlah_trip }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($trip->tagihPelayaran?->total_penumpang ?? 0) }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($trip->tagihPelayaran?->total_kendaraan ?? 0) }}</td>
                        <td class="px-4 py-3 text-right font-semibold">
                            @if($trip->tagihPelayaran)
                                Rp {{ number_format($trip->tagihPelayaran->total_pendapatan, 0, ',', '.') }}
                            @else
                                <span class="text-orange-500 text-xs">Belum diinput</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1 flex-wrap">
                                @if(!$shift->isApproved())
                                <a href="{{ route('operasional.trip-kapal.edit', $trip) }}"
                                   class="px-2 py-1 bg-amber-50 text-amber-900 rounded-lg text-xs font-medium hover:bg-amber-100 border border-amber-200">
                                    Edit Trip
                                </a>
                                @endif
                                @if(!$trip->tagihPelayaran)
                                <a href="{{ route('operasional.tagih-pelayaran.create', $trip) }}"
                                   class="px-2 py-1 bg-asdp-50 text-asdp-700 rounded-lg text-xs font-medium hover:bg-asdp-100">
                                    Input Tagih
                                </a>
                                @else
                                <a href="{{ route('operasional.tagih-pelayaran.edit', $trip->tagihPelayaran) }}"
                                   class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100">
                                    Edit Tagih
                                </a>
                                @endif
                                @if(!$trip->manifest)
                                <a href="{{ route('operasional.manifest.create', $trip) }}"
                                   class="px-2 py-1 bg-gray-50 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-100">
                                    Manifest
                                </a>
                                @endif
                                @if(!$shift->isApproved())
                                <form method="POST" action="{{ route('operasional.trip-kapal.destroy', $trip) }}" class="inline"
                                    onsubmit="return confirm('Hapus trip ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400 text-sm">Belum ada data trip kapal</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('operasional.jasa-sandar._tagih03', ['shift' => $shift, 'dermaga' => $dermaga])

</div>
@endsection
