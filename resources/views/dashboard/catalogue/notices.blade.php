@extends('layouts.app')

@section('content')
<main class="container mx-auto px-4 py-10 bg-white/90 rounded-xl shadow-lg backdrop-blur-sm">
    <!-- Page Heading -->
    <h2 class="text-3xl font-serif font-bold text-[#e8b923] text-center mb-8">
        Livres dans la sous-catégorie <strong>{{ $subcategory->nom }}</strong>
    </h2>

    <!-- Empty State -->
    @if ($notices->isEmpty())
        <p class="text-center text-gray-500 italic">Aucun livre trouvé dans cette sous-catégorie.</p>
    @else
        <!-- Book List -->
        <ul class="space-y-4 max-w-2xl mx-auto">
            @foreach ($notices as $notice)
                <li class="bg-[#e8f0fe] px-6 py-4 rounded-xl border border-[#c5cae9] hover:bg-[#d7e3fc] transition">
                    <div>
                        <h3 class="font-semibold text-lg text-[#1a3a6e]">{{ $notice->doc_titre }}</h3>
                        <p class="text-sm text-[#1a3a6e]">par {{ $notice->doc_auteur }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <!-- Back Button -->
    <div class="mt-10 text-center">
        <a href="{{ route('catalogue.subcategories', $subcategory->parent_id) }}"
           class="inline-block px-6 py-2 border border-[#1a3a6e] text-[#1a3a6e] rounded-md hover:bg-[#1a3a6e] hover:text-white transition font-medium">
            ← Retour aux sous-catégories
        </a>
    </div>
</main>
@endsection
