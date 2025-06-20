@extends('layouts.app')

@section('title', 'Gestion des prêts')

@section('content')
<main class="container mx-auto px-4 py-10 bg-white/90 rounded-xl shadow-lg backdrop-blur-sm space-y-10">
    <!-- Header and Create Button -->
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-serif font-bold text-[#1a3a6e]">Gestion des prêts</h2>
        <div class="flex gap-4">
            <a href="{{ route('dashboard.create') }}" 
               class="bg-[#1a3a6e] hover:bg-[#122c4e] text-white px-5 py-2.5 rounded-md flex items-center space-x-2 shadow-custom transition-all duration-300">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span class="text-lg font-semibold">Nouveau prêt</span>
            </a>
            <a href="{{ route('pret_employers.show') }}" 
               class="bg-[#1a3a6e] hover:bg-[#122c4e] text-white px-5 py-2.5 rounded-md flex items-center space-x-2 shadow-custom transition-all duration-300">
                <i data-lucide="eye" class="w-5 h-5"></i>
                <span class="text-lg font-semibold">Voir pret employer</span>
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-[#f0f4f8] p-6 rounded-xl shadow-md border border-[#c5cae9]">
        <form method="GET" action="{{ route('prets.index') }}" class="flex flex-col md:flex-row md:items-center gap-5">
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

    <button type="submit" 
        class="bg-[#e8b923] hover:bg-[#d4a400] px-6 py-3 rounded-lg font-semibold transition-colors duration-200 text-white shadow-sm">
        Rechercher
    </button>
</form>

    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-x-auto border border-gray-300">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-[#1a3a6e]">
                <tr>
                    @foreach(['ID', 'Utilisateur', 'Livre', 'Date d\'emprunt', 'Date de retour', 'Statut', 'Actions'] as $head)
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider select-none">{{ $head }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300">
                @forelse ($prets as $item)
                    <tr class="hover:bg-[#e3f2fd] transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">L-{{ $item->lecteur->lec_id ?? 'null'}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->lecteur->lec_nom ?? 'Lecteur missing'}} {{ $item->lecteur->lec_prenom ?? '-'}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->exemplaire->notice->doc_titre ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ \Carbon\Carbon::parse($item->pret_date)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->date_retour->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $statuColors = [
                                    'En cours' => 'bg-yellow-100 text-yellow-800',
                                    'En retard' => 'bg-red-100 text-red-800',
                                    'Retourné' => 'bg-green-100 text-green-800',
                                ];
                                $colorClass = $statuColors[$item->statut] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $colorClass }}">
                                {{ $item->statut ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($item->statut !== 'Retourné')
                                <form action="{{ route('prets.return', $item->pret_id) }}" method="POST" onsubmit="return confirm('Confirmer le retour de ce prêt ?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                        ✅ Retourner
                                    </button>
                                </form>
                            @else
                                <span class="text-green-600 font-semibold">Retourné</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500 italic">Aucun prêt trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-300 bg-[#f0f4f8] rounded-b-xl">
            {{ $prets->withQueryString()->links() }}
        </div>
    </div>
</main>
@endsection
