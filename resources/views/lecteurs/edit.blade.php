@extends('layouts.lecteur')

@section('content')
<section class="max-w-2xl mx-auto bg-card p-8 rounded-xl shadow-md mt-10">
  <h2 class="text-2xl font-serif text-primary mb-6">✏️ Modifier le profil</h2>

  <form action="{{ route('profile.update', ['lecteur' => $lecteur->lec_id]) }}" method="POST" class="space-y-6">

  @csrf
  @method('PUT')

  <div>
    <label for="lec_nom" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
    <input type="text" name="lec_nom" id="lec_nom" value="{{ old('lec_nom', $lecteur->lec_nom) }}"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
  </div>

  <div>
    <label for="lec_prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
    <input type="text" name="lec_prenom" id="lec_prenom" value="{{ old('lec_prenom', $lecteur->lec_prenom) }}"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
  </div>

  <div>
    <label for="lec_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
    <input type="email" name="lec_email" id="lec_email" value="{{ old('lec_email', $lecteur->lec_email) }}"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
  </div>

  <div class="flex justify-end">
    <button type="submit"
            class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
      ✅ Sauvegarder
    </button>
  </div>
</form>

</section>
@endsection
