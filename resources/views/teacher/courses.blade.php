@extends('layouts.app')

@section('title', 'Mes cours')
@section('page-title', 'Mes cours')
@section('guard-role', 'teacher')

@section('content')
    <section class="space-y-8" data-teacher-courses-page>
        <div class="hero-panel">
            <div class="grid gap-8 xl:grid-cols-[0.92fr_1.08fr]">
                <div>
                    <span class="badge-soft">Gestion enseignant</span>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                        Crée et pilote tes cours depuis un seul écran.
                    </h1>
                    <p class="mt-4 max-w-2xl text-lg leading-8 text-slate-600">
                        Cette première version te permet de consulter tes cours, préparer un nouveau cours et mettre à jour rapidement les informations principales.
                    </p>
                </div>

                <div class="surface-card">
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-lg font-semibold text-slate-950">Formulaire de cours</p>
                            <p class="text-sm text-slate-500">Utilise le même formulaire pour créer ou modifier.</p>
                        </div>
                        <button class="btn-secondary cursor-pointer" type="button" data-reset-course-form>
                            Nouveau cours
                        </button>
                    </div>

                    <div class="hidden rounded-2xl border px-4 py-3 text-sm" data-course-form-message></div>

                    <form class="space-y-5" data-course-form>
                        <input type="hidden" name="course_id">

                        <label class="form-group">
                            <span class="form-label">Titre du cours</span>
                            <input class="input-field" type="text" name="title" placeholder="Laravel API avancée" required>
                        </label>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <label class="form-group">
                                <span class="form-label">Domaine</span>
                                <select class="input-field" name="domain_id" required>
                                    <option value="">Choisir un domaine</option>
                                    @foreach ($domains as $domain)
                                        <option value="{{ $domain->id }}">{{ $domain->name }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="form-group">
                                <span class="form-label">Prix</span>
                                <input class="input-field" type="number" min="0" step="0.01" name="price" placeholder="299.99" required>
                            </label>
                        </div>

                        <label class="form-group">
                            <span class="form-label">Description</span>
                            <textarea class="input-field min-h-36 resize-y" name="description" placeholder="Décris le contenu du cours, les objectifs et ce que les étudiants vont apprendre." required></textarea>
                        </label>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            <button class="btn-primary justify-center sm:justify-start" type="submit" data-submit-label="Créer le cours">
                                Créer le cours
                            </button>
                            <button class="btn-secondary hidden justify-center sm:justify-start" type="button" data-cancel-edit>
                                Annuler la modification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-950">Mes cours actuels</h2>
                <p class="mt-1 text-sm text-slate-500">Les cartes ci-dessous se mettent à jour après chaque création, modification ou suppression.</p>
            </div>
            <a class="btn-secondary" href="{{ route('dashboard.teacher') }}">Retour au dashboard</a>
        </div>

        <div class="hidden rounded-2xl border px-4 py-3 text-sm" data-courses-list-message></div>

        <div class="grid gap-5 lg:grid-cols-2" data-teacher-courses-grid>
            <article class="surface-card animate-pulse">
                <div class="h-5 w-28 rounded-full bg-slate-200"></div>
                <div class="mt-5 h-8 w-3/4 rounded-xl bg-slate-200"></div>
                <div class="mt-4 h-20 rounded-2xl bg-slate-100"></div>
                <div class="mt-5 flex gap-3">
                    <div class="h-10 flex-1 rounded-2xl bg-slate-200"></div>
                    <div class="h-10 flex-1 rounded-2xl bg-slate-200"></div>
                </div>
            </article>
            <article class="surface-card animate-pulse">
                <div class="h-5 w-28 rounded-full bg-slate-200"></div>
                <div class="mt-5 h-8 w-3/4 rounded-xl bg-slate-200"></div>
                <div class="mt-4 h-20 rounded-2xl bg-slate-100"></div>
                <div class="mt-5 flex gap-3">
                    <div class="h-10 flex-1 rounded-2xl bg-slate-200"></div>
                    <div class="h-10 flex-1 rounded-2xl bg-slate-200"></div>
                </div>
            </article>
        </div>
    </section>
@endsection
