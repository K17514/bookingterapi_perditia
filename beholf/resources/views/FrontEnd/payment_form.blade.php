@extends('FrontEnd.layout.headfoot')

@section('content')

@php
  $userLevel = auth()->check() ? auth()->user()->level : null;
@endphp

<div class="container">
    <h2>Payment for Booking: {{ $booking->kode_booking }}</h2>

    <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="kode_booking" value="{{ $booking->kode_booking }}">
        <input type="hidden" name="grand_total" value="{{ $grandTotal }}">

        <div class="mb-3">
            <label for="biaya_layanan" class="form-label">Biaya Layanan (Rp)</label>
            <input type="text" class="form-control" value="{{ number_format($booking->biaya_layanan, 0, ',', '.') }}" readonly>
        </div>

        <div class="mb-3">
            <label for="biaya_jadwal" class="form-label">Biaya Jadwal (Rp)</label>
            <input type="text" class="form-control" value="{{ number_format($booking->jadwal->biaya_jadwal, 0, ',', '.') }}" readonly>
        </div>

        <div class="mb-3">
            <label for="grand_total" class="form-label">Grand Total (Rp)</label>
            <input type="text" class="form-control" value="{{ number_format($grandTotal, 0, ',', '.') }}" readonly>
        </div>

        <div class="mb-3">
            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="form-control" required>
                <option value="BCA">BCA</option>
                <option value="dana">Dana</option>
                <option value="gopay">GoPay</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="foto_pembayaran" class="form-label">Foto Pembayaran</label>
            <input type="file" class="form-control" name="foto_pembayaran" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit Payment</button>
    </form>
</div>

@endsection


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const metodePembayaran = document.getElementById('metode_pembayaran');
    const rekeningInfo = document.getElementById('rekening-info');
    const rekeningBank = document.getElementById('rekening-bank');
    const rekeningInput = document.getElementById('rekening');

    metodePembayaran.addEventListener('change', function () {
      const method = this.value;

      if (method === 'BCA') {
        rekeningBank.textContent = 'BCA';
        rekeningInput.value = '1234567890';  // Example account number
      } else if (method === 'dana') {
        rekeningBank.textContent = 'Dana';
        rekeningInput.value = '0987654321';
      } else if (method === 'gopay') {
        rekeningBank.textContent = 'GoPay';
        rekeningInput.value = '1122334455';
      }
    });
  });
</script>
