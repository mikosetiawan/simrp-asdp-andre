@extends('layouts.app')
@section('title', 'Shift Operasional')
@section('breadcrumb', 'Operasional → Shift')

@section('content')
<div class="space-y-5">

    {{-- Filter + Action --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <input type="date" name="tanggal" value="{{ $tanggal }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-asdp-500">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-asdp-500">
                <option value="">Semua Status</option>
                <option value="draft"     {{ $status === 'draft'     ? 'selected' : '' }}>Draft</option>
                <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="approved"  {{ $status === 'approved'  ? 'selected' : '' }}>Approved</option>
            </select>
            <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                Filter
            </button>
        </form>
        @can('shift.create')
        <a href="{{ route('operasional.shift.create') }}"
           class="bg-asdp-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-asdp-700 transition flex items-center gap-2">
            <span>+</span> Buat Shift Baru
        </a>
        @endcan
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-asdp-900 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Tanggal</th>
                        <th class="px-4 py-3 text-left font-medium">Regu</th>
                        <th class="px-4 py-3 text-left font-medium">Shift</th>
                        <th class="px-4 py-3 text-left font-medium">Jam</th>
                        <th class="px-4 py-3 text-left font-medium">Supervisi</th>
                        <th class="px-4 py-3 text-right font-medium">Trip</th>
                        <th class="px-4 py-3 text-right font-medium">Pendapatan</th>
                        <th class="px-4 py-3 text-center font-medium">Status</th>
                        <th class="px-4 py-3 text-center font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($shifts as $shift)
                    @php
                        $totalPend = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->total_pendapatan ?? 0);
                        $totalTrip = $shift->tripKapal->sum('jumlah_trip');
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $shift->tanggal->isoFormat('D MMM Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 bg-asdp-50 text-asdp-800 rounded-full text-xs font-semibold">
                                {{ $shift->regu->nama_regu ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $shift->nama_shift }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            {{ substr($shift->jam_mulai, 0, 5) }} – {{ substr($shift->jam_selesai, 0, 5) }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $shift->supervisi->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ $totalTrip }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                            Rp {{ number_format($totalPend, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $shift->status === 'approved'  ? 'bg-green-100 text-green-700' :
                                   ($shift->status === 'submitted' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($shift->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('operasional.shift.show', $shift) }}"
                                   class="p-1.5 text-gray-500 hover:text-asdp-700 hover:bg-asdp-50 rounded-lg transition" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if($shift->isDraft())
                                <a href="{{ route('operasional.shift.edit', $shift) }}"
                                   class="p-1.5 text-gray-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @endif
                                @if($shift->isDraft())
                                <form method="POST" action="{{ route('operasional.shift.submit', $shift) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-gray-500 hover:text-green-700 hover:bg-green-50 rounded-lg transition" title="Submit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                            <div class="text-4xl mb-2">📭</div>
                            <p>Tidak ada data shift untuk filter yang dipilih</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($shifts->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $shifts->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
