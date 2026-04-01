@extends('layouts.app')

@section('title', 'Tableau de bord étudiant')
@section('page-title', 'Dashboard étudiant')
@section('guard-role', 'student')

@section('content')
    <section class="space-y-8">
        <div class="hero-panel">
            <div>
                <span class="badge-soft">Espace étudiant</span>
                <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                    Bonjour <span data-page-user-name>étudiant</span>, ta progression démarre ici.
                </h1>
                <p class="mt-4 max-w-3xl text-lg leading-8 text-slate-600">
                    Cette base front est prête pour accueillir les cours, les favoris, les paiements et les groupes qui arrivent dans la prochaine étape.
                </p>
            </div>
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="surface-card">
                    <p class="text-sm font-medium text-slate-500">Suggestions</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-950">Personnalisées</p>
                </div>
                <div class="surface-card">
                    <p class="text-sm font-medium text-slate-500">Favoris</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-950">Wishlist</p>
                </div>
                <div class="surface-card">
                    <p class="text-sm font-medium text-slate-500">Groupes</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-950">Auto</p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-3">
            <a class="action-card" href="{{ route('courses.index') }}">
                <p class="text-base font-semibold text-slate-950">Découvrir mes cours</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Explorer le catalogue et consulter les détails utiles.</p>
            </a>
            <a class="action-card" href="#">
                <p class="text-base font-semibold text-slate-950">Gérer ma wishlist</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Sauvegarder les cours à revoir plus tard.</p>
            </a>
            <a class="action-card" href="#">
                <p class="text-base font-semibold text-slate-950">Suivre mes inscriptions</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Payer, s’inscrire puis voir le groupe attribué.</p>
            </a>
        </div>
    </section>
@endsection
