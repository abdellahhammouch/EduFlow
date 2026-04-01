@extends('layouts.app')

@section('title', 'Tableau de bord enseignant')
@section('page-title', 'Dashboard enseignant')
@section('guard-role', 'teacher')

@section('content')
    <section class="space-y-8">
        <div class="hero-panel">
            <div>
                <span class="badge-soft">Espace enseignant</span>
                <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                    Bonjour <span data-page-user-name>enseignant</span>, tout est prêt pour piloter tes cours.
                </h1>
                <p class="mt-4 max-w-3xl text-lg leading-8 text-slate-600">
                    Cette première base te donne un point d’entrée clair. La prochaine étape sera d’y brancher les vrais écrans CRUD, les groupes et les statistiques détaillées.
                </p>
            </div>
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="surface-card">
                    <p class="text-sm font-medium text-slate-500">Cours</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-950">CRUD</p>
                </div>
                <div class="surface-card">
                    <p class="text-sm font-medium text-slate-500">Groupes</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-950">25 max</p>
                </div>
                <div class="surface-card">
                    <p class="text-sm font-medium text-slate-500">Statistiques</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-950">En direct</p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-3">
            <a class="action-card" href="{{ route('teacher.courses') }}">
                <p class="text-base font-semibold text-slate-950">Gérer mes cours</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Créer, modifier et supprimer les cours actifs.</p>
            </a>
            <a class="action-card" href="#">
                <p class="text-base font-semibold text-slate-950">Voir mes groupes</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Parcourir les groupes par cours et leurs participants.</p>
            </a>
            <a class="action-card" href="#">
                <p class="text-base font-semibold text-slate-950">Suivre les statistiques</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Consulter le nombre d’inscrits et l’activité de tes cours.</p>
            </a>
        </div>
    </section>
@endsection
