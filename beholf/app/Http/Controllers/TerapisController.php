<?php

namespace App\Http\Controllers;

use App\Models\Terapi;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class TerapisController extends Controller
{
    public function daftar()
    {
        if (Auth::check()) {
            $terapis = Terapi::all(); // ambil semua terapis dari DB
            return view('terapis.daftar-terapis', compact('terapis'));
        } else {
            return redirect()->route('login');
        }
    }


    public function detail($id)
    {
        if (Auth::check()) {
            $terapi = Terapi::with(['jadwals', 'bookings'])->findOrFail($id);
            return view('terapis.detail-terapis', compact('terapi'));
        } else {
            return redirect()->route('login');
        }
    }
}
