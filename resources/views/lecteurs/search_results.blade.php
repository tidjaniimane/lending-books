@extends('layouts.lecteur')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4" style="color: #1a3a6e;">Résultats de la recherche</h1>

        @if ($notices->isEmpty())
            <div class="alert text-center" style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fee2e2;">
                Aucun résultat trouvé.
            </div>
        @else
        <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead style="background-color: #1a3a6e; color: white;">
                        <tr>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Cote de l'exemplaire</th>
                            <th>État</th>
                            <th>Disponibilité</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notices as $notice)
                            @foreach ($notice->exemplaires as $exemplaire)
                                <tr style="transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#e3f2fd'" onmouseout="this.style.backgroundColor=''">
                                    <td style="color: #374151;">{{ $notice->doc_titre }}</td>
                                    <td style="color: #6b7280;">{{ $notice->doc_auteur }}</td>
                                    <td style="color: #6b7280;">{{ $exemplaire->exp_cote }}</td>
                                    <td style="color: #6b7280;">{{ $exemplaire->etat }}</td>
                                    <td>
                                        @if ($exemplaire->statut == 'Disponible')
                                            <span class="badge" style="background-color: #f0fdf4; color: #166534; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem;">Disponible</span>
                                        @elseif ($exemplaire->statut == 'on bib')
                                            <span class="badge" style="background-color: #fef2f2; color:rgb(76, 26, 193); padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem;">read on bib</span>
                                            @else ($exemplaire->statut == 'indisponible')
                                            <span class="badge" style="background-color: #fef2f2; color: #991b1b; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem;">Indisponible</span>
                                        @endif
                                    </td>
                                    <td>
                                         @if ($exemplaire->statut == 'Disponible')
                                        <button 
                                            type="button"
                                            class="btn demande-btn transition" 
                                            style="background-color: #e8b923; color: #1a3a6e; border: none; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-weight: 500;"
                                            onmouseover="this.style.backgroundColor='#d4a400'"
                                            onmouseout="this.style.backgroundColor='#e8b923'"
                                            data-docid="{{ $notice->doc_id }}"
                                            data-title="{{ $notice->doc_titre }}"
                                            data-cote="{{ $exemplaire->exp_cote }}"
                                        >
                                            Demander
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif
    </div>

    <!-- Modal pour confirmer la demande -->
    <div class="modal fade" id="demandeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('demandes.store') }}">
                @csrf
                <input type="hidden" name="doc_id" id="modalDocId">
                <div class="modal-content">
                    <div class="modal-header text-white" style="background-color: #1a3a6e;">
                        <h5 class="modal-title">Demande de prêt</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="lec_id" style="color: #374151; font-weight: 500;">Numéro de carte :</label>
                            <input type="text" name="lec_id" id="lec_id" class="form-control" style="border-color: #d1d5db;" required>
                        </div>
                        <div class="form-group">
                            <label style="color: #374151; font-weight: 500;">Livre :</label>
                            <p id="modalTitre" class="font-weight-bold" style="color: #1a3a6e;"></p>
                        </div>
                        <div class="form-group">
                            <label style="color: #374151; font-weight: 500;">Cote de l'exemplaire :</label>
                            <p id="modalCote" style="color: #e8b923; font-weight: 500;"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('demandes.store') }}" method="POST">
                            @csrf

                            <!-- Add your form fields here -->
                            @foreach ($notices as $notice)
                            @foreach ($notice->exemplaires as $exemplaire) {{-- supposant que tu as une relation définie --}}
                                <form action="{{ route('demandes.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="lec_id" value="{{ auth()->user()->lec_id }}">
                                    <input type="hidden" name="doc_id" value="{{ $notice->doc_id }}">
                                    <input type="hidden" name="exp_id" value="{{ $exemplaire->exp_id }}">
                                </form>
                            @endforeach
                        @endforeach

                        <button type="submit" class="btn transition" style="background-color: #f0fdf4; color: #166534; border: 1px solid #dcfce7; padding: 0.375rem 0.75rem; border-radius: 0.375rem;" onmouseover="this.style.backgroundColor='#dcfce7'" onmouseout="this.style.backgroundColor='#f0fdf4'">Valider</button>
                        </form>

                        <button type="button" class="btn" style="background-color: #f8f9fa; color: #6b7280; border: 1px solid #d1d5db; padding: 0.375rem 0.75rem; border-radius: 0.375rem;" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.demande-btn').forEach(function(button) {
            button.addEventListener('click', function () {
                const titre = this.getAttribute('data-title');
                const cote = this.getAttribute('data-cote');
                const doc_id = this.getAttribute('data-docid');

                document.getElementById('modalTitre').textContent = titre;
                document.getElementById('modalCote').textContent = cote;
                document.getElementById('modalDocId').value = doc_id;

                $('#demandeModal').modal('show');
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
    const searchButton = document.getElementById("searchButton");
    if (searchButton) {
        searchButton.addEventListener("click", triggerSearch);
    }
});

</script>
@endsection