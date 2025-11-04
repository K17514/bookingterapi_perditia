<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Therapist Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(100, 100, 100, 0.4) transparent;
        }
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
    <div class="max-w-5xl mx-auto bg-[#EDEAE5]/90 shadow-xl p-8 rounded-2xl border border-stone-600 backdrop-blur-sm">
        <!-- Header -->
        <header class="mb-8">
            <h1 class="text-3xl font-extrabold tracking-widest border-b-2 border-stone-600 pb-3">INFORMATION</h1>
            <p class="text-sm text-stone-600 mt-2 uppercase">CHARACTER INTRODUCTION 角色信息介绍</p>
        </header>

        <div class="flex flex-col md:flex-row gap-10">
            <!-- Left Panel (ID Card) -->
            <div class="relative flex-shrink-0 max-w-xs w-full md:w-80">
                <div
                    class="bg-stone-100 shadow-inner p-5 rounded-xl transform md:-rotate-2 transition duration-300 hover:rotate-0">
                    <div class="border border-stone-500 rounded-lg bg-white shadow-md overflow-hidden">
                        <!-- Placeholder for ID photo -->
                        @if ($terapi->foto)
                            <img src="{{ asset('beholf/public/' . $terapi->foto) }}" alt="{{ $terapi->name }}"
                                class="w-full aspect-[3/4] object-cover" />
                        @else
                            <div
                                class="bg-stone-500 aspect-[3/4] flex items-center justify-center text-white text-3xl font-bold">
                                {{ strtoupper(substr($terapi->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="p-4">
                            <p class="text-xs uppercase tracking-widest text-stone-500">Name</p>
                            <h2 class="text-3xl font-black uppercase tracking-wide mt-1 text-stone-800">
                                {{ strtoupper($terapi->name) }}
                            </h2>

                            <p class="mt-2 text-xs font-mono tracking-wide text-stone-500">IDENTIFICATION NUMBER</p>
                            <p class="text-lg font-semibold tracking-widest text-stone-700">
                                {{ strtoupper($terapi->kode_terapi) }}</p>
                        </div>
                    </div>
                    <!-- Vertical Label -->
                    <div
                        class="absolute top-8 left-[-3.2rem] rotate-[-90deg] text-stone-400 text-xs font-bold tracking-widest select-none">
                        THERAPIST
                    </div>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="flex-1 flex flex-col justify-between">
                <!-- Stats -->
                <section>

                    <div class="flex justify-between items-start mb-6">
                        <!-- Left: Name + Specialization -->
                        <div>
                            <h3 class="text-3xl font-bold tracking-wide uppercase text-stone-800">
                                {{ strtoupper($terapi->name) }}
                            </h3>
                            <p class="text-sm text-stone-600 italic mt-1">{{ $terapi->spesialis }} specialist</p>
                        </div>

                        <!-- Right: Patients -->
                        <span
                            class="bg-stone-700 text-white px-4 py-1 rounded-lg font-mono tracking-wide text-sm shadow">
                            PATIENTS <strong>{{ $terapi->bookings->where('status', 'accepted')->count() }}</strong>
                        </span>
                    </div>



                    <!-- Jadwal Tersedia -->
                    <div
                        class="rounded-lg shadow-inner border border-stone-900 h-96 overflow-y-auto scrollbar-thin p-4 space-y-5 mb-6">
                        @foreach ($terapi->jadwals->filter(function ($jadwal) {
        $startDateTime = \Carbon\Carbon::parse($jadwal->tanggal->format('Y-m-d') . ' ' . $jadwal->jam_mulai);
        return $startDateTime->isFuture();
    }) as $jadwal)
                            @php
                                $isAvailable = strtolower($jadwal->status) === 'available';
                            @endphp

                            <div>
                                <!-- Date & Status Row -->
                                <div
                                    class="flex items-center justify-between mb-1 font-semibold {{ $isAvailable ? 'text-stone-800' : 'text-stone-400 line-through' }}">
                                    <div class="flex items-center gap-2">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" class="w-5 h-5 text-stone-600">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10m-13 8h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </span>
                                        <span>{{ $jadwal->tanggal->format('d M Y') }}</span>
                                    </div>
                                    <span
                                        class="text-xs uppercase tracking-wide {{ $isAvailable ? 'text-green-600' : 'text-stone-400' }}">
                                        {{ $jadwal->status }}
                                    </span>
                                </div>

                                <!-- Time and Price Row (your latest design) -->
                                <div
                                    class="w-full text-sm px-4 py-2 rounded-md flex justify-between items-center
        {{ $isAvailable ? 'bg-white/70 text-stone-800' : 'bg-stone-100 text-stone-400' }}">

                                    <div class="font-mono">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                        - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </div>

                                    <div class="font-bold">
                                        Rp{{ number_format($jadwal->biaya_jadwal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if (
                            $terapi->jadwals->filter(function ($jadwal) {
                                    $startDateTime = \Carbon\Carbon::parse($jadwal->tanggal->format('Y-m-d') . ' ' . $jadwal->jam_mulai);
                                    return $startDateTime->isFuture();
                                })->count() === 0)
                            <p class="text-sm text-stone-500 italic mt-2">No available schedule at the moment.
                            </p>
                        @endif

                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('booking.create', $terapi->kode_terapi) }}"
                            class="mt-8 px-8 py-2 bg-stone-700 text-white font-semibold rounded-lg shadow hover:bg-stone-800 hover:shadow-lg transition">
                            BOOKING
                        </a>

                    </div>

                </section>

                <!-- Story -->

            </div>
        </div>
        <section class="mt-10 bg-stone-50 p-5 rounded-lg border border-stone-500 shadow-inner">
            <div class="flex justify-between items-center mb-3 text-stone-800 font-semibold">
                <span>Description</span>
                <span class="bg-stone-700 text-white px-2 rounded text-sm font-mono select-none">2/2</span>
            </div>
            <p class="text-stone-800 text-sm leading-relaxed whitespace-pre-line mb-4">
                {{ $terapi->deskripsi }}
            </p>
        </section>
    </div>

    @include('bottom-menu')

</body>

</html>
