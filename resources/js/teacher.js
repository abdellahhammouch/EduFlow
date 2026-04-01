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

function setMessageClasses(container, type) {
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

function showMessage(container, message, type) {
    if (!container) {
        return;
    }

    container.textContent = message;
    setMessageClasses(container, type || 'error');
}

function createCourseHtml(course) {
    let description = course.description || '';
    let domainName = 'Domaine';

    if (description.length > 200) {
        description = description.substring(0, 200) + '...';
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

            <div class="mt-6 flex flex-wrap gap-3">
                <button class="btn-secondary cursor-pointer" type="button" data-edit-course="${course.id}">
                    Modifier
                </button>
                <button class="btn-primary cursor-pointer" type="button" data-delete-course="${course.id}">
                    Supprimer
                </button>
            </div>
        </article>
    `;
}

function createEmptyCoursesHtml() {
    return `
        <div class="surface-card lg:col-span-2">
            <p class="text-lg font-semibold text-slate-950">Aucun cours pour le moment.</p>
            <p class="mt-2 text-sm text-slate-600">Commence par créer ton premier cours avec le formulaire ci-dessus.</p>
        </div>
    `;
}

function renderCourses(grid, courses) {
    if (!grid) {
        return;
    }

    if (!courses.length) {
        grid.innerHTML = createEmptyCoursesHtml();
        return;
    }

    let html = '';

    for (let i = 0; i < courses.length; i += 1) {
        html += createCourseHtml(courses[i]);
    }

    grid.innerHTML = html;
}

function getCourseById(courses, courseId) {
    for (let i = 0; i < courses.length; i += 1) {
        if (Number(courses[i].id) === Number(courseId)) {
            return courses[i];
        }
    }

    return null;
}

function removeCourseById(courses, courseId) {
    const newCourses = [];

    for (let i = 0; i < courses.length; i += 1) {
        if (Number(courses[i].id) !== Number(courseId)) {
            newCourses.push(courses[i]);
        }
    }

    return newCourses;
}

function getErrorMessage(error, defaultMessage) {
    if (error.data && error.data.errors) {
        let message = '';

        for (const key in error.data.errors) {
            if (message !== '') {
                message += ' ';
            }

            message += error.data.errors[key].join(' ');
        }

        return message;
    }

    if (error.message) {
        return error.message;
    }

    return defaultMessage;
}

async function initTeacherCoursesPage() {
    const page = document.querySelector('[data-teacher-courses-page]');

    if (!page) {
        return;
    }

    const grid = page.querySelector('[data-teacher-courses-grid]');
    const form = page.querySelector('[data-course-form]');
    const formMessage = page.querySelector('[data-course-form-message]');
    const listMessage = page.querySelector('[data-courses-list-message]');
    const submitButton = form.querySelector('button[type="submit"]');
    const cancelEditButton = page.querySelector('[data-cancel-edit]');
    const resetButton = page.querySelector('[data-reset-course-form]');
    let editingCourseId = null;
    let courses = [];

    function setSubmitting(isSubmitting) {
        submitButton.disabled = isSubmitting;

        if (isSubmitting) {
            submitButton.textContent = 'Veuillez patienter...';
            return;
        }

        if (editingCourseId) {
            submitButton.textContent = 'Mettre à jour le cours';
        } else {
            submitButton.textContent = submitButton.getAttribute('data-submit-label');
        }
    }

    function resetForm() {
        editingCourseId = null;
        form.reset();
        form.querySelector('[name="course_id"]').value = '';
        submitButton.textContent = submitButton.getAttribute('data-submit-label');
        cancelEditButton.classList.add('hidden');
    }

    function fillForm(course) {
        editingCourseId = course.id;
        form.querySelector('[name="course_id"]').value = String(course.id);
        form.querySelector('[name="title"]').value = course.title ? course.title : '';
        form.querySelector('[name="domain_id"]').value = course.domain_id ? String(course.domain_id) : '';
        form.querySelector('[name="price"]').value = course.price ? course.price : '';
        form.querySelector('[name="description"]').value = course.description ? course.description : '';
        submitButton.textContent = 'Mettre à jour le cours';
        cancelEditButton.classList.remove('hidden');
        window.scrollTo(0, 0);
    }

    async function loadCourses() {
        try {
            const response = await fetchApi('/teacher/courses', {
                auth: true,
            });

            if (response && response.data) {
                courses = response.data;
            } else {
                courses = [];
            }

            renderCourses(grid, courses);
        } catch (error) {
            showMessage(listMessage, error.message || 'Impossible de charger les cours.', 'error');

            if (grid) {
                grid.innerHTML = '';
            }
        }
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const payload = {
            title: form.querySelector('[name="title"]').value,
            domain_id: Number(form.querySelector('[name="domain_id"]').value),
            price: Number(form.querySelector('[name="price"]').value),
            description: form.querySelector('[name="description"]').value,
        };

        setSubmitting(true);

        try {
            if (editingCourseId) {
                await fetchApi('/teacher/courses/' + editingCourseId, {
                    method: 'PUT',
                    body: payload,
                    auth: true,
                });

                showMessage(formMessage, 'Cours mis à jour avec succès.', 'success');
            } else {
                await fetchApi('/teacher/courses', {
                    method: 'POST',
                    body: payload,
                    auth: true,
                });

                showMessage(formMessage, 'Cours créé avec succès.', 'success');
            }

            resetForm();
            await loadCourses();
        } catch (error) {
            showMessage(formMessage, getErrorMessage(error, 'Impossible d’enregistrer ce cours.'), 'error');
        } finally {
            setSubmitting(false);
        }
    });

    resetButton.addEventListener('click', function () {
        resetForm();
    });

    cancelEditButton.addEventListener('click', function () {
        resetForm();
    });

    grid.addEventListener('click', async function (event) {
        const editButton = event.target.closest('[data-edit-course]');
        const deleteButton = event.target.closest('[data-delete-course]');

        if (editButton) {
            const course = getCourseById(courses, editButton.getAttribute('data-edit-course'));

            if (course) {
                fillForm(course);
            }
        }

        if (deleteButton) {
            const courseId = deleteButton.getAttribute('data-delete-course');
            const shouldDelete = window.confirm('Supprimer ce cours ? Cette action le retirera du catalogue.');

            if (!shouldDelete) {
                return;
            }

            try {
                await fetchApi('/teacher/courses/' + courseId, {
                    method: 'DELETE',
                    auth: true,
                });

                courses = removeCourseById(courses, courseId);
                renderCourses(grid, courses);
                showMessage(listMessage, 'Cours supprimé avec succès.', 'success');

                if (Number(editingCourseId) === Number(courseId)) {
                    resetForm();
                }
            } catch (error) {
                showMessage(listMessage, error.message || 'Impossible de supprimer ce cours.', 'error');
            }
        }
    });

    await loadCourses();
};

document.addEventListener('DOMContentLoaded', function () {
    initTeacherCoursesPage();
});
