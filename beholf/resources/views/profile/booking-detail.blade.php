<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Booking Detail</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icons/lightbg-logo.png') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* remove body background, we’ll use an absolutely positioned div instead */
        body {
            overflow-x: hidden;
        }

        /* Minimal, modern scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: rgba(100, 100, 100, 0.4);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: rgba(100, 100, 100, 0.6);
        }

        /* Firefox support */
        /* * {
            scrollbar-width: thin;
            scrollbar-color: rgba(100, 100, 100, 0.4) transparent;
        } */

        /* Fix bottom menu width on this page */
        /* .fixed.bottom-4 {
            width: 60% !important;
            max-width: 20rem !important;
        } */
    </style>
</head>

<body class="relative min-h-screen p-6 font-serif">

    <!-- Back Button -->
    <div class="fixed top-6 left-4 z-50 flex gap-3">
        <!-- Back Button -->
        <button onclick="window.history.back()"
            class="w-12 h-12 bg-white/70 hover:bg-white shadow-lg flex items-center justify-center transition"
            title="Go Back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                class="w-6 h-6 text-stone-800">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <!-- Home Button -->
        <button onclick="window.location.href='/booking_terapi'"
            class="w-12 h-12 bg-white/70 hover:bg-white shadow-lg flex items-center justify-center transition"
            title="Go Home">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                class="w-6 h-6 text-stone-800">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0-8h6v8m-6 0h6" />
            </svg>
        </button>
    </div>



    <!-- Background image with blur -->
    <div class="absolute inset-0 overflow-hidden">
        <img src="../assets/img/backgrounds/counseling_room.png" alt="Background"
            class="w-full h-full object-cover scale-110 blur-sm" />
    </div>



    <!-- Main card -->
    <div class="max-w-5xl mx-auto bg-[#EDEAE5]/90 shadow-xl p-8 rounded-2xl border border-stone-600 backdrop-blur-sm relative">
        <!-- Header -->
        <header class="mb-8">
            <h1 class="text-3xl font-extrabold tracking-widest border-b-2 border-stone-600 pb-3">BOOKING DETAILS</h1>
            <p class="text-sm text-stone-600 mt-2 uppercase">Booking Information 预约信息</p>
        </header>

        <div class="flex gap-10">
            <!-- Left Panel: Booking Details -->
            <div class="flex-1">
                <div class="flex justify-between items-start mb-6">
                    <!-- Therapist Name + Specialization -->
                    <div>
                    </div>

                    <!-- Status -->
                    <span
                        class="bg-stone-700 text-white px-4 py-1 rounded-lg font-mono tracking-wide text-sm shadow">
                        STATUS <strong>{{ strtoupper($booking->status) }}</strong>
                    </span>
                </div>

                <!-- Booking Details -->
                <div class="space-y-4">
                    <p><strong>Booking Code:</strong> {{ strtoupper($booking->kode_booking) }}</p>
                    <p><strong>Date:</strong> {{ $booking->jadwal_tanggal ? \Carbon\Carbon::parse($booking->jadwal_tanggal)->translatedFormat('d M Y') : 'N/A' }}</p>
                    <p><strong>Time:</strong> {{ $booking->jam_mulai ?? 'N/A' }} - {{ $booking->jam_selesai ?? 'N/A' }}</p>
                    <p><strong>Complaint:</strong> {{ $booking->keluhan }}</p>
                    <p><strong>Medical History:</strong> {{ $booking->riwayat_penyakit }}</p>
                    <p><strong>Service Fee:</strong> Rp {{ number_format($booking->biaya_layanan, 0, ',', '.') }}</p>
                    <p><strong>Schedule Fee:</strong> Rp {{ number_format($booking->biaya_jadwal, 0, ',', '.') }}</p>
                    <p><strong>Grand Total:</strong> Rp {{ number_format(($booking->biaya_layanan + $booking->biaya_jadwal), 0, ',', '.') }}</p>
                    @if ($booking->pembayaran)
                        <p><strong>Payment Method:</strong> {{ $booking->pembayaran->metode_pembayaran }}</p>
                        <p><strong>Payment Status:</strong>
                            @if ($booking->pembayaran->status == 'pending')
                                <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                            @elseif($booking->pembayaran->status == 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @elseif($booking->pembayaran->status == 'gagal')
                                <span class="badge bg-danger">Gagal</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($booking->pembayaran->status) }}</span>
                            @endif
                        </p>
                    @else
                        <p><strong>Payment:</strong> Not yet processed</p>
                    @endif
                    <p><strong>Created At:</strong> {{ $booking->created_at }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex gap-4">
                    @php
                        $userLevel = auth()->check() ? auth()->user()->level : null;
                    @endphp

                    @if ($booking->status == 'pending' && in_array($userLevel, [1, 2, 3]))
                        <!-- Accept Button -->
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition" data-bs-toggle="modal"
                            data-bs-target="#acceptModal{{ $booking->id }}">Terima</button>

                        <!-- Cancel Button (Form-based POST request) -->
                        <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
                                onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                        </form>
                    @elseif(in_array($userLevel, [1, 2, 3, 4]) && $booking->status == 'accepted')
                        <!-- Payment Button -->
                        @if (!$booking->pembayaran)
                            <!-- Belum ada pembayaran, tampilkan tombol Bayar -->
                            <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition" data-bs-toggle="modal"
                                data-bs-target="#paymentModal{{ $booking->id }}">Bayar</button>
                        @endif
                    @endif

                    <!-- View Payment Button for Level 2 and 3 -->
                    @if ($booking->pembayaran && $booking->pembayaran->status == 'pending' && $booking->pembayaran->foto_pembayaran)
                        @if (in_array($userLevel, [2, 3]))
                            <!-- Show View Payment button if payment is pending -->
                            <button class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition" data-bs-toggle="modal"
                                data-bs-target="#viewPaymentModal{{ $booking->id }}">View Payment</button>
                        @endif
                    @endif
                </div>

            </div>

            <!-- Bottom Right: Therapist Photo with Signature -->
            <div class="absolute bottom-8 right-8">
                <div class="bg-stone-100 shadow-inner p-5 rounded-xl transform md:-rotate-2 transition duration-300 hover:rotate-0">
                    <div class="relative w-40 h-40 border border-stone-500 rounded-lg bg-white shadow-md overflow-hidden">
                        @if ($booking->terapi->foto)
                            <img src="{{ asset('beholf/public/' . $booking->terapi->foto) }}" alt="{{ $booking->terapi->name }}"
                                class="w-full h-full object-cover" />
                        @else
                            <div
                                class="w-full h-full bg-stone-500 flex items-center justify-center text-white text-3xl font-bold">
                                {{ strtoupper(substr($booking->terapi->name, 0, 1)) }}
                            </div>
                        @endif
                        <!-- Signature Overlay -->
                        <div class="absolute top-2 left-2 bg-white/80 px-2 py-1 rounded text-xs font-bold text-stone-800">
                            {{ strtoupper($booking->terapi->name) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modals -->
    <!-- Accept Modal -->
    <div class="modal fade" id="acceptModal{{ $booking->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Terima Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('booking.accept', $booking->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="biaya_layanan" class="form-label">Biaya Layanan (Rp)</label>
                            <input type="text" class="form-control rupiah-input" id="biaya_layanan"
                                name="biaya_layanan" value="{{ $booking->biaya_layanan }}">
                        </div>

                        <div class="mb-3">
                            <label for="biaya_jadwal" class="form-label">Biaya Jadwal (Rp)</label>
                            <input type="text" class="form-control rupiah-input secondary-field" id="biaya_jadwal"
                                name="biaya_jadwal" value="{{ $booking->biaya_jadwal }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control secondary-field" value="{{ $booking->jadwal_tanggal }}"
                                disabled>
                        </div>

                        <div class="mb-3">
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control secondary-field"
                                value="{{ $booking->jam_mulai }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control secondary-field"
                                value="{{ $booking->jam_selesai }}" disabled>
                        </div>
                        <button type="submit" class="btn btn-primary">Done</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal{{ $booking->id }}" tabindex="-1"
        aria-labelledby="paymentModalLabel{{ $booking->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel{{ $booking->id }}">Payment for Booking:
                        {{ $booking->kode_booking }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="kode_booking" value="{{ $booking->kode_booking }}">
                        <input type="hidden" id="grand_total{{ $booking->id }}" name="grand_total"
                            value="{{ $booking->biaya_layanan + $booking->biaya_jadwal }}">

                        <div class="mb-3">
                            <label for="biaya_layanan" class="form-label">Biaya Layanan (Rp)</label>
                            <input type="text" class="form-control"
                                value="{{ number_format($booking->biaya_layanan, 0, ',', '.') }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="biaya_jadwal" class="form-label">Biaya Jadwal (Rp)</label>
                            <input type="text" class="form-control"
                                value="{{ number_format($booking->biaya_jadwal, 0, ',', '.') }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="grand_total" class="form-label">Grand Total (Rp)</label>
                            <input type="text" class="form-control"
                                value="{{ number_format($booking->biaya_layanan + $booking->biaya_jadwal, 0, ',', '.') }}"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-control" required>
                                <option value="BCA">BCA - 9012</option>
                                <option value="dana">Dana - 1092</option>
                                <option value="gopay">GoPay - 9812</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="foto_pembayaran" class="form-label">Foto Pembayaran</label>
                            <input type="file" class="form-control" name="foto_pembayaran" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Payment Modal (for Level 2 and 3 Users) -->
    @if ($booking->pembayaran && $booking->pembayaran->status == 'pending')
        <div class="modal fade" id="viewPaymentModal{{ $booking->id }}" tabindex="-1"
            aria-labelledby="viewPaymentModalLabel{{ $booking->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewPaymentModalLabel{{ $booking->id }}">Payment Proof for
                            Booking: {{ $booking->kode_booking }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Payment Proof Image -->
                        @if ($booking->pembayaran && $booking->pembayaran->foto_pembayaran)
                            <img src="{{ asset($booking->pembayaran->foto_pembayaran) }}"
                                alt="Payment Proof" class="img-fluid mb-3"
                                style="width: 100%; height: auto; object-fit: cover;">
                        @else
                            <small class="text-muted">No image available</small>
                        @endif

                        <!-- Approve or Reject Buttons -->
                        <a href="{{ route('payment.approve', $booking->pembayaran->id) }}"
                            class="btn btn-success">Lunas</a>
                        <a href="{{ route('payment.reject', $booking->pembayaran->id) }}"
                            class="btn btn-danger">Gagal</a>
                    </div>
                </div>
            </div>
        </div>
    @endif


</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Format for currency input
        function formatRupiah(angka, prefix) {
            if (!angka) return '';
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
        }

        // Format currency on keyup
        document.querySelectorAll('.rupiah-input').forEach(input => {
            input.addEventListener('keyup', function() {
                this.value = formatRupiah(this.value, 'Rp ');
            });
        });
    });
</script>
