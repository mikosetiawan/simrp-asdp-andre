@extends('layouts.app')
@section('title', 'Master Dermaga')
@section('breadcrumb', 'Master Data → Dermaga')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Total <strong class="text-gray-700">{{ $dermaga->total() }}</strong> dermaga terdaftar
        </p>
        <a href="{{ route('master.dermaga.create') }}"
           class="inline-flex items-center gap-2 bg-asdp-700 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-asdp-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Dermaga
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-asdp-800 text-white">
                        <th class="px-4 py-3 text-left font-medium text-xs uppercase tracking-wide">No</th>
                        <th class="px-4 py-3 text-left font-medium text-xs uppercase tracking-wide">Kode</th>
                        <th class="px-4 py-3 text-left font-medium text-xs uppercase tracking-wide">Nama Dermaga</th>
                        <th class="px-4 py-3 text-right font-medium text-xs uppercase tracking-wide">Tarif JSN / Trip</th>
                        <th class="px-4 py-3 text-right font-medium text-xs uppercase tracking-wide">Tarif Engker / Trip</th>
                        <th class="px-4 py-3 text-right font-medium text-xs uppercase tracking-wide">Kapasitas / Hari</th>
                        <th class="px-4 py-3 text-center font-medium text-xs uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-center font-medium text-xs uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($dermaga as $d)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $dermaga->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 bg-asdp-50 text-asdp-800 rounded-lg text-xs font-bold tracking-wide">
                                {{ $d->kode_dermaga }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $d->nama_dermaga }}</td>
                        <td class="px-4 py-3 text-right text-gray-700 font-medium">
                            Rp {{ number_format($d->tarif_jsn_per_trip, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right text-gray-700">
                            Rp {{ number_format($d->tarif_engker_per_trip, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right text-gray-600">
                            {{ $d->kapasitas_trip_per_hari }} trip
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $d->aktif ? 'bg-green-100 text-green-700' : 'bg-red-50 text-red-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $d->aktif ? 'bg-green-500' : 'bg-red-400' }}"></span>
                                {{ $d->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('master.dermaga.edit', $d) }}"
                                   class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('master.dermaga.destroy', $d) }}"
                                      onsubmit="return confirm('Hapus dermaga {{ $d->nama_dermaga }}?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-medium hover:bg-red-100 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-14 text-center">
                            <div class="text-4xl mb-3">⚓</div>
                            <p class="text-gray-400 text-sm">Belum ada data dermaga</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($dermaga->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $dermaga->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
