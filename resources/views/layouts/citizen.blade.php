<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('Dashboard')) - WardSewa {{ __('Citizen Portal') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Mukta:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN + Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nepal: {
                            red: '#DC143C',
                            crimson: '#C41230',
                            blue: '#003893',
                            darkblue: '#002566',
                            gold: '#D4AF37'
                        }
                    },
                    fontFamily: {
                        sans: ['Mukta', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans min-h-screen flex" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
    @php
        $citizen = auth('citizen')->user();
        $isNe = app()->getLocale() === 'ne';
    @endphp

    <!-- Mobile Sidebar Backdrop & Drawer -->
    <div x-show="mobileSidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 md:hidden"
         @click="mobileSidebarOpen = false"
         style="display: none;">
    </div>

    <!-- Mobile Off-Canvas Drawer -->
    <aside x-show="mobileSidebarOpen"
           x-transition:enter="transition ease-in-out duration-300 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in-out duration-300 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-slate-300 flex flex-col md:hidden shadow-2xl"
           style="display: none;">
        <!-- Mobile Header -->
        <div class="p-4 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <x-logo class="w-8 h-8 text-white" />
                <div>
                    <h1 class="text-white font-bold text-base leading-none">WardSewa</h1>
                    <span class="text-[11px] text-nepal-gold font-medium">{{ __('Citizen Portal') }}</span>
                </div>
            </div>
            <button @click="mobileSidebarOpen = false" class="text-slate-400 hover:text-white p-1 rounded-lg">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Jurisdiction -->
        @if($citizen?->ward)
            <div class="p-3 mx-3 my-2 bg-slate-800/90 rounded-xl border border-slate-700/60 text-xs">
                <div class="text-slate-400 font-medium text-[10px] uppercase tracking-wider">{{ __('Jurisdiction') }}</div>
                <div class="font-bold text-white mt-0.5 truncate">
                    {{ $isNe ? ($citizen->ward->palika->name_ne ?? $citizen->ward->palika->name_en) : ($citizen->ward->palika->name_en ?? $citizen->ward->palika->name_ne) }}
                </div>
                <div class="text-nepal-gold text-[11px] font-semibold mt-0.5">
                    {{ __('Ward No.') }} {{ $citizen->ward->ward_number }}
                </div>
            </div>
        @endif

        <!-- Mobile CTA -->
        <div class="px-3 pt-2 pb-1">
            <a href="{{ route('citizen.applications.create') }}" class="flex items-center justify-center space-x-2 w-full py-2.5 px-3 bg-nepal-red hover:bg-nepal-crimson text-white text-xs font-bold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>{{ __('New Application') }}</span>
            </a>
        </div>

        <!-- Mobile Navigation Links -->
        <nav class="p-3 space-y-1 text-sm font-medium flex-grow overflow-y-auto">
            <a href="{{ route('citizen.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('citizen.dashboard') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>{{ __('Dashboard') }}</span>
            </a>
            <a href="{{ route('citizen.applications.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('citizen.applications.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>{{ __('My Applications') }}</span>
            </a>
            <a href="{{ route('citizen.bills.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('citizen.bills.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>{{ __('Utility Bills') }}</span>
            </a>
            <a href="{{ route('citizen.appointments.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('citizen.appointments.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>{{ __('Appointments') }}</span>
            </a>
            <a href="{{ route('citizen.complaints.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('citizen.complaints.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <span>{{ __('Grievance') }}</span>
            </a>
            <a href="{{ route('citizen.profile') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('citizen.profile') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>{{ __('Profile') }}</span>
            </a>
        </nav>

        <!-- Mobile User & Logout -->
        <div class="p-4 border-t border-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2.5 truncate">
                    <div class="w-9 h-9 rounded-full bg-nepal-crimson text-white flex items-center justify-center font-bold text-xs shrink-0">
                        {{ substr($citizen?->full_name ?? 'C', 0, 1) }}
                    </div>
                    <div class="truncate">
                        <div class="text-xs font-semibold text-white truncate">{{ $citizen?->full_name }}</div>
                        <div class="text-[11px] text-slate-400 truncate">{{ $citizen?->phone }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('citizen.logout') }}">
                    @csrf
                    <button type="submit" title="{{ __('Logout') }}" class="text-slate-400 hover:text-rose-400 p-2 rounded-lg hover:bg-slate-800 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Desktop Collapsible Sidebar -->
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" 
           class="bg-slate-900 text-slate-300 flex-col shrink-0 min-h-screen transition-all duration-300 ease-in-out hidden md:flex sticky top-0 h-screen z-30">
        
        <!-- Brand Header -->
        <div class="h-16 px-4 border-b border-slate-800 flex items-center justify-between">
            <a href="{{ route('citizen.dashboard') }}" class="flex items-center space-x-3 overflow-hidden">
                <x-logo class="w-8 h-8 shrink-0 text-white" />
                <div x-show="sidebarOpen" x-transition class="truncate">
                    <h1 class="text-white font-bold text-base leading-none tracking-tight">WardSewa</h1>
                    <span class="text-[11px] text-nepal-gold font-medium">{{ __('Citizen Portal') }}</span>
                </div>
            </a>
        </div>

        <!-- Citizen Jurisdiction Card -->
        @if($citizen?->ward)
            <div x-show="sidebarOpen" x-transition class="p-3 mx-3 my-3 bg-slate-800/80 rounded-xl border border-slate-700/60 text-xs">
                <div class="text-slate-400 font-medium text-[10px] uppercase tracking-wider">{{ __('Jurisdiction') }}</div>
                <div class="font-bold text-white mt-0.5 truncate text-xs">
                    {{ $isNe ? ($citizen->ward->palika->name_ne ?? $citizen->ward->palika->name_en) : ($citizen->ward->palika->name_en ?? $citizen->ward->palika->name_ne) }}
                </div>
                <div class="text-nepal-gold text-[11px] font-semibold mt-0.5">
                    {{ __('Ward No.') }} {{ $citizen->ward->ward_number }}
                </div>
            </div>
        @endif

        <!-- Quick Action "+ New Application" -->
        <div class="px-3 pt-2 pb-1">
            <a href="{{ route('citizen.applications.create') }}" 
               class="flex items-center justify-center space-x-2 w-full py-2.5 px-2 bg-nepal-red hover:bg-nepal-crimson text-white text-xs font-bold rounded-lg shadow-sm transition group"
               :title="!sidebarOpen ? '{{ __('New Application') }}' : ''">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span x-show="sidebarOpen" x-transition class="truncate">{{ __('New Application') }}</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="p-3 space-y-1 text-xs font-medium flex-grow overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('citizen.dashboard') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('citizen.dashboard') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
               :title="!sidebarOpen ? '{{ __('Dashboard') }}' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span x-show="sidebarOpen" x-transition class="truncate">{{ __('Dashboard') }}</span>
            </a>

            <!-- Applications -->
            <a href="{{ route('citizen.applications.index') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('citizen.applications.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
               :title="!sidebarOpen ? '{{ __('My Applications') }}' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span x-show="sidebarOpen" x-transition class="truncate">{{ __('My Applications') }}</span>
            </a>

            <!-- Utility Bills -->
            <a href="{{ route('citizen.bills.index') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('citizen.bills.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
               :title="!sidebarOpen ? '{{ __('Utility Bills') }}' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span x-show="sidebarOpen" x-transition class="truncate">{{ __('Utility Bills') }}</span>
            </a>

            <!-- Appointments -->
            <a href="{{ route('citizen.appointments.index') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('citizen.appointments.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
               :title="!sidebarOpen ? '{{ __('Appointments') }}' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span x-show="sidebarOpen" x-transition class="truncate">{{ __('Appointments') }}</span>
            </a>

            <!-- Grievance -->
            <a href="{{ route('citizen.complaints.index') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('citizen.complaints.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
               :title="!sidebarOpen ? '{{ __('Grievance') }}' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <span x-show="sidebarOpen" x-transition class="truncate">{{ __('Grievance') }}</span>
            </a>

            <!-- Profile -->
            <a href="{{ route('citizen.profile') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('citizen.profile') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
               :title="!sidebarOpen ? '{{ __('Profile') }}' : ''">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span x-show="sidebarOpen" x-transition class="truncate">{{ __('Profile') }}</span>
            </a>
        </nav>

        <!-- Current User Profile & Logout -->
        <div class="p-3 border-t border-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 truncate">
                    <div class="w-8 h-8 rounded-full bg-nepal-crimson text-white flex items-center justify-center font-bold text-xs shrink-0">
                        {{ substr($citizen?->full_name ?? 'C', 0, 1) }}
                    </div>
                    <div x-show="sidebarOpen" x-transition class="truncate">
                        <div class="text-xs font-semibold text-white truncate">{{ $citizen?->full_name }}</div>
                        <div class="text-[10px] text-slate-400 truncate">{{ $citizen?->phone }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('citizen.logout') }}" x-show="sidebarOpen" x-transition>
                    @csrf
                    <button type="submit" title="{{ __('Logout') }}" class="text-slate-400 hover:text-rose-400 p-1.5 rounded hover:bg-slate-800 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Navigation Bar -->
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-20 shadow-sm">
            <div class="flex items-center space-x-3">
                <!-- Desktop Sidebar Toggle Button -->
                <button @click="sidebarOpen = !sidebarOpen" class="hidden md:flex text-slate-500 hover:text-slate-800 p-2 rounded-lg hover:bg-slate-100 transition focus:outline-none" title="Toggle Sidebar">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                </button>

                <!-- Mobile Menu Button -->
                <button @click="mobileSidebarOpen = true" class="md:hidden text-slate-500 hover:text-slate-800 p-2 rounded-lg hover:bg-slate-100 transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <h2 class="text-base sm:text-lg font-bold text-slate-800 truncate">
                    @yield('title', __('Dashboard'))
                </h2>
            </div>

            <div class="flex items-center space-x-3">
                <!-- Monolingual Language Switcher -->
                <div class="flex items-center space-x-1 bg-slate-100 p-1 rounded-lg text-xs font-semibold border border-slate-200">
                    <a href="{{ route('locale.switch', 'ne') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'ne' ? 'bg-nepal-crimson text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">नेपाली</a>
                    <a href="{{ route('locale.switch', 'en') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'en' ? 'bg-nepal-crimson text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">EN</a>
                </div>

                <!-- Citizen Avatar & Profile Link -->
                <a href="{{ route('citizen.profile') }}" class="flex items-center space-x-2 text-slate-700 hover:text-slate-900 p-1 rounded-lg transition">
                    <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs">
                        {{ substr($citizen?->full_name ?? 'C', 0, 1) }}
                    </div>
                    <span class="hidden sm:inline-block text-xs font-medium max-w-[120px] truncate">{{ $citizen?->full_name }}</span>
                </a>
            </div>
        </header>

        <!-- Flash Alerts -->
        <div class="px-4 sm:px-6 lg:px-8 mt-4 w-full">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg mb-4 text-xs font-medium flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg mb-4 text-xs font-medium flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-4 text-xs font-medium flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif
        </div>

        <!-- Main Content Area -->
        <main class="flex-grow p-4 sm:p-6 lg:px-8 w-full max-w-7xl">
            @yield('content')
        </main>

        <!-- Clean Footer -->
        <footer class="bg-white border-t border-slate-200 py-4 px-6 text-center text-xs text-slate-500">
            <p>WardSewa — {{ __('Digital Ward Citizen Services') }} | &copy; {{ date('Y') }} {{ __('All Rights Reserved') }}.</p>
        </footer>
    </div>
</body>
</html>
