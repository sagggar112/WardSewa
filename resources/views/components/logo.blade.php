@props(['size' => 'md', 'showText' => true, 'textColor' => 'text-white'])

@php
    $dimensions = match($size) {
        'sm' => ['emblem' => 'w-8 h-8', 'title' => 'text-lg', 'sub' => 'text-[10px]'],
        'lg' => ['emblem' => 'w-14 h-14', 'title' => 'text-3xl', 'sub' => 'text-xs'],
        default => ['emblem' => 'w-11 h-11', 'title' => 'text-2xl', 'sub' => 'text-[11px]'],
    };
@endphp

<div class="flex items-center space-x-3">
    <!-- WardSewa State Crest Emblem SVG -->
    <div class="{{ $dimensions['emblem'] }} relative flex-shrink-0 flex items-center justify-center filter drop-shadow-md">
        <svg viewBox="0 0 100 100" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Outer Gold Ring / Crest Border -->
            <path d="M50 4 C68 4 92 12 92 38 C92 68 50 96 50 96 C50 96 8 68 8 38 C8 12 32 4 50 4 Z"
                  fill="url(#goldGradient)" stroke="#002566" stroke-width="2.5" />

            <!-- Inner Crimson Shield -->
            <path d="M50 8 C65 8 86 15 86 38 C86 64 50 90 50 90 C50 90 14 64 14 38 C14 15 35 8 50 8 Z"
                  fill="url(#crimsonGradient)" />

            <!-- Himalayan Mountain Peaks Silhouette -->
            <path d="M22 55 L35 38 L45 50 L58 32 L72 50 L78 55 C70 58 50 64 22 55 Z"
                  fill="#FFFFFF" fill-opacity="0.25" />
            <path d="M30 55 L40 43 L48 52 L58 38 L68 53 C58 57 42 58 30 55 Z"
                  fill="#FFFFFF" fill-opacity="0.35" />

            <!-- Golden Sun Rays Behind Peaks -->
            <circle cx="50" cy="36" r="10" fill="url(#sunGradient)" />

            <!-- Traditional Temple Pagoda Pinnacle / Gajur -->
            <path d="M47 12 L50 6 L53 12 L51 15 L49 15 Z" fill="#FFD700" stroke="#B8860B" stroke-width="0.8" />
            <circle cx="50" cy="5" r="1.5" fill="#FFE066" />

            <!-- Devanagari Letter 'व' -->
            <text x="50" y="68" font-family="'Mukta', sans-serif" font-weight="900" font-size="34"
                  fill="#FFFFFF" text-anchor="middle" filter="url(#textShadow)">व</text>

            <!-- Digital Connectivity Star Base -->
            <polygon points="50,78 52,82 56,82 53,85 54,89 50,86 46,89 47,85 44,82 48,82" fill="#FFD700" />

            <!-- Gradients & Filters Definitions -->
            <defs>
                <linearGradient id="crimsonGradient" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#DC143C" />
                    <stop offset="60%" stop-color="#C41230" />
                    <stop offset="100%" stop-color="#800020" />
                </linearGradient>
                <linearGradient id="goldGradient" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#FFF2A3" />
                    <stop offset="50%" stop-color="#D4AF37" />
                    <stop offset="100%" stop-color="#AA8010" />
                </linearGradient>
                <linearGradient id="sunGradient" x1="50" y1="26" x2="50" y2="46" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#FFE885" />
                    <stop offset="100%" stop-color="#D4AF37" stop-opacity="0.4" />
                </linearGradient>
                <filter id="textShadow" x="-20%" y="-20%" width="140%" height="140%">
                    <feDropShadow dx="0" dy="1.5" stdDeviation="1" flood-color="#4A000E" flood-opacity="0.6"/>
                </filter>
            </defs>
        </svg>
    </div>

    @if($showText)
        <div class="leading-tight">
            <div class="flex items-center space-x-1.5">
                <span class="{{ $dimensions['title'] }} font-black tracking-tight {{ $textColor }}">
                    Ward<span class="text-nepal-gold">Sewa</span>
                </span>
                <span class="px-1.5 py-0.2 bg-nepal-crimson text-white text-[9px] font-extrabold uppercase rounded tracking-wider shadow-xs">
                    नेपाल
                </span>
            </div>
            <span class="{{ $dimensions['sub'] }} text-slate-300 font-semibold block tracking-wide">
                {{ __('डिजिटल वडा सेवा प्रणाली (Citizen Portal)') }}
            </span>
        </div>
    @endif
</div>
