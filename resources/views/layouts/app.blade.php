<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — RANCANG BANGUN SISTEM INFORMASI MANAJEMEN REKAPITULASI PENJUALAN TIKET KAPAL FERRY PADA PT. ASDP Indonesia Ferry — Cabang Utama Merak</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        asdp: {
                            50:'#eef2fb', 100:'#d5dff5', 200:'#adbfeb',
                            300:'#7a97dc', 400:'#4f74cc', 500:'#2f56bb',
                            600:'#1e44a8', 700:'#163591', 800:'#0d2570',
                            900:'#091852',
                        },
                    },
                    fontFamily: { sans: ['Inter','sans-serif'] },
                }
            }
        }
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* ── Sidebar nav item ── */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            color: rgba(255,255,255,0.62);
            transition: background 0.15s, color 0.15s;
            cursor: pointer;
            text-decoration: none;
            line-height: 1;
        }
        .nav-item:hover {
            background: rgba(255,255,255,0.09);
            color: rgba(255,255,255,0.95);
        }
        .nav-item.active {
            background: rgba(255,255,255,0.15);
            color: #ffffff;
            font-weight: 600;
        }
        .nav-item .icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
            background: rgba(255,255,255,0.08);
        }
        .nav-item.active .icon {
            background: rgba(255,255,255,0.22);
        }
        .nav-section {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.28);
            padding: 18px 12px 6px;
        }
        /* ── Scrollbar thin ── */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }
    </style>
    @stack('styles')
</head>
<body class="h-full font-sans bg-gray-50" x-data="{ open: false }">

<div class="flex h-screen overflow-hidden">

    {{-- ══ OVERLAY mobile ══ --}}
    <div x-show="open" @click="open=false" x-transition.opacity
         class="fixed inset-0 bg-black/40 z-20 lg:hidden" style="display:none"></div>

    {{-- ══ SIDEBAR ══ --}}
    <aside
        class="fixed inset-y-0 left-0 z-30 w-[240px] flex flex-col lg:static lg:z-auto transition-transform duration-200"
        :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        style="background: linear-gradient(160deg, #0d2570 0%, #163591 55%, #1e44a8 100%);">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-4 py-4 border-b border-white/10 flex-shrink-0">
            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0 bg-white/10 rounded-xl p-1">
                <img src="{{ asset('images/asdp-ferry.png') }}" alt="ASDP Logo" class="w-full h-full object-contain filter drop-shadow-md">
            </div>
            <div class="min-w-0">
                <div class="text-white font-bold text-[11.5px] leading-tight tracking-wide truncate" title="SIM Rekap Penjualan Tiket Ferry">SIM REKAP TIKET FERRY</div>
                <div class="text-white/70 text-[8.5px] mt-0.5 leading-tight truncate">SI Rekapitulasi Penjualan Tiket Kapal Ferry</div>
                <div class="text-white/40 text-[8px] leading-tight truncate">PT. ASDP Indonesia Ferry — Cabang Utama Merak</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto sidebar-scroll px-3 py-3">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span>
                <span>Dashboard</span>
            </a>

            {{-- ── Operasional ── --}}
            @canany(['shift.view','trip.view','tagih.view'])
            <div class="nav-section">Operasional</div>
            <a href="{{ route('operasional.shift.index') }}"
               class="nav-item {{ request()->routeIs('operasional.shift.*') ? 'active' : '' }}">
                <span class="icon">📋</span>
                <span>Shift Operasional</span>
            </a>
            @endcanany

            {{-- ── Laporan ── --}}
            @can('laporan.view')
            <div class="nav-section">Laporan</div>
            <a href="{{ route('laporan.rekap-harian') }}"
               class="nav-item {{ request()->routeIs('laporan.rekap-harian*') ? 'active' : '' }}">
                <span class="icon">📅</span>
                <span>Rekap Harian</span>
            </a>
            <a href="{{ route('laporan.rekap-bulanan') }}"
               class="nav-item {{ request()->routeIs('laporan.rekap-bulanan*') ? 'active' : '' }}">
                <span class="icon">🗓️</span>
                <span>Rekap Bulanan</span>
            </a>
            <a href="{{ route('laporan.rekap-tahunan') }}"
               class="nav-item {{ request()->routeIs('laporan.rekap-tahunan*') ? 'active' : '' }}">
                <span class="icon">📆</span>
                <span>Rekap Tahunan</span>
            </a>
            <a href="{{ route('laporan.klaim-roro') }}"
               class="nav-item {{ request()->routeIs('laporan.klaim-roro*') ? 'active' : '' }}">
                <span class="icon">🛳️</span>
                <span>Klaim RoRo</span>
            </a>
            <a href="{{ route('laporan.penjualan-tiket') }}"
               class="nav-item {{ request()->routeIs('laporan.penjualan-tiket') ? 'active' : '' }}">
                <span class="icon">🎫</span>
                <span>Penjualan Tiket</span>
            </a>
            <a href="{{ route('laporan.limpahan-tiket') }}"
               class="nav-item {{ request()->routeIs('laporan.limpahan-tiket') ? 'active' : '' }}">
                <span class="icon">🔄</span>
                <span>Limpahan Tiket</span>
            </a>
            <a href="{{ route('laporan.bap') }}"
               class="nav-item {{ request()->routeIs('laporan.bap*') ? 'active' : '' }}">
                <span class="icon">📄</span>
                <span>BAP</span>
            </a>
            @endcan

            {{-- ── Master Data ── --}}
            @can('master.view')
            <div class="nav-section">Master Data</div>
            <a href="{{ route('master.kapal.index') }}"
               class="nav-item {{ request()->routeIs('master.kapal*') ? 'active' : '' }}">
                <span class="icon">⚓</span>
                <span>Master Kapal</span>
            </a>
            <a href="{{ route('master.dermaga.index') }}"
               class="nav-item {{ request()->routeIs('master.dermaga*') ? 'active' : '' }}">
                <span class="icon">🏗️</span>
                <span>Master Dermaga</span>
            </a>
            <a href="{{ route('master.tarif.index') }}"
               class="nav-item {{ request()->routeIs('master.tarif*') ? 'active' : '' }}">
                <span class="icon">💰</span>
                <span>Master Tarif</span>
            </a>
            <a href="{{ route('master.regu.index') }}"
               class="nav-item {{ request()->routeIs('master.regu*') ? 'active' : '' }}">
                <span class="icon">👥</span>
                <span>Master Regu</span>
            </a>
            <a href="{{ route('master.petugas.index') }}"
               class="nav-item {{ request()->routeIs('master.petugas*') ? 'active' : '' }}">
                <span class="icon">👤</span>
                <span>Data Petugas</span>
            </a>
            @endcan

        </nav>

        {{-- User card --}}
        <div class="flex-shrink-0 border-t border-white/10 px-3 py-3">
            <div class="flex items-center gap-3 bg-white/8 rounded-xl px-3 py-2.5"
                 style="background:rgba(255,255,255,0.07)">
                {{-- Avatar --}}
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                     style="background:rgba(255,255,255,0.2)">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="text-white text-[12.5px] font-semibold truncate leading-tight">
                        {{ auth()->user()->name ?? 'Pengguna' }}
                    </div>
                    <div class="text-white/45 text-[11px] truncate mt-0.5 capitalize">
                        {{ auth()->user()->getRoleNames()->first() ?? '-' }}
                    </div>
                </div>
                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit"
                            title="Keluar"
                            class="w-7 h-7 rounded-lg flex items-center justify-center text-white/40 hover:text-white hover:bg-white/15 transition-all">
                        <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ══ MAIN AREA ══ --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-200 flex items-center justify-between px-5 py-0 h-14 flex-shrink-0">
            <div class="flex items-center gap-3">
                {{-- Hamburger mobile --}}
                <button @click="open=true"
                        class="lg:hidden w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                {{-- Breadcrumb / title --}}
                <div>
                    <h1 class="text-[15px] font-semibold text-gray-800 leading-tight">
                        @yield('title', 'Dashboard')
                    </h1>
                    @hasSection('breadcrumb')
                    <p class="text-[11px] text-gray-400 mt-px">@yield('breadcrumb')</p>
                    @endif
                </div>
            </div>
            {{-- Right side --}}
            <div class="flex items-center gap-3">
                <span class="hidden sm:block text-[12px] text-gray-400">
                    {{ now()->locale('id')->isoFormat('ddd, D MMM Y') }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-green-700 bg-green-50 px-2.5 py-1 rounded-full border border-green-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    Online
                </span>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success') || session('error'))
        <div class="px-5 pt-3 space-y-2 flex-shrink-0">
            @if(session('success'))
            <div class="flex items-center gap-2.5 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm"
                 x-data x-init="setTimeout(()=>$el.remove(),5000)">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm"
                 x-data x-init="setTimeout(()=>$el.remove(),6000)">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-5">
            @yield('content')
        </main>
    </div>

</div>

@stack('scripts')
</body>
</html>
