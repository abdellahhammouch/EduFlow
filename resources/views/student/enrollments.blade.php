@extends('layouts.app')

@section('title', 'Mes inscriptions')
@section('page-title', 'Mes inscriptions')
@section('guard-role', 'student')

@section('content')
    <section class="space-y-6" data-student-enrollments-page>
        <div class="panel-card">
            <h1 class="text-2xl font-semibold text-gray-900">Paiement et inscriptions</h1>
            <p class="mt-2 text-sm text-gray-600">
                Choisis un cours, crée une intention de paiement Stripe, puis finalise ton inscription si le paiement est validé.
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="panel-card">
                <h2 class="text-lg font-semibold text-gray-900">Nouvelle inscription</h2>
                <p class="mt-2 text-sm text-gray-600">Le paiement Stripe crée d’abord un `payment_intent`, puis l’inscription utilise le `payment_id` validé.</p>

                <div class="mt-4 hidden rounded-md border px-4 py-3 text-sm" data-payment-message></div>

                <form class="mt-4 space-y-4" data-payment-form>
                    <label class="form-group">
                        <span class="form-label">Cours</span>
                        <select class="input-field" name="course_id" data-payment-course-select required>
                            <option value="">Choisir un cours</option>
                        </select>
                    </label>

                    <button class="btn-primary" type="submit" data-submit-label="Créer l'intention de paiement">
                        Créer l'intention de paiement
                    </button>
                </form>

                <div class="mt-6 hidden space-y-4 rounded border border-gray-300 p-4" data-stripe-box>
                    <div class="text-sm text-gray-700" data-payment-summary></div>
                    <div class="rounded border border-gray-300 bg-white p-3" id="stripe-card-element"></div>
                    <button class="btn-primary" type="button" data-confirm-payment>
                        Payer avec Stripe
                    </button>
                    <button class="btn-secondary" type="button" data-create-enrollment>
                        Finaliser l'inscription
                    </button>
                </div>
            </div>

            <div class="panel-card">
                <h2 class="text-lg font-semibold text-gray-900">Mes inscriptions actuelles</h2>
                <p class="mt-2 text-sm text-gray-600">Cette liste affiche aussi le groupe reçu automatiquement après inscription.</p>

                <div class="mt-4 hidden rounded-md border px-4 py-3 text-sm" data-enrollment-message></div>

                <div class="mt-4 space-y-4" data-student-enrollments-list>
                    <div class="surface-card">Chargement des inscriptions...</div>
                </div>
            </div>
        </div>
    </section>
@endsection
