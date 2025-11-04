@extends('FrontEnd.layout.headfoot')
@section('content')
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Welcome to Psychic Therapy</title>

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            .bubble {
                position: absolute;
                width: 30px;
                height: 30px;
                background-color: rgba(130, 232, 255, 0.5);
                border-radius: 50%;
                animation-name: float;
                animation-timing-function: ease-in-out;
                animation-iteration-count: infinite;
                animation-duration: calc(10s + var(--i) * 5s);
                animation-delay: calc(var(--i) * -3s);
                top: calc(10% + (var(--i) * 18%) + (random(5) * 1%));
                /* random sedikit */
                left: -40px;
                filter: drop-shadow(0 0 6px rgba(108, 78, 255, 0.7));
            }

            /* keyframe dengan gerakan Y acak */
            @keyframes float {
                0% {
                    transform: translateX(0) translateY(0);
                    opacity: 1;
                }

                20% {
                    transform: translateX(20vw) translateY(-25px);
                    opacity: 0.8;
                }

                40% {
                    transform: translateX(40vw) translateY(15px);
                    opacity: 0.9;
                }

                60% {
                    transform: translateX(60vw) translateY(-10px);
                    opacity: 0.6;
                }

                80% {
                    transform: translateX(80vw) translateY(20px);
                    opacity: 0.7;
                }

                100% {
                    transform: translateX(100vw) translateY(0);
                    opacity: 0;
                }
            }
        </style>


    </head>

    <body class="bg-[#f0f3fa] font-[Poppins] min-h-screen flex items-center">






        <div
            class="max-w-[1440px] w-full mx-auto flex flex-col md:flex-row items-start px-6 md:px-20 py-12 md:py-24 gap-12">
            <!-- Left Side: Welcome Text & Button -->
            <div class="md:w-1/2 flex flex-col justify-center max-w-[400px]">
                <h1 class="text-[3.5rem] leading-tight font-normal text-gray-700 mb-3">
                    Welcome to <br /> <b>Psychic Therapy</b>
                </h1>
                <h2 class="text-[#6c4eff] font-semibold text-lg md:text-xl mb-6">
                    Place for mental help
                </h2>
                <p class="text-gray-500 text-base mb-10 leading-relaxed">
                    We believe mental health matters and wanted to help the people who are struggling.
                </p>
            </div>

            <!-- Right Side: Card with Text + Button + Charts -->
            <div class="md:w-1/2 w-full max-w-[720px]">
                <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col space-y-8">
                    <!-- Text & Button -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Mental Health Stats Overview</h3>
                        <p class="text-gray-500 max-w-md leading-relaxed mb-4">
                            Insights into common mental health issues and the impact of therapy over time.
                        </p>
                        {{-- <a href="{{ route('terapis.cards') }}">
                            <button
                                class="w-[180px] md:w-[200px] border-2 border-primary text-gray-700 font-semibold py-3 rounded-full hover:bg-[#4ed6ff] hover:text-white transition-colors duration-300">
                                SEE OUR THERAPISTS
                            </button>
                        </a> --}}
                    </div>

                    <!-- Charts Row -->
                    <div class="flex flex-row flex-wrap md:flex-nowrap justify-between items-start space-x-6 mt-4">
                        <!-- Pie Chart -->
                        <div class="flex-1 min-w-[200px]">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Common Mental Health Issues</h4>
                            <div class="w-full flex justify-center">
                                <div class="w-52 h-52">
                                    <canvas id="issuesChart" width="210" height="210"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Bar Chart -->
                        <div class="flex-1 min-w-[200px]">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Therapy Impact Stats</h4>
                            <div class="w-full flex justify-center">
                                <div class="w-64 h-64">
                                    <canvas id="impactChart" width="250" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="bubbles" class="fixed top-0 left-0 w-full h-full pointer-events-none overflow-hidden">
            <!-- bubbles akan dibuat JS -->
        </div>

        <!-- Chart.js Scripts -->
        <script>
            // Pie Chart: Common Issues
            const ctx1 = document.getElementById('issuesChart').getContext('2d');
            new Chart(ctx1, {
                type: 'pie',
                data: {
                    labels: ['Anxiety', 'Depression', 'PTSD', 'Stress', 'Other'],
                    datasets: [{
                        data: [35, 25, 15, 20, 5],
                        backgroundColor: ['#6c4eff', '#f97316', '#10b981', '#3b82f6', '#facc15'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 20,
                                padding: 15
                            }
                        }
                    }
                }
            });

            // Bar Chart: Therapy Impact
            const ctx2 = document.getElementById('impactChart').getContext('2d');
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: ['Before Therapy', 'After 1 Month', 'After 3 Months'],
                    datasets: [{
                        label: 'Stress Level (0-100)',
                        data: [85, 55, 30],
                        backgroundColor: '#6c4eff',
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                stepSize: 20
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            const bubblesContainer = document.getElementById('bubbles');
            const bubbleCount = 7;

            for (let i = 0; i < bubbleCount; i++) {
                const bubble = document.createElement('div');
                bubble.classList.add('bubble');

                // random ukuran 20-40px
                const size = Math.random() * 20 + 20;
                bubble.style.width = size + 'px';
                bubble.style.height = size + 'px';

                // random posisi top 5%-85%
                bubble.style.top = (Math.random() * 80 + 5) + '%';

                // random animation duration 8-16s
                bubble.style.animationDuration = (Math.random() * 8 + 8) + 's';

                // random animation delay 0-16s (buat stagger)
                bubble.style.animationDelay = (Math.random() * 16) + 's';

                bubblesContainer.appendChild(bubble);
            }
        </script>

    </body>

    </html>
@stop
