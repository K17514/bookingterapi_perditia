<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Therapist List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            overflow: hidden;
            font-family: 'Times New Roman', serif;
        }

        /* Smooth scroll and elegant gradient fade */
        .scroll-smooth {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        .fade-mask {
            position: relative;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        /* Soft fade top & bottom */
        .fade-mask::before,
        .fade-mask::after {
            content: '';
            position: sticky;
            left: 0;
            right: 0;
            height: 2rem;
            pointer-events: none;
            z-index: 10;
        }

        .fade-mask::before {
            top: 0;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.8), transparent);
        }

        .fade-mask::after {
            bottom: 0;
            background: linear-gradient(to top, rgba(255, 255, 255, 0.8), transparent);
        }
    </style>
</head>

<body class="relative min-h-screen p-4 md:p-6">

    <!-- Background -->
    <div class="absolute inset-0 overflow-hidden -z-10">
        <img src="assets/img/backgrounds/counseling_room.png" alt="Background"
            class="w-full h-full object-cover scale-110 blur-sm" />
    </div>

    <!-- Floating back & home buttons -->
    <div class="fixed top-4 left-4 z-50 flex gap-2 md:gap-3">
        <button onclick="window.history.back()"
            class="w-10 h-10 md:w-12 md:h-12 bg-white/70 hover:bg-white shadow-lg flex items-center justify-center transition rounded-lg"
            title="Go Back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                class="w-5 h-5 md:w-6 md:h-6 text-stone-800">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <button onclick="window.location.href='/booking_terapi'"
            class="w-10 h-10 md:w-12 md:h-12 bg-white/70 hover:bg-white shadow-lg flex items-center justify-center transition rounded-lg"
            title="Go Home">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                class="w-5 h-5 md:w-6 md:h-6 text-stone-800">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0-8h6v8m-6 0h6" />
            </svg>
        </button>
    </div>

    <!-- Main Container -->
    <div class="max-w-5xl mx-auto relative flex flex-col h-[90vh]">
        <!-- Title -->
        <h2 class="text-right text-2xl font-semibold text-stone-200 mb-4 drop-shadow-lg">Therapist Available</h2>

        <!-- Scrollable Card -->
        <div
            class="bg-white/80 shadow-xl p-6 rounded-2xl border border-stone-200 backdrop-blur-sm flex-1 flex flex-col overflow-hidden">

            <!-- Scrollable therapist list with fade -->
            <div class="fade-mask space-y-4 pr-2 scroll-smooth">

                @foreach ($terapis as $terapi)
                    <details class="bg-stone-100 rounded-xl overflow-hidden">
                        <summary
                            class="flex items-center justify-between cursor-pointer p-3 hover:bg-stone-200 transition">
                            <div class="flex items-center gap-3">
                                @if ($terapi->foto)
                                    <img src="{{ asset('beholf/public/' . $terapi->foto) }}" alt="Therapist"
                                        class="w-10 h-10 rounded-full object-cover" />
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full bg-stone-300 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($terapi->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-stone-800">{{ $terapi->name }}</p>
                                    <p class="text-sm text-stone-500">{{ $terapi->spesialis }} specialist</p>
                                </div>
                            </div>
                            <button
                                onclick="event.stopPropagation(); window.location.href='{{ route('detail-terapis', $terapi->id) }}';"
                                class="bg-stone-700 text-white px-4 py-2 rounded hover:bg-stone-800 transition">
                                Details
                            </button>

                        </summary>
                        <div class="p-3 text-stone-700 text-sm border-t border-stone-300 bg-stone-50">
                            {{ $terapi->deskripsi }}
                        </div>
                    </details>
                @endforeach

            </div>

        </div>
    </div>

    @include('bottom-menu')

</body>

</html>
