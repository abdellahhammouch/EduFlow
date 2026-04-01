@extends('layouts.app')

@section('title', 'Catalogue des cours')
@section('page-title', 'Catalogue des cours')

@section('content')
    <section class="space-y-8" data-courses-page="catalog">
        <div class="hero-panel">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-end">
                <div>
                    <span class="badge-soft">Catalogue</span>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                        Trouve la formation qui correspond à ton prochain objectif.
                    </h1>
                    <p class="mt-4 max-w-3xl text-lg leading-8 text-slate-600">
                        Explore les cours disponibles, filtre rapidement par mot-clé et ouvre une fiche détaillée avant de passer à l’inscription.
                    </p>
                </div>

                <div class="surface-card">
                    <label class="form-group">
                        <span class="form-label">Rechercher un cours</span>
                        <input
                            class="input-field"
                            type="search"
                            placeholder="Laravel, design, data..."
                            data-course-search
                        >
                    </label>
                    <p class="mt-3 text-sm text-slate-500">
                        Le filtrage se fait instantanément sur le titre, la description et le domaine.
                    </p>
                </div>
            </div>
        </div>

        <div class="hidden rounded-2xl border px-4 py-3 text-sm" data-courses-message></div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3" data-courses-grid>
            <article class="surface-card animate-pulse">
                <div class="h-5 w-28 rounded-full bg-slate-200"></div>
                <div class="mt-5 h-8 w-3/4 rounded-xl bg-slate-200"></div>
                <div class="mt-4 h-20 rounded-2xl bg-slate-100"></div>
                <div class="mt-5 h-10 rounded-2xl bg-slate-200"></div>
            </article>
            <article class="surface-card animate-pulse">
                <div class="h-5 w-24 rounded-full bg-slate-200"></div>
                <div class="mt-5 h-8 w-2/3 rounded-xl bg-slate-200"></div>
                <div class="mt-4 h-20 rounded-2xl bg-slate-100"></div>
                <div class="mt-5 h-10 rounded-2xl bg-slate-200"></div>
            </article>
            <article class="surface-card animate-pulse">
                <div class="h-5 w-32 rounded-full bg-slate-200"></div>
                <div class="mt-5 h-8 w-4/5 rounded-xl bg-slate-200"></div>
                <div class="mt-4 h-20 rounded-2xl bg-slate-100"></div>
                <div class="mt-5 h-10 rounded-2xl bg-slate-200"></div>
            </article>
        </div>
    </section>
@endsection
