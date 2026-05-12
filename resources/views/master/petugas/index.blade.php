@extends('layouts.app')
@section('title', 'Data Petugas')
@section('breadcrumb', 'Master Data → Petugas')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Total <strong class="text-gray-700">{{ $petugas->total() }}</strong> pengguna terdaftar
        </p>
        <a href="{{ route('master.petugas.create') }}"
           class="inline-flex items-center gap-2 bg-asdp-700 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-asdp-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Petugas
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-asdp-800 text-white">
                        <th class="px-4 py-3 text-left font-medium text-xs uppercase tracking-wide">No</th>
                        <th class="px-4 py-3 text-left font-medium text-xs uppercase tracking-wide">Nama</th>
                        <th class="px-4 py-3 text-left font-medium text-xs uppercase tracking-wide">NIK</th>
                        <th class="px-4 py-3 text-left font-medium text-xs uppercase tracking-wide">Email</th>
                        <th class="px-4 py-3 text-left font-medium text-xs uppercase tracking-wide">Jabatan</th>
                        <th class="px-4 py-3 text-left font-medium text-xs uppercase tracking-wide">Regu</th>
                        <th class="px-4 py-3 text-center font-medium text-xs uppercase tracking-wide">Role</th>
                        <th class="px-4 py-3 text-center font-medium text-xs uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-center font-medium text-xs uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($petugas as $p)
                    @php $role = $p->roles->first()?->name ?? '-'; @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $petugas->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                     style="background: linear-gradient(135deg, #163591, #1e44a8)">
                                    {{ strtoupper(substr($p->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-gray-800">{{ $p->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $p->nik ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->email }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $p->jabatan ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($p->regu)
                            <span class="px-2 py-0.5 bg-asdp-50 text-asdp-800 rounded-lg text-xs font-semibold">
                                {{ $p->regu->nama_regu }}
                            </span>
                            @else
                            <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $roleColors = [
                                    'admin'     => 'bg-purple-100 text-purple-700',
                                    'supervisi' => 'bg-blue-100 text-blue-700',
                                    'kolektor'  => 'bg-orange-100 text-orange-700',
                                    'eksekutif' => 'bg-green-100 text-green-700',
                                ];
                                $roleColor = $roleColors[$role] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize {{ $roleColor }}">
                                {{ $role }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $p->aktif ? 'bg-green-100 text-green-700' : 'bg-red-50 text-red-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $p->aktif ? 'bg-green-500' : 'bg-red-400' }}"></span>
                                {{ $p->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('master.petugas.edit', $p) }}"
                                   class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                                    Edit
                                </a>
                                @if($p->id !== auth()->id())
                                <form method="POST" action="{{ route('master.petugas.destroy', $p) }}"
                                      onsubmit="return confirm('Hapus petugas {{ $p->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-medium hover:bg-red-100 transition">
                                        Hapus
                                    </button>
                                </form>
                                @else
                                <span class="px-3 py-1.5 text-gray-300 text-xs">Anda</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-14 text-center">
                            <div class="text-4xl mb-3">👤</div>
                            <p class="text-gray-400 text-sm">Belum ada data petugas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($petugas->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $petugas->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
