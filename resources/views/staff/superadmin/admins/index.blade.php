@extends('layouts.staff')

@section('page_title', __('Admins Management'))

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">{{ __('Admins Management') }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">{{ app()->getLocale() === 'ne' ? 'केन्द्रीय, जिल्ला, पालिका तथा वडा स्तरका सबै प्रशासनिक प्रयोगकर्ताहरूको सूची।' : 'Comprehensive directory of central, district, municipal, and ward-level administrators.' }}</p>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('staff.superadmin.admins') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="{{ app()->getLocale() === 'ne' ? 'नाम, इमेल वा फोनबाट खोज्नुहोस्...' : 'Search by name, email or phone...' }}"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
            </div>

            <div>
                <select name="role" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
                    <option value="">{{ app()->getLocale() === 'ne' ? 'सबै भूमिकाहरू' : 'All Roles' }}</option>
                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>{{ __('Super Admin') }}</option>
                    <option value="district_admin" {{ request('role') === 'district_admin' ? 'selected' : '' }}>{{ __('District Admin') }}</option>
                    <option value="local_government_admin" {{ request('role') === 'local_government_admin' ? 'selected' : '' }}>{{ __('Local Govt Admin') }}</option>
                    <option value="ward_admin" {{ request('role') === 'ward_admin' ? 'selected' : '' }}>{{ __('Ward Admin') }}</option>
                    <option value="ward_chair" {{ request('role') === 'ward_chair' ? 'selected' : '' }}>{{ __('Ward Chairperson') }}</option>
                    <option value="secretary" {{ request('role') === 'secretary' ? 'selected' : '' }}>{{ __('Ward Secretary') }}</option>
                    <option value="clerk" {{ request('role') === 'clerk' ? 'selected' : '' }}>{{ __('Ward Clerk') }}</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Admins Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                    <tr>
                        <th class="p-3">{{ app()->getLocale() === 'ne' ? 'प्रशासकको नाम' : 'Administrator' }}</th>
                        <th class="p-3">{{ app()->getLocale() === 'ne' ? 'इमेल / फोन' : 'Email / Phone' }}</th>
                        <th class="p-3">{{ app()->getLocale() === 'ne' ? 'प्रशासनिक तह' : 'Administrative Tier' }}</th>
                        <th class="p-3">{{ __('Jurisdiction') }}</th>
                        <th class="p-3">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($admins as $admin)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3">
                                <div class="font-bold text-slate-900">{{ $admin->name }}</div>
                                <div class="text-[10px] text-slate-400">{{ $admin->designation ?? 'Administrative Officer' }}</div>
                            </td>
                            <td class="p-3">
                                <div class="font-mono text-slate-800">{{ $admin->email }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $admin->phone ?? '-' }}</div>
                            </td>
                            <td class="p-3">
                                @if($admin->role === 'super_admin')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-purple-100 text-purple-800">{{ __('Super Admin') }}</span>
                                @elseif($admin->role === 'district_admin')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-indigo-100 text-indigo-800">{{ __('District Admin') }}</span>
                                @elseif($admin->role === 'local_government_admin')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-blue-100 text-blue-800">{{ __('Local Govt Admin') }}</span>
                                @elseif($admin->role === 'ward_chair')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-100 text-rose-800">{{ __('Ward Chairperson') }}</span>
                                @elseif($admin->role === 'secretary')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">{{ __('Ward Secretary') }}</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700">{{ str_replace('_', ' ', $admin->role) }}</span>
                                @endif
                            </td>
                            <td class="p-3 text-slate-700 font-medium">
                                {{ $admin->scope_description }}
                            </td>
                            <td class="p-3">
                                @if($admin->is_active)
                                    <span class="inline-flex items-center text-emerald-600 font-bold text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        {{ app()->getLocale() === 'ne' ? 'सक्रिय' : 'Active' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-rose-600 font-bold text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                        {{ app()->getLocale() === 'ne' ? 'निष्क्रिय' : 'Inactive' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $admins->links() }}
        </div>
    </div>
</div>
@endsection
