<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>कर्मचारी तथा प्रशासक पोर्टल लगइन - WardSewa</title>
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
<body class="bg-slate-900 text-slate-800 antialiased font-sans min-h-screen flex flex-col justify-center items-center px-4 py-8">
    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-700">
        <!-- Header -->
        <div class="bg-gradient-to-r from-nepal-darkblue via-slate-900 to-nepal-darkblue text-white p-6 sm:p-8 text-center border-b border-slate-800">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-nepal-crimson to-nepal-red text-white flex items-center justify-center font-black text-3xl mx-auto shadow-lg shadow-red-900/50">
                व
            </div>
            <h1 class="text-2xl font-black text-white mt-3">वडा कर्मचारी तथा प्रशासक लगइन</h1>
            <p class="text-xs text-nepal-gold font-medium mt-1">WardSewa 4-Tier Administrative & Operational Portal</p>
        </div>

        <div class="p-6 sm:p-8">
            @if(session('error'))
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs p-3.5 rounded-xl flex items-center space-x-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3.5 rounded-xl flex items-center space-x-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('staff.login.submit') }}" id="staffLoginForm" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        इमेल वा मोबाइल नम्बर (Email Address or Phone Number) *
                    </label>
                    <input type="text" name="email" id="email" value="{{ old('email') }}" required autofocus
                           placeholder="उदा: chair@ward32.gov.np वा admin.kmc@wardsewa.gov.np"
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue focus:outline-none font-medium text-slate-900 bg-slate-50 focus:bg-white transition">
                    @error('email')<p class="text-rose-600 text-xs mt-1.5 font-semibold">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        पासवर्ड (Password) *
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" value="" required
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue focus:outline-none font-mono text-slate-900 bg-slate-50 focus:bg-white transition">
                        <button type="button" onclick="togglePasswordVisibility('password')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="text-rose-600 text-xs mt-1.5 font-semibold">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-nepal-blue focus:ring-nepal-blue">
                        <span class="text-slate-600">मलाई सम्झनुहोस् (Remember Me)</span>
                    </label>
                    <a href="{{ route('home') }}" class="text-nepal-blue font-semibold hover:underline flex items-center gap-1">
                        <span>सार्वजनिक पोर्टल &rarr;</span>
                    </a>
                </div>

                <button type="submit" class="w-full py-3.5 bg-nepal-blue hover:bg-nepal-darkblue text-white font-bold rounded-xl shadow-md hover:shadow-lg transition text-sm flex items-center justify-center gap-2">
                    <span>लगइन गर्नुहोस् (Log In to Workspace)</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
        </div>
    </div>

<script>
function togglePasswordVisibility(fieldId) {
    const input = document.getElementById(fieldId);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
