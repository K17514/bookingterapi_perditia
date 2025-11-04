<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Perditia - Register</title>
<link rel="icon" type="image/png" href="{{ asset('assets/img/icons/lightbg-logo.png') }}" />
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Playfair Display', serif; overflow-x: hidden; }
  #google-recaptcha-container { display: flex; justify-content: center; transform: scale(0.9); transform-origin: center; }
  @media (max-width: 400px) { #google-recaptcha-container { transform: scale(0.8); } }
</style>
</head>
<body class="relative min-h-screen flex items-center justify-center p-6">

  <!-- Background -->
  <div class="absolute inset-0 overflow-hidden">
    <img src="assets/img/backgrounds/vintage-bg.jpg" alt="Background"
         class="w-full h-full object-cover scale-110 blur-sm" />
  </div>

  <!-- Register Card -->
  <div class="max-w-md w-full bg-[#EDEAE5]/90 border border-[#9C9286] shadow-[0_0_40px_rgba(0,0,0,0.1)] backdrop-blur-sm rounded-xl p-8 space-y-6">
    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold tracking-wide text-[#68615B]">Create Account</h1>
      <p class="text-[#7a7268] mt-1 text-sm italic">"The beginning of finding yourself”</p>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
      @csrf

      <div>
        <label for="name" class="block text-sm font-semibold text-[#68615B] mb-1">Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}"
               class="w-full border border-[#9C9286] bg-[#f5f3ef] focus:ring-2 focus:ring-[#9C9286] text-[#4b453d] px-4 py-2 rounded-md outline-none"
               placeholder="John Doe" required>
        @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label for="email" class="block text-sm font-semibold text-[#68615B] mb-1">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
               class="w-full border border-[#9C9286] bg-[#f5f3ef] focus:ring-2 focus:ring-[#9C9286] text-[#4b453d] px-4 py-2 rounded-md outline-none"
               placeholder="your@email.com" required>
        @error('email') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label for="foto" class="block text-sm font-semibold text-[#68615B] mb-1">Photo (optional)</label>
        <input type="file" id="foto" name="foto" accept="image/*"
               class="w-full border border-[#9C9286] bg-[#f5f3ef] px-4 py-2 rounded-md outline-none">
        @error('foto') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label for="password" class="block text-sm font-semibold text-[#68615B] mb-1">Password</label>
        <div class="relative">
          <input type="password" id="password" name="password" placeholder="••••••••"
                 class="w-full border border-[#9C9286] bg-[#f5f3ef] focus:ring-2 focus:ring-[#9C9286] text-[#4b453d] px-4 py-2 rounded-md outline-none" required>
          <span class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer toggle-password text-[#7a7268]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.26 4.5 12 4.5c4.74 0 8.577 3.01 9.964 7.183.07.207.07.431 0 .639C20.577 16.49 16.74 19.5 12 19.5c-4.74 0-8.577-3.01-9.964-7.178z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </span>
        </div>
        @error('password') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label for="password_confirmation" class="block text-sm font-semibold text-[#68615B] mb-1">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation"
               class="w-full border border-[#9C9286] bg-[#f5f3ef] focus:ring-2 focus:ring-[#9C9286] text-[#4b453d] px-4 py-2 rounded-md outline-none"
               placeholder="••••••••" required>
      </div>

      <div id="google-recaptcha-container" class="flex justify-center">
        {!! NoCaptcha::display() !!}
        @error('g-recaptcha-response') <div class="text-red-600 text-sm mt-1 text-center">{{ $message }}</div> @enderror
      </div>

      <button type="submit"
              class="w-full bg-[#9C9286] hover:bg-[#7a7268] text-white font-semibold py-2 rounded-md shadow-md transition">
        Register
      </button>

      <p class="text-center text-sm text-[#7a7268]">
        Already have an account?
        <a href="{{ route('login') }}" class="underline hover:text-[#68615B]">Sign In</a>
      </p>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const togglePassword = document.querySelector('.toggle-password');
      const passwordInput = document.querySelector('#password');
      togglePassword.addEventListener('click', () => {
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
      });
    });

    window.onload = function () {
      var offlineCaptcha = document.getElementById('offline-captcha-container');
      var offlineInput = offlineCaptcha?.querySelector('input[name="captcha"]');
      var googleCaptchaContainer = document.getElementById('google-recaptcha-container');

      if (typeof grecaptcha === 'undefined') {
        if (googleCaptchaContainer) googleCaptchaContainer.style.display = 'none';
        if (offlineCaptcha) offlineCaptcha.classList.remove('hidden');
        if (offlineInput) offlineInput.required = true;
      } else {
        if (googleCaptchaContainer) googleCaptchaContainer.style.display = 'flex';
        if (offlineCaptcha) offlineCaptcha.classList.add('hidden');
        if (offlineInput) offlineInput.required = false;
      }
    };
  </script>

  {!! NoCaptcha::renderJs() !!}
</body>
</html>
