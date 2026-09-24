@extends('layouts.staff')

@section('page_title', __('Security & Audit Logs'))

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">{{ __('Security & Audit Logs') }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">{{ app()->getLocale() === 'ne' ? 'प्रशासक लगइन, निवेदन स्थिति परिवर्तन, तथा महत्त्वपूर्ण प्रशासनिक गतिविधिको पूर्ण अभिलेख।' : 'Immutable trail of administrator logins, status modifications, and security actions.' }}</p>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('staff.superadmin.audit-logs') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="{{ app()->getLocale() === 'ne' ? 'विवरणबाट खोज्नुहोस्...' : 'Search in audit description...' }}"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
            </div>

            <div>
                <select name="action" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
                    <option value="">{{ app()->getLocale() === 'ne' ? 'सबै गतिविधि' : 'All Actions' }}</option>
                    <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                    <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
                    <option value="failed_login" {{ request('action') === 'failed_login' ? 'selected' : '' }}>Failed Login</option>
                    <option value="appointment_status_update" {{ request('action') === 'appointment_status_update' ? 'selected' : '' }}>Appointment Update</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                    <tr>
                        <th class="p-3">{{ app()->getLocale() === 'ne' ? 'मिति तथा समय' : 'Timestamp' }}</th>
                        <th class="p-3">{{ app()->getLocale() === 'ne' ? 'गतिविधि' : 'Action' }}</th>
                        <th class="p-3">{{ app()->getLocale() === 'ne' ? 'प्रशासक' : 'Staff / Origin' }}</th>
                        <th class="p-3">{{ app()->getLocale() === 'ne' ? 'विवरण' : 'Description' }}</th>
                        <th class="p-3">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 text-slate-500 whitespace-nowrap">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $log->action === 'failed_login' ? 'bg-rose-100 text-rose-800' : ($log->action === 'login' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-3 font-semibold text-slate-800">
                                {{ $log->staff ? $log->staff->name : (app()->getLocale() === 'ne' ? 'प्रणाली' : 'System') }}
                            </td>
                            <td class="p-3 text-slate-700 font-medium">
                                {{ $log->description }}
                            </td>
                            <td class="p-3 font-mono text-slate-400 text-[11px]">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 text-xs">{{ app()->getLocale() === 'ne' ? 'कुनै अडिट गतिविधि फेला परेन।' : 'No audit records found.' }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
