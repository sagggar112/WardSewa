<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'कर्मचारी ड्यासबोर्ड') - WardSewa Staff Portal</title>

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
<body class="bg-slate-100 text-slate-800 antialiased font-sans min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 min-h-screen">
        <!-- Logo & Ward Header -->
        <div class="p-4 border-b border-slate-800">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-nepal-crimson text-white flex items-center justify-center font-bold text-lg">
                    व
                </div>
                <div>
                    <h1 class="text-white font-bold text-base leading-none">WardSewa Staff</h1>
                    <span class="text-[11px] text-nepal-gold font-medium">वडा कार्य सम्पादन पोर्टल</span>
                </div>
            </div>

            <!-- Assigned Ward Info -->
            <div class="mt-4 p-2.5 bg-slate-800/80 rounded-lg text-xs border border-slate-700">
                <div class="text-slate-400 font-medium">कार्यरत कार्यालय:</div>
                <div class="font-bold text-white mt-0.5">
                    @if(auth('staff')->user()->ward)
                        {{ auth('staff')->user()->ward->palika->name_ne ?? auth('staff')->user()->ward->palika->name_en }} - वडा नं. {{ auth('staff')->user()->ward->ward_number }}
                    @else
                        {{ auth('staff')->user()->palika->name_ne ?? auth('staff')->user()->palika->name_en }} (पालिका स्तर)
                    @endif
                </div>
                <div class="mt-1 flex items-center space-x-1">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span class="text-emerald-300 uppercase tracking-wider font-semibold text-[10px]">
                        {{ str_replace('_', ' ', auth('staff')->user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="p-3 space-y-1 text-sm flex-grow">
            <a href="{{ route('staff.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.dashboard') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>{{ __('Dashboard') }}</span>
            </a>

            <a href="{{ route('staff.applications.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.applications.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>{{ __('Applications & Recommendations') }}</span>
            </a>

            <a href="{{ route('staff.notices.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.notices.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                <span>{{ __('Notice Management') }}</span>
            </a>
        </nav>

        <!-- Current User Profile & Logout -->
        <div class="p-3 border-t border-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 truncate">
                    <div class="w-8 h-8 rounded-full bg-nepal-blue text-white flex items-center justify-center font-bold text-xs shrink-0">
                        {{ substr(auth('staff')->user()->name, 0, 1) }}
                    </div>
                    <div class="truncate">
                        <div class="text-xs font-semibold text-white truncate">{{ auth('staff')->user()->name }}</div>
                        <div class="text-[10px] text-slate-400 truncate">{{ auth('staff')->user()->email }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('staff.logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="text-slate-400 hover:text-rose-400 p-1.5 rounded hover:bg-slate-800">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Staff Header -->
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6">
            <div class="flex items-center space-x-3">
                <h2 class="text-lg font-bold text-slate-800">@yield('page_title', 'कर्मचारी पोर्टल')</h2>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Language Switcher -->
                <div class="flex items-center space-x-1 bg-slate-100 rounded px-2 py-1 text-xs">
                    <a href="{{ route('locale.switch', 'ne') }}" class="{{ app()->getLocale() === 'ne' ? 'font-bold text-nepal-blue' : 'text-slate-600 hover:text-slate-900' }}">नेपाली</a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'font-bold text-nepal-blue' : 'text-slate-600 hover:text-slate-900' }}">EN</a>
                </div>

                <a href="{{ route('home') }}" target="_blank" class="text-xs text-slate-500 hover:text-nepal-blue flex items-center space-x-1">
                    <span>{{ __('View Public Portal') }}</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm flex items-center shadow-sm">
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg text-sm flex items-center shadow-sm">
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Content Body -->
        <main class="p-6 flex-grow overflow-auto">
            @yield('content')
        </main>
    </div>
</body>
</html>
