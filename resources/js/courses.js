import { fetchApi } from './api';

function escapeHtml(text) {
    let value = String(text || '');

    value = value.replace(/&/g, '&amp;');
    value = value.replace(/</g, '&lt;');
    value = value.replace(/>/g, '&gt;');
    value = value.replace(/"/g, '&quot;');
    value = value.replace(/'/g, '&#039;');

    return value;
}

function formatPrice(price) {
    return Number(price || 0).toFixed(2) + ' USD';
}

function setBoxMessage(container, message, type) {
    if (!container) {
        return;
    }

    container.textContent = message;
    container.classList.remove(
        'hidden',
        'border-rose-200',
        'bg-rose-50',
        'text-rose-700',
        'border-emerald-200',
        'bg-emerald-50',
        'text-emerald-800'
    );

    if (type === 'success') {
        container.classList.add('border-emerald-200', 'bg-emerald-50', 'text-emerald-800');
    } else {
        container.classList.add('border-rose-200', 'bg-rose-50', 'text-rose-700');
    }
}

function createCourseCardHtml(course) {
    let description = course.description || '';

    if (description.length > 180) {
        description = description.substring(0, 180) + '...';
    }

    let teacherName = 'À venir';
    let domainName = 'Domaine';

    if (course.teacher && course.teacher.name) {
        teacherName = course.teacher.name;
    }

    if (course.domain && course.domain.name) {
        domainName = course.domain.name;
    }

    return `
        <article class="surface-card flex h-full flex-col">
            <div class="flex items-start justify-between gap-3">
                <span class="badge-soft">${escapeHtml(domainName)}</span>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                    ${formatPrice(course.price)}
                </span>
            </div>

            <h3 class="mt-5 text-2xl font-semibold tracking-tight text-slate-950">${escapeHtml(course.title)}</h3>

            <p class="mt-4 flex-1 text-sm leading-7 text-slate-600">
                ${escapeHtml(description)}
            </p>

            <div class="mt-6 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Enseignant</p>
                    <p class="mt-1 text-sm font-medium text-slate-700">${escapeHtml(teacherName)}</p>
                </div>

                <a class="btn-primary" href="/courses/${course.id}">
                    Voir détails
                </a>
            </div>
        </article>
    `;
}

function createEmptySearchHtml() {
    return `
        <div class="surface-card md:col-span-2 xl:col-span-3">
            <p class="text-lg font-semibold text-slate-950">Aucun cours ne correspond à cette recherche.</p>
            <p class="mt-2 text-sm text-slate-600">Essaie un autre mot-clé ou vide le champ de recherche.</p>
        </div>
    `;
}

function renderCourseCards(grid, courses) {
    if (!grid) {
        return;
    }

    if (!courses.length) {
        grid.innerHTML = createEmptySearchHtml();
        return;
    }

    let html = '';

    for (let i = 0; i < courses.length; i += 1) {
        html += createCourseCardHtml(courses[i]);
    }

    grid.innerHTML = html;
}

function courseMatchesSearch(course, searchText) {
    const text = searchText.toLowerCase();
    let fullText = '';

    if (course.title) {
        fullText += ' ' + course.title;
    }

    if (course.description) {
        fullText += ' ' + course.description;
    }

    if (course.domain && course.domain.name) {
        fullText += ' ' + course.domain.name;
    }

    if (course.teacher && course.teacher.name) {
        fullText += ' ' + course.teacher.name;
    }

    fullText = fullText.toLowerCase();

    return fullText.indexOf(text) !== -1;
}

async function initCatalogPage() {
    const page = document.querySelector('[data-courses-page="catalog"]');

    if (!page) {
        return;
    }

    const grid = page.querySelector('[data-courses-grid]');
    const searchInput = page.querySelector('[data-course-search]');
    const messageBox = page.querySelector('[data-courses-message]');
    let allCourses = [];

    try {
        const response = await fetchApi('/courses');

        if (response && response.data) {
            allCourses = response.data;
        }

        renderCourseCards(grid, allCourses);
    } catch (error) {
        if (grid) {
            grid.innerHTML = '';
        }

        setBoxMessage(messageBox, error.message || 'Impossible de charger les cours.', 'error');
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = searchInput.value.trim();
            const filteredCourses = [];

            for (let i = 0; i < allCourses.length; i += 1) {
                if (courseMatchesSearch(allCourses[i], query)) {
                    filteredCourses.push(allCourses[i]);
                }
            }

            renderCourseCards(grid, filteredCourses);
        });
    }
}

function createCourseDetailHtml(course) {
    let teacherName = 'À venir';
    let domainName = 'Non défini';

    if (course.teacher && course.teacher.name) {
        teacherName = course.teacher.name;
    }

    if (course.domain && course.domain.name) {
        domainName = course.domain.name;
    }

    return `
        <div class="flex flex-wrap items-center justify-between gap-4">
            <span class="badge-soft">${escapeHtml(domainName)}</span>
            <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                ${formatPrice(course.price)}
            </span>
        </div>

        <h1 class="mt-5 max-w-4xl text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
            ${escapeHtml(course.title)}
        </h1>

        <p class="mt-5 max-w-4xl text-lg leading-8 text-slate-600">
            ${escapeHtml(course.description)}
        </p>

        <div class="mt-8 grid gap-4 md:grid-cols-3">
            <div class="surface-card">
                <p class="text-sm font-medium text-slate-500">Enseignant</p>
                <p class="mt-3 text-xl font-semibold text-slate-950">${escapeHtml(teacherName)}</p>
            </div>
            <div class="surface-card">
                <p class="text-sm font-medium text-slate-500">Domaine</p>
                <p class="mt-3 text-xl font-semibold text-slate-950">${escapeHtml(domainName)}</p>
            </div>
            <div class="surface-card">
                <p class="text-sm font-medium text-slate-500">Prix</p>
                <p class="mt-3 text-xl font-semibold text-slate-950">${formatPrice(course.price)}</p>
            </div>
        </div>
    `;
}

async function initCourseDetailPage() {
    const page = document.querySelector('[data-course-detail-page]');

    if (!page) {
        return;
    }

    const courseId = page.getAttribute('data-course-id');
    const card = page.querySelector('[data-course-detail-card]');
    const messageBox = page.querySelector('[data-course-message]');

    try {
        const course = await fetchApi('/courses/' + courseId);

        if (card) {
            card.classList.remove('animate-pulse');
            card.innerHTML = createCourseDetailHtml(course);
        }
    } catch (error) {
        if (card) {
            card.classList.remove('animate-pulse');
            card.innerHTML = '';
        }

        setBoxMessage(messageBox, error.message || 'Impossible de charger ce cours.', 'error');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    initCatalogPage();
    initCourseDetailPage();
});
