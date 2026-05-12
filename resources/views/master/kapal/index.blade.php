{{-- resources/views/master/kapal/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Master Kapal')
@section('breadcrumb', 'Master Data → Kapal')
@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">Total: <strong>{{ $kapal->total() }}</strong> kapal terdaftar</p>
        <a href="{{ route('master.kapal.create') }}"
           class="bg-asdp-800 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
            + Tambah Kapal
        </a>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-asdp-900 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Kapal</th>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-right">GRT</th>
                    <th class="px-4 py-3 text-center">Jenis</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($kapal as $k)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $kapal->firstItem() + $loop->index }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-800">{{ $k->nama_kapal }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $k->kode_kapal ?? '-' }}</td>
                    <td class="px-4 py-3 text-right font-medium">{{ number_format($k->grt) }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold uppercase">{{ $k->jenis }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $k->aktif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                            {{ $k->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-1">
                            <a href="{{ route('master.kapal.edit', $k) }}"
                               class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100">Edit</a>
                            <form method="POST" action="{{ route('master.kapal.destroy', $k) }}" onsubmit="return confirm('Hapus kapal {{ $k->nama_kapal }}?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-medium hover:bg-red-100">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-10 text-center text-gray-400">Belum ada data kapal</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($kapal->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $kapal->links() }}</div>
        @endif
    </div>
</div>
@endsection
