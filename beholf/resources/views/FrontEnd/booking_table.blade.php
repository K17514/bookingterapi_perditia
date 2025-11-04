@extends('FrontEnd.layout.headfoot')

@section('content')
    @php
        $userLevel = auth()->check() ? auth()->user()->level : null;
    @endphp


    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Booking Table</h5>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Nama Customer</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->kode_booking }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->keluhan }}</td>
                                <td>
                                    @if ($booking->pembayaran)
                                        @if ($booking->pembayaran->status == 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                        @elseif($booking->pembayaran->status == 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @elseif($booking->pembayaran->status == 'gagal')
                                            <span class="badge bg-danger">Gagal</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                    @endif
                                </td>

                                <td>{{ $booking->jam_mulai ?? 'N/A' }}</td>
                                <td>{{ $booking->jam_selesai ?? 'N/A' }}</td>
                                <td>
                                    {{ $booking->tanggal ? \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d M Y') : 'N/A' }}
                                </td>

                                <td>
                                    @if ($booking->status == 'pending' && in_array($userLevel, [1, 2, 3]))
                                        <!-- Accept Button -->
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#acceptModal{{ $booking->id }}">Terima</button>

                                        <!-- Cancel Button (Form-based POST request) -->
                                        <form action="{{ route('booking.cancel', $booking->id) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                                        </form>
                                    @elseif(in_array($userLevel, [1, 2, 3, 4]) && $booking->status == 'accepted')
                                        <!-- Payment Button -->
                                        @if (!$booking->pembayaran)
                                            <!-- Belum ada pembayaran, tampilkan tombol Bayar -->
                                            <a href="#" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#paymentModal{{ $booking->id }}">Bayar</a>
                                        @endif
                                    @endif

                                    <!-- View Payment Button for Level 1, 2 and 3 -->
                                    @if ($booking->pembayaran && $booking->pembayaran->status == 'pending' && $booking->pembayaran->foto_pembayaran)
                                        @if (in_array($userLevel, [1, 2, 3]))
                                            <!-- Show View Payment button if payment is pending -->
                                            <button class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#viewPaymentModal{{ $booking->id }}">View Payment</button>
                                        @endif
                                    @endif
                                    <!-- View Details Button -->
                                    <button class="btn btn-info" data-bs-toggle="modal"
                                        data-bs-target="#detailsModal{{ $booking->id }}">Details</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Move all Modals here, after the table -->
    @foreach ($bookings as $booking)
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
                                <input type="date" class="form-control secondary-field" value="{{ $booking->tanggal }}"
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
                                <img src="{{ asset('beholf/public/' . $booking->pembayaran->foto_pembayaran) }}"
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

        <!-- Details Modal -->
        <div class="modal fade" id="detailsModal{{ $booking->id }}" tabindex="-1"
            aria-labelledby="detailsModalLabel{{ $booking->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailsModalLabel{{ $booking->id }}">Booking Details:
                            {{ $booking->kode_booking }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="detailBookingId" value="{{ $booking->id }}">

                        <div class="mb-3">
                            <label for="detailCustomerName" class="form-label">Customer Name</label>
                            <input type="text" id="detailCustomerName" class="form-control"
                                value="{{ $booking->user->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailKeluhan" class="form-label">Keluhan</label>
                            <input type="text" id="detailKeluhan" class="form-control"
                                value="{{ $booking->keluhan }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailStatus" class="form-label">Status</label>
                            <input type="text" id="detailStatus" class="form-control"
                                value="{{ ucfirst($booking->status) }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailTanggal" class="form-label">Tanggal</label>
                            <input type="text" id="detailTanggal" class="form-control"
                                value="{{ $booking->tanggal }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailJamMulai" class="form-label">Jam Mulai</label>
                            <input type="text" id="detailJamMulai" class="form-control"
                                value="{{ $booking->jam_mulai }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailJamSelesai" class="form-label">Jam Selesai</label>
                            <input type="text" id="detailJamSelesai" class="form-control"
                                value="{{ $booking->jam_selesai }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailBiayaLayanan" class="form-label">Biaya Layanan</label>
                            <input type="text" id="detailBiayaLayanan" class="form-control"
                                value="{{ number_format($booking->biaya_layanan, 0, ',', '.') }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailBiayaJadwal" class="form-label">Biaya Jadwal</label>
                            <input type="text" id="detailBiayaJadwal" class="form-control"
                                value="{{ number_format($booking->biaya_jadwal, 0, ',', '.') }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailGrandTotal" class="form-label">Grand Total</label>
                            <input type="text" id="detailGrandTotal" class="form-control"
                                value="{{ number_format($booking->biaya_layanan + $booking->biaya_jadwal, 0, ',', '.') }}"
                                readonly>
                        </div>

                        <!-- Check if 'pembayaran' is not null before trying to access its properties -->
                        <div class="mb-3">
                            <label for="detailMetodePembayaran" class="form-label">Metode Pembayaran</label>
                            <input type="text" id="detailMetodePembayaran" class="form-control"
                                value="{{ $booking->pembayaran ? $booking->pembayaran->metode_pembayaran : 'N/A' }}"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailTerapiName" class="form-label">Terapi Name</label>
                            <input type="text" id="detailTerapiName" class="form-control"
                                value="{{ $booking->terapi ? $booking->terapi->name : 'N/A' }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailStatusPembayaran" class="form-label">Payment Status</label>
                            <input type="text" id="detailStatusPembayaran" class="form-control"
                                value="{{ $booking->pembayaran ? ucfirst($booking->pembayaran->status) : 'N/A' }}"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label for="detailCreatedAt" class="form-label">Created At</label>
                            <input type="text" id="detailCreatedAt" class="form-control"
                                value="{{ $booking->created_at }}" readonly>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

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
