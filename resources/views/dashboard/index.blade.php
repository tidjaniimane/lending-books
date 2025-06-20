@extends('layouts.app')

@section('content')
<!-- Include Cairo font from Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@700&display=swap" rel="stylesheet">

<style>
  .hero-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start; /* left align */
    gap: 20px;
    color: white;
    font-family: 'Cairo', sans-serif;
    padding: 40px 20px;
    max-width: 450px;
  }

  .hero-logo {
    width: 160px; /* bigger logo */
    filter: brightness(0) invert(1);
  }

  .title-row {
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    width: 100%;
  }

  .hero-title {
    font-size: 3.2rem;
    line-height: 1.2;
    font-weight: 700;
    padding-left: 10px;
    flex-grow: 1;
    position: relative;
  }

  /* Line behind the title */
  .hero-title::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    transform: translateY(-50%);
    width: 100%;
    height: 7px;
    background-color: rgba(232, 185, 35, 0.5); /* golden semi-transparent */
    z-index: -1;
    border-radius: 3px;
  }

  .decorative-icon {
    width: 40px;
    height: 40px;
    fill: #e8b923;
    filter: drop-shadow(0 0 3px rgba(232, 185, 35, 0.7));
  }
</style>

<div class="hero-container">
  <img 
    src="https://upload.wikimedia.org/wikipedia/commons/8/89/National_Library_of_Algeria%2C_Logo.png" 
    alt="Logo BNA" 
    class="hero-logo" 
  />
  <div class="title-row">
    <h1 class="hero-title">المكتبة الوطنية الجزائرية</h1>
    <!-- Decorative star icon -->
    <svg class="decorative-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
      <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
    </svg>
  </div>
</div>
@endsection