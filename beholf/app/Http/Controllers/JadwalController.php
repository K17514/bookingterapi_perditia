<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Terapi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index(): View
    {
        $jadwals = Jadwal::with('terapi')->latest()->paginate(10);

        // User logged in
        $user = Auth::user();

        // Ambil jadwal yang di soft delete hanya untuk admin level 1
        $deletedJadwals = null;
        if ($user && in_array($user->level, [1, 2])) {
            $deletedJadwals = Jadwal::onlyTrashed()
                ->with('terapi', 'creator', 'updater', 'deleter')
                ->latest()
                ->paginate(10, ['*'], 'deletedPage');
        }

        // Logika terapi
        if (in_array($user->level, [1, 2])) {
            // Admin / petugas bisa lihat semua terapi
            $terapi = Terapi::all();
        } else {
            // User level 3 hanya punya 1 terapi sendiri
            $terapi = Terapi::where('id_user', $user->id)->first();
        }

        return view('jadwals.index', compact('jadwals', 'deletedJadwals', 'terapi', 'user'));
    }


    public function create(): View
    {
        $terapis = Terapi::all();
        return view('jadwals.create', compact('terapis'));
    }

    private function sanitizeRupiah(string $value): int
    {
        // Remove 'Rp', dots and spaces, then convert to int
        return (int) preg_replace('/[^0-9]/', '', $value);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'jam_mulai'    => 'required|date_format:H:i',
            'jam_selesai'  => 'required|date_format:H:i|after:jam_mulai',
            'tanggal'      => 'required|date',
            'status'       => 'required|in:available,unavailable',
            'id_terapi'    => 'required|exists:terapis,id',
            'biaya_jadwal' => 'nullable|string',
        ]);


        Jadwal::create([
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tanggal'     => $request->tanggal,
            'status'      => $request->status,
            'id_terapi'   => $request->id_terapi,
            'biaya_jadwal' => $this->sanitizeRupiah($request->biaya_jadwal ?? '0'),
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('jadwals.index')->with('success', 'Jadwal successfully created!');
    }


    public function edit(string $id): View
    {
        $jadwal = Jadwal::findOrFail($id);
        $terapis = Terapi::all();
        return view('jadwals.edit', compact('jadwal', 'terapis'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'jam_mulai' => 'sometimes|date_format:H:i:s',
            'jam_selesai' => 'sometimes|date_format:H:i:s|after:jam_mulai',
            'tanggal' => 'sometimes|date',
            'status' => 'sometimes|in:available,unavailable',
            'id_terapi' => 'sometimes|exists:terapis,id',
            'biaya_jadwal' => 'nullable|string',
        ]);


        $jadwal->update([
            'jam_mulai'   => $request->input('jam_mulai', $jadwal->jam_mulai),
            'jam_selesai' => $request->input('jam_selesai', $jadwal->jam_selesai),
            'tanggal'     => $request->input('tanggal', $jadwal->tanggal),
            'status'      => $request->input('status', $jadwal->status),
            'id_terapi'   => $request->input('id_terapi', $jadwal->id_terapi),
            'biaya_jadwal' => $this->sanitizeRupiah($request->input('biaya_jadwal', $jadwal->biaya_jadwal)),
            'updated_by'  => Auth::id(),
        ]);

        return redirect()->route('jadwals.index')->with('success', 'Jadwal successfully updated!');
    }

    public function destroy(string $id): RedirectResponse
    {
        $jadwal = Jadwal::findOrFail($id);

        $jadwal->deleted_by = Auth::id();
        $jadwal->save();

        $jadwal->delete(); // Soft delete

        return redirect()->route('jadwals.index')->with('success', 'Jadwal successfully deleted!');
    }
    // Restore single jadwal
    public function restore(string $id): RedirectResponse
    {
        $jadwal = Jadwal::onlyTrashed()->findOrFail($id);
        $jadwal->restore();

        return redirect()->route('jadwals.index')->with('success', 'Jadwal restored successfully!');
    }

    // Permanently delete single jadwal
    public function forceDelete(string $id): RedirectResponse
    {
        $jadwal = Jadwal::onlyTrashed()->findOrFail($id);
        $jadwal->forceDelete();

        return redirect()->route('jadwals.index')->with('success', 'Jadwal permanently deleted!');
    }
}
