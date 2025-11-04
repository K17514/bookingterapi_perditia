<?php

namespace App\Http\Controllers;

use App\Models\Terapi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TerapiController extends Controller
{
      public function index(): View
    {
        $terapis = Terapi::with(['user', 'creator', 'updater', 'deleter'])->latest()->paginate(10);
        $deletedTerapis = null;

        if (Auth::check() && Auth::user()->level == 1) {
            $deletedTerapis = Terapi::onlyTrashed()
                ->with(['user', 'creator', 'updater', 'deleter'])
                ->latest()
                ->paginate(10, ['*'], 'deletedPage');
        }

        return view('terapis.index', compact('terapis', 'deletedTerapis'));
    }

    public function create(): View
    {
        return view('terapis.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:1|confirmed',

            'name' => 'required|min:2',
            'spesialis' => 'required',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_hp' => 'required',
            'kode_terapi' => 'required|unique:terapis,kode_terapi',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|image|max:2048',
            'metode_terapi' => 'required|in:panggilan,lokasi',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/fotos'), $filename);
            $fotoPath = 'uploads/fotos/' . $filename;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 3,
            'foto' => $fotoPath,
        ]);

        Terapi::create([
            'id_user' => $user->id,
            'name' => $request->name,
            'spesialis' => $request->spesialis,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'kode_terapi' => $request->kode_terapi,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'metode_terapi' => $request->metode_terapi,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('terapis.index')->with('success', 'Data berhasil disimpan!');
    }

    public function edit(string $id): View
    {
        $terapi = Terapi::with('user')->findOrFail($id);
        return view('terapis.edit', compact('terapi'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $terapis = Terapi::findOrFail($id);
        $user = User::findOrFail($terapis->id_user);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'spesialis' => 'required',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_hp' => 'required',
            'kode_terapi' => 'required|unique:terapis,kode_terapi,' . $id,
            'deskripsi' => 'nullable',
            'foto' => 'nullable|image|max:2048',
            'metode_terapi' => 'required|in:panggilan,lokasi',
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photos if exist (check file existence on public path)
            if ($terapis->foto && file_exists(public_path($terapis->foto))) {
                unlink(public_path($terapis->foto));
            }
            if ($user->foto && $user->foto !== $terapis->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }

            // Move new photo
            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/fotos'), $filename);
            $fotoPath = 'uploads/fotos/' . $filename;

            $terapis->foto = $fotoPath;
            $user->foto = $fotoPath;
        }

        // Update User
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Update Terapis
        $terapis->fill([
            'name' => $request->name,
            'spesialis' => $request->spesialis,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'kode_terapi' => $request->kode_terapi,
            'deskripsi' => $request->deskripsi,
            'metode_terapi' => $request->metode_terapi,
            'updated_by' => Auth::id(),
        ])->save();

        return redirect()->route('terapis.index')->with('success', 'Data terapis berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $terapi = Terapi::findOrFail($id);
        $user = $terapi->user;

        // Soft delete terapi
        $terapi->deleted_by = Auth::id();
        $terapi->save();
        $terapi->delete();

        // Soft delete user linked to terapi as well
        if ($user) {
            $user->deleted_by = Auth::id();
            $user->save();
            $user->delete();
        }

        return redirect()->route('terapis.index')->with('success', 'Terapi and related user soft deleted!');
    }
      public function restore(string $id): RedirectResponse
    {
        $terapi = Terapi::onlyTrashed()->findOrFail($id);
        $terapi->restore();

        // Restore related user
        $user = User::onlyTrashed()->find($terapi->id_user);
        if ($user) {
            $user->restore();
        }

        return redirect()->route('terapis.index')->with('success', 'Terapi and related user restored!');
    }

    public function forceDelete(string $id): RedirectResponse
    {
        $terapi = Terapi::onlyTrashed()->findOrFail($id);
        $user = User::onlyTrashed()->find($terapi->id_user);

        // Delete terapi photo file if exists
        if ($terapi->foto && file_exists(public_path($terapi->foto))) {
            unlink(public_path($terapi->foto));
        }

        $terapi->forceDelete();

        // Force delete user and photo
        if ($user) {
            if ($user->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }
            $user->forceDelete();
        }

        return redirect()->route('terapis.index')->with('success', 'Terapi and related user permanently deleted!');
    }
}
