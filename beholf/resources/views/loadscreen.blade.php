<!DOCTYPE html>
<html lang="en">
@php
    $option1 = url('/reservasi');
    $option2 = url('/daftar-terapis');
    $option3 = url('/');
    $option4 = url('/histori');
    $option5 = url('/logout');

    $label1 = 'Reservation';
    $label2 = 'Therapist List';
    $label3 = 'Look Reservation';
    $label4 = 'Profile';
    $label5 = 'Log Out';
@endphp

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perditia - Main Menu</title>
<link rel="icon" type="image/png" href="{{ asset('assets/img/icons/lightbg-logo.png') }}" />
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative&display=swap" rel="stylesheet">
<style>
body {
    margin: 0;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: url('assets/img/backgrounds/counseling_room.png') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Cinzel Decorative', cursive;
    overflow: hidden;
    position: relative;
}

/* Curtains */
.curtain {
    position: absolute;
    top: 0;
    width: 20%;
    height: 100%;
    z-index: 2;
}
.curtain.left {
    left: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.9), rgba(0,0,0,0));
}
.curtain.right {
    right: 0;
    background: linear-gradient(to left, rgba(0,0,0,0.9), rgba(0,0,0,0));
}

/* Particles */
.particles {
    position: absolute;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
    z-index: 1;
}
.particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: gold;
    border-radius: 50%;
    opacity: 0.7;
    animation: float 12s linear infinite;
}
@keyframes float {
    0% { transform: translateY(100vh) translateX(0); opacity:0.7; }
    50% { opacity:1; }
    100% { transform: translateY(-10vh) translateX(20vw); opacity:0; }
}

/* Button container layout */
.button-container {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 25px;
    padding: 20px 40px;
    background: rgba(0,0,0,0.35);
    border-radius: 25px;
    box-shadow: 0 0 50px rgba(0,0,0,0.6);
    max-width: 380px;
    width: 90%;
}

/* Buttons */
.button-55 {
    align-self: center;
    background-color: transparent;
    border-radius: 15px 225px 255px 15px 15px 255px 225px 15px;
    border-style: solid;
    border-color: white;
    border-width: 2px;
    box-shadow: rgba(0, 0, 0, .4) 15px 28px 25px -18px;
    box-sizing: border-box;
    color: white;
    cursor: pointer;
    font-family: Georgia, sans-serif;
    font-size: 1.1rem;
    line-height: 1.5;
    outline: none;
    padding: 0.9rem 2rem;
    text-decoration: none;
    transition: all 0.25s ease-in-out;
}
.button-55:hover {
    box-shadow: rgba(0, 0, 0, .6) 2px 8px 8px -5px;
    transform: translateY(-2px);
}
.button-55:focus {
    box-shadow: rgba(0, 0, 0, .6) 2px 8px 4px -6px;
}

/* Logo */
.logo {
    position: absolute;
    top: 5%;
    text-align: center;
    width: 100%;
}
.logo img {
    max-width: 180px;
    height: auto;
    filter: drop-shadow(0 0 10px rgba(255,255,255,0.6));
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .button-container {
        gap: 20px;
        padding: 15px 20px;
    }
    .button-55 {
        font-size: 1rem;
        padding: 0.7rem 1.5rem;
    }
    .logo img {
        max-width: 140px;
    }
}
</style>
</head>

<body>
<div class="curtain left"></div>
<div class="curtain right"></div>

<div class="particles">
    @for ($i=0; $i<25; $i++)
        <div class="particle" style="left: {{ rand(0, 100) }}%; animation-duration: {{ rand(8,15) }}s;"></div>
    @endfor
</div>

<div class="logo">
    <img src="assets/img/icons/logo-white.png" alt="Logo" />
</div>

<div class="button-container">
    @if(Auth::user()->level == 4)
        <form action="{{ $option1 }}" method="get">
            <button type="submit" class="button-55">{{ $label1 }}</button>
        </form>
        <form action="{{ $option2 }}" method="get">
            <button type="submit" class="button-55">{{ $label2 }}</button>
        </form>
    @endif
    @if(Auth::user()->level == 2)
        <form action="{{ $option3 }}" method="get">
            <button type="submit" class="button-55">{{ $label3 }}</button>
        </form>
    @endif
    <form action="{{ $option4 }}" method="get">
        <button type="submit" class="button-55">{{ $label4 }}</button>
    </form>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="button-55">{{ $label5 }}</button>
    </form>
</div>
</body>
</html>
