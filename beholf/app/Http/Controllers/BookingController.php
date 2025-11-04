<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Terapi;
use App\Models\Jadwal;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch bookings and related jadwal data
        $bookings = Booking::select('bookings.*', 'jadwals.jam_mulai', 'jadwals.jam_selesai', 'jadwals.tanggal', 'jadwals.biaya_jadwal')
            ->join('jadwals', 'bookings.jadwal', '=', 'jadwals.id')  // Joining jadwals table
            ->with('terapi')  // Load terapi relationship to avoid null errors
            ->get();

        // Level-based filtering
        if ($user->level == 1 || $user->level == 2) {
            // Level 1 and 2: Can see all bookings
        } elseif ($user->level == 3) {
            // Level 3: Only see bookings for their own therapy
            $bookings = $bookings->where('kode_terapi', $user->terapis->kode_terapi);
        } elseif ($user->level == 4) {
            // Level 4: Only see bookings they made
            $bookings = $bookings->where('id_user', $user->id);
        }

        // Pass bookings and user data to the view
        return view('FrontEnd.booking_table', compact('bookings', 'user'));
    }

    public function accept(Request $request, $id)
    {
        $booking = Booking::findOrFail($id); // Find the booking by ID

        // Clean the input for 'biaya_layanan' by removing 'Rp' and dots
        $cleanedBiayaLayanan = str_replace(['Rp', '.', ' '], '', $request->biaya_layanan);

        // Update the status of the booking to 'accepted'
        $booking->status = 'accepted';

        // Update 'biaya_layanan' based on the cleaned input
        $booking->biaya_layanan = (float) $cleanedBiayaLayanan;  // Store as a float or integer

        // Save the updated booking data
        $booking->save();

        // Optional: You can also add additional logic here if needed, such as sending notifications or updating other tables

        return redirect()->route('booking.index')->with('success', 'Booking has been accepted.');
    }


    public function cancel($bookingId)
    {
        $booking = Booking::findOrFail($bookingId); // Find the booking by its ID

        // Update the status of the booking to 'cancelled'
        $booking->status = 'cancel';
        $booking->save(); // Save the changes

        // You can add extra logic if required, like sending an email notification, etc.

        return redirect()->route('booking.index')->with('success', 'Booking has been cancelled.');
    }
    public function create($kode_terapi)
    {
        // Retrieve the therapist details using the given therapy code
        $terapi = Terapi::where('kode_terapi', $kode_terapi)->firstOrFail();

        // Fetch all jadwals (schedules) related to the selected therapist
        $jadwals = Jadwal::where('id_terapi', $terapi->id)
            ->whereDate('tanggal', '>=', Carbon::today())
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();


        // Pass both the therapist and jadwals to the view
        return view('FrontEnd.booking_form', compact('terapi', 'jadwals'));
    }
    public function store(Request $request, $kode_terapi)
    {
        $request->validate([
            'jadwal' => 'required|exists:jadwals,id',
            'riwayat_penyakit' => 'required|string',
            'keluhan' => 'required|string',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal);

        if ($jadwal->status === 'unavailable') {
            return redirect()->back()->with('error', 'Selected schedule is no longer available.');
        }

        $user = Auth::user();
        $terapi = Terapi::where('kode_terapi', $kode_terapi)->firstOrFail();

        $currentDate = Carbon::now()->format('Ymd');
        $lastBooking = Booking::whereDate('tanggal_booking', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->first();
        $lastNumber = $lastBooking ? (int) substr($lastBooking->kode_booking, -4) : 0;
        $sequentialNumber = str_pad($lastNumber + 3, 4, '0', STR_PAD_LEFT);
        $kodeBooking = $currentDate . '-' . $sequentialNumber;

        $booking = new Booking();
        $booking->kode_booking = $kodeBooking;
        $booking->kode_terapi = $terapi->kode_terapi;
        $booking->id_user = $user->id;
        $booking->tanggal_booking = Carbon::today(); // ✅ Set today's date
        $booking->status = 'pending'; // ✅ Already setting to 'pending'
        $booking->jadwal = $jadwal->id;
        $booking->riwayat_penyakit = $request->riwayat_penyakit;
        $booking->keluhan = $request->keluhan;
        $booking->save();



        $jadwal->status = 'unavailable';
        $jadwal->save();

        return redirect('/histori')->with('success', 'Booking has been created successfully.');
    }

    // BookingController.php
    public function form()
    {
        $terapis = Terapi::all();
        return view('FrontEnd.reservation_form', compact('terapis'));
    }
    public function getJadwalByTerapis($kode_terapi)
{
    $terapi = Terapi::where('kode_terapi', $kode_terapi)->firstOrFail();

    $jadwals = $terapi->jadwals()
        ->whereDate('tanggal', '>=', \Carbon\Carbon::today())
        ->orderBy('tanggal')
        ->orderBy('jam_mulai')
        ->get();

    return response()->json($jadwals->map(function ($j) {
        return [
            'id' => $j->id,
            'tanggal' => \Carbon\Carbon::parse($j->tanggal)->format('Y-m-d'),
            'jam_mulai' => $j->jam_mulai,
            'jam_selesai' => $j->jam_selesai,
            'status' => $j->status,
        ];
    }));
}

    public function history()
    {
        $user = Auth::user();

        // Fetch bookings for the current user with jadwal data joined
        $bookings = Booking::where('id_user', $user->id)
            ->with(['terapi', 'pembayaran'])
            ->join('jadwals', 'bookings.jadwal', '=', 'jadwals.id')
            ->select('bookings.*', 'jadwals.tanggal as jadwal_tanggal', 'jadwals.jam_mulai', 'jadwals.jam_selesai', 'jadwals.biaya_jadwal')
            ->get();

        return view('profile.histori', compact('bookings'));
    }

    public function show($id)
    {
        $user = Auth::user();

        // Fetch the specific booking for the current user with jadwal data joined
        $booking = Booking::where('bookings.id', $id)
            ->where('bookings.id_user', $user->id)
            ->with(['terapi', 'pembayaran'])
            ->join('jadwals', 'bookings.jadwal', '=', 'jadwals.id')
            ->select('bookings.*', 'jadwals.tanggal as jadwal_tanggal', 'jadwals.jam_mulai', 'jadwals.jam_selesai', 'jadwals.biaya_jadwal')
            ->firstOrFail();

        return view('profile.booking-detail', compact('booking'));
    }

}
