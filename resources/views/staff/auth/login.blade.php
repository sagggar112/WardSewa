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
</head>
<body class="bg-slate-900 text-slate-800 antialiased font-sans min-h-screen flex flex-col justify-center items-center px-4 py-8" x-data="{ activeTier: 'ward' }">
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
                        इमेल ठेगाना (Staff / Administrator Email) *
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', 'chair@ward32.gov.np') }}" required autofocus
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue focus:outline-none font-medium text-slate-900 bg-slate-50 focus:bg-white transition">
                    @error('email')<p class="text-rose-600 text-xs mt-1.5 font-semibold">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        पासवर्ड (Password) *
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" value="password123" required
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

            <!-- 1-Click Demo Accounts by 4-Tiers -->
            <div class="mt-8 pt-6 border-t border-slate-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="font-black text-slate-800 text-xs uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-nepal-crimson" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        ४-तहका परीक्षण खाताहरू (1-Click Demo Logins):
                    </span>
                    <span class="text-[11px] text-slate-500 font-mono font-bold bg-slate-100 px-2 py-0.5 rounded">पासवर्ड: password123</span>
                </div>

                <!-- Tier Selector Tabs -->
                <div class="flex space-x-1 bg-slate-100 p-1 rounded-xl text-xs font-bold mb-3">
                    <button type="button" @click="activeTier = 'ward'"
                            :class="activeTier === 'ward' ? 'bg-white text-nepal-crimson shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                            class="flex-1 py-1.5 px-2 rounded-lg transition text-center">
                        तह ४: वडा कार्यालय
                    </button>
                    <button type="button" @click="activeTier = 'palika'"
                            :class="activeTier === 'palika' ? 'bg-white text-nepal-blue shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                            class="flex-1 py-1.5 px-2 rounded-lg transition text-center">
                        तह ३: पालिका (Local Govt)
                    </button>
                    <button type="button" @click="activeTier = 'district'"
                            :class="activeTier === 'district' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                            class="flex-1 py-1.5 px-2 rounded-lg transition text-center">
                        तह २: जिल्ला (DCC)
                    </button>
                    <button type="button" @click="activeTier = 'super'"
                            :class="activeTier === 'super' ? 'bg-white text-purple-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                            class="flex-1 py-1.5 px-2 rounded-lg transition text-center">
                        तह १: सुपर एडमिन
                    </button>
                </div>

                <!-- Tier 4: Ward Level Accounts -->
                <div x-show="activeTier === 'ward'" class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    <button type="button" onclick="fillStaff('chair@ward32.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-nepal-crimson rounded-xl text-left transition group">
                        <div class="font-bold text-nepal-crimson flex items-center justify-between">
                            <span>वडा अध्यक्ष (Ward Chair)</span>
                            <span class="text-[10px] bg-red-100 text-red-800 px-1.5 py-0.2 rounded">KMC ३२</span>
                        </div>
                        <div class="text-[11px] text-slate-500 font-mono mt-0.5">chair@ward32.gov.np</div>
                        <div class="text-[10px] text-emerald-700 font-semibold mt-1">अन्तिम स्वीकृति तथा डिजिटल हस्ताक्षर &rarr;</div>
                    </button>

                    <button type="button" onclick="fillStaff('secretary@ward32.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-nepal-blue rounded-xl text-left transition group">
                        <div class="font-bold text-nepal-blue flex items-center justify-between">
                            <span>वडा सचिव (Ward Secretary)</span>
                            <span class="text-[10px] bg-blue-100 text-blue-800 px-1.5 py-0.2 rounded">KMC ३२</span>
                        </div>
                        <div class="text-[11px] text-slate-500 font-mono mt-0.5">secretary@ward32.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold mt-1">कागजात अध्ययन तथा पेश &rarr;</div>
                    </button>

                    <button type="button" onclick="fillStaff('clerk@ward32.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-left transition group">
                        <div class="font-bold text-slate-800 flex items-center justify-between">
                            <span>वडा सहायक (Front Clerk)</span>
                            <span class="text-[10px] bg-slate-200 text-slate-700 px-1.5 py-0.2 rounded">KMC ३२</span>
                        </div>
                        <div class="text-[11px] text-slate-500 font-mono mt-0.5">clerk@ward32.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold mt-1">दर्ता, रुजु तथा भेटघाट तालिका &rarr;</div>
                    </button>

                    <button type="button" onclick="fillStaff('admin.ward32@wardsewa.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-left transition group">
                        <div class="font-bold text-slate-800 flex items-center justify-between">
                            <span>वडा प्रशासक (Ward Admin)</span>
                            <span class="text-[10px] bg-slate-200 text-slate-700 px-1.5 py-0.2 rounded">KMC ३२</span>
                        </div>
                        <div class="text-[11px] text-slate-500 font-mono mt-0.5">admin.ward32@wardsewa.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold mt-1">वडा कर्मचारी तथा सूचना व्यवस्थापन &rarr;</div>
                    </button>
                </div>

                <!-- Tier 3: Palika / Municipal Accounts -->
                <div x-show="activeTier === 'palika'" class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs" style="display: none;">
                    <button type="button" onclick="fillStaff('admin.kmc@wardsewa.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-nepal-blue rounded-xl text-left transition">
                        <div class="font-bold text-nepal-blue">काठमाडौँ महानगरपालिका (KMC)</div>
                        <div class="text-[11px] text-slate-500 font-mono">admin.kmc@wardsewa.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold mt-0.5">समग्र ३२ वडा व्यवस्थापन &rarr;</div>
                    </button>

                    <button type="button" onclick="fillStaff('admin.lmc@wardsewa.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-nepal-blue rounded-xl text-left transition">
                        <div class="font-bold text-nepal-blue">ललितपुर महानगरपालिका (LMC)</div>
                        <div class="text-[11px] text-slate-500 font-mono">admin.lmc@wardsewa.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold mt-0.5">समग्र २९ वडा व्यवस्थापन &rarr;</div>
                    </button>

                    <button type="button" onclick="fillStaff('admin.bkm@wardsewa.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-nepal-blue rounded-xl text-left transition">
                        <div class="font-bold text-nepal-blue">भक्तपुर नगरपालिका (BKM)</div>
                        <div class="text-[11px] text-slate-500 font-mono">admin.bkm@wardsewa.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold mt-0.5">समग्र १० वडा व्यवस्थापन &rarr;</div>
                    </button>

                    <button type="button" onclick="fillStaff('admin.chandragiri@wardsewa.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-nepal-blue rounded-xl text-left transition">
                        <div class="font-bold text-nepal-blue">चन्द्रागिरी नगरपालिका (CGM)</div>
                        <div class="text-[11px] text-slate-500 font-mono">admin.chandragiri@wardsewa.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold mt-0.5">समग्र १५ वडा व्यवस्थापन &rarr;</div>
                    </button>
                </div>

                <!-- Tier 2: District Coordination Committee (DCC) -->
                <div x-show="activeTier === 'district'" class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs" style="display: none;">
                    <button type="button" onclick="fillStaff('admin.ktm@wardsewa.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-500 rounded-xl text-left transition">
                        <div class="font-bold text-indigo-800">काठमाडौँ जिल्ला</div>
                        <div class="text-[10px] text-slate-500 font-mono">admin.ktm@wardsewa.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold mt-1">११ स्थानीय तह, १३८ वडा &rarr;</div>
                    </button>

                    <button type="button" onclick="fillStaff('admin.lalitpur@wardsewa.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-500 rounded-xl text-left transition">
                        <div class="font-bold text-indigo-800">ललितपुर जिल्ला</div>
                        <div class="text-[10px] text-slate-500 font-mono">admin.lalitpur@wardsewa.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold mt-1">६ स्थानीय तह, ७१ वडा &rarr;</div>
                    </button>

                    <button type="button" onclick="fillStaff('admin.bhaktapur@wardsewa.gov.np')"
                            class="p-2.5 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-500 rounded-xl text-left transition">
                        <div class="font-bold text-indigo-800">भक्तपुर जिल्ला</div>
                        <div class="text-[10px] text-slate-500 font-mono">admin.bhaktapur@wardsewa.gov.np</div>
                        <div class="text-[10px] text-slate-600 font-semibold mt-1">४ स्थानीय तह, ३८ वडा &rarr;</div>
                    </button>
                </div>

                <!-- Tier 1: Super Admin -->
                <div x-show="activeTier === 'super'" class="text-xs" style="display: none;">
                    <button type="button" onclick="fillStaff('superadmin@wardsewa.gov.np')"
                            class="w-full p-3.5 bg-gradient-to-r from-purple-50 to-indigo-50 hover:from-purple-100 hover:to-indigo-100 border border-purple-200 hover:border-purple-400 rounded-xl text-left transition">
                        <div class="font-bold text-purple-900 flex items-center justify-between text-sm">
                            <span>केन्द्रीय सुपर एडमिन (National Super Admin)</span>
                            <span class="text-[10px] bg-purple-200 text-purple-900 font-bold px-2 py-0.5 rounded-full">SYSTEM WIDE</span>
                        </div>
                        <div class="text-xs text-slate-600 font-mono mt-0.5">superadmin@wardsewa.gov.np</div>
                        <div class="text-[11px] text-purple-700 font-semibold mt-1">
                            समग्र ७७ जिल्ला, ७५३ स्थानीय तह, सुरक्षा लग तथा केन्द्रीय नियन्त्रण &rarr;
                        </div>
                    </button>
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
