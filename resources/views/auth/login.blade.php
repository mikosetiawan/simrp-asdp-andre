<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — SIMRP ASDP Merak</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter','sans-serif'] }, colors: { asdp: { 800:'#003087',700:'#1a4d9a',600:'#2c5fab' } } } } }</script>
</head>
<body class="min-h-screen bg-gradient-to-br from-asdp-800 via-blue-800 to-blue-600 flex items-center justify-center p-4 font-sans">

<div class="w-full max-w-md">
    {{-- Logo Card --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-lg mb-4 p-2">
            <img src="{{ asset('images/asdp-ferry.png') }}" alt="ASDP Logo" class="w-full h-full object-contain">
        </div>
        <h1 class="text-white font-bold text-2xl">SIMRP ASDP Merak</h1>
        <p class="text-blue-200 text-sm mt-1">Sistem Informasi Manajemen Rekapitulasi Pendapatan</p>
        <p class="text-blue-300 text-xs mt-0.5">PT. ASDP Indonesia Ferry — Cabang Utama Merak</p>
    </div>

    {{-- Login Form --}}
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-asdp-800 to-blue-700 px-6 py-4">
            <h2 class="text-white font-semibold">Masuk ke Sistem</h2>
            <p class="text-blue-200 text-xs mt-0.5">Gunakan akun yang telah diberikan oleh Administrator</p>
        </div>
        <form method="POST" action="{{ route('login') }}" class="p-6 space-y-4">
            @csrf

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-3">
                ❌ {{ $errors->first() }}
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="nama@asdpmerak.co.id"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-asdp-600 focus:border-asdp-600 transition @error('email') border-red-400 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input type="password" name="password" required
                    placeholder="••••••••"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-asdp-600 focus:border-asdp-600 transition @error('password') border-red-400 @enderror">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-asdp-600 rounded">
                    <span class="text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>

            <button type="submit"
                class="w-full bg-asdp-800 hover:bg-asdp-700 text-white font-semibold py-3 rounded-xl transition text-sm shadow-sm">
                🔐 Masuk ke Sistem
            </button>
        </form>

        {{-- Demo Accounts --}}
        <div class="px-6 pb-5">
            <details class="text-xs text-gray-400 cursor-pointer">
                <summary class="hover:text-gray-600 transition">Akun demo tersedia</summary>
                <div class="mt-2 bg-gray-50 rounded-xl p-3 space-y-1 text-gray-600">
                    <p><strong>Admin:</strong> admin@asdpmerak.co.id / Admin@1234</p>
                    <p><strong>Supervisi:</strong> supervisi1@asdpmerak.co.id / Supervisi@1</p>
                    <p><strong>Kolektor:</strong> kolektor1@asdpmerak.co.id / Kolektor@1</p>
                    <p><strong>Eksekutif:</strong> eksekutif@asdpmerak.co.id / Eksekutif@1</p>
                </div>
            </details>
        </div>
    </div>

    <p class="text-center text-blue-300 text-xs mt-6">
        © 2026 PT. ASDP Indonesia Ferry (Persero) — Cabang Utama Merak
    </p>
</div>
</body>
</html>
