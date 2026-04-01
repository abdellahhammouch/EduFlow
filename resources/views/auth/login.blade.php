@extends('layouts.app')

@section('title', 'Connexion')
@section('page-title', 'Connexion')

@section('content')
    <section class="grid gap-8 lg:grid-cols-2">
        <div class="space-y-5">
            <span class="badge-soft">Connexion JWT</span>
            <div class="space-y-4">
                <h1 class="max-w-2xl text-4xl font-semibold text-slate-950 sm:text-5xl">
                    Connecte-toi à ton espace.
                </h1>
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    Utilise ton compte pour accéder à tes cours, tes inscriptions et ton tableau de bord.
                </p>
            </div>

            <div class="simple-box">
                <p class="text-sm font-semibold text-slate-900">Astuce</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Après connexion, tu seras redirigé automatiquement vers le tableau de bord correspondant à ton rôle.
                </p>
            </div>
        </div>

        <div class="panel-card">
            <div class="mb-6 space-y-2">
                <h2 class="text-2xl font-semibold text-slate-950">Se connecter</h2>
                <p class="text-sm text-slate-500">Utilise ton email et ton mot de passe pour ouvrir ta session.</p>
            </div>

            <div class="hidden rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" data-form-message></div>

            <form class="mt-6 space-y-5" data-auth-form="login">
                <label class="form-group">
                    <span class="form-label">Adresse email</span>
                    <input class="input-field" type="email" name="email" placeholder="example@test.com" required>
                </label>

                <label class="form-group">
                    <span class="form-label">Mot de passe</span>
                    <input class="input-field" type="password" name="password" placeholder="Votre mot de passe" required>
                </label>

                <div class="flex items-center justify-between gap-4 text-sm">
                    <a class="font-medium text-sky-700 transition hover:text-sky-900" href="{{ route('password.forgot') }}">
                        Mot de passe oublié ?
                    </a>
                    <a class="font-medium text-slate-500 transition hover:text-slate-800" href="{{ route('register') }}">
                        Créer un compte
                    </a>
                </div>

                <button class="btn-primary w-full justify-center" type="submit" data-submit-label="Connexion">
                    Connexion
                </button>
            </form>
        </div>
    </section>
@endsection
