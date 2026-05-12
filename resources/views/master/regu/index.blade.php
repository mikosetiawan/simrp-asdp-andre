@extends('layouts.app')
@section('title', 'Master Regu')
@section('breadcrumb', 'Master Data → Regu')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Data regu kerja operasional pelabuhan</p>
        <a href="{{ route('master.regu.create') }}"
           class="inline-flex items-center gap-2 bg-asdp-700 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-asdp-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Regu
        </a>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        @forelse($regu as $r)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
            <div class="bg-gradient-to-br from-asdp-700 to-asdp-900 px-5 py-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center mx-auto mb-3">
                    <span class="text-white font-black text-2xl">{{ $r->kode_regu }}</span>
                </div>
                <h3 class="text-white font-bold text-lg">{{ $r->nama_regu }}</h3>
                <span class="inline-flex items-center gap-1 mt-2 px-2.5 py-0.5 rounded-full text-xs font-semibold
                    {{ $r->aktif ? 'bg-green-400/25 text-green-200' : 'bg-red-400/25 text-red-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $r->aktif ? 'bg-green-400' : 'bg-red-400' }}"></span>
                    {{ $r->aktif ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <div class="px-5 py-4">
                <div class="flex items-center justify-between mb-3 text-sm">
                    <span class="text-gray-500">Total Shift</span>
                    <span class="font-bold text-gray-800">{{ $r->shifts_count ?? 0 }}</span>
                </div>
                @if($r->keterangan)
                <p class="text-xs text-gray-400 mb-3">{{ $r->keterangan }}</p>
                @endif
                <div class="flex gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('master.regu.edit', $r) }}"
                       class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold hover:bg-blue-100 transition">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('master.regu.destroy', $r) }}"
                          onsubmit="return confirm('Hapus {{ $r->nama_regu }}?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-2 bg-red-50 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-100 transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-2xl border border-gray-100 py-16 text-center">
            <div class="text-4xl mb-3">👥</div>
            <p class="text-gray-400 text-sm">Belum ada data regu</p>
        </div>
        @endforelse
    </div>

</div>
@endsection
