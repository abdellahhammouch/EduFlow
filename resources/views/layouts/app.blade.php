<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'EduFlow')</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <script>
            window.eduFlowConfig = {
                apiBaseUrl: '{{ url('/api/v1') }}',
                routes: {
                    home: '{{ route('home') }}',
                    login: '{{ route('login') }}',
                    register: '{{ route('register') }}',
                    forgotPassword: '{{ route('password.forgot') }}',
                    teacherDashboard: '{{ route('dashboard.teacher') }}',
                    studentDashboard: '{{ route('dashboard.student') }}',
                    teacherCourses: '{{ route('teacher.courses') }}',
                    teacherGroups: '{{ route('teacher.groups') }}',
                    teacherStats: '{{ route('teacher.stats') }}',
                    studentRecommendations: '{{ route('student.recommendations') }}',
                    studentWishlist: '{{ route('student.wishlist') }}',
                    studentEnrollments: '{{ route('student.enrollments') }}',
                },
                stripeKey: '{{ (string) config('services.stripe.key') }}',
            };
        </script>

        <script src="https://js.stripe.com/v3/"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="min-h-screen bg-slate-100 text-slate-900"
        data-guard-role="@yield('guard-role')"
        data-page-title="@yield('page-title', 'EduFlow')"
    >
        <div class="min-h-screen">
            @include('partials.header')

            <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-6xl">
                    <div
                        class="mb-6 hidden rounded-2xl border border-emerald-200 bg-emerald-50/90 px-4 py-3 text-sm text-emerald-800 shadow-sm"
                        data-global-message
                    ></div>

                    @yield('content')
                </div>
            </main>
        </div>
    </body>
</html>
