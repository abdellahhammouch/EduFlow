@extends('layouts.app')

@section('title', 'Groupes')
@section('page-title', 'Groupes')
@section('guard-role', 'teacher')

@section('content')
    <section class="space-y-6" data-teacher-groups-page>
        <div class="panel-card">
            <h1 class="text-2xl font-semibold text-gray-900">Groupes par cours</h1>
            <p class="mt-2 text-sm text-gray-600">
                Sélectionne un cours pour voir ses groupes et les participants de chaque groupe.
            </p>
        </div>

        <div class="panel-card">
            <label class="form-group">
                <span class="form-label">Cours</span>
                <select class="input-field" data-teacher-group-course-select>
                    <option value="">Choisir un cours</option>
                </select>
            </label>
        </div>

        <div class="hidden rounded-md border px-4 py-3 text-sm" data-teacher-groups-message></div>

        <div class="grid gap-4 lg:grid-cols-2" data-teacher-groups-list>
            <div class="surface-card">Choisis un cours pour afficher ses groupes.</div>
        </div>
    </section>
@endsection
