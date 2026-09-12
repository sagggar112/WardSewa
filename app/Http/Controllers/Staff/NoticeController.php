<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    public function index()
    {
        $staff = Auth::guard('staff')->user();

        $query = Notice::query();
        if ($staff->ward_id) {
            $query->where('ward_id', $staff->ward_id);
        } else {
            $query->where('palika_id', $staff->palika_id);
        }

        $notices = $query->latest()->paginate(10);

        return view('staff.notices.index', compact('notices'));
    }

    public function create()
    {
        return view('staff.notices.create');
    }

    public function store(Request $request)
    {
        $staff = Auth::guard('staff')->user();

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'in:general,procurement,tax,emergency,health,event'],
            'is_pinned' => ['nullable', 'boolean'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('notices', 'public');
        }

        Notice::create([
            'palika_id' => $staff->palika_id,
            'ward_id' => $staff->ward_id,
            'staff_id' => $staff->id,
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'is_pinned' => $request->boolean('is_pinned'),
            'attachment_path' => $attachmentPath,
            'published_at' => now(),
        ]);

        return redirect()->route('staff.notices.index')
            ->with('success', 'Notice published successfully!');
    }

    public function destroy($id)
    {
        $staff = Auth::guard('staff')->user();
        $notice = Notice::where('ward_id', $staff->ward_id ?? $staff->palika_id)->findOrFail($id);
        $notice->delete();

        return back()->with('success', 'Notice deleted.');
    }
}
