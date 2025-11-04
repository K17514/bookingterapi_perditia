<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Add this line
use App\Models\Terapi;

class FrontController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (Auth::check()) {  // Use Auth facade
            return view('FrontEnd.index'); // Your homepage view
        } else {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }
    }

    public function loadscreen()
    {
        // Check if user is authenticated
        if (Auth::check()) {  // Use Auth facade
            return view('loadscreen'); // Your homepage view
        } else {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }
    }

    public function therapists()
    {
        $terapis = Terapi::with('jadwals')->get();

        foreach ($terapis as $terapi) {
            $terapi->status = $terapi->jadwals->contains('status', 'available') ? 'available' : 'unavailable';
        }

        return view('FrontEnd.therapists', compact('terapis'));
    }
}
