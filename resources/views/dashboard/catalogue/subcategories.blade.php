@extends('layouts.app')

@section('content')
<main class="container mx-auto px-6 py-12 bg-white/90 rounded-2xl shadow-lg backdrop-blur-md max-w-4xl">
    <!-- Page Heading -->
    <h1 class="text-4xl font-serif font-bold text-[#e8b923] text-center mb-12 tracking-wide drop-shadow-md">
        Sous-catégories de {{ $parentCategory->nom }}
    </h1>

    @if ($subcategories->isEmpty())
        <p class="text-center text-gray-500 italic text-lg">Aucune sous-catégorie disponible.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
            @foreach ($subcategories as $subcategory)
                <a href="{{ route('catalogue.notices', $subcategory->cat_id) }}"
                   class="block px-6 py-4 bg-[#1a3a6e] text-white rounded-2xl border border-transparent hover:bg-[#122c4e] transition-colors font-semibold text-center shadow-md hover:shadow-xl">
                    {{ $subcategory->nom }}
                </a>
            @endforeach
        </div>
    @endif

    <!-- Back Button -->
    <div class="mt-14 text-center">
        <a href="{{ route('catalogue.index')}}"
           class="inline-block px-8 py-3 border-2 border-[#e8b923] text-[#e8b923] rounded-lg hover:bg-[#e8b923] hover:text-white transition font-medium tracking-wide shadow-sm hover:shadow-md">
            ← Retour aux catégories
        </a>
    </div>
</main>
@endsection
