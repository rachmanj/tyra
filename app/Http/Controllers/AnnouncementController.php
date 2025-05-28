<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AnnouncementController extends Controller
{
    /**
     * Constructor - Apply middleware untuk memastikan hanya superadmin yang bisa akses
     */
    public function __construct()
    {
        $this->middleware(['role:superadmin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:65535',
            'start_date' => 'required|date|after_or_equal:today',
            'duration_days' => 'required|integer|min:1|max:365',
            'status' => 'required|in:active,inactive',
        ], [
            'content.required' => 'Konten announcement harus diisi',
            'content.max' => 'Konten announcement terlalu panjang',
            'start_date.required' => 'Tanggal mulai harus diisi',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini',
            'duration_days.required' => 'Durasi hari harus diisi',
            'duration_days.min' => 'Durasi minimal 1 hari',
            'duration_days.max' => 'Durasi maksimal 365 hari',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status harus active atau inactive',
        ]);

        $validated['created_by'] = Auth::id();

        Announcement::create($validated);

        Alert::success('Berhasil', 'Announcement berhasil dibuat');
        return redirect()->route('announcements.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:65535',
            'start_date' => 'required|date',
            'duration_days' => 'required|integer|min:1|max:365',
            'status' => 'required|in:active,inactive',
        ], [
            'content.required' => 'Konten announcement harus diisi',
            'content.max' => 'Konten announcement terlalu panjang',
            'start_date.required' => 'Tanggal mulai harus diisi',
            'duration_days.required' => 'Durasi hari harus diisi',
            'duration_days.min' => 'Durasi minimal 1 hari',
            'duration_days.max' => 'Durasi maksimal 365 hari',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status harus active atau inactive',
        ]);

        $announcement->update($validated);

        Alert::success('Berhasil', 'Announcement berhasil diupdate');
        return redirect()->route('announcements.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        Alert::success('Berhasil', 'Announcement berhasil dihapus');
        return redirect()->route('announcements.index');
    }

    /**
     * Toggle status announcement (active/inactive)
     */
    public function toggleStatus(Announcement $announcement)
    {
        $announcement->update([
            'status' => $announcement->status === 'active' ? 'inactive' : 'active'
        ]);

        $status = $announcement->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';
        Alert::success('Berhasil', "Announcement berhasil {$status}");

        return redirect()->route('announcements.index');
    }
}
