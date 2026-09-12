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
<body class="bg-slate-900 text-slate-800 antialiased font-sans min-h-screen flex flex-col justify-center items-center px-4 py-10">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-700">
        <!-- Header -->
        <div class="bg-gradient-to-r from-nepal-darkblue via-nepal-blue to-nepal-darkblue text-white p-6 sm:p-8 text-center">
            <div class="w-12 h-12 rounded-xl bg-white text-nepal-crimson flex items-center justify-center font-black text-2xl mx-auto shadow-md">
                व
            </div>
            <h1 class="text-2xl font-black text-white mt-3">वडा कर्मचारी तथा प्रशासक पोर्टल</h1>
            <p class="text-xs text-slate-200 mt-1">Ward Staff & Local Body Administration Portal</p>
        </div>

        <div class="p-6 sm:p-8">
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
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        इमेल ठेगाना (Staff Email) *
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', 'chair@ward32.gov.np') }}" required autofocus
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none font-medium">
                    @error('email')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        पासवर्ड (Password) *
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" value="password123" required
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none font-mono">
                        <button type="button" onclick="togglePasswordVisibility('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-nepal-blue">
                        <span class="text-slate-600">मलाई सम्झनुहोस् (Remember Me)</span>
                    </label>
                    <a href="{{ route('home') }}" class="text-nepal-blue hover:underline">सार्वजनिक पोर्टल &rarr;</a>
                </div>

                <button type="submit" class="w-full py-3.5 bg-nepal-darkblue hover:bg-nepal-blue text-white font-bold rounded-xl shadow-md transition text-sm flex items-center justify-center gap-2">
                    <span>कर्मचारी लगइन गर्नुहोस् (Staff Login)</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <!-- 1-Click Demo Staff Logins -->
            <div class="mt-6 pt-5 border-t border-slate-200">
                <span class="font-bold text-slate-700 text-xs uppercase tracking-wider block mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-nepal-crimson" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    १-क्लिक परीक्षण खाताहरू (Quick Demo Credentials):
                </span>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" onclick="fillStaff('chair@ward32.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-red-50 border border-slate-200 rounded-lg text-left transition">
                        <div class="font-bold text-nepal-crimson">वडा अध्यक्ष (Chair)</div>
                        <div class="text-[11px] text-slate-500">chair@ward32.gov.np</div>
                        <div class="text-[10px] text-emerald-700 font-semibold">अन्तिम स्वीकृति तथा हस्ताक्षर</div>
                    </button>

                    <button type="button" onclick="fillStaff('secretary@ward32.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-blue-50 border border-slate-200 rounded-lg text-left transition">
                        <div class="font-bold text-nepal-blue">वडा सचिव (Secretary)</div>
                        <div class="text-[11px] text-slate-500">secretary@ward32.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold">कागजात अध्ययन तथा पेश</div>
                    </button>

                    <button type="button" onclick="fillStaff('clerk@ward32.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-left transition">
                        <div class="font-bold text-slate-800">वडा सहायक (Clerk)</div>
                        <div class="text-[11px] text-slate-500">clerk@ward32.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold">दर्ता तथा प्रारम्भिक रुजु</div>
                    </button>

                    <button type="button" onclick="fillStaff('admin@kathmandu.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-amber-50 border border-slate-200 rounded-lg text-left transition">
                        <div class="font-bold text-amber-900">काठमाडौँ प्रशासक (Admin)</div>
                        <div class="text-[11px] text-slate-500">admin@kathmandu.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold">महानगरपालिका समग्र व्यवस्थापन</div>
                    </button>

                    <button type="button" onclick="fillStaff('admin@lalitpur.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-blue-50 border border-slate-200 rounded-lg text-left transition">
                        <div class="font-bold text-nepal-blue">ललितपुर प्रशासक</div>
                        <div class="text-[11px] text-slate-500">admin@lalitpur.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold">ललितपुर महानगर प्रणाली</div>
                    </button>

                    <button type="button" onclick="fillStaff('admin@bhaktapur.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-red-50 border border-slate-200 rounded-lg text-left transition">
                        <div class="font-bold text-nepal-crimson">भक्तपुर प्रशासक</div>
                        <div class="text-[11px] text-slate-500">admin@bhaktapur.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold">भक्तपुर नगरपालिका प्रणाली</div>
                    </button>
                </div>
                <div class="mt-2 text-[11px] text-slate-500 text-center font-mono">
                    सबै खाताहरूको पासवर्ड: <strong class="text-slate-700">password123</strong>
                </div>
            </div>
        </div>
    </div>

<script>
function togglePasswordVisibility(fieldId) {
    const input = document.getElementById(fieldId);
    input.type = input.type === 'password' ? 'text' : 'password';
}

function fillStaff(email) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'password123';
}
</script>
</body>
</html>
