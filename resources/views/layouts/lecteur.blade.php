<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque | @yield('title', 'Lecteur')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" >
    <link href="https://unpkg.com/lucide@latest" >
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/search.js') }}"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                    // Original colors maintained
                    primary: '#4E342E',     // dark brown
                    secondary: '#795548',   // mid brown
                    accent: '#A1887F',      // light brown/gray
                    background: '#f9f5f3',  // soft background
                    card: '#fff8f5',        // card background
                    success: '#388e3c',     // green success badge
                    danger: '#c62828',      // red danger badge
                    
                    // New color scheme for header/footer
                    'header-primary': '#1a3a6e',     // Deep Blue
                    'header-secondary': '#e8b923',   // Golden Yellow
                    'header-secondary-hover': '#d4a400', // Golden Yellow hover
                    'header-bg': '#f0f4f8',          // Light Blue-Gray
                },
                fontFamily: {
                    serif: ['Playfair Display', 'serif'],
                    sans: ['Roboto', 'sans-serif']
                },
                boxShadow: {
                    soft: '0 0 10px rgba(0,0,0,0.1)',
                    medium: '0 2px 8px rgba(0,0,0,0.2)'
                },
                spacing: {
                    '128': '32rem',
                    '144': '36rem',
                }
                
                }
            }
        };
    </script>
    
</head>
<style>
    
</style>
<body x-data="{ mobileMenuOpen: false }" class="bg-background text-gray-900 font-sans">

    <!-- Navbar -->
    <header style="background-color:#1a3a6e;">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <button class="md:hidden text-white" @click="mobileMenuOpen = !mobileMenuOpen">
                <i data-lucide="menu" x-show="!mobileMenuOpen" class="w-6 h-6"></i>
                <i data-lucide="x" x-show="mobileMenuOpen" class="w-6 h-6"></i>
            </button>
            <nav class="hidden md:flex space-x-4">
                @foreach([
                    'lecteurs.dashboard' => ['icon' => 'home', 'label' => 'Accueil'],
                    'lecteur.profile' => ['icon' => 'user', 'label' => 'Profil'],
                    'lecteur.history' => ['icon' => 'clock', 'label' => 'Historique']
                ] as $route => $item)
                    <a href="{{ route($route) }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-white hover:bg-white/20 transition {{ request()->routeIs($route) ? 'bg-white/30' : '' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                        <span class="text-sm">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
            <div class="flex items-center space-x-3">
                <a href="{{ route('logout') }}" >
                    
                    <span>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form >
                        <a class="bg-header-secondary hover:bg-header-secondary-hover text-header-primary font-medium px-4 py-2 rounded-md transition flex items-center space-x-1" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </span>
                </a>
            </div>
        </div>
        <div class="md:hidden transition-all duration-500 ease-in-out overflow-hidden" style="background-color:#1a3a6e;" :class="{ 'max-h-96': mobileMenuOpen, 'max-h-0': !mobileMenuOpen }">
            <nav class="flex flex-col px-6 py-4 space-y-2">
                @foreach([
                    'lecteurs.dashboard' => ['icon' => 'home', 'label' => 'Accueil'],
                    'lecteur.profile' => ['icon' => 'user', 'label' => 'Profil'],
                    'lecteur.history' => ['icon' => 'clock', 'label' => 'Historique']
                ] as $route => $item)
                    <a href="{{ route($route) }}" class="flex items-center space-x-2 py-2 px-3 rounded text-white hover:bg-white/20 {{ request()->routeIs($route) ? 'bg-white/30' : '' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                        <span class="text-sm">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
    </header>

    

    <!-- Main Content -->
    <main style="backgreound-color:rgb(245, 245, 220); padding: 2rem;">
        @yield('content') 
        @yield('scripts')
        @yield('modals')
        @yield('styles')
    </main>

    <!-- Footer -->
    <footer style="background-color:#1a3a6e; color: white; padding: 1.5rem 0; font-size: 0.875rem;">
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: space-between; gap: 1rem;">
            <p>© 2025 Your Company. All rights reserved.</p>
            <div>
                <a href="#" style="color: #e8b923; margin: 0 1rem; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='#d4a400'" onmouseout="this.style.color='#e8b923'">Privacy Policy</a>
                <a href="#" style="color:rgb(214, 154, 34); margin: 0 1rem; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='#d4a400'" onmouseout="this.style.color='#e8b923'">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</body>
</html>