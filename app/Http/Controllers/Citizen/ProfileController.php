<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Palika;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $citizen = Auth::guard('citizen')->user();
        $palikas = Palika::with('wards')->get();

        return view('citizen.profile', compact('citizen', 'palikas'));
    }

    public function update(Request $request)
    {
        $citizen = Auth::guard('citizen')->user();

        $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'citizenship_no' => ['nullable', 'string', 'max:50'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['required', 'string', 'max:255'],
            'ward_id' => ['required', 'exists:wards,id'],
        ]);

        $citizen->update($request->only([
            'full_name',
            'email',
            'citizenship_no',
            'national_id',
            'dob',
            'gender',
            'address',
            'ward_id',
        ]));

        return back()->with('success', 'Profile updated successfully!');
    }
}
