@extends('layouts.app')

@section('title', 'Demandes de prêt')

@section('content')
<main class="container mx-auto px-4 py-10 bg-white/90 rounded-xl shadow-lg backdrop-blur-sm space-y-10">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-serif font-bold text-[#1a3a6e]">Demandes de prêt</h2>
    </div>

    <!-- Flash Messages (optional, if you use them) -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded shadow-md" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded shadow-md" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="bg-[#f0f4f8] p-6 rounded-xl shadow-md border border-[#c5cae9]">
        <form method="GET" action="{{ route('demandes.index') }}" class="flex flex-col md:flex-row md:items-center gap-5">
            <div class="relative flex-1">
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Rechercher une demande..."
                    class="w-full pl-12 pr-4 py-3 border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1a3a6e] focus:border-transparent text-gray-800 placeholder-gray-600"
                >
                <i data-lucide="search-check" class="absolute left-4 top-3.5 text-gray-500 w-5 h-5"></i>
            </div>

            

           <button type="submit" 
                class="bg-[#e8b923] hover:bg-[#d4a400] px-6 py-3 rounded-lg font-semibold transition-colors duration-200 text-white shadow-sm">
                Rechercher
            </button>
        </form>
    </div>

    <!-- Demandes Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-x-auto border border-gray-300">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-[#1a3a6e]">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider select-none">ID</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider select-none">Lecteur</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider select-none">Notice demandée</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider select-none">Cote de l'exemplaire</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider select-none">Date</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider select-none">Statut</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider select-none">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300">
                @forelse ($demandes as $demande)
                <tr class="hover:bg-[#e3f2fd] transition-colors duration-150">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">REQ-{{ $demande->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $demande->lecteur->lec_nom ?? '-' }} {{ $demande->lecteur->lec_prenom ?? '' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">
    {{ $demande->noticeExemplaire && $demande->noticeExemplaire->notice ? $demande->noticeExemplaire->notice->doc_titre : 'Aucun titre disponible' }}
                    </td>
                    
                    <td class="px-6 py-4 text-sm text-gray-800">
                        {{ $demande->noticeExemplaire && $demande->noticeExemplaire->notice ?  $demande->noticeExemplaire->exp_cote : '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">
                        {{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $demande->statut === 'En attente' ? 'bg-yellow-100 text-yellow-800' : 
                               ($demande->statut === 'Acceptée' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                            {{ $demande->statut }}
                        </span>
                    </td>
                    <td class="px-6 py-4 flex flex-wrap gap-2 text-sm font-medium">
                        <form action="{{ route('demandes.updateStatut', $demande->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="statut" value="Acceptée">
                            <button type="submit" class="bg-[#1a3a6e] hover:bg-[#122c4e] text-white px-4 py-2 rounded shadow">Accepter</button>
                        </form>
                        <form action="{{ route('demandes.updateStatut', $demande->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="statut" value="Refusée">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow">Refuser</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500 italic">Aucune demande de prêt trouvée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection
