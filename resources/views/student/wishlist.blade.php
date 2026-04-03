@extends('layouts.app')

@section('title', 'Ma wishlist')
@section('page-title', 'Ma wishlist')
@section('guard-role', 'student')

@section('content')
    <section class="space-y-6" data-student-wishlist-page>
        <div class="panel-card">
            <h1 class="text-2xl font-semibold text-gray-900">Mes cours sauvegardés</h1>
            <p class="mt-2 text-sm text-gray-600">
                Les cours ajoutés aux favoris sont affichés ici. Tu peux aussi les retirer à tout moment.
            </p>
        </div>

        <div class="hidden rounded-md border px-4 py-3 text-sm" data-student-message></div>

        <div class="grid gap-4 md:grid-cols-2" data-student-wishlist-list>
            <div class="surface-card">Chargement de la wishlist...</div>
        </div>
    </section>
@endsection
