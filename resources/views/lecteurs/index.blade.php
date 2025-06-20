@extends('layouts.app')

@section('title', 'Lecteurs')

@section('content')
<main class="container mx-auto px-4 py-10 bg-white/90 rounded-xl shadow-lg backdrop-blur-sm space-y-10">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-serif font-bold text-[#1a3a6e]">Gestion des Lecteurs</h2>
        <a href="{{ route('lecteurs.create') }}" 
           class="bg-[#1a3a6e] hover:bg-[#122c4e] text-white px-5 py-3 rounded-lg shadow transition-colors">
            + Nouveau Lecteur
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-[#f0f4f8] p-6 rounded-xl shadow-md border border-[#c5cae9] relative">
        <form method="GET" action="{{ route('lecteurs.index') }}" class="flex flex-col md:flex-row md:items-center gap-5">
            <div class="relative flex-1">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Rechercher par nom, email ou ID..."
                    value="{{ request('search') }}"
                    class="w-full pl-12 pr-4 py-3 border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1a3a6e] focus:border-transparent text-gray-800 placeholder-gray-600"
                >
                <i data-lucide="search" class="absolute left-4 top-3.5 text-gray-500 w-5 h-5"></i>
            </div>
            <button type="submit" 
                class="bg-[#e8b923] hover:bg-[#d4a400] px-6 py-3 rounded-lg font-semibold transition-colors duration-200 text-white shadow-sm">
                Rechercher
            </button>
        </form>
    </div>

    <!-- Lecteurs Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-x-auto border border-gray-300">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-[#1a3a6e] text-white">
                <tr>
                    @foreach(['ID', 'Nom', 'Email', 'Téléphone', 'Actions'] as $head)
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider select-none">{{ $head }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300">
                @forelse ($lecteurs as $lecteur)

                    <tr class="hover:bg-[#e3f2fd] transition-colors duration-150">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $lecteur->lec_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $lecteur->lec_nom }} {{ $lecteur->lec_prenom }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $lecteur->lec_email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $lecteur->lec_tel ?? 'NULL' }}</td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <button
                                onclick="showLecteurDetails(this)"
                                data-nom="{{ $lecteur->lec_nom }}"
                                data-prenom="{{ $lecteur->lec_prenom ?? 'NULL' }}"
                                data-adresse="{{ $lecteur->lec_adress ?? 'NULL' }}"
                                data-telephone="{{ $lecteur->lec_tel ?? 'NULL' }}"
                                data-email="{{ $lecteur->lec_email ?? 'NULL' }}"
                                class="bg-[#1a3a6e] hover:bg-[#122c4e] text-white px-4 py-2 rounded shadow transition-colors"
                            >
                                Détails
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-gray-500 italic">Aucun lecteur trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
<!-- Modal -->
<div id="lecteurDetailModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 space-y-4 relative">
        <button onclick="closeLecteurModal()" class="absolute top-2 right-4 text-gray-600 text-xl font-bold">&times;</button>
        
        <h3 class="text-xl font-semibold text-[#1a3a6e]">Détails du Lecteur</h3>

        <div class="space-y-2 text-sm text-gray-700">
            <p><strong>Nom:</strong> <span id="modalNom"></span></p>
            <p><strong>Prénom:</strong> <span id="modalPrenom"></span></p>
            <p><strong>Adresse:</strong> <span id="modalAdresse"></span></p>
            <p><strong>Téléphone:</strong> <span id="modalTelephone"></span></p>
            <p><strong>Email:</strong> <span id="modalEmail"></span></p>
        </div>
    </div>
</div>

<script>
    function showLecteurDetails(button) {
        document.getElementById('modalNom').textContent = button.getAttribute('data-nom');
        document.getElementById('modalPrenom').textContent = button.getAttribute('data-prenom');
        document.getElementById('modalAdresse').textContent = button.getAttribute('data-adresse');
        document.getElementById('modalTelephone').textContent = button.getAttribute('data-telephone');
        document.getElementById('modalEmail').textContent = button.getAttribute('data-email');

        document.getElementById('lecteurDetailModal').classList.remove('hidden');
    }

    function closeLecteurModal() {
        document.getElementById('lecteurDetailModal').classList.add('hidden');
    }
</script>
@endsection
