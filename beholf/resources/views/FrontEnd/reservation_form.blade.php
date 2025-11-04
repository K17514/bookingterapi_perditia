<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Booking Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: serif;
            overflow-x: hidden;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: rgba(100, 100, 100, 0.4);
            border-radius: 4px;
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(100, 100, 100, 0.4) transparent;
        }
    </style>
</head>

<body class="relative min-h-screen p-6 bg-[#EDEAE5]">

    <!-- Background Blur Image -->
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <img src="assets/img/backgrounds/counseling_room.png" alt="Background"
            class="w-full h-full object-cover scale-110 blur-sm" />
    </div>

    <!-- Back Button -->
    <div class="fixed top-6 left-4 z-50">
        <button onclick="window.history.back()"
            class="w-12 h-12 bg-white/70 hover:bg-white shadow-lg flex items-center justify-center transition"
            title="Go Back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                class="w-6 h-6 text-stone-800">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
    </div>

    <!-- Main Booking Card -->
    <div
        class="max-w-3xl mx-auto mt-24 bg-[#EDEAE5]/90 shadow-xl p-8 rounded-2xl border border-stone-600 backdrop-blur-sm">
        <header class="mb-6">
            <h1 class="text-3xl font-extrabold tracking-widest border-b-2 border-stone-600 pb-3 text-stone-800">
                BOOKING FORM
            </h1>
            <p class="text-sm text-stone-600 mt-1 uppercase">Schedule your session</p>
        </header>

        <form id="bookingForm" action="#" method="POST" class="space-y-5">
            @csrf

            <!-- Customer Name -->
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Customer</label>
                <input type="text" value="{{ auth()->user()->name }}" disabled
                    class="w-full px-4 py-2 rounded-lg border border-stone-600 text-stone-800 placeholder:text-stone-400" />
            </div>

            <!-- Terapis Dropdown -->
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Choose Therapist</label>
                <select id="terapisSelect" name="terapis" required
                    class="w-full px-4 py-2 rounded-lg bg-transparent border border-stone-600 text-stone-800">
                    <option value="" selected disabled>Select a therapist</option>
                    @foreach ($terapis as $t)
                        <option value="{{ $t->kode_terapi }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jadwal -->
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Choose Schedule</label>
                <select id="jadwalSelect" name="jadwal" required disabled
                    class="w-full px-4 py-2 rounded-lg bg-transparent border border-stone-600 text-stone-800">
                    <option>Select therapist first</option>
                </select>
            </div>



            <!-- Riwayat Penyakit -->
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Illness History</label>
                <input type="text" name="riwayat_penyakit" required
                    class="w-full px-4 py-2 rounded-lg border bg-transparent border-stone-600 text-stone-800" />
            </div>

            <!-- Keluhan -->
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Problem</label>
                <textarea name="keluhan" rows="3" required
                    class="w-full px-4 py-2 rounded-lg  border border-stone-600 bg-transparent text-stone-800 resize-none"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <button type="submit"
                    class="w-full bg-stone-700 hover:bg-stone-800 text-white font-semibold px-4 py-2 rounded-lg transition">
                    Booking
                </button>
                <button type="reset"
                    class="w-full bg-indigo-800 hover:bg-indigo-900 text-white font-semibold px-4 py-2 rounded-lg transition">
                    Reset
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('terapisSelect').addEventListener('change', function() {
    const kodeTerapis = this.value;
    const bookingForm = document.getElementById('bookingForm');
    const jadwalSelect = document.getElementById('jadwalSelect');

    // Set form action sesuai terapis terpilih
    bookingForm.action = `/booking/${kodeTerapis}`;

    // Reset dan disable dropdown jadwal
    jadwalSelect.innerHTML = '<option>Loading schedules...</option>';
    jadwalSelect.disabled = true;

    // Fetch jadwal dari API
    fetch(`/api/jadwal/by-terapis/${kodeTerapis}`)
        .then(response => response.json())
        .then(data => {
            jadwalSelect.innerHTML = '';

            if (data.length === 0) {
                jadwalSelect.innerHTML = '<option disabled>No available schedules</option>';
            } else {
                data.forEach(j => {
                    const option = document.createElement('option');
                    option.value = j.id;
                    option.textContent = `${j.tanggal} | ${j.jam_mulai} - ${j.jam_selesai} (${j.status})`;
                    option.disabled = j.status === 'unavailable';
                    jadwalSelect.appendChild(option);
                });
            }

            jadwalSelect.disabled = false;
        })
        .catch(err => {
            console.error(err);
            jadwalSelect.innerHTML = '<option disabled>Error loading schedules</option>';
        });
});

    </script>

    @include('bottom-menu')

</body>

</html>
