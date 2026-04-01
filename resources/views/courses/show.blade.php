@extends('layouts.app')

@section('title', 'Détail du cours')
@section('page-title', 'Détail du cours')

@section('content')
    <section class="space-y-6" data-course-detail-page data-course-id="{{ $courseId }}">
        <a class="btn-secondary" href="{{ route('courses.index') }}">
            Retour au catalogue
        </a>

        <div class="hidden rounded-2xl border px-4 py-3 text-sm" data-course-message></div>

        <article class="hero-panel animate-pulse" data-course-detail-card>
            <div class="h-5 w-32 rounded-full bg-slate-200"></div>
            <div class="mt-5 h-12 w-2/3 rounded-2xl bg-slate-200"></div>
            <div class="mt-4 h-24 rounded-3xl bg-slate-100"></div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="surface-card">
                    <div class="h-4 w-24 rounded-full bg-slate-200"></div>
                    <div class="mt-4 h-8 w-28 rounded-xl bg-slate-200"></div>
                </div>
                <div class="surface-card">
                    <div class="h-4 w-24 rounded-full bg-slate-200"></div>
                    <div class="mt-4 h-8 w-32 rounded-xl bg-slate-200"></div>
                </div>
                <div class="surface-card">
                    <div class="h-4 w-24 rounded-full bg-slate-200"></div>
                    <div class="mt-4 h-8 w-24 rounded-xl bg-slate-200"></div>
                </div>
            </div>
        </article>
    </section>
@endsection
