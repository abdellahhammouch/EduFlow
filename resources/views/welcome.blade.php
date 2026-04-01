@extends('layouts.app')

@section('title', 'EduFlow')
@section('page-title', 'Accueil')

@section('content')
    <section class="grid gap-8 lg:grid-cols-2">
        <div class="space-y-5">
            <span class="badge-soft">Bienvenue sur EduFlow</span>
            <div class="space-y-4">
                <h1 class="max-w-3xl text-4xl font-semibold text-slate-950 sm:text-5xl">
                    Gérez les cours et les inscriptions simplement.
                </h1>
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    EduFlow permet aux étudiants et aux enseignants de travailler sur la même plateforme: consulter les cours, s’inscrire et suivre les groupes.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a class="btn-primary justify-center sm:justify-start" href="{{ route('register') }}">Commencer maintenant</a>
                <a class="btn-secondary justify-center sm:justify-start" href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>

        <div class="panel-card space-y-5">
            <div class="simple-box">
                <p class="text-sm font-semibold text-slate-900">Pour les enseignants</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Créer des cours, voir les étudiants inscrits et consulter les groupes.</p>
            </div>
            <div class="simple-box">
                <p class="text-sm font-semibold text-slate-900">Pour les étudiants</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Consulter les cours, payer, s’inscrire et rejoindre automatiquement un groupe.</p>
            </div>
            <div class="simple-box">
                <p class="text-sm font-semibold text-slate-900">Objectif du projet</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">Offrir une interface simple, responsive et facile à comprendre pour interagir avec l’API.</p>
            </div>
        </div>
    </section>
@endsection
