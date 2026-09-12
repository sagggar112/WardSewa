<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>कर्मचारी पोर्टल लगइन - WardSewa Staff Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="bg-slate-900 text-slate-800 antialiased font-sans min-h-screen flex flex-col justify-center items-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 border border-slate-700">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-nepal-darkblue text-white flex items-center justify-center font-bold text-2xl mx-auto shadow-md">
                व
            </div>
            <h1 class="text-2xl font-black text-slate-900 mt-3">वडा कर्मचारी पोर्टल</h1>
            <p class="text-xs text-slate-500 mt-1">काठमाडौँ महानगरपालिका (पाइलट वडा नं. ३२)</p>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs p-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('staff.login.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase mb-1">इमेल ठेगाना (Staff Email)</label>
                <input type="email" name="email" id="email" value="{{ old('email', 'chair@ward32.gov.np') }}" required autofocus
                       class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none">
                @error('email')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase mb-1">पासवर्ड (Password)</label>
                <input type="password" name="password" id="password" value="password123" required
                       class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none">
                @error('password')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-nepal-blue">
                    <span class="text-slate-600">मलाई सम्झनुहोस्</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 bg-nepal-darkblue hover:bg-nepal-blue text-white font-bold rounded-lg shadow transition text-sm">
                कर्मचारी लगइन गर्नुहोस् &rarr;
            </button>
        </form>

        <!-- Test Accounts Card -->
        <div class="mt-6 pt-5 border-t border-slate-100 bg-slate-50 -mx-8 -mb-8 p-6 rounded-b-2xl text-xs space-y-2">
            <span class="font-bold text-slate-700 uppercase block text-[11px]">परीक्षण खाताहरू (Seeded Test Accounts):</span>
            <div class="text-slate-600 space-y-1 font-mono text-[11px]">
                <div>&bull; <strong>वडा अध्यक्ष (Chair):</strong> <span class="text-nepal-crimson">chair@ward32.gov.np</span> / password123</div>
                <div>&bull; <strong>वडा सचिव (Secretary):</strong> <span class="text-nepal-blue">secretary@ward32.gov.np</span> / password123</div>
                <div>&bull; <strong>वडा सहायक (Clerk):</strong> clerk@ward32.gov.np / password123</div>
            </div>
        </div>
    </div>
</body>
</html>
