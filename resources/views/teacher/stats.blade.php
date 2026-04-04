@extends('layouts.app')

@section('title', 'Statistiques')
@section('page-title', 'Statistiques')
@section('guard-role', 'teacher')

@section('content')
    <section class="space-y-6" data-teacher-stats-page>
        <div class="panel-card">
            <h1 class="text-2xl font-semibold text-gray-900">Statistiques des cours</h1>
            <p class="mt-2 text-sm text-gray-600">
                Cette page affiche le nombre d’inscriptions, les retraits, les groupes et le revenu par cours.
            </p>
        </div>

        <div class="hidden rounded-md border px-4 py-3 text-sm" data-teacher-stats-message></div>

        <div class="space-y-4" data-teacher-stats-list>
            <div class="surface-card">Chargement des statistiques...</div>
        </div>
    </section>
@endsection
