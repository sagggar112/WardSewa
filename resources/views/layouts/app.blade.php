<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WardSewa') }} - डिजिटल वडा सेवा प्रणाली</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Mukta:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN fallback + Vite -->
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
<body class="bg-slate-50 text-slate-800 antialiased font-sans flex flex-col min-h-screen">
    <!-- Top Government Header Banner -->
    <header class="bg-gradient-to-r from-nepal-darkblue via-nepal-blue to-nepal-darkblue text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-3 border-b border-white/10 text-xs sm:text-sm">
                <div class="flex items-center space-x-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
                    <span>{{ __('Government of Nepal | Bagmati Province | Kathmandu Valley (Kathmandu, Lalitpur, Bhaktapur)') }}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-1 bg-white/10 rounded px-2 py-0.5">
                        <a href="{{ route('locale.switch', 'ne') }}" class="{{ app()->getLocale() === 'ne' ? 'font-bold text-nepal-gold' : 'text-slate-200 hover:text-white' }}">नेपाली</a>
                        <span class="text-white/40">|</span>
                        <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'font-bold text-nepal-gold' : 'text-slate-200 hover:text-white' }}">EN</a>
                    </div>
                    <a href="{{ route('staff.login') }}" class="text-xs bg-white/10 hover:bg-white/20 text-white px-2.5 py-1 rounded transition">{{ __('Staff Portal (Staff)') }}</a>
                </div>
            </div>

            <!-- Navbar -->
            <div class="flex items-center justify-between py-4">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow">
                        <span class="text-nepal-crimson text-xl font-black">व</span>
                    </div>
                    <div>
                        <span class="text-2xl font-bold tracking-tight text-white block leading-tight">WardSewa</span>
                        <span class="text-xs text-nepal-gold block -mt-1 font-medium">{{ __('Digital Ward Citizen Services') }}</span>
                    </div>
                </a>

                <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
                    <a href="{{ route('home') }}" class="text-white hover:text-nepal-gold transition">{{ __('Home') }}</a>
                    <a href="{{ route('notices.index') }}" class="text-white hover:text-nepal-gold transition">{{ __('Notice Board') }}</a>
                    <a href="{{ route('home') }}#services" class="text-white hover:text-nepal-gold transition">{{ __('Services') }}</a>
                    <a href="{{ route('home') }}#verify" class="text-white hover:text-nepal-gold transition">{{ __('Certificate Verification') }}</a>
                </nav>

                <div class="flex items-center space-x-3">
                    @auth('citizen')
                        <a href="{{ route('citizen.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-nepal-red hover:bg-nepal-crimson text-white text-sm font-semibold rounded-lg shadow-sm transition">
                            {{ __('My Dashboard') }} &rarr;
                        </a>
                    @else
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('citizen.login') }}" class="text-xs text-white hover:text-nepal-gold font-bold px-2 py-1.5 transition">
                                {{ __('Citizen Login') }}
                            </a>
                            <a href="{{ route('citizen.register') }}" class="inline-flex items-center px-3.5 py-1.5 bg-nepal-crimson hover:bg-nepal-red text-white text-xs font-bold rounded-lg shadow-sm transition">
                                {{ __('Register') }} &rarr;
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center justify-between shadow-sm">
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg flex items-center justify-between shadow-sm">
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
    @if(session('dev_otp'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-amber-50 border border-amber-300 text-amber-900 px-4 py-3 rounded-lg flex items-center justify-between shadow-sm">
                <div>
                    <strong class="font-bold">Sandbox Testing OTP: </strong>
                    <span class="font-mono text-lg bg-amber-200 px-2 py-0.5 rounded font-bold">{{ session('dev_otp') }}</span>
                    <span class="text-xs text-amber-700 ml-2">(Auto-displayed for quick local evaluation)</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-10 mt-16 border-t border-slate-800 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center">
                        <span class="text-nepal-crimson text-base font-black">व</span>
                    </div>
                    <span class="text-lg font-bold text-white">WardSewa</span>
                </div>
                <p class="text-xs text-slate-400">{{ __('WardSewa - Digital Ward Service Platform for Local Governments of Nepal') }}</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">{{ __('Quick Links') }}</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('home') }}#services" class="hover:text-white">{{ __('Four Boundaries Recommendation') }}</a></li>
                    <li><a href="{{ route('home') }}#services" class="hover:text-white">{{ __('Unmarried Certificate') }}</a></li>
                    <li><a href="{{ route('home') }}#services" class="hover:text-white">{{ __('Residence Certificate') }}</a></li>
                    <li><a href="{{ route('home') }}#services" class="hover:text-white">{{ __('Birth Registration') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">{{ __('Help & Contact') }}</h4>
                <p class="text-xs text-slate-400">{{ __('Kathmandu Metropolitan City Ward No. 32 Office') }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ __('Koteshwor, Kathmandu | Phone: 01-4601234') }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ __('Email: ward32@kathmandu.gov.np') }}</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">{{ __('Secure Digital Nepal') }}</h4>
                <p class="text-xs text-slate-400">{{ __('Instant QR code recommendation authenticity verification and Khalti payment gateway integration.') }}</p>
                <p class="text-xs text-slate-500 mt-4">&copy; {{ date('Y') }} WardSewa. {{ __('All rights reserved. Government of Nepal.') }}</p>
            </div>
        </div>
    </footer>
</body>
</html>
