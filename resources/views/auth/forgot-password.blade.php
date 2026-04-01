@extends('layouts.app')

@section('title', 'Mot de passe oublié')
@section('page-title', 'Mot de passe oublié')

@section('content')
    <section class="mx-auto grid max-w-5xl gap-8 lg:grid-cols-[0.9fr_1.1fr]">
        <div class="space-y-5">
            <span class="badge-soft">Récupération</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                On t’aide à reprendre la main sur ton compte.
            </h1>
            <p class="text-lg leading-8 text-slate-600">
                Envoie ton email, puis utilise le lien reçu pour choisir un nouveau mot de passe en toute sécurité.
            </p>
        </div>

        <div class="panel-card">
            <div class="mb-6 space-y-2">
                <h2 class="text-2xl font-semibold text-slate-950">Recevoir le lien de réinitialisation</h2>
                <p class="text-sm text-slate-500">Si `MAIL_MAILER=log`, pense à récupérer le lien dans `storage/logs/laravel.log`.</p>
            </div>

            <div class="hidden rounded-2xl border px-4 py-3 text-sm" data-form-message></div>

            <form class="space-y-5" data-auth-form="forgot-password">
                <label class="form-group">
                    <span class="form-label">Adresse email</span>
                    <input class="input-field" type="email" name="email" placeholder="vous@exemple.com" required>
                </label>

                <button class="btn-primary w-full justify-center" type="submit" data-submit-label="Envoyer le lien">
                    Envoyer le lien
                </button>
            </form>
        </div>
    </section>
@endsection
