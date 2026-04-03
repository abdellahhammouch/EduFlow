@extends('layouts.app')

@section('title', 'Recommandations')
@section('page-title', 'Recommandations')
@section('guard-role', 'student')

@section('content')
    <section class="space-y-6" data-student-recommendations-page>
        <div class="panel-card">
            <h1 class="text-2xl font-semibold text-gray-900">Cours recommandés</h1>
            <p class="mt-2 text-sm text-gray-600">
                Cette page affiche les cours proposés en fonction des domaines choisis lors de l’inscription.
            </p>
        </div>

        <div class="hidden rounded-md border px-4 py-3 text-sm" data-student-message></div>

        <div class="grid gap-4 md:grid-cols-2" data-student-recommendations-list>
            <div class="surface-card">Chargement des recommandations...</div>
        </div>
    </section>
@endsection
