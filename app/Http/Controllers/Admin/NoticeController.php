<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::latest()->get();
        return Inertia::render('Admin/Notice/Index', [
            'notices' => $notices
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'is_active' => 'boolean'
        ]);

        Notice::create($validated);

        return back()->with('success', 'Notice created successfully.');
    }

    public function update(Request $request, Notice $notice)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'is_active' => 'boolean'
        ]);

        $notice->update($validated);

        return back()->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return back()->with('success', 'Notice deleted successfully.');
    }

    public function toggleActive(Notice $notice)
    {
        $notice->update(['is_active' => !$notice->is_active]);
        return back()->with('success', 'Notice status toggled.');
    }
}
