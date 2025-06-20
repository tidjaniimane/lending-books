@extends('layouts.app')

@section('title', 'Ajouter un Prêt Employé')

@section('content')
<main class="container mx-auto px-6 py-8 bg-white/90 rounded-xl shadow-lg backdrop-blur-sm" style ="width: 1000px;">
    <div class="bg-white rounded-xl shadow-md p-8 border border-gray-200">
        <h2 class="text-2xl font-serif font-bold text-[#1a3a6e] mb-5">Ajouter un Prêt pour un Employé</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-3 py-2 rounded mb-5 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('pret_employers.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="nom" class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                <input type="text" name="nom" id="nom" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a3a6e] focus:border-transparent" required>
            </div>

            <div>
                <label for="prenom" class="block text-sm font-semibold text-gray-700 mb-1">Prénom</label>
                <input type="text" name="prenom" id="prenom" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a3a6e] focus:border-transparent" required>
            </div>

            <div>
                <label for="doc_titre" class="block text-sm font-semibold text-gray-700 mb-1">Titre du Document</label>
                <input type="text" name="doc_titre" id="doc_titre" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a3a6e] focus:border-transparent" required>
            </div>

            <div>
                <label for="exp_id" class="block text-sm font-semibold text-gray-700 mb-1">ID de l’Exemplaire</label>
                <input type="number" name="exp_id" id="exp_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a3a6e] focus:border-transparent" required>
            </div>

            <div>
                <label for="post" class="block text-sm font-semibold text-gray-700 mb-1">Poste</label>
                <input type="text" name="post" id="post" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a3a6e] focus:border-transparent" required>
            </div>

            <div>
                <label for="num_tel" class="block text-sm font-semibold text-gray-700 mb-1">Numéro de Téléphone</label>
                <input type="text" name="num_tel" id="num_tel" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a3a6e] focus:border-transparent">
            </div>

            <button type="submit" class="bg-[#e8b923] hover:bg-[#d4a400] text-white font-semibold px-5 py-2 rounded-md shadow transition duration-200 w-full">
                Enregistrer
            </button>
        </form>
    </div>
</main>
@endsection
