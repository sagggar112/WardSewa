import '../css/app.css';

const config = {
    url: import.meta.env.VITE_SUPABASE_URL,
    anonKey: import.meta.env.VITE_SUPABASE_ANON_KEY,
};

const services = [
    ['FOUR_BOUNDARIES', 'चार किल्ला सिफारिस', 'recommendation'],
    ['RESIDENCE', 'बसोबास सिफारिस', 'recommendation'],
    ['UNMARRIED', 'अविवाहित सिफारिस', 'recommendation'],
    ['BIRTH_REGISTRATION', 'जन्म दर्ता', 'vital_registration'],
    ['COMPLAINT', 'गुनासो दर्ता', 'complaint'],
];

const api = async (path, options = {}) => {
    if (!config.url || !config.anonKey) throw new Error('Supabase is not configured yet.');
    const response = await fetch(`${config.url}/rest/v1/${path}`, {
        ...options,
        headers: {
            apikey: config.anonKey,
            Authorization: `Bearer ${sessionStorage.getItem('wardsewa_token') || config.anonKey}`,
            'Content-Type': 'application/json',
            ...(options.headers || {}),
        },
    });
    if (!response.ok) throw new Error((await response.json()).message || 'Request failed.');
    return response.status === 204 ? null : response.json();
};

const app = document.querySelector('#app');
const escapeHtml = (value = '') => String(value).replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' })[character]);
const layout = (content) => `
  <header class="bg-nepal-darkblue text-white shadow-sm">
    <nav class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between gap-4">
      <a href="#/" class="font-black text-xl tracking-tight">वार्डसेवा <span class="font-medium text-blue-200">WardSewa</span></a>
      <div class="flex gap-4 text-sm font-semibold"><a href="#/notices">सूचनाहरू</a><a href="#/login" class="rounded-lg bg-nepal-crimson px-4 py-2">नागरिक लगइन</a></div>
    </nav>
  </header>
  <main>${content}</main>
  <footer class="mt-16 border-t bg-white"><div class="max-w-6xl mx-auto px-4 py-8 text-sm text-slate-500">© ${new Date().getFullYear()} WardSewa · डिजिटल वडा सेवा</div></footer>`;

const home = () => layout(`
  <section class="bg-gradient-to-br from-nepal-darkblue to-slate-900 text-white"><div class="max-w-6xl mx-auto px-4 py-20 text-center">
    <p class="inline-block rounded-full border border-white/20 bg-white/10 px-4 py-1 text-sm text-blue-100">काठमाडौं उपत्यका वडा सेवा पोर्टल</p>
    <h1 class="mt-6 text-4xl md:text-6xl font-black leading-tight">घरबाटै वडा कार्यालयका<br><span class="text-nepal-gold">डिजिटल सेवाहरू</span></h1>
    <p class="mx-auto mt-5 max-w-2xl text-slate-300">सिफारिसका लागि आवेदन दिनुहोस्, गुनासो दर्ता गर्नुहोस्, र आफ्नो निवेदनको स्थिति हेर्नुहोस्।</p>
    <a href="#/login" class="mt-8 inline-block rounded-xl bg-nepal-crimson px-7 py-3 font-bold shadow-lg">नागरिक लगइन / दर्ता</a>
  </div></section>
  <section class="max-w-6xl mx-auto px-4 py-14"><h2 class="text-2xl font-black">उपलब्ध सेवाहरू</h2><div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    ${services.map(([, name, category]) => `<a href="#/apply" class="rounded-2xl border bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"><p class="text-xs font-bold uppercase text-nepal-crimson">${category.replace('_', ' ')}</p><h3 class="mt-2 font-bold">${name}</h3><p class="mt-3 text-sm text-slate-500">अनलाइन आवेदन दिनुहोस् →</p></a>`).join('')}
  </div></section>`);

const login = () => layout(`
  <section class="max-w-md mx-auto px-4 py-14"><div class="rounded-2xl bg-white p-7 shadow-sm border"><h1 class="text-2xl font-black">नागरिक प्रवेश</h1><p class="mt-2 text-sm text-slate-500">इमेल र पासवर्ड प्रयोग गरेर सुरक्षित रूपमा प्रवेश वा दर्ता गर्नुहोस्।</p>
  <form id="login-form" class="mt-6 space-y-4"><label class="block text-sm font-bold">इमेल<input required name="email" type="email" autocomplete="email" class="mt-1 w-full rounded-lg border p-3" /></label><label class="block text-sm font-bold">पासवर्ड<input required name="password" type="password" autocomplete="current-password" minlength="8" class="mt-1 w-full rounded-lg border p-3" /></label><div class="grid grid-cols-2 gap-3"><button name="mode" value="signin" class="rounded-lg bg-nepal-darkblue p-3 font-bold text-white">प्रवेश गर्नुहोस्</button><button name="mode" value="signup" class="rounded-lg bg-nepal-crimson p-3 font-bold text-white">नयाँ दर्ता</button></div></form><p id="form-message" class="mt-4 text-sm"></p></div></section>`);

const apply = () => layout(`
  <section class="max-w-2xl mx-auto px-4 py-14"><div class="rounded-2xl border bg-white p-7 shadow-sm"><h1 class="text-2xl font-black">नयाँ आवेदन</h1><p class="mt-2 text-sm text-slate-500">लगइन गरेपछि तपाईंको आवेदन सम्बन्धित वडा कार्यालयमा पठाइन्छ।</p>
  <form id="application-form" class="mt-6 grid gap-4"><label class="text-sm font-bold">सेवा<select name="service_code" class="mt-1 w-full rounded-lg border p-3">${services.map(([code, name]) => `<option value="${code}">${name}</option>`).join('')}</select></label><label class="text-sm font-bold">विषय / विवरण<textarea name="details" required class="mt-1 min-h-28 w-full rounded-lg border p-3" placeholder="आवश्यक विवरण लेख्नुहोस्"></textarea></label><button class="rounded-lg bg-nepal-darkblue p-3 font-bold text-white">आवेदन पठाउनुहोस्</button></form><p id="form-message" class="mt-4 text-sm"></p></div></section>`);

const notices = async () => {
    app.innerHTML = layout('<section class="max-w-4xl mx-auto px-4 py-14"><h1 class="text-2xl font-black">सार्वजनिक सूचनाहरू</h1><div id="notices" class="mt-6 text-slate-500">लोड हुँदैछ…</div></section>');
    try {
        const rows = await api('notices?select=title,body,published_at&is_published=eq.true&order=published_at.desc');
        document.querySelector('#notices').innerHTML = rows.length ? rows.map((n) => `<article class="mb-4 rounded-xl border bg-white p-5"><h2 class="font-bold">${escapeHtml(n.title)}</h2><p class="mt-2 text-sm text-slate-600">${escapeHtml(n.body)}</p></article>`).join('') : 'अहिलेसम्म कुनै सूचना छैन।';
    } catch (error) { document.querySelector('#notices').textContent = error.message; }
};

const submitLogin = async (form, submitter) => {
    const message = document.querySelector('#form-message');
    try {
        if (!config.url || !config.anonKey) throw new Error('Supabase is not configured yet.');
        const data = new FormData(form);
        const email = data.get('email');
        const password = data.get('password');
        const isSignup = submitter?.value === 'signup';
        const endpoint = isSignup ? 'signup' : 'token?grant_type=password';
        const response = await fetch(`${config.url}/auth/v1/${endpoint}`, { method: 'POST', headers: { apikey: config.anonKey, 'Content-Type': 'application/json' }, body: JSON.stringify({ email, password }) });
        const payload = await response.json();
        if (!response.ok) throw new Error(payload.msg || payload.message || 'Could not complete sign-in.');
        if (isSignup && !payload.access_token) { message.textContent = 'दर्ता भयो। इमेलमा आएको पुष्टि लिंक खोलेर प्रवेश गर्नुहोस्।'; message.className = 'mt-4 text-sm text-emerald-700'; return; }
        sessionStorage.setItem('wardsewa_token', payload.access_token);
        sessionStorage.setItem('wardsewa_user', JSON.stringify(payload.user));
        location.hash = '/apply';
    } catch (error) { message.textContent = error.message; message.className = 'mt-4 text-sm text-red-700'; }
};

const submitApplication = async (form) => {
    const message = document.querySelector('#form-message');
    try {
        const data = new FormData(form);
        const user = JSON.parse(sessionStorage.getItem('wardsewa_user') || 'null');
        if (!user) throw new Error('Please sign in before submitting an application.');
        await api('applications', { method: 'POST', headers: { Prefer: 'return=minimal' }, body: JSON.stringify({ citizen_id: user.id, service_code: data.get('service_code'), form_data: { details: data.get('details') } }) });
        message.textContent = 'तपाईंको आवेदन दर्ता भयो।'; message.className = 'mt-4 text-sm text-emerald-700'; form.reset();
    } catch (error) { message.textContent = error.message; message.className = 'mt-4 text-sm text-red-700'; }
};

const render = () => {
    const route = location.hash.slice(1) || '/';
    if (route === '/notices') return notices();
    app.innerHTML = route === '/login' ? login() : route === '/apply' ? apply() : home();
    document.querySelector('#login-form')?.addEventListener('submit', (e) => { e.preventDefault(); submitLogin(e.currentTarget, e.submitter); });
    document.querySelector('#application-form')?.addEventListener('submit', (e) => { e.preventDefault(); submitApplication(e.currentTarget); });
};

window.addEventListener('hashchange', render);
render();
