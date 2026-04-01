@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe')
@section('page-title', 'Réinitialiser le mot de passe')

@section('content')
    <section class="mx-auto grid max-w-5xl gap-8 lg:grid-cols-[0.9fr_1.1fr]">
        <div class="space-y-5">
            <span class="badge-soft">Nouveau mot de passe</span>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                Choisis un mot de passe clair et solide.
            </h1>
            <p class="text-lg leading-8 text-slate-600">
                Colle le token reçu par email si besoin, puis confirme ton nouveau mot de passe pour retrouver l’accès à la plateforme.
            </p>
        </div>

        <div class="panel-card">
            <div class="mb-6 space-y-2">
                <h2 class="text-2xl font-semibold text-slate-950">Réinitialisation</h2>
                <p class="text-sm text-slate-500">Le token et l’email peuvent être préremplis depuis le lien Laravel.</p>
            </div>

            <div class="hidden rounded-2xl border px-4 py-3 text-sm" data-form-message></div>

            <form class="space-y-5" data-auth-form="reset-password">
                <label class="form-group">
                    <span class="form-label">Token</span>
                    <input class="input-field" type="text" name="token" value="{{ $token }}" placeholder="Token de réinitialisation" required>
                </label>

                <label class="form-group">
                    <span class="form-label">Adresse email</span>
                    <input class="input-field" type="email" name="email" value="{{ $email }}" placeholder="vous@exemple.com" required>
                </label>

                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="form-group">
                        <span class="form-label">Nouveau mot de passe</span>
                        <input class="input-field" type="password" name="password" placeholder="Nouveau mot de passe" required>
                    </label>

                    <label class="form-group">
                        <span class="form-label">Confirmation</span>
                        <input class="input-field" type="password" name="password_confirmation" placeholder="Confirmer" required>
                    </label>
                </div>

                <button class="btn-primary w-full justify-center" type="submit" data-submit-label="Mettre à jour">
                    Mettre à jour
                </button>
            </form>
        </div>
    </section>
@endsection
