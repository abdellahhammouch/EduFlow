@extends('layouts.app')

@section('title', 'Inscription')
@section('page-title', 'Inscription')

@section('content')
    <section class="grid gap-8 lg:grid-cols-2">
        <div class="space-y-5">
            <span class="badge-soft">Nouveau compte</span>
            <div class="space-y-4">
                <h1 class="max-w-2xl text-4xl font-semibold text-slate-950 sm:text-5xl">
                    Crée ton compte simplement.
                </h1>
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    Choisis ton rôle dès le départ. L’interface s’adaptera ensuite automatiquement à ton profil.
                </p>
            </div>

            <div class="simple-box">
                <p class="text-sm font-semibold text-slate-900">À savoir</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Si tu choisis le rôle étudiant, tu pourras aussi sélectionner tes domaines d’intérêt pour les recommandations.
                </p>
            </div>
        </div>

        <div class="panel-card">
            <div class="mb-6 space-y-2">
                <h2 class="text-2xl font-semibold text-slate-950">Créer un compte</h2>
                <p class="text-sm text-slate-500">Le rôle est définitif après l’inscription, donc on le choisit tout de suite.</p>
            </div>

            <div class="hidden rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" data-form-message></div>

            <form class="space-y-5" data-auth-form="register">
                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="form-group sm:col-span-2">
                        <span class="form-label">Nom complet</span>
                        <input class="input-field" type="text" name="name" placeholder="Abdellah Hammouch" required>
                    </label>

                    <label class="form-group sm:col-span-2">
                        <span class="form-label">Adresse email</span>
                        <input class="input-field" type="email" name="email" placeholder="vous@exemple.com" required>
                    </label>

                    <label class="form-group">
                        <span class="form-label">Mot de passe</span>
                        <input class="input-field" type="password" name="password" placeholder="Mot de passe" required>
                    </label>

                    <label class="form-group">
                        <span class="form-label">Confirmation</span>
                        <input class="input-field" type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required>
                    </label>
                </div>

                <fieldset class="space-y-3">
                    <legend class="form-label">Rôle</legend>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="option-card">
                            <input checked class="sr-only" type="radio" name="role" value="student" data-role-toggle>
                            <span class="text-sm font-semibold text-slate-900">Étudiant</span>
                            <span class="mt-1 text-sm text-slate-500">Accès aux recommandations, favoris et inscriptions.</span>
                        </label>
                        <label class="option-card">
                            <input class="sr-only" type="radio" name="role" value="teacher" data-role-toggle>
                            <span class="text-sm font-semibold text-slate-900">Enseignant</span>
                            <span class="mt-1 text-sm text-slate-500">Accès au CRUD de cours, groupes et statistiques.</span>
                        </label>
                    </div>
                </fieldset>

                <fieldset class="space-y-3" data-student-interests>
                    <legend class="form-label">Centres d’intérêt</legend>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($domains as $domain)
                            <label class="option-card">
                                <input class="sr-only" type="checkbox" name="domain_ids[]" value="{{ $domain->id }}">
                                <span class="text-sm font-semibold text-slate-900">{{ $domain->name }}</span>
                                <span class="mt-1 text-sm text-slate-500">Utilisé pour les recommandations personnalisées.</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="flex items-center justify-between gap-4 text-sm">
                    <span class="text-slate-500">Déjà inscrit ?</span>
                    <a class="font-medium text-sky-700 transition hover:text-sky-900" href="{{ route('login') }}">
                        Se connecter
                    </a>
                </div>

                <button class="btn-primary w-full justify-center" type="submit" data-submit-label="Créer mon compte">
                    Créer mon compte
                </button>
            </form>
        </div>
    </section>
@endsection
