@extends('layouts.app')

@section('content')
<main class="container mx-auto px-4 py-10 bg-white/90 rounded-xl shadow-lg backdrop-blur-sm space-y-10 relative">
    <!-- Page Heading -->
    <h2 class="text-3xl font-serif font-bold text-[#c68600] text-center">
        Catalogue des Catégories
    </h2>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('catalogue.index') }}" class="max-w-md mx-auto mb-8 relative" autocomplete="off">
        <input
            type="search"
            id="search-input"
            name="search"
            placeholder="Rechercher une catégorie..."
            value="{{ request('search') }}"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#c68600] text-gray-800"
            aria-label="Recherche catégorie"
        />

        <!-- Suggestions dropdown -->
        <ul id="suggestions-list" class="absolute z-50 bg-white border border-gray-300 rounded-lg w-full mt-1 max-h-48 overflow-y-auto hidden shadow-lg">
            {{-- Suggestions will be inserted here by JS --}}
        </ul>

        <button
            type="submit"
            class="mt-3 w-full bg-[#c68600] hover:bg-[#a57300] text-white py-3 rounded-lg font-semibold transition-colors duration-300"
        >
            Rechercher
        </button>
    </form>

    @if ($categories->isEmpty())
        <p class="text-center text-gray-500 italic mt-10">Aucune catégorie disponible.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach ($categories as $category)
                <div class="bg-white shadow-md rounded-2xl p-6 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <h5 class="text-xl font-semibold text-[#a67c00]">{{ $category->nom }}</h5>
                    <a href="{{ route('catalogue.subcategories', ['cat_id' => $category->cat_id]) }}" 
                       class="mt-4 inline-block px-5 py-2 bg-[#2a4d69] text-white rounded-lg text-sm hover:bg-[#1a3a6e] transition-colors duration-300 text-center">
                        Voir les sous-catégories
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-input');
    const suggestionsList = document.getElementById('suggestions-list');
    let debounceTimer;

    input.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (!query) {
            suggestionsList.classList.add('hidden');
            suggestionsList.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('catalogue.suggestions') }}?term=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        suggestionsList.innerHTML = data.map(item => `
                            <li 
                                class="px-4 py-2 cursor-pointer hover:bg-[#c68600] hover:text-white transition-colors duration-200"
                                role="option"
                            >${item}</li>
                        `).join('');
                        suggestionsList.classList.remove('hidden');

                        // Click event: fill input and hide suggestions
                        suggestionsList.querySelectorAll('li').forEach(li => {
                            li.addEventListener('click', () => {
                                input.value = li.textContent;
                                suggestionsList.classList.add('hidden');
                                suggestionsList.innerHTML = '';
                            });
                        });
                    } else {
                        suggestionsList.classList.add('hidden');
                        suggestionsList.innerHTML = '';
                    }
                });
        }, 300); // debounce delay
    });

    // Hide suggestions if clicking outside
    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !suggestionsList.contains(e.target)) {
            suggestionsList.classList.add('hidden');
        }
    });
});
</script>
@endsection
