@extends('layouts.app')

@section('title', 'Renouvellements')

@section('content')
<main class="container mx-auto px-4 py-10 bg-white/90 rounded-xl shadow-lg backdrop-blur-sm space-y-10">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-serif font-bold text-[#1a3a6e]">Gestion des renouvellements</h2>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded shadow-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded shadow-sm" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-[#f0f4f8] p-6 rounded-xl shadow-md border border-[#c5cae9]">
        <form method="GET" action="{{ route('renewals') }}" class="flex flex-col md:flex-row md:items-center gap-5">
            <!-- Search Input -->
            <div class="relative flex-1">
                <input 
                    type="text" 
                    name="search"
                    placeholder="Rechercher par ID, nom d'utilisateur ou titre..."
                    class="w-full pl-12 pr-4 py-3 border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1a3a6e] focus:border-transparent text-gray-800 placeholder-gray-600"
                    value="{{ request('search') }}"
                >
                <i data-lucide="search" class="absolute left-4 top-3.5 text-gray-500 w-5 h-5"></i>
            </div>

            <div>
                <button type="submit" 
                class="bg-[#e8b923] hover:bg-[#d4a400] px-6 py-3 rounded-lg font-semibold transition-colors duration-200 text-white shadow-sm">
                Rechercher
            </button>
            </div>
        </form>
    </div>

    <!-- Renewals Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-x-auto border border-gray-300">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-[#1a3a6e] text-white">
                <tr>
                    @foreach(['ID', 'Utilisateur', 'Livre', 'Date d\'emprunt', 'Date de retour', 'Actions'] as $head)
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider select-none">{{ $head }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300">
                @forelse ($renewals as $prets)
                    <tr class="hover:bg-[#e3f2fd] transition-colors duration-150">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">P-{{ $prets->pret_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $prets->lec_nom }} {{ $prets->lec_prenom }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $prets->doc_titre }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ \Carbon\Carbon::parse($prets->date_pret)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ \Carbon\Carbon::parse($prets->date_retour)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            <form method="POST" action="{{ route('renewals.renew', $prets->pret_id) }}" class="inline-block">
                                @csrf
                                <button class="bg-green-100 hover:bg-green-200 text-green-800 px-4 py-2 rounded shadow-sm transition">
                                    Approuver
                                </button>
                            </form>
                            <form method="POST" action="{{ route('renewals.reject', $prets->pret_id) }}" class="inline-block">
                                @csrf
                                <button class="bg-red-100 hover:bg-red-200 text-red-800 px-4 py-2 rounded shadow-sm transition">
                                    Rejeter
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500 italic">Aucune demande de renouvellement en attente.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination if needed -->
        
    </div>
</main>
@endsection
