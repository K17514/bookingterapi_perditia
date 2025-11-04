@extends('FrontEnd.layout.headfoot')

@section('content')
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tambah Kategori</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap");
    body {
      font-family: "Inter", sans-serif;
      background-color: #f5f3ff; /* light purple background */
    }
  </style>
</head>
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-[#f5f3ff]">
    <div class="w-full max-w-4xl bg-white rounded-xl-lg p-8 border-2 border-purple-400 hover:border-purple-600 transition font-[Inter]">
        <!-- Therapist Info -->
        <div class="flex flex-col lg:flex-row items-center gap-6 mb-8">
            @if($terapi->foto)
                <img src="{{ asset($terapi->foto) }}" class="w-40 h-40 object-cover rounded-xl" alt="Foto Terapis">
            @else
                <div class="w-40 h-40 bg-purple-100 text-purple-300 flex items-center justify-center rounded-xl text-5xl shadow">
                    <i class="fas fa-user"></i>
                </div>
            @endif
            <div class="text-center lg:text-left">
                <h2 class="text-2xl font-bold text-purple-900">{{ $terapi->name }}</h2>
                <p class="text-purple-700 mt-1">{{ $terapi->spesialis }}</p>
                <p class="text-purple-500 text-sm mt-2">{{ $terapi->deskripsi }}</p>
            </div>
        </div>

        <!-- Booking Form -->
       <form action="{{ route('booking.store', $terapi->kode_terapi) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer -->
                <div>
                    <label class="block text-purple-800 font-semibold mb-1">Customer</label>
                    <input type="text" value="{{ auth()->user()->name }}" disabled class="w-full px-4 py-2 border border-purple-300 rounded-lg bg-gray-100">
                </div>

                <!-- Jadwal -->
                <div>
                    <label class="block text-purple-800 font-semibold mb-1">Pilih Jadwal</label>
                <select name="jadwal" class="w-full px-4 py-2 border border-purple-300 rounded-lg" required>
    @foreach($jadwals as $j)
        <option value="{{ $j->id }}" {{ $j->status === 'unavailable' ? 'disabled' : '' }}>
            {{ \Carbon\Carbon::parse($j->tanggal)->format('Y-m-d') }} | {{ $j->jam_mulai }} - {{ $j->jam_selesai }} ({{ ucfirst($j->status) }})
        </option>
    @endforeach
</select>




                </div>

                <!-- Riwayat Penyakit -->
                <div class="md:col-span-2">
                    <label class="block text-purple-800 font-semibold mb-1">Riwayat Penyakit</label>
                    <input type="text" name="riwayat_penyakit" class="w-full px-4 py-2 border-2 border-purple-400 hover:border-purple-600 rounded-lg " required>
                </div>

                <!-- Keluhan -->
                <div class="md:col-span-2">
                    <label class="block text-purple-800 font-semibold mb-1">Keluhan</label>
                    <textarea name="keluhan" rows="4" class="w-full px-4 py-2 border-2 border-purple-400 hover:border-purple-600 rounded-lg" required></textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between mt-8">
              <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-6 rounded-lg transition">
    Booking
</button>

                <button type="reset" class="bg-yellow-400 hover:bg-yellow-500 text-purple-900 font-semibold py-2 px-6 rounded-lg transition">
                    Reset
                 </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>

@endsection
