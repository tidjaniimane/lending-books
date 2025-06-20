<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bibliothèque Nationale d'Algérie | Tableau de bord</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
    primary: '#1a3a6e',         // deep blue
    secondary: '#2c5282',       // slightly lighter blue
    accent: '#e8b923',          // golden accent
    background: '#f0f4f8',      // soft bluish-gray background
    card: '#ffffff',            // white cards
    'primary-light': '#3b5a99', // lighter blue
    'primary-dark': '#122c4e'   // darker navy blue
},
fontFamily: {
    serif: ['Playfair Display', 'serif'],
    sans: ['Roboto', 'sans-serif'],
    arabic: ['Tajawal', 'sans-serif']
},
boxShadow: {
    'custom': '0 4px 20px rgba(0, 0, 0, 0.08)'
}

                }
            }
        };
    </script>
    <style>
         /* Base font sizes */
  body {
    font-size: 1.1rem; /* Slightly larger base size (default is 1rem = 16px) */
    line-height: 1.6; /* Better readability */
  }

  /* Navigation links - increased size and spacing */
  .nav-link span {
    font-size: 1rem; /* Increased from 0.95rem */
    letter-spacing: 0.5px;
  }

  /* Dashboard cards and content */
  .card-title {
    font-size: 1.35rem; /* Slightly larger for headers */
    font-weight: 600;
  }

  /* Table text - more readable */
  table {
    font-size: 1.05rem;
  }
  th {
    font-size: 1.1rem;
  }

  /* Buttons - more prominent */
  button, .btn {
    font-size: 1.05rem;
    font-weight: 500;
  }

  /* Footer text - more visible */
  footer {
    font-size: 1.05rem; /* Increased from 1rem */
  }
  footer p {
    margin-bottom: 0.5rem;
  }

  /* Hero section text - more impactful */
  .hero-content h1 {
    font-size: 2.75rem; /* Larger heading */
    line-height: 1.2;
    margin-bottom: 1rem;
  }
  .hero-content p {
    font-size: 1.35rem; /* Larger subheading */
  }

  /* Arabic text specific - optimized for readability */
  .arabic-text {
    font-size: 1.15rem; /* Slightly larger for Arabic script */
    line-height: 1.8; /* Arabic text often needs more line spacing */
  }

  /* Mobile menu text */
  @media (max-width: 767px) {
    .mobile-menu-item {
      font-size: 1.1rem;
      padding: 0.75rem 1rem;
    }
  }

  /* Existing styles remain the same */
  .arabic-text {
    font-family: 'Tajawal', sans-serif;
    direction: rtl;
  }
  .arabic-text {
    font-family: 'Tajawal', sans-serif;
    direction: rtl;
}

.hero-gradient {
    background: linear-gradient(135deg, #1a3a6e 0%, #2c5282 100%);
}

.nav-link {
    position: relative;
}

.nav-link:after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: #e8b923; /* Golden underline */
    transition: width 0.3s ease;
}

.nav-link:hover:after {
    width: 100%;
}

.active-nav:after {
    width: 100%;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(26, 58, 110, 0.15); /* Adjusted for blue tone */
}

.accent-highlight {
    transition: all 0.3s ease;
}

.accent-highlight:hover {
    background-color: #dbe5f1; /* Light blue highlight */
    border-color:rgb(151, 185, 227); /* Deep blue border */
}

body {
    background: linear-gradient(rgba(153, 166, 194, 0.7), rgba(26, 58, 110, 0.7)),
                url('https://i.pinimg.com/originals/eb/b5/0a/ebb50a0d74954cfaa0c3e27f928eb500.jpg') no-repeat center center;
    background-size: cover;
    background-attachment: fixed;
}

.library-hero {
    height: 70vh;
    min-height: 500px;
    background-image: url('https://images.unsplash.com/photo-1589998059171-988d887df646?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.library-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(72, 91, 122, 0.7); /* Blue overlay */
}

.hero-content {
    position: relative;
    z-index: 1;
    text-align: center;
    color: white;
    max-width: 1200px;
    padding: 2rem;
}

.logo-white {
    filter: brightness(0) invert(1);
    max-width: 300px;
    margin: 0 auto;
}

    </style>
</head>
<body x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="bg-background text-gray-900 font-sans min-h-screen flex flex-col">
   
    <!-- Main Navigation -->
    <header class="hero-gradient text-white ">
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center big-text">
                <button class="md:hidden py-4 px-2" @click="mobileMenuOpen = !mobileMenuOpen">
                    <i data-lucide="menu" x-show="!mobileMenuOpen" class="w-6 h-6"></i>
                    <i data-lucide="x" x-show="mobileMenuOpen" class="w-6 h-6"></i>
                </button>
                
                <nav class="hidden md:flex space-x-1 ">
                    @foreach([
                        'dashboard' => ['icon' => 'layout-dashboard', 'label' => 'Tableau de bord'],
                        'loans' => ['icon' => 'book-check', 'label' => 'Prêts'],
                        'requests' => ['icon' => 'calendar-clock', 'label' => 'Demandes'],
                        'returns' => ['icon' => 'rotate-ccw', 'label' => 'Retours'],
                        'renewals' => ['icon' => 'refresh-cw', 'label' => 'Renouvellements'],
                        'stats' => ['icon' => 'bar-chart-2', 'label' => 'Statistiques'],
                        'users' => ['icon' => 'users', 'label' => 'Usagers'],
                        'catalogue.index' => ['icon' => 'library', 'label' => 'Catalogue']
                    ] as $route => $item)
                     @if(Auth::check() && Auth::user()->is_admin == 2 && in_array($route, ['stats', 'users']))
                   @continue
                   @endif
                        <a href="{{ route($route) }}" 
                           class="nav-link flex items-center space-x-2 px-4 py-3 transition {{ request()->routeIs($route) ? 'active-nav bg-primary-dark' : 'hover:bg-primary-dark' }}">
                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                            <span class="text-sm font-medium">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
                
                <div class="hidden md:flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                <a href="{{ route('logout') }}" class="bg-header-secondary hover:bg-header-secondary-hover text-header-primary font-medium px-4 py-2 rounded-md transition flex items-center space-x-1">
                    
                    <span>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="bg-header-secondary hover:bg-header-secondary-hover text-header-primary font-medium px-4 py-2 rounded-md transition flex items-center space-x-1" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </span>
                </a>
            </div>
                    <button class="p-2 rounded-full hover:bg-primary-dark">
                        <i data-lucide="settings" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" class="md:hidden bg-primary-dark">
              <div class="px-2 pt-2 pb-3 space-y-1">
    @foreach([
        'dashboard' => ['icon' => 'layout-dashboard', 'label' => 'Tableau de bord'],
        'loans' => ['icon' => 'book-check', 'label' => 'Prêts'],
        'requests' => ['icon' => 'calendar-clock', 'label' => 'Demandes'],
        'returns' => ['icon' => 'rotate-ccw', 'label' => 'Retours'],
        'renewals' => ['icon' => 'refresh-cw', 'label' => 'Renouvellements'],
    ] as $route => $item)
        @if(Auth::check() && Auth::user()->is_admin == 2 && in_array($route, ['stats', 'users']))
            @continue
        @endif

        <a href="{{ route($route) }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
            <i data-lucide="{{ $item['icon'] }}" class="inline-block w-5 h-5 mr-2"></i>
            {{ $item['label'] }}
        </a>
    @endforeach
</div>


            </div>
        </div>

        </div>
    </header>

    
    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        @yield('content')
    </main>
@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

    
    <!-- Footer -->
     
    <footer style="background-color:#1a3a6e; color: white; padding: 1.5rem 0; font-size: 0.875rem;">
        
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: space-between; gap: 1rem;">
            <p>© 2025 Your Company. All rights reserved.</p>
            <p>المكتبة الوطنيية الجزائرية</p>
            <div>
                <a href="#" style="color: #e8b923; margin: 0 1rem; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='#d4a400'" onmouseout="this.style.color='#e8b923'">Privacy Policy</a>
                <a href="#" style="color:rgb(214, 154, 34); margin: 0 1rem; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='#d4a400'" onmouseout="this.style.color='#e8b923'">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>