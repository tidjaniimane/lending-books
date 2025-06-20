@extends('layouts.lecteur')

@section('content')
<section class="container mx-auto px-4 py-10">
  <h2 class="text-3xl font-serif text-primary mb-8">📚 Historique des prêts</h2>

  <div class="space-y-6">
    @forelse ($prets as $pret)
      <div class="bg-card rounded-xl shadow-md p-6 flex flex-col md:flex-row md:justify-between md:items-center">
        <div>
          <p class="text-lg font-semibold text-primary">
       
    <p>{{ $pret->notice ? $pret->notice->doc_titre : 'Titre indisponible' }}<p>

          </p>
          <p class="text-sm text-gray-700">
            Prêté le : {{ \Carbon\Carbon::parse($pret->date_pret)->format('d/m/Y') }}
          </p>

          @if ($pret->date_retour)
            <p class="text-sm text-gray-700">
              Retour le : {{ \Carbon\Carbon::parse($pret->date_retour)->format('d/m/Y') }}
            </p>
          @elseif ($pret->date_prevue_retour)
            <p class="text-sm text-gray-700">
              Retour prévu le : {{ \Carbon\Carbon::parse($pret->date_prevue_retour)->format('d/m/Y') }}
            </p>
          @endif
        </div>

        <div class="mt-4 md:mt-0 flex items-center font-semibold gap-2
          @if($pret->statut == 'retourne') text-green-600
          @elseif($pret->statut == 'cherche') text-blue-600
          @else text-yellow-600 @endif">

          @if ($pret->statut === 'retourne')
    <i data-lucide="check-circle" class="w-6 h-6"></i> Déjà retourné
@elseif ($pret->statut === 'cherche')
    <i data-lucide="book-open" class="w-6 h-6"></i> Déjà cherché
@elseif ($pret->statut === 'en_cours')
    <i data-lucide="clock" class="w-6 h-6"></i> En cours
@elseif ($pret->statut === 'en_retard')
    <i data-lucide="alert-triangle" class="w-6 h-6"></i> En retard
@else
    <i data-lucide="help-circle" class="w-6 h-6"></i> Statut inconnu
@endif

        </div>
      </div>
    @empty
      <p class="text-gray-600">Aucun prêt trouvé.</p>
    @endforelse
  </div>
</section>
@endsection

@push('scripts')
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
@endpush
