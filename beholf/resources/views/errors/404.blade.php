
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>404 Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>
<body class="bg-purple-400 min-h-screen flex items-center justify-center relative">
  <div class="flex items-center space-x-6 px-4">
    <h1 class="text-white text-[10rem] leading-none font-normal select-none">404</h1>
    <div class="text-white">
      <p class="font-semibold text-2xl leading-tight select-none">SORRY!</p>
      <p class="text-lg leading-relaxed select-none">The page you’re looking for was not found.</p>
    </div>
  </div>
  <div class="absolute bottom-20 w-full text-center">
    <a href="{{ route('home') }}" class="text-white text-sm px-6 py-2 border border-white rounded select-none hover:bg-white hover:text-purple-400 transition">
      Back to home
</a>
  </div>

