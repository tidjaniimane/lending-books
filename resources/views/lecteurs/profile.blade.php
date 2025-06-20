@extends('layouts.lecteur')

@section('title', 'Profil')

@section('content')
    <main class="flex-grow container mx-auto px-6 py-12 max-w-5xl">
        <div class="bg-white shadow-lg rounded-3xl p-10 flex flex-col md:flex-row items-center md:items-start gap-10" style="background-color: rgba(255, 255, 255, 0.9);">
            
            <!-- Profile Image -->
            <div class="flex-shrink-0 relative">
                
                <button
                    class="absolute bottom-0 right-0 rounded-full p-2 text-white shadow-lg transition"
                    style="background-color: #e8b923; color: #1a3a6e;"
                    onmouseover="this.style.backgroundColor='#d4a400'"
                    onmouseout="this.style.backgroundColor='#e8b923'"
                    title="Changer la photo de profil"
                >
                    <i data-lucide="camera" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Profile Details -->
            <div class="flex-1 w-full">
                <h1 class="text-4xl font-serif mb-3" style="color: #1a3a6e;">{{ Auth::user()->lec_nom }}</h1>
                <p class="text-lg mb-1" style="color: #374151;">Numéro de bibliothèque : <span class="font-semibold" style="color: #e8b923;">{{ Auth::user()->lec_id }}</span></p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8" style="color: #374151;">
                    <div>
                        <label class="block font-medium text-sm mb-1" style="color: #6b7280;">Email</label>
                        <p class="text-lg font-semibold bg-white rounded-xl p-4 shadow-inner border" style="border-color: #d1d5db;">{{ Auth::user()->lec_email }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-sm mb-1" style="color: #6b7280;">Téléphone</label>
                        <p class="text-lg font-semibold bg-white rounded-xl p-4 shadow-inner border" style="border-color: #d1d5db;">{{ Auth::user()->lec_tel }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-medium text-sm mb-1" style="color: #6b7280;">Adresse</label>
                        <p class="text-lg font-semibold bg-white rounded-xl p-4 shadow-inner border" style="border-color: #d1d5db;">
                            {{ Auth::user()->lec_adress }}
                        </p>
                    </div>
                </div>

                
            </div>
        </div>
    </main>
@endsection