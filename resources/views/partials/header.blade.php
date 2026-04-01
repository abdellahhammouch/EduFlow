<header class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a class="flex items-center gap-3" href="{{ route('home') }}">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-600 text-lg font-bold text-white">
                E
            </span>
            <div>
                <p class="text-lg font-semibold text-slate-950">EduFlow</p>
                <p class="text-xs text-slate-500">Formation, groupes et paiements au même endroit.</p>
            </div>
        </a>

        <nav class="hidden items-center gap-3 md:flex">
            <a class="nav-link" href="{{ route('home') }}">Accueil</a>
            <a class="nav-link" href="{{ route('courses.index') }}">Cours</a>
            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
            <a class="btn-secondary" href="{{ route('register') }}">Créer un compte</a>
        </nav>

        <div class="hidden items-center gap-3" data-authenticated-nav>
            <p class="text-sm font-medium text-slate-600">
                Connecté en tant que
                <span class="font-semibold text-slate-900" data-auth-user-name>utilisateur</span>
            </p>
            <a class="btn-secondary" href="{{ route('home') }}" data-dashboard-link hidden>Tableau de bord</a>
            <button class="btn-primary cursor-pointer" type="button" data-logout-trigger>
                Déconnexion
            </button>
        </div>

        <button
            class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-600 md:hidden"
            type="button"
            data-mobile-nav-toggle
        >
            Menu
        </button>
    </div>

    <div class="hidden border-t border-slate-200 bg-white px-4 py-4 md:hidden" data-mobile-nav>
        <div class="flex flex-col gap-3">
            <a class="nav-link" href="{{ route('home') }}">Accueil</a>
            <a class="nav-link" href="{{ route('courses.index') }}">Cours</a>
            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
            <a class="btn-secondary text-center" href="{{ route('register') }}">Créer un compte</a>
            <a class="btn-secondary hidden text-center" href="#" data-mobile-dashboard-link>Tableau de bord</a>
            <button class="btn-primary hidden cursor-pointer" type="button" data-mobile-logout-trigger>
                Déconnexion
            </button>
        </div>
    </div>
</header>
