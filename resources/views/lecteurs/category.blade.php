@extends('layouts.lecteur')

@section('content')
<main class="container mx-auto px-4 py-10 bg-white/90 rounded-xl shadow-lg backdrop-blur-sm">
    <!-- Page Heading -->
    <h2 class="text-3xl font-serif font-bold text-[#e8b923] text-center mb-8">
        Livres dans la sous-catégorie <strong>{{ $category->nom }}</strong>
    </h2>

    <!-- Search Input -->
    <div class="max-w-2xl mx-auto mb-8">
        <input
            id="searchInput"
            type="text"
            placeholder="Rechercher un livre par titre ou auteur..."
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#e8b923]"
            onkeyup="filterBooks()"
        >
    </div>

    <!-- Book List -->
    <ul id="booksList" class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-3xl mx-auto">
        @foreach ($notices as $notice)
            <li class="book-item bg-[#e8f0fe] px-6 py-4 rounded-xl border border-[#c5cae9] hover:bg-[#d7e3fc] transition">
                <h3 class="font-semibold text-lg text-[#1a3a6e]">{{ $notice->doc_titre }}</h3>
                <p class="text-sm text-[#1a3a6e]">par {{ $notice->doc_auteur }}</p>
            </li>
        @endforeach
    </ul>

    <!-- Empty State -->
    <p id="noResults" class="hidden text-center text-gray-500 italic mt-4">Aucun livre trouvé.</p>

    <!-- Back Button -->
    <div class="mt-10 text-center">
        <a href="{{ route('lecteurs.dashboard') }}"
           class="inline-block px-6 py-2 border border-[#1a3a6e] text-[#1a3a6e] rounded-md hover:bg-[#1a3a6e] hover:text-white transition font-medium">
            ← Retour aux sous-catégories
        </a>
    </div>
</main>

<script>
function filterBooks() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const books = document.querySelectorAll('.book-item');
    let visibleCount = 0;

    books.forEach(book => {
        const title = book.querySelector('h3').textContent.toLowerCase();
        const author = book.querySelector('p').textContent.toLowerCase();

        if (title.includes(input) || author.includes(input)) {
            book.style.display = '';
            visibleCount++;
        } else {
            book.style.display = 'none';
        }
    });

    // Show "no results" message if no visible books
    document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
}
</script>
@endsection
