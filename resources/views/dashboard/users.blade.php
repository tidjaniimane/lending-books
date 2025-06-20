@extends('layouts.app')

@section('title', 'Lecteurs')

@section('content')
<div class="container mx-auto p-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gestion des Lecteurs</h2>
        <a href="{{ route('lecteurs.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Nouveau Lecteur
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <input 
            type="text" 
            placeholder="Rechercher par nom, email ou ID..." 
            class="w-full border pl-10 pr-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
        >
        <i data-lucide="search" class="absolute left-3 top-10 text-gray-400 w-5 h-5"></i>
    </div>

    <!-- Patrons Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Sample row -->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">L-103</td>
                    <td class="px-6 py-4 whitespace-nowrap">Yasmine Merabet</td>
                    <td class="px-6 py-4 whitespace-nowrap">yasmine@example.com</td>
                    <td class="px-6 py-4 whitespace-nowrap">0558 23 45 67</td>
                    <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                        <a href="#" class="text-blue-600 hover:underline">Éditer</a>
                        <form method="POST" action="#" onsubmit="return confirm('Confirmer la suppression ?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <!-- Add more rows -->
            </tbody>
        </table>
    </div>
</div>
@endsection