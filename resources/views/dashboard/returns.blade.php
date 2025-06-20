@extends('layouts.app')

@section('title', 'Retours de livres')
<style>
    .printer-icon {
        display: inline-block;
        width: 16px;
        height: 16px;
        position: relative;
    }
    
    .printer-icon::before,
    .printer-icon::after {
        content: "";
        position: absolute;
    }
    
    /* Printer body */
    .printer-icon::before {
        width: 12px;
        height: 10px;
        border: 2px solid white;
        border-radius: 2px;
        top: 0;
        left: 2px;
    }
    
    /* Printer top */
    .printer-icon::after {
        width: 8px;
        height: 2px;
        background: white;
        top: -4px;
        left: 4px;
    }
    
    /* Optional: Add a small line for the paper output */
    .printer-icon {
        overflow: hidden;
    }
    
    .printer-icon::after {
        box-shadow: 
            0 8px 0 white, /* Paper coming out */
            0 12px 0 white; /* Another line of paper */
    }
        .css-printer-icon {
        display: inline-block;
        width: 16px;
        height: 16px;
        position: relative;
    }
    
    /* Printer body */
    .css-printer-icon::before {
        content: "";
        position: absolute;
        width: 14px;
        height: 10px;
        border: 2px solid currentColor;
        border-radius: 2px 2px 0 0;
        top: 4px;
        left: 0;
    }
    
    /* Printer top */
    .css-printer-icon::after {
        content: "";
        position: absolute;
        width: 10px;
        height: 4px;
        border: 2px solid currentColor;
        border-bottom: none;
        border-radius: 2px 2px 0 0;
        top: 0;
        left: 2px;
    }
    
    /* Paper coming out */
    .css-printer-icon span {
        position: absolute;
        width: 8px;
        height: 2px;
        background: currentColor;
        top: 14px;
        left: 4px;
    }
</style>

@section('content')
<main class="container mx-auto px-4 py-10 bg-white/90 rounded-xl shadow-lg backdrop-blur-sm space-y-10">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-serif font-bold text-[#1a3a6e]">Gestion des Retours</h2>
    </div>

    <!-- Search & Filter -->
    <div class="bg-[#f0f4f8] p-6 rounded-xl shadow-md border border-[#c5cae9]">
        <form method="GET" action="{{ route('returns') }}" class="flex flex-col md:flex-row md:items-center gap-5">

            <div class="relative flex-1">
                <input 
                    type="text" 
                    name="search"
                    placeholder="Rechercher par lecteur ou titre..."
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

    <!-- Returns Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-x-auto border border-gray-300">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-[#1a3a6e]">
                <tr>
                    @foreach(['ID Prêt', 'Lecteur', 'Livre', 'Date retour prévue', 'Statut', 'Actions'] as $head)
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider select-none">{{ $head }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300">
                @forelse($retards as $pret)
                    <tr class="hover:bg-[#e3f2fd] transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">P-{{ $pret->pret_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                            {{ $pret->lecteur->lec_nom ?? '-' }} {{ $pret->lecteur->lec_prenom ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                            {{ $pret->exemplaire->notice->doc_titre ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                            {{ \Carbon\Carbon::parse($pret->date_retour)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $statusColors = [
                                    'retourné' => 'bg-green-100 text-green-800',
                                    'en retard' => 'bg-red-100 text-red-800',
                                    'en cours' => 'bg-yellow-100 text-yellow-800',
                                ];
                                $colorClass = $statusColors[strtolower($pret->statut)] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $colorClass }}">
                                {{ ucfirst($pret->statut) }}
                            </span>
                        </td>
                       <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($pret->lecteur && $pret->exemplaire && $pret->exemplaire->notice)
                            <button 
                                onclick="showDetails({{ $pret->toJson() }}, {{ $pret->lecteur->toJson() }}, {{ $pret->exemplaire->notice->toJson() }})"
                                class="text-[#1a3a6e] hover:underline"
                            >
                                Détails
                            </button>
                            @else
                            <span class="text-gray-400">Données manquantes</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($pret->statut == 'en_retard')
                            <form action="{{ route('prets.return', $pret->pret_id) }}" method="POST">
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
                        @if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500 italic">Aucun retour trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-300 bg-[#f0f4f8] rounded-b-xl">
            {{ $retards->withQueryString()->links() }}
        </div>
    </div>
<!-- Modal -->
    <div id="detailModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6 space-y-4 relative">
            <button onclick="closeModal()" class="absolute top-2 right-4 text-gray-600 text-xl font-bold">&times;</button>
            
            <h3 class="text-xl font-semibold text-[#1a3a6e]">Détails du prêt</h3>

            <div class="space-y-2 text-sm text-gray-700" id="printSection">
                <p><strong>Nom:</strong> <span id="modalNom"></span></p>
                <p><strong>Prénom:</strong> <span id="modalPrenom"></span></p>
                <p><strong>Adresse:</strong> <span id="modalAdresse"></span></p>
                <p><strong>Téléphone:</strong> <span id="modalTel"></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <hr>
                <p><strong>Livre prêté:</strong> <span id="modalLivre"></span></p>
                <p><strong>Date de retour prévue:</strong> <span id="modalDateRetour"></span></p>
            </div>

            <div class="flex justify-end mt-4">
              <button onclick="printModal()" class="bg-[#1a3a6e] text-white px-5 py-2 rounded-lg hover:bg-[#0f2452] flex items-center gap-2 shadow-md transition">
    <span class="css-printer-icon"></span>
    Imprimer
</button>
            </div>
        </div>
    </div>
</main>

<script>
    
    function showDetails(pret, lecteur, livre) {
        document.getElementById('modalNom').textContent = lecteur?.lec_nom || '—';
        document.getElementById('modalPrenom').textContent = lecteur?.lec_prenom || '—';
        document.getElementById('modalAdresse').textContent = lecteur?.lec_adress || '—';
        document.getElementById('modalTel').textContent = lecteur?.lec_tel || '—';
        document.getElementById('modalEmail').textContent = lecteur?.lec_email || '—';
        document.getElementById('modalLivre').textContent = livre?.doc_titre || '—';

        if (pret?.date_retour) {
            const date = new Date(pret.date_retour);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            document.getElementById('modalDateRetour').textContent = `${day}/${month}/${year}`;
        } else {
            document.getElementById('modalDateRetour').textContent = '—';
        }
        
        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    function printModal() {
        const nom = document.getElementById('modalNom').textContent;
        const prenom = document.getElementById('modalPrenom').textContent;
        const adresse = document.getElementById('modalAdresse').textContent;
        const tel = document.getElementById('modalTel').textContent;
        const email = document.getElementById('modalEmail').textContent;
        const livre = document.getElementById('modalLivre').textContent;
        const dateRetour = document.getElementById('modalDateRetour').textContent;

        const printContent = `
            <div style="font-family: Arial, sans-serif; margin: 20px; color: #1a3a6e;">
                <h2 style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #1a3a6e; padding-bottom: 10px;">
                    Fiche de demande de retour
                </h2>
                <section style="margin-bottom: 20px;">
                    <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; color: #333;">Informations du lecteur</h3>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                        <tr>
                            <td style="padding: 8px; font-weight: bold; width: 30%;">Nom :</td>
                            <td style="padding: 8px;">${nom}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; font-weight: bold;">Prénom :</td>
                            <td style="padding: 8px;">${prenom}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; font-weight: bold;">Adresse :</td>
                            <td style="padding: 8px;">${adresse}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; font-weight: bold;">Téléphone :</td>
                            <td style="padding: 8px;">${tel}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; font-weight: bold;">Email :</td>
                            <td style="padding: 8px;">${email}</td>
                        </tr>
                    </table>
                </section>
                <section>
                    <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px; color: #333;">Informations sur le prêt</h3>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                        <tr>
                            <td style="padding: 8px; font-weight: bold; width: 40%;">Livre prêté :</td>
                            <td style="padding: 8px;">${livre}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; font-weight: bold;">Date de retour prévue :</td>
                            <td style="padding: 8px;">${dateRetour}</td>
                        </tr>
                    </table>
             </section>
                <footer style="margin-top: 50px; text-align: center; font-size: 0.9em; color: #666;">
                    Fiche générée le ${new Date().toLocaleDateString('fr-FR')}
                </footer>
            </div>
        `;

        const printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write(`
            <html>
            <head>
                <title>Impression - Fiche de demande de retour</title>
                <style>
                    @media print {
                        body {
                            margin: 0;
                            -webkit-print-color-adjust: exact;
                        }
                    }
                </style>
            </head>
            <body>${printContent}</body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
</script>


@endsection