<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class KategoriController extends Controller
{
    public function index(): View
    {
        $kategoris = Kategori::latest()->paginate(10);
        return view('kategoris.index', compact('kategoris'));
    }

    public function create(): View
    {
        return view('kategoris.create');
    }

   public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name_kategori' => 'required|min:3',
    ]);

    Kategori::create([
        'name_kategori' => $request->name_kategori,
        'created_by' => Auth::user()->name ?? 'system',  // example, adjust as needed
    ]);

    return redirect()->route('kategoris.index')->with(['success' => 'Data berhasil disimpan!']);
}

    public function edit(string $id): View
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategoris.edit', compact('kategori'));
    }

  public function update(Request $request, string $id): RedirectResponse
{
    $request->validate([
        'name_kategori' => 'required|min:3',
    ]);

    $kategori = Kategori::findOrFail($id);
    $kategori->update([
        'name_kategori' => $request->name_kategori,
        'updated_by' => Auth::user()->name ?? 'system',
    ]);

    return redirect()->route('kategoris.index')->with(['success' => 'Data berhasil diedit!']);
}

public function destroy(string $id): RedirectResponse
{
    $kategori = Kategori::findOrFail($id);
    $kategori->deleted_by = Auth::user()->name ?? 'system';
    $kategori->save();
    $kategori->delete();

    return redirect()->route('kategoris.index')->with(['success' => 'Data berhasil dihapus!']);
}
}
