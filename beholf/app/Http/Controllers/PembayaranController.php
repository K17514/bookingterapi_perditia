<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch bookings and related jadwals data with aliases
        $bookings = DB::table('bookings')
            ->join('jadwals', 'bookings.jadwal', '=', 'jadwals.id')
            ->select(
                'bookings.*',
                'jadwals.jam_mulai',
                'jadwals.jam_selesai',
                'jadwals.tanggal',
                'jadwals.biaya_jadwal as jadwals_biaya_jadwal'  // Alias for jadwals columns
            )
            ->whereDate('tanggal_booking', Carbon::today())
            ->get();

        // Level-based filtering
        if ($user->level == 1 || $user->level == 2) {
            // Level 1 and 2: Can see all bookings
        } elseif ($user->level == 3) {
            $bookings = $bookings->where('kode_terapi', $user->terapis->kode_terapi);
        } elseif ($user->level == 4) {
            $bookings = $bookings->where('id_user', $user->id);
        }

        // Pass bookings and user data to the view
        return view('FrontEnd.booking_table', compact('bookings', 'user'));
    }

    public function createPayment($kode_booking)
    {
        // Fetch the booking, related jadwal, and payment data
        $booking = DB::table('bookings')
            ->join('pembayarans', 'bookings.kode_booking', '=', 'pembayarans.kode_booking')
            ->join('jadwals', 'bookings.jadwal', '=', 'jadwals.id')
            ->where('bookings.kode_booking', $kode_booking)
            ->select(
                'bookings.kode_booking',
                'bookings.biaya_layanan',
                'jadwals.biaya_jadwal',
                'pembayarans.grand_total',
                'pembayarans.metode_pembayaran',
                'pembayarans.status'
            )
            ->first();

        if ($booking) {
            // Calculate grand total by adding biaya_layanan and biaya_jadwal
            $grandTotal = $booking->biaya_layanan + $booking->biaya_jadwal;

            // Pass the $booking and $grandTotal to the view
            return view('FrontEnd.payment_form', compact('booking', 'grandTotal'));
        }

        return back()->with('error', 'Booking not found.');
    }
    public function storePayment(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required',
            'foto_pembayaran' => 'required|image',
        ]);

        // Get current date in 'YYYYMMDD' format
        $currentDate = Carbon::now()->format('Ymd');

        // Start a database transaction to avoid race condition
        DB::beginTransaction();

        try {
            // Get the last payment number for the current date (if any)
            $lastPayment = Pembayaran::whereDate('created_at', Carbon::today())
                ->orderBy('created_at', 'desc')
                ->first();

            // Increment the sequential number or start at 1 if no payment exists for today
            $lastNumber = $lastPayment ? (int) substr($lastPayment->nomor_pembayaran, -4) : 0;
            $sequentialNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);  // Pad to 4 digits

            // Generate the full nomor_pembayaran in format YYYYMMDD-XXXX
            $nomorPembayaran = $currentDate . '-' . $sequentialNumber;

            // Ensure the nomor_pembayaran is unique before proceeding
            while (Pembayaran::where('nomor_pembayaran', $nomorPembayaran)->exists()) {
                // If the nomor_pembayaran already exists, increment the number and try again
                $lastNumber++;
                $sequentialNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                $nomorPembayaran = $currentDate . '-' . $sequentialNumber;
            }

            // Create a new Pembayaran instance and store the payment
            $payment = new Pembayaran();
            $payment->kode_booking = $request->kode_booking;
            $payment->grand_total = $request->grand_total;  // The grand_total is now passed directly from the form
            $payment->metode_pembayaran = $request->metode_pembayaran;
            $payment->status = 'pending';  // Set payment status to pending
            $payment->nomor_pembayaran = $nomorPembayaran;  // Store the generated nomor_pembayaran

            if ($request->hasFile('foto_pembayaran')) {
                $file = $request->file('foto_pembayaran');
                $filename = $nomorPembayaran . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/payments'), $filename);
                $payment->foto_pembayaran = 'uploads/payments/' . $filename;
            }

            $payment->created_by = Auth::id();  // Store user ID as creator
            $payment->save();  // Save the payment record

            // Commit the transaction if everything is successful
            DB::commit();

            // Redirect to the booking index page with a success message
            return redirect()->route('histori')->with('success', 'Payment has been submitted and is now pending.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();
        }
    }


    // View payment details for authorized users
    public function viewPayment($kode_booking)
    {
        $payment = Pembayaran::where('kode_booking', $kode_booking)->firstOrFail();
        return view('FrontEnd.payment_details', compact('payment'));
    }

    // Approve payment (for level 2 and level 3 users)
    public function approvePayment($paymentId)
    {
        $payment = Pembayaran::find($paymentId);
        if ($payment) {
            $payment->status = 'lunas';  // Mark as "paid"
            $payment->save();
            return back()->with('success', 'Payment has been marked as paid.');
        }
        return back()->with('error', 'Payment not found.');
    }

    // Reject payment (for level 2 and level 3 users)
    public function rejectPayment($paymentId)
    {
        $payment = Pembayaran::find($paymentId);
        if ($payment) {
            $payment->status = 'gagal';  // Mark as failed
            $payment->save();
            return back()->with('success', 'Payment has been marked as failed.');
        }
        return back()->with('error', 'Payment not found.');
    }
}
