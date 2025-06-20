@extends('layouts.app')

@section('title', 'Liste des prêts employés')

@section('content')
<main class="container mx-auto px-4 py-10 bg-white/90 rounded-xl shadow-lg backdrop-blur-sm space-y-10">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-serif font-bold text-[#1a3a6e]">📋 Liste des Prêts Employés</h2>
        <a href="{{ route('dashboard.create') }}" 
           class="bg-[#e8b923] hover:bg-[#d4a400] text-white px-5 py-2.5 rounded-md flex items-center space-x-2 shadow-custom transition-all duration-300">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span class="text-lg font-semibold">Nouveau prêt</span>
        </a>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-x-auto border border-gray-300">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-[#1a3a6e]">
                <tr>
                    @foreach(['#', 'Nom', 'Prénom', 'Document', 'ID Ex.', 'Poste', 'Téléphone', 'Créé le', 'Action'] as $head)
                        <th class="px-6 py-4 text-center text-sm font-semibold text-white uppercase tracking-wider select-none">
                            {{ $head }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300">
                @php
                    $filteredPrets = $prets->filter(fn($p) => in_array($p->statut, ['en_cours', 'en_retard']));
                @endphp

                @forelse ($filteredPrets as $pret)
                    <tr class="text-center hover:bg-[#e3f2fd] transition-colors duration-150 {{ $pret->statut === 'en_retard' ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $pret->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $pret->nom }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $pret->prenom }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $pret->doc_titre }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $pret->exp_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $pret->post }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $pret->num_tel ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ \Carbon\Carbon::parse($pret->created_at)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <form action="{{ route('dashboard.return', $pret->id) }}" method="POST" onsubmit="return confirm('Confirmer le retour ?');">
    @csrf
    @method('PUT')
    <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
        ✅ Retour
    </button>
</form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-6 text-center text-gray-500 italic">Aucun prêt en cours ou en retard.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-300 bg-[#f0f4f8] rounded-b-xl">
            {{ $prets->links() }}
        </div>
    </div>
</main>
@endsection
