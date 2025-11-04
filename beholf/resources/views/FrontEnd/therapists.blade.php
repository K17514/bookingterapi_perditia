@extends('FrontEnd.layout.headfoot')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Therapist Booking Cards</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: "Inter", sans-serif;
      background-color: #f5f3ff;
    }
  </style>
</head>

<body class="flex justify-center py-10 px-4">
  <div class="flex flex-col sm:flex-row flex-wrap gap-8 max-w-6xl">
    @foreach($terapis as $terapi)
    <div class="max-w-[260px] bg-white rounded-xl-lg p-6 flex flex-col items-center border-2 border-purple-400 hover:border-purple-600 transition">
      <div class="w-[220px] h-[220px] rounded-lg bg-purple-100-md flex items-center justify-center text-purple-300 text-6xl select-none overflow-hidden">
        @if($terapi->foto)
          <img src="{{ asset($terapi->foto) }}" class="object-cover w-full h-full rounded-lg" alt="Foto Terapis">
        @else
          <i class="fas fa-user"></i>
        @endif
      </div>

      <p class="mt-5 text-center text-lg font-semibold text-purple-900 leading-tight">
        {{ $terapi->name }}
      </p>
      <p class="text-center text-sm text-purple-600 mt-1">
        {{ $terapi->spesialis }}
      </p>
      <p class="text-center text-sm italic text-purple-400 mt-1">
        {{ $terapi->deskripsi}}
      </p>
      <p class="text-center text-sm text-purple-700 mt-3 font-semibold">
        {{ $terapi->status === 'available' ? 'Available for booking' : 'Not available' }}
      </p>
       
      <a href="{{ route('booking.create', $terapi->kode_terapi) }}" class="w-full">
        <button class="mt-5 w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 rounded-lg transition">
          Book Now
        </button>
      </a>
    </div>
    @endforeach
  </div>
</body>
</html>

  

@stop
