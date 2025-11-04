<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
   public function index(): View
{
    $users = User::latest()->paginate(10);
    $user = Auth::user();

    $deletedUsers = null;
    if ($user && $user->level == 1) {
        $deletedUsers = User::onlyTrashed()
            ->with(['creator', 'updater', 'deleter'])
            ->latest()
            ->paginate(10, ['*'], 'deletedPage');
    }

    return view('users.index', compact('users', 'deletedUsers', 'user'));
}
    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|min:2',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:1|confirmed',
            'level'    => 'required|integer',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;
if ($request->hasFile('foto')) {
    $foto = $request->file('foto');
    $filename = time() . '_' . $foto->getClientOriginalName();
    $foto->move(public_path('/uploads/fotos'), $filename);
    $fotoPath = '/uploads/fotos/' . $filename;
}


        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'level'       => $request->level,
            'foto'        => $fotoPath,
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('users.index')->with('success', 'User successfully created!');
    }

    public function edit(string $id): View
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|min:1',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:1|confirmed',
            'level'    => 'required|integer',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->level = $request->level;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

       if ($request->hasFile('foto')) {
    if ($user->foto && file_exists(public_path($user->foto))) {
        unlink(public_path($user->foto));
    }

    $foto = $request->file('foto');
    $filename = time() . '_' . $foto->getClientOriginalName();
    $foto->move(public_path('/uploads/fotos'), $filename);
    $user->foto = '/uploads/fotos/' . $filename;
}

        $user->updated_by = Auth::id();
        $user->save();

        return redirect()->route('users.index')->with('success', 'User successfully updated!');
    }

    public function destroy(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->deleted_by = Auth::id();
        $user->save();

        $user->delete(); // Soft delete

        return redirect()->route('users.index')->with('success', 'User successfully deleted!');
    }
     // Restore single user
    public function restore(string $id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.index')->with('success', 'User restored successfully!');
    }
    // Permanently delete single user
    public function forceDelete(string $id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        if ($user->foto && file_exists(public_path($user->foto))) {
            unlink(public_path($user->foto));
        }
        $user->forceDelete();

        return redirect()->route('users.index')->with('success', 'User permanently deleted!');
    }
}
