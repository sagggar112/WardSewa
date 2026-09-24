<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('Administrative Portal')) - WardSewa</title>

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
    @php
        $staff = auth('staff')->user();
        $isNe = app()->getLocale() === 'ne';
    @endphp

    <!-- Dynamic 4-Tier Sidebar -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 min-h-screen">
        <!-- Brand & Geographic Jurisdiction -->
        <div class="p-4 border-b border-slate-800">
            <div class="flex items-center space-x-3">
                <x-logo class="w-8 h-8 text-white shrink-0" />
                <div>
                    <h1 class="text-white font-bold text-base leading-none">WardSewa</h1>
                    <span class="text-[11px] text-nepal-gold font-medium">{{ __('Administrative Portal') }}</span>
                </div>
            </div>

            <!-- Geographic Scope Banner (Strict Monolingual) -->
            <div class="mt-4 p-2.5 bg-slate-800/80 rounded-lg text-xs border border-slate-700">
                <div class="text-slate-400 font-medium text-[10px] uppercase tracking-wider">{{ __('Jurisdiction:') }}</div>
                <div class="font-bold text-white mt-0.5 text-xs truncate">
                    @if($staff?->isSuperAdmin())
                        {{ __('Nationwide (System-Wide)') }}
                    @elseif($staff?->isDistrictAdmin())
                        {{ $isNe ? (($staff->district->name_ne ?? 'काठमाडौँ') . ' जिल्ला') : (($staff->district->name_en ?? 'Kathmandu') . ' District') }}
                    @elseif($staff?->isLocalGovtAdmin())
                        {{ $isNe ? ($staff->palika->name_ne ?? 'काठमाडौँ महानगरपालिका') : ($staff->palika->name_en ?? 'Kathmandu Metropolitan City') }}
                    @elseif($staff?->ward)
                        {{ $isNe ? (($staff->ward->palika->name_ne ?? '') . ' - वडा नं. ' . $staff->ward->ward_number) : (($staff->ward->palika->name_en ?? '') . ' - Ward No. ' . $staff->ward->ward_number) }}
                    @else
                        {{ __('Ward Jurisdiction') }}
                    @endif
                </div>
                <div class="mt-1.5 flex items-center space-x-1.5">
                    @if($staff?->isSuperAdmin())
                        <span class="inline-block w-2 h-2 rounded-full bg-purple-400"></span>
                        <span class="text-purple-300 uppercase tracking-wider font-bold text-[10px]">{{ __('Super Admin') }}</span>
                    @elseif($staff?->isDistrictAdmin())
                        <span class="inline-block w-2 h-2 rounded-full bg-indigo-400"></span>
                        <span class="text-indigo-300 uppercase tracking-wider font-bold text-[10px]">{{ __('District Admin') }}</span>
                    @elseif($staff?->isLocalGovtAdmin())
                        <span class="inline-block w-2 h-2 rounded-full bg-blue-400"></span>
                        <span class="text-blue-300 uppercase tracking-wider font-bold text-[10px]">{{ __('Local Govt Admin') }}</span>
                    @elseif($staff?->role === 'ward_chair')
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="text-emerald-300 uppercase tracking-wider font-bold text-[10px]">{{ __('Ward Chairperson') }}</span>
                    @elseif($staff?->role === 'secretary')
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="text-emerald-300 uppercase tracking-wider font-bold text-[10px]">{{ __('Ward Secretary') }}</span>
                    @elseif($staff?->role === 'clerk')
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="text-emerald-300 uppercase tracking-wider font-bold text-[10px]">{{ __('Ward Clerk') }}</span>
                    @else
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="text-emerald-300 uppercase tracking-wider font-bold text-[10px]">{{ __('Ward Admin') }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Role-Specific Navigation Links (Strict Monolingual) -->
        <nav class="p-3 space-y-1 text-xs font-medium flex-grow overflow-y-auto">
            @if($staff?->isSuperAdmin())
                <!-- 1. SUPER ADMIN SIDEBAR -->
                <div class="text-[10px] font-bold text-slate-500 uppercase px-3 pt-2 pb-1">{{ __('Central Control') }}</div>
                <a href="{{ route('staff.superadmin.dashboard') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.superadmin.dashboard') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('System Dashboard') }}</span>
                </a>
                <a href="{{ route('staff.superadmin.geography') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.superadmin.geography*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ __('Geography Management') }}</span>
                </a>
                <a href="{{ route('staff.superadmin.admins') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.superadmin.admins*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>{{ __('Admins Management') }}</span>
                </a>
                <a href="{{ route('staff.applications.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.applications.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ __('All Applications') }}</span>
                </a>
                <a href="{{ route('staff.appointments.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.appointments.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ __('Appointments Management') }}</span>
                </a>
                <a href="{{ route('staff.notices.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.notices.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <span>{{ __('Notice Management') }}</span>
                </a>
                <a href="{{ route('staff.superadmin.audit-logs') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.superadmin.audit-logs*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>{{ __('Security & Audit Logs') }}</span>
                </a>

            @elseif($staff?->isDistrictAdmin())
                <!-- 2. DISTRICT ADMIN SIDEBAR -->
                <div class="text-[10px] font-bold text-slate-500 uppercase px-3 pt-2 pb-1">{{ __('District Oversight') }}</div>
                <a href="{{ route('staff.district.dashboard') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.district.dashboard') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('District Dashboard') }}</span>
                </a>
                <a href="{{ route('staff.district.palikas') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.district.palikas*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>{{ __('Local Governments') }}</span>
                </a>
                <a href="{{ route('staff.district.applications') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.district.applications*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ __('District-Wide Applications') }}</span>
                </a>
                <a href="{{ route('staff.appointments.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.appointments.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ __('Appointment Monitoring') }}</span>
                </a>
                <a href="{{ route('staff.notices.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.notices.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <span>{{ __('District Notices') }}</span>
                </a>

            @elseif($staff?->isLocalGovtAdmin())
                <!-- 3. LOCAL GOVT ADMIN SIDEBAR -->
                <div class="text-[10px] font-bold text-slate-500 uppercase px-3 pt-2 pb-1">{{ __('Palika Management') }}</div>
                <a href="{{ route('staff.localgovt.dashboard') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.localgovt.dashboard') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('Palika Dashboard') }}</span>
                </a>
                <a href="{{ route('staff.localgovt.wards') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.localgovt.wards*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>{{ __('Ward Offices') }}</span>
                </a>
                <a href="{{ route('staff.localgovt.applications') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.localgovt.applications*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ __('Palika-Wide Applications') }}</span>
                </a>
                <a href="{{ route('staff.appointments.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.appointments.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ __('Appointments Management') }}</span>
                </a>
                <a href="{{ route('staff.notices.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.notices.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <span>{{ __('Palika Notices') }}</span>
                </a>

            @else
                <!-- 4. WARD ADMIN & STAFF SIDEBAR -->
                <div class="text-[10px] font-bold text-slate-500 uppercase px-3 pt-2 pb-1">{{ __('Ward Operations') }}</div>
                <a href="{{ route('staff.dashboard') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.dashboard') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('Ward Dashboard') }}</span>
                </a>
                <a href="{{ route('staff.applications.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.applications.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ __('Applications Inbox') }}</span>
                </a>
                <a href="{{ route('staff.appointments.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.appointments.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ __('Ward Appointments') }}</span>
                </a>
                <a href="{{ route('staff.notices.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.notices.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <span>{{ __('Ward Notices') }}</span>
                </a>
                @if(in_array($staff?->role, ['ward_chair', 'ward_admin', 'super_admin']))
                <a href="{{ route('staff.team.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.team.*') ? 'bg-nepal-blue text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>{{ __('Ward Team') }}</span>
                </a>
                @endif
            @endif
        </nav>

        <!-- Current User Profile & Logout -->
        <div class="p-3 border-t border-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 truncate">
                    <div class="w-8 h-8 rounded-full bg-nepal-blue text-white flex items-center justify-center font-bold text-xs shrink-0">
                        {{ substr($staff?->name ?? 'S', 0, 1) }}
                    </div>
                    <div class="truncate">
                        <div class="text-xs font-semibold text-white truncate">{{ $staff?->name ?? 'Staff' }}</div>
                        <div class="text-[10px] text-slate-400 truncate">{{ $staff?->designation ?? $staff?->email ?? '' }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('staff.logout') }}">
                    @csrf
                    <button type="submit" title="{{ __('Logout') }}" class="text-slate-400 hover:text-rose-400 p-1.5 rounded hover:bg-slate-800 transition">
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
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 sticky top-0 z-20 shadow-sm">
            <div class="flex items-center space-x-3">
                <h2 class="text-lg font-bold text-slate-800">@yield('page_title', __('Administrative Portal'))</h2>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Language Switcher -->
                <div class="flex items-center space-x-1 bg-slate-100 p-1 rounded-lg text-xs font-semibold border border-slate-200">
                    <a href="{{ route('locale.switch', 'ne') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'ne' ? 'bg-nepal-blue text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">नेपाली</a>
                    <a href="{{ route('locale.switch', 'en') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'en' ? 'bg-nepal-blue text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">EN</a>
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
                    <svg class="w-4 h-4 mr-2 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg text-sm flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
