<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ड्यासबोर्ड') - WardSewa नागरिक पोर्टल</title>

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
<body class="bg-slate-100 text-slate-800 antialiased font-sans min-h-screen flex flex-col">
    <!-- Citizen Top Navigation -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Brand & Ward Pill -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('citizen.dashboard') }}" class="flex items-center space-x-2">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-nepal-crimson to-nepal-red text-white flex items-center justify-center font-black shadow">
                            व
                        </div>
                        <span class="font-bold text-xl tracking-tight text-slate-900">WardSewa</span>
                    </a>

                    @if(auth('citizen')->user()->ward)
                        <div class="hidden sm:flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-nepal-blue border border-blue-200">
                            <span>{{ auth('citizen')->user()->ward->palika->name_ne ?? auth('citizen')->user()->ward->palika->name_en }} - वडा नं. {{ auth('citizen')->user()->ward->ward_number }}</span>
                        </div>
                    @endif
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center space-x-6 text-sm font-medium">
                    <a href="{{ route('citizen.dashboard') }}" class="{{ request()->routeIs('citizen.dashboard') ? 'text-nepal-crimson font-bold border-b-2 border-nepal-crimson' : 'text-slate-600 hover:text-slate-900' }} py-5">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('citizen.applications.index') }}" class="{{ request()->routeIs('citizen.applications.*') ? 'text-nepal-crimson font-bold border-b-2 border-nepal-crimson' : 'text-slate-600 hover:text-slate-900' }} py-5">
                        {{ __('My Applications') }}
                    </a>
                    <a href="{{ route('citizen.applications.create') }}" class="inline-flex items-center px-3 py-1.5 bg-nepal-red hover:bg-nepal-crimson text-white text-xs font-semibold rounded-md shadow-sm transition">
                        {{ __('+ New Application') }}
                    </a>
                    <a href="{{ route('citizen.bills.index') }}" class="{{ request()->routeIs('citizen.bills.*') ? 'text-nepal-crimson font-bold border-b-2 border-nepal-crimson' : 'text-slate-600 hover:text-slate-900' }} py-5">
                        {{ __('Utility Bills') }}
                    </a>
                    <a href="{{ route('citizen.appointments.index') }}" class="{{ request()->routeIs('citizen.appointments.*') ? 'text-nepal-crimson font-bold border-b-2 border-nepal-crimson' : 'text-slate-600 hover:text-slate-900' }} py-5">
                        {{ __('Appointments') }}
                    </a>
                    <a href="{{ route('citizen.complaints.index') }}" class="{{ request()->routeIs('citizen.complaints.*') ? 'text-nepal-crimson font-bold border-b-2 border-nepal-crimson' : 'text-slate-600 hover:text-slate-900' }} py-5">
                        {{ __('Grievance') }}
                    </a>
                </div>

                <!-- User profile & Logout -->
                <div class="hidden md:flex items-center space-x-3">
                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-1 bg-slate-100 rounded px-2 py-1 text-xs">
                        <a href="{{ route('locale.switch', 'ne') }}" class="{{ app()->getLocale() === 'ne' ? 'font-bold text-nepal-crimson' : 'text-slate-600 hover:text-slate-900' }}">नेपाली</a>
                        <span class="text-slate-300">|</span>
                        <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'font-bold text-nepal-crimson' : 'text-slate-600 hover:text-slate-900' }}">EN</a>
                    </div>

                    <a href="{{ route('citizen.profile') }}" class="text-sm font-medium text-slate-700 hover:text-slate-900 flex items-center space-x-1.5">
                        <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs">
                            {{ substr(auth('citizen')->user()->full_name, 0, 1) }}
                        </div>
                        <span class="max-w-[120px] truncate">{{ auth('citizen')->user()->full_name }}</span>
                    </a>
                    <form method="POST" action="{{ route('citizen.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-slate-500 hover:text-red-600 bg-slate-100 hover:bg-red-50 px-2.5 py-1.5 rounded transition">
                            {{ __('Logout') }}
                        </button>
                    </form>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center space-x-2 md:hidden">
                    <div class="flex items-center space-x-1 bg-slate-100 rounded px-1.5 py-0.5 text-xs">
                        <a href="{{ route('locale.switch', 'ne') }}" class="{{ app()->getLocale() === 'ne' ? 'font-bold text-nepal-crimson' : 'text-slate-600' }}">NE</a>
                        <span class="text-slate-300">|</span>
                        <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'font-bold text-nepal-crimson' : 'text-slate-600' }}">EN</a>
                    </div>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-600 hover:text-slate-900 focus:outline-none p-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" class="md:hidden border-t border-slate-200 bg-white px-4 pt-2 pb-4 space-y-2">
            <a href="{{ route('citizen.dashboard') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('Dashboard') }}</a>
            <a href="{{ route('citizen.applications.create') }}" class="block px-3 py-2 rounded-md text-sm font-medium bg-nepal-red text-white">{{ __('+ New Application') }}</a>
            <a href="{{ route('citizen.applications.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('My Applications') }}</a>
            <a href="{{ route('citizen.bills.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('Utility Bills') }}</a>
            <a href="{{ route('citizen.appointments.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('Appointments') }}</a>
            <a href="{{ route('citizen.complaints.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('Grievance') }}</a>
            <a href="{{ route('citizen.profile') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('Profile') }}</a>
            <form method="POST" action="{{ route('citizen.logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-sm font-medium text-red-600 hover:bg-red-50">{{ __('Logout') }}</button>
            </form>
        </div>
    </nav>

    <!-- Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg mb-4 text-sm flex items-center shadow-sm">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg mb-4 text-sm flex items-center shadow-sm">
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-4 text-sm flex items-center shadow-sm">
                <span>{{ session('info') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>WardSewa — डिजिटल वडा नागरिक सेवा प्रणाली | &copy; {{ date('Y') }} सबै अधिकार सुरक्षित।</p>
    </footer>
</body>
</html>
