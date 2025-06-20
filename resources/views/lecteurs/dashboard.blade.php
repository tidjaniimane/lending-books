@extends('layouts.lecteur')

@section('styles')
<style>
/* Enhanced Catalogue Carousel Styles with Consistent Color Scheme */
.catalogue-section {
    background: linear-gradient(135deg, #f0f4f8 0%, #e3f2fd 100%);
    padding: 3rem 2rem;
    margin-bottom: 3rem;
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(26, 58, 110, 0.1);
    border: 1px solid #c5cae9;
}

.section-title {
    color: #1a3a6e;
    font-size: 2.5rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 2.5rem;
    font-family: 'Georgia', serif;
    text-shadow: 0 2px 4px rgba(26, 58, 110, 0.1);
}

.catalogue-carousel {
    position: relative;
    overflow: hidden;
    border-radius: 1rem;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    padding: 2rem;
    box-shadow: 0 8px 25px rgba(26, 58, 110, 0.15);
}

.catalogue-slides {
    display: flex;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    gap: 2rem;
}

.catalogue-slide {
    flex: 0 0 calc(33.333% - 1.33rem);
    min-width: 300px;
}

@media (max-width: 1024px) {
    .catalogue-slide {
        flex: 0 0 calc(50% - 1rem);
    }
}

@media (max-width: 640px) {
    .catalogue-slide {
        flex: 0 0 100%;
    }
}

.catalogue-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    height: 100%;
    border: 2px solid #c5cae9;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(26, 58, 110, 0.1);
    position: relative;
    overflow: hidden;
}

.catalogue-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #1a3a6e, #e8b923);
}

.catalogue-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(26, 58, 110, 0.2);
    border-color: #1a3a6e;
}

.catalogue-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    border: 2px solid #f0f4f8;
}

.catalogue-info {
    text-align: center;
}

.catalogue-icon {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a3a6e;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.catalogue-description {
    color: #6b7280;
    font-size: 0.95rem;
    line-height: 1.6;
}

.carousel-control {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: #1a3a6e;
    color: white;
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 1.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(26, 58, 110, 0.3);
    z-index: 10;
}

.carousel-control:hover {
    background: #e8b923;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 6px 20px rgba(232, 185, 35, 0.4);
}

.carousel-control.prev {
    left: -25px;
}

.carousel-control.next {
    right: -25px;
}

.catalogue-dots {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
    margin-top: 2rem;
}

.catalogue-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #c5cae9;
    cursor: pointer;
    transition: all 0.3s ease;
}

.catalogue-dot.active {
    background: #1a3a6e;
    transform: scale(1.3);
}

.catalogue-dot:hover {
    background: #e8b923;
}

/* Enhanced Container and Search Styles */


.search-container {
    background: #f0f4f8;
    padding: 2.5rem;
    border-radius: 1rem;
    margin-bottom: 3rem;
    border: 2px solid #c5cae9;
    box-shadow: 0 6px 20px rgba(26, 58, 110, 0.1);
}

.search-form {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.search-input-container {
    position: relative;
    flex: 1;
    min-width: 300px;
}

.search-input-container input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid #d1d5db;
    border-radius: 0.75rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    color: #1f2937;
}

.search-input-container input:focus {
    outline: none;
    border-color: #1a3a6e;
    box-shadow: 0 0 0 3px rgba(26, 58, 110, 0.1);
}

.search-input-container i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
}

.search-button {
    background: #e8b923;
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(232, 185, 35, 0.3);
}

.search-button:hover {
    background: #d4a400;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(232, 185, 35, 0.4);
}

/* Enhanced Prêts Section */
.prets-section {
    margin-top: 3rem;
}

.prets-title {
    color: #1a3a6e;
    font-size: 2.25rem;
    font-weight: 700;
    margin-bottom: 2rem;
    font-family: 'Georgia', serif;
    text-align: center;
}

.pret-card {
    background: white;
    border: 2px solid #c5cae9;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 6px 20px rgba(26, 58, 110, 0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.pret-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #1a3a6e, #e8b923);
}

.pret-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 35px rgba(26, 58, 110, 0.15);
    border-color: #1a3a6e;
}

.pret-title {
    color: #1a3a6e;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.pret-author {
    color: #6b7280;
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
    font-style: italic;
}

.pret-dates {
    background: #f0f4f8;
    padding: 1.25rem;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    border: 1px solid #c5cae9;
}

.pret-date {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #1f2937;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.pret-date:last-child {
    margin-bottom: 0;
}

.pret-date strong {
    color: #1a3a6e;
    font-weight: 600;
}

.pret-button {
    width: 100%;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: white;
    border: none;
    padding: 0.875rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.pret-button:hover {
    background: linear-gradient(135deg, #16a34a, #15803d);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
}

.no-prets {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem;
    color: #6b7280;
    font-size: 1.125rem;
    background: #f9fafb;
    border-radius: 1rem;
    border: 2px dashed #d1d5db;
}

/* Enhanced Alert Styles */
.alert {
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    margin-bottom: 2rem;
    font-weight: 500;
    border: 2px solid;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    border-color: #22c55e;
}

.alert-error {
    background: #fef2f2;
    color: #dc2626;
    border-color: #ef4444;
}

/* Responsive Design */
@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }
    
    .prets-title {
        font-size: 1.875rem;
    }
    
    .catalogue-section {
        padding: 2rem 1rem;
    }
    
    .container {
        padding: 1.5rem;
        margin: 1rem auto;
    }
    
    .search-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-input-container {
        min-width: 100%;
    }
    
    .carousel-control {
        display: none;
    }
}

/* Loading Animation */
.loading::after {
    content: '';
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 2px solid #1a3a6e;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-left: 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Smooth Transitions */
* {
    transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}
</style>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

<!-- Enhanced Catalogue Carousel -->
<div class="catalogue-section">
    <h2 class="section-title">📖 Découvrez nos catalogues</h2>
    <div class="catalogue-carousel">
        <div class="catalogue-slides" id="catalogueSlides">
           @foreach($categories->whereNull('parent_id') as $category)
    @php
        // Assign relevant library images based on your specific categories
        $categoryImages = [
            'Histoire et géographie' => 'https://images.unsplash.com/photo-1589998059171-988d887df646?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
            'Littérature' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
            'Arts et loisirs' => 'https://images.unsplash.com/photo-1536924940846-227afb31e2a5?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
            'Technologie (sciences appliquées)' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
            'Sciences naturelles et mathématiques' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
            'Langues' => 'https://images.unsplash.com/photo-1541963463532-d68292c34b19?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
            'Sciences sociales' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
            'RELIGION' => 'https://sl.bing.net/eFVg6RD4s8a',
            'Philosophie et psychologie' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
            'Œuvres générales' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
            'default' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'
        ];
        
        // Debugging: Check the exact category name
        // dd($category->nom); // Uncomment this to see the exact category name
        
        $image = $categoryImages[trim($category->nom)] ?? $categoryImages['default'];
    @endphp
    
    <a href="{{ route('lecteurs.category', ['category' => $category->cat_id]) }}" class="catalogue-slide-link">
        <div class="catalogue-slide">
            <div class="catalogue-card">
                <img src="{{ $image }}" alt="{{ $category->nom }}" class="catalogue-image">
                <div class="catalogue-info">
                    <div class="catalogue-icon">{{ $category->nom }}</div>
                    <p class="catalogue-description">{{ $category['description'] ?? 'Explorez cette collection...' }}</p>
                </div>
            </div>
        </div>
    </a>
@endforeach

        </div>
        <button class="carousel-control prev "style="margin-left: 30px" onclick="moveSlide(-1)" aria-label="Précédent">❮</button>
        <button class="carousel-control next"style="margin-right: 30px" onclick="moveSlide(1)" aria-label="Suivant">❯</button>
    </div>
    <div class="catalogue-dots" id="catalogueDots"></div>
</div>

<div class="container">
    <!-- Enhanced Search Section -->
    <div class="search-container">
        <form action="{{ route('search') }}" method="GET" class="search-form">
            <div class="search-input-container">
                <input type="text" name="query" placeholder="Rechercher un livre, un auteur..." required>
                
            </div>
            <button type="submit" class="search-button">🔍 Rechercher</button>
        </form>
    </div>

    <!-- Enhanced Prêts Section -->
    <div class="prets-section">
        <h2 class="prets-title">📚 Mes prêts en cours</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($prets as $pret)
                <div class="pret-card">
                    <h3 class="pret-title">{{ $pret->exemplaire->notice->doc_titre ?? 'N/A' }}</h3>
                    <p class="pret-author">Par {{ $pret->notice ? $pret->notice->doc_auteur : 'Auteur indisponible' }}</p>
                    
                    <div class="pret-dates">
                        <div class="pret-date">
                            <strong>Emprunté le:</strong>
                            <span>{{ \Carbon\Carbon::parse($pret->date_debut)->format('d/m/Y') }}</span>
                        </div>
                        <div class="pret-date">
                            <strong>À rendre le:</strong>
                            <span>{{ \Carbon\Carbon::parse($pret->date_retour)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    
                    <form action="{{ route('demandes.renouveler') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pret_id" value="{{ $pret->pret_id }}">
                        {{-- Add the exp_id directly if available --}}
                        @if(isset($pret->exp_id))
                            <input type="hidden" name="exp_id" value="{{ $pret->exp_id }}">
                        @elseif(isset($pret->exemplaire_id))
                            <input type="hidden" name="exp_id" value="{{ $pret->exemplaire_id }}">
                        @elseif(isset($pret->exemplaire) && isset($pret->exemplaire->exp_id))
                            <input type="hidden" name="exp_id" value="{{ $pret->exemplaire->exp_id }}">
                        @endif
                        
                        <button type="submit" class="pret-button">
                            🔁 Demander le renouvellement
                        </button>
                    </form>
                </div>
            @empty
                <div class="no-prets">
                    <p>🔍 Aucun prêt en cours pour le moment.</p>
                    <p style="margin-top: 0.5rem; font-size: 0.95rem;">Utilisez la recherche ci-dessus pour découvrir de nouveaux livres !</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Enhanced Book Search with improved UX
    document.addEventListener('DOMContentLoaded', function() {
        const bookSearchInput = document.querySelector('input[name="query"]');
        if (bookSearchInput) {
            let searchTimeout;
            
            bookSearchInput.addEventListener('input', function () {
                const query = this.value;
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    const resultsContainer = document.getElementById('bookResults');
                    if (resultsContainer) resultsContainer.innerHTML = '';
                    return;
                }

                this.classList.add('loading');
                
                searchTimeout = setTimeout(() => {
                    fetch(`/NoticeExemplaire/search?q=${encodeURIComponent(query)}`)
                        .then(res => {
                            if (!res.ok) throw new Error('Network response was not ok');
                            return res.json();
                        })
                        .then(books => {
                            let output = '';
                            books.forEach(book => {
                                output += `
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 shadow-sm">
                                            <div class="card-body">
                                                <h5 class="card-title">${book.doc_titre}</h5>
                                                <p class="card-text text-muted">${book.doc_auteur}</p>
                                                <button class="btn btn-primary btn-sm" onclick="openPretModal(${book.id}, '${book.doc_titre}')">
                                                    ➕ Nouveau prêt
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            const resultsContainer = document.getElementById('bookResults');
                            if (resultsContainer) resultsContainer.innerHTML = output;
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            const resultsContainer = document.getElementById('bookResults');
                            if (resultsContainer) {
                                resultsContainer.innerHTML = '<div class="alert alert-error">Erreur lors de la recherche. Veuillez réessayer.</div>';
                            }
                        })
                        .finally(() => {
                            this.classList.remove('loading');
                        });
                }, 300);
            });
        }
    });

    function openPretModal(id, title) {
        const noticeIdInput = document.getElementById('noticeId');
        const bookTitleElement = document.getElementById('bookTitle');
        
        if (noticeIdInput) noticeIdInput.value = id;
        if (bookTitleElement) bookTitleElement.textContent = title;
        
        if (typeof $ !== 'undefined' && $('#pretModal').length) {
            $('#pretModal').modal('show');
        }
    }
    
    // Enhanced Catalogue Carousel with better performance
    class CatalogueCarousel {
        constructor() {
            this.slidesContainer = document.getElementById('catalogueSlides');
            this.dotsContainer = document.getElementById('catalogueDots');
            this.slides = document.querySelectorAll('.catalogue-slide');
            this.currentSlide = 0;
            this.slidesToShow = this.getSlidesToShow();
            this.isAutoPlaying = true;
            this.autoPlayInterval = null;
            this.isTransitioning = false;
            
            this.init();
        }
        
        getSlidesToShow() {
            const width = window.innerWidth;
            if (width <= 640) return 1;
            if (width <= 1024) return 2;
            return 3;
        }
        
        init() {
            if (!this.slidesContainer || !this.dotsContainer || this.slides.length === 0) {
                console.log('Carousel elements not found or no slides available');
                return;
            }
            
            this.createDots();
            this.updateCarousel();
            this.bindEvents();
            this.startAutoPlay();
            
            console.log(`Enhanced carousel initialized with ${this.slides.length} slides, showing ${this.slidesToShow} at a time`);
        }
        
        createDots() {
            this.dotsContainer.innerHTML = '';
            const dotCount = Math.max(1, Math.ceil(this.slides.length / this.slidesToShow));
            
            for (let i = 0; i < dotCount; i++) {
                const dot = document.createElement('div');
                dot.classList.add('catalogue-dot');
                if (i === 0) dot.classList.add('active');
                
                dot.addEventListener('click', () => {
                    if (!this.isTransitioning) this.goToSlide(i);
                });
                
                this.dotsContainer.appendChild(dot);
            }
        }
        
        updateCarousel() {
            if (this.slides.length === 0 || this.isTransitioning) return;
            
            this.isTransitioning = true;
            
            const slideWidth = this.slides[0].offsetWidth + 32; // Include gap
            const translateX = this.currentSlide * slideWidth * this.slidesToShow;
            
            this.slidesContainer.style.transform = `translateX(-${translateX}px)`;
            
            // Update active dot
            const dots = document.querySelectorAll('.catalogue-dot');
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === this.currentSlide);
            });
            
            // Update slide visibility for accessibility
            this.slides.forEach((slide, index) => {
                const isVisible = index >= this.currentSlide * this.slidesToShow && 
                                index < (this.currentSlide + 1) * this.slidesToShow;
                slide.setAttribute('aria-hidden', !isVisible);
                slide.style.opacity = isVisible ? '1' : '0.5';
            });
            
            setTimeout(() => {
                this.isTransitioning = false;
            }, 400);
        }
        
        moveSlide(direction) {
            if (this.isTransitioning) return;
            
            const maxSlide = Math.max(0, Math.ceil(this.slides.length / this.slidesToShow) - 1);
            this.currentSlide += direction;
            
            if (this.currentSlide < 0) {
                this.currentSlide = maxSlide;
            } else if (this.currentSlide > maxSlide) {
                this.currentSlide = 0;
            }
            
            this.updateCarousel();
            this.resetAutoPlay();
        }
        
        goToSlide(slideIndex) {
            if (this.isTransitioning) return;
            
            this.currentSlide = Math.max(0, Math.min(slideIndex, Math.ceil(this.slides.length / this.slidesToShow) - 1));
            this.updateCarousel();
            this.resetAutoPlay();
        }
        
        bindEvents() {
            const prevBtn = document.querySelector('.carousel-control.prev');
            const nextBtn = document.querySelector('.carousel-control.next');
            
            if (prevBtn) prevBtn.addEventListener('click', () => this.moveSlide(-1));
            if (nextBtn) nextBtn.addEventListener('click', () => this.moveSlide(1));
            
            // Pause/resume auto-play on hover
            if (this.slidesContainer) {
                this.slidesContainer.addEventListener('mouseenter', () => this.pauseAutoPlay());
                this.slidesContainer.addEventListener('mouseleave', () => this.resumeAutoPlay());
            }
            
            // Handle window resize with debouncing
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    const newSlidesToShow = this.getSlidesToShow();
                    if (newSlidesToShow !== this.slidesToShow) {
                        this.slidesToShow = newSlidesToShow;
                        this.currentSlide = 0;
                        this.createDots();
                        this.updateCarousel();
                    }
                }, 250);
            });
            
            // Enhanced touch support
            this.addTouchSupport();
            
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (document.activeElement.closest('.catalogue-carousel')) {
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        this.moveSlide(-1);
                    }
                    if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        this.moveSlide(1);
                    }
                }
            });
        }
        
        addTouchSupport() {
            let startX = 0;
            let startY = 0;
            let isDragging = false;
            
            this.slidesContainer.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                isDragging = true;
                this.pauseAutoPlay();
            }, { passive: true });
            
            this.slidesContainer.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                
                const currentX = e.touches[0].clientX;
                const currentY = e.touches[0].clientY;
                const diffX = Math.abs(currentX - startX);
                const diffY = Math.abs(currentY - startY);
                
                // If horizontal swipe is more pronounced, prevent default scrolling
                if (diffX > diffY && diffX > 10) {
                    e.preventDefault();
                }
            }, { passive: false });
            
            this.slidesContainer.addEventListener('touchend', (e) => {
                if (!isDragging) return;
                
                const endX = e.changedTouches[0].clientX;
                const endY = e.changedTouches[0].clientY;
                const diffX = startX - endX;
                const diffY = Math.abs(startY - endY);
                
                isDragging = false;
                this.resumeAutoPlay();
                
                // Only trigger swipe if horizontal movement is greater than vertical
                if (Math.abs(diffX) > 50 && Math.abs(diffX) > diffY) {
                    if (diffX > 0) {
                        this.moveSlide(1); // Swipe left
                    } else {
                        this.moveSlide(-1); // Swipe right
                    }
                }
            }, { passive: true });
        }
        
        startAutoPlay() {
            if (this.slides.length <= this.slidesToShow) {
                // Hide navigation if not enough slides
                document.querySelectorAll('.carousel-control').forEach(btn => btn.style.display = 'none');
                this.dotsContainer.style.display = 'none';
                return;
            }
            
            this.autoPlayInterval = setInterval(() => {
                if (this.isAutoPlaying && !this.isTransitioning) {
                    this.moveSlide(1);
                }
            }, 6000); // Increased interval for better UX
        }
        
        pauseAutoPlay() {
            this.isAutoPlaying = false;
        }
        
        resumeAutoPlay() {
            this.isAutoPlaying = true;
        }
        
        resetAutoPlay() {
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
                this.startAutoPlay();
            }
        }
        
        destroy() {
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
            }
        }
    }
    
    // Initialize carousel when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        window.catalogueCarousel = new CatalogueCarousel();
        
        // Global function for backward compatibility
        window.moveSlide = function(direction) {
            if (window.catalogueCarousel && !window.catalogueCarousel.isTransitioning) {
                window.catalogueCarousel.moveSlide(direction);
            }
        };
    });
    
    // Enhanced form handling with better UX
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '⏳ Traitement en cours...';
                    
                    // Re-enable after reasonable timeout to prevent permanent disable
                    setTimeout(() => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    }, 3000);
                }
            });
        });
    });
    
    // Enhanced accessibility features
    document.addEventListener('DOMContentLoaded', function() {
        // Add skip links for better accessibility
        const skipLink = document.createElement('a');
        skipLink.href = '#main-content';
        skipLink.textContent = 'Passer au contenu principal';
        skipLink.className = 'sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 bg-blue-600 text-white p-2 z-50';
        document.body.insertBefore(skipLink, document.body.firstChild);
        
        // Add main content ID if not present
        const container = document.querySelector('.container');
        if (container && !container.id) {
            container.id = 'main-content';
        }
        
        // Improve focus management for carousel
        const carouselSlides = document.querySelectorAll('.catalogue-slide');
        carouselSlides.forEach((slide, index) => {
            slide.setAttribute('tabindex', '0');
            slide.setAttribute('role', 'group');
            slide.setAttribute('aria-label', `Catalogue ${index + 1} sur ${carouselSlides.length}`);
        });
        
        // Add loading states for better UX
        const searchForm = document.querySelector('.search-form');
        if (searchForm) {
            searchForm.setAttribute('aria-live', 'polite');
            searchForm.setAttribute('aria-label', 'Formulaire de recherche de livres');
        }
    });
    
    // Performance optimization: Intersection Observer for carousel
    if ('IntersectionObserver' in window) {
        const carouselObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Carousel is visible, ensure it's active
                    if (window.catalogueCarousel && !window.catalogueCarousel.isAutoPlaying) {
                        window.catalogueCarousel.resumeAutoPlay();
                    }
                } else {
                    // Carousel is not visible, pause it for performance
                    if (window.catalogueCarousel) {
                        window.catalogueCarousel.pauseAutoPlay();
                    }
                }
            });
        }, {
            threshold: 0.1
        });
        
        const catalogueSection = document.querySelector('.catalogue-section');
        if (catalogueSection) {
            carouselObserver.observe(catalogueSection);
        }
    }
    
    // Add smooth scrolling for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Enhanced error handling and user feedback
    window.addEventListener('error', function(e) {
        console.error('JavaScript Error:', e.error);
        // Could add user-friendly error notification here
    });
    
    // Add progressive enhancement for older browsers
    function addFallbacks() {
        // CSS Grid fallback
        if (!CSS.supports('display', 'grid')) {
            const gridContainers = document.querySelectorAll('.grid');
            gridContainers.forEach(container => {
                container.style.display = 'flex';
                container.style.flexWrap = 'wrap';
                container.style.gap = '1.5rem';
            });
        }
        
        // Backdrop-filter fallback
        if (!CSS.supports('backdrop-filter', 'blur(10px)')) {
            const blurElements = document.querySelectorAll('[style*="backdrop-filter"]');
            blurElements.forEach(element => {
                element.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
            });
        }
    }
    
    // Run fallbacks after DOM is loaded
    document.addEventListener('DOMContentLoaded', addFallbacks);
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (window.catalogueCarousel) {
            window.catalogueCarousel.destroy();
        }
    });
</script>
@endsection