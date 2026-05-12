@extends('layouts.app')
@section('title', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Petugas')
@section('breadcrumb', 'Master Data → Petugas → ' . ($mode === 'create' ? 'Tambah' : 'Edit'))

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="bg-gradient-to-r from-asdp-800 to-asdp-600 px-6 py-4">
            <h2 class="text-white font-semibold text-base">
                {{ $mode === 'create' ? '👤 Tambah Petugas Baru' : '✏️ Edit Data Petugas' }}
            </h2>
            <p class="text-white/60 text-xs mt-0.5">Kelola akun dan hak akses petugas operasional</p>
        </div>

        <form method="POST"
              action="{{ $mode === 'create' ? route('master.petugas.store') : route('master.petugas.update', $petugas) }}"
              class="p-6 space-y-5">
            @csrf
            @if($mode === 'edit') @method('PUT') @endif

            {{-- Nama & NIK --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', $petugas->name) }}"
                           placeholder="Nama lengkap petugas"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">NIK</label>
                    <input type="text" name="nik"
                           value="{{ old('nik', $petugas->nik) }}"
                           placeholder="Nomor Induk Kependudukan"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition @error('nik') border-red-400 @enderror">
                    @error('nik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Email & Jabatan --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email"
                           value="{{ old('email', $petugas->email) }}"
                           placeholder="email@asdpmerak.co.id"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Jabatan</label>
                    <input type="text" name="jabatan"
                           value="{{ old('jabatan', $petugas->jabatan) }}"
                           placeholder="Contoh: Supervisi Usaha Regu I"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition">
                </div>
            </div>

            {{-- Password --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Password {{ $mode === 'edit' ? '(kosongkan jika tidak diubah)' : '*' }}
                    </label>
                    <input type="password" name="password"
                           placeholder="{{ $mode === 'create' ? 'Minimal 8 karakter' : 'Biarkan kosong jika tidak diubah' }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition @error('password') border-red-400 @enderror">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Konfirmasi Password {{ $mode === 'create' ? '*' : '' }}
                    </label>
                    <input type="password" name="password_confirmation"
                           placeholder="Ulangi password"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition">
                </div>
            </div>

            {{-- Role & Regu --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Role / Hak Akses <span class="text-red-500">*</span>
                    </label>
                    <select name="role"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition @error('role') border-red-400 @enderror">
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $r)
                        <option value="{{ $r->name }}"
                            {{ old('role', $petugas->roles->first()?->name) === $r->name ? 'selected' : '' }}>
                            {{ ucfirst($r->name) }}
                        </option>
                        @endforeach
                    </select>
                    @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Regu</label>
                    <select name="regu_id"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition">
                        <option value="">-- Tidak Terikat Regu --</option>
                        @foreach($regu as $rg)
                        <option value="{{ $rg->id }}"
                            {{ old('regu_id', $petugas->regu_id) == $rg->id ? 'selected' : '' }}>
                            {{ $rg->nama_regu }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Status aktif --}}
            <div>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="aktif" value="1"
                               {{ old('aktif', $petugas->aktif ?? true) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 peer-checked:bg-asdp-600 rounded-full transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Akun Aktif</span>
                </label>
                <p class="text-xs text-gray-400 mt-1 ml-12">Akun nonaktif tidak dapat login ke sistem</p>
            </div>

            {{-- Info role --}}
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-xs text-blue-700 space-y-1">
                <p class="font-semibold mb-1">📌 Panduan Role:</p>
                <p><strong>Admin</strong> — Akses penuh termasuk master data</p>
                <p><strong>Supervisi</strong> — Input shift, trip, tagih pelayaran, laporan</p>
                <p><strong>Kolektor</strong> — Input tagih pelayaran dan penjualan tiket</p>
                <p><strong>Eksekutif</strong> — Hanya bisa melihat laporan & dashboard</p>
            </div>

            <div class="flex gap-3 pt-3 border-t border-gray-100">
                <a href="{{ route('master.petugas.index') }}"
                   class="flex-1 text-center px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-asdp-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-800 transition">
                    {{ $mode === 'create' ? '✅ Simpan Petugas' : '💾 Perbarui Petugas' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
