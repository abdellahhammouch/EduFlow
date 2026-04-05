import { fetchApi, getAuthSession } from './api';

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

function hideMessage(container) {
    if (!container) {
        return;
    }

    container.classList.add('hidden');
    container.textContent = '';
}

function showMessage(container, message, type) {
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

function getErrorMessage(error, defaultMessage) {
    if (error && error.data && error.data.errors) {
        let message = '';

        for (const key in error.data.errors) {
            if (message !== '') {
                message += ' ';
            }

            message += error.data.errors[key].join(' ');
        }

        return message;
    }

    if (error && error.message) {
        return error.message;
    }

    return defaultMessage;
}

function getRelationValue(item, firstKey, secondKey) {
    if (item && item[firstKey]) {
        return item[firstKey];
    }

    if (item && item[secondKey]) {
        return item[secondKey];
    }

    return null;
}

function createCourseActionCard(course, actionLabel, actionName) {
    let teacherName = 'Enseignant';
    let domainName = 'Domaine';
    let description = course.description || '';

    if (description.length > 160) {
        description = description.substring(0, 160) + '...';
    }

    if (course.teacher && course.teacher.name) {
        teacherName = course.teacher.name;
    }

    if (course.domain && course.domain.name) {
        domainName = course.domain.name;
    }

    return `
        <article class="surface-card space-y-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase text-gray-500">${escapeHtml(domainName)}</p>
                    <h2 class="mt-2 text-xl font-semibold text-gray-900">${escapeHtml(course.title)}</h2>
                </div>
                <span class="text-sm font-semibold text-gray-700">${formatPrice(course.price)}</span>
            </div>

            <p class="text-sm leading-6 text-gray-600">${escapeHtml(description)}</p>

            <p class="text-sm text-gray-500">Enseignant : ${escapeHtml(teacherName)}</p>

            <div class="flex flex-wrap gap-3">
                <a class="btn-secondary" href="/courses/${course.id}">Voir détails</a>
                <button class="btn-primary" type="button" data-student-action="${actionName}" data-course-id="${course.id}">
                    ${escapeHtml(actionLabel)}
                </button>
            </div>
        </article>
    `;
}

function createWishlistCard(item) {
    const course = item.course ? item.course : item;

    return createCourseActionCard(course, 'Retirer', 'remove-wishlist');
}

function createRecommendationCard(course) {
    return createCourseActionCard(course, 'Ajouter aux favoris', 'add-wishlist');
}

function renderCards(container, html, emptyMessage) {
    if (!container) {
        return;
    }

    if (html === '') {
        container.innerHTML = '<div class="surface-card">' + emptyMessage + '</div>';
        return;
    }

    container.innerHTML = html;
}

async function addCourseToWishlist(courseId) {
    await fetchApi('/student/wishlist', {
        method: 'POST',
        body: {
            course_id: Number(courseId),
        },
        auth: true,
    });
}

async function removeCourseFromWishlist(courseId) {
    await fetchApi('/student/wishlist/' + courseId, {
        method: 'DELETE',
        auth: true,
    });
}

async function initRecommendationsPage() {
    const page = document.querySelector('[data-student-recommendations-page]');

    if (!page) {
        return;
    }

    const list = page.querySelector('[data-student-recommendations-list]');
    const message = page.querySelector('[data-student-message]');
    let courses = [];

    async function loadRecommendations() {
        hideMessage(message);

        try {
            courses = await fetchApi('/student/recommendations', {
                auth: true,
            });

            let html = '';

            for (let i = 0; i < courses.length; i += 1) {
                html += createRecommendationCard(courses[i]);
            }

            renderCards(list, html, 'Aucune recommandation pour le moment.');
        } catch (error) {
            renderCards(list, '', 'Impossible de charger les recommandations.');
            showMessage(message, getErrorMessage(error, 'Impossible de charger les recommandations.'), 'error');
        }
    }

    list.addEventListener('click', async function (event) {
        const button = event.target.closest('[data-student-action="add-wishlist"]');

        if (!button) {
            return;
        }

        try {
            await addCourseToWishlist(button.getAttribute('data-course-id'));
            showMessage(message, 'Cours ajouté à la wishlist.', 'success');
        } catch (error) {
            showMessage(message, getErrorMessage(error, 'Impossible d’ajouter ce cours.'), 'error');
        }
    });

    await loadRecommendations();
}

async function initWishlistPage() {
    const page = document.querySelector('[data-student-wishlist-page]');

    if (!page) {
        return;
    }

    const list = page.querySelector('[data-student-wishlist-list]');
    const message = page.querySelector('[data-student-message]');
    let wishlist = [];

    async function loadWishlist() {
        hideMessage(message);

        try {
            wishlist = await fetchApi('/student/wishlist', {
                auth: true,
            });

            let html = '';

            for (let i = 0; i < wishlist.length; i += 1) {
                html += createWishlistCard(wishlist[i]);
            }

            renderCards(list, html, 'Aucun cours sauvegardé pour le moment.');
        } catch (error) {
            renderCards(list, '', 'Impossible de charger la wishlist.');
            showMessage(message, getErrorMessage(error, 'Impossible de charger la wishlist.'), 'error');
        }
    }

    list.addEventListener('click', async function (event) {
        const button = event.target.closest('[data-student-action="remove-wishlist"]');

        if (!button) {
            return;
        }

        try {
            await removeCourseFromWishlist(button.getAttribute('data-course-id'));
            showMessage(message, 'Cours retiré de la wishlist.', 'success');
            await loadWishlist();
        } catch (error) {
            showMessage(message, getErrorMessage(error, 'Impossible de retirer ce cours.'), 'error');
        }
    });

    await loadWishlist();
}

function fillCourseSelect(select, courses) {
    if (!select) {
        return;
    }

    let html = '<option value="">Choisir un cours</option>';

    for (let i = 0; i < courses.length; i += 1) {
        html += '<option value="' + courses[i].id + '">' + escapeHtml(courses[i].title) + '</option>';
    }

    select.innerHTML = html;
}

function createEnrollmentCard(enrollment) {
    const course = enrollment.course ? enrollment.course : {};
    const teacher = course.teacher ? course.teacher : {};
    const domain = course.domain ? course.domain : {};
    const group = getRelationValue(enrollment, 'course_group', 'courseGroup');
    const payment = enrollment.payment ? enrollment.payment : {};
    let groupText = 'Aucun groupe';
    let paymentText = payment.status ? payment.status : 'inconnu';

    if (group && group.group_number) {
        groupText = 'Groupe ' + group.group_number;
    }

    return `
        <article class="surface-card space-y-3">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase text-gray-500">${escapeHtml(domain.name || 'Domaine')}</p>
                    <h3 class="mt-1 text-lg font-semibold text-gray-900">${escapeHtml(course.title || 'Cours')}</h3>
                </div>
                <span class="text-sm font-semibold text-gray-700">${escapeHtml(enrollment.status || 'active')}</span>
            </div>

            <p class="text-sm text-gray-600">Enseignant : ${escapeHtml(teacher.name || 'Non défini')}</p>
            <p class="text-sm text-gray-600">Groupe : ${escapeHtml(groupText)}</p>
            <p class="text-sm text-gray-600">Paiement : ${escapeHtml(paymentText)}</p>

            <div class="flex flex-wrap gap-3">
                <a class="btn-secondary" href="/courses/${course.id}">Voir détails</a>
                ${enrollment.status === 'active'
                    ? '<button class="btn-primary" type="button" data-withdraw-course="' + course.id + '">Se retirer</button>'
                    : ''
                }
            </div>
        </article>
    `;
}

function renderEnrollments(list, enrollments) {
    let html = '';

    for (let i = 0; i < enrollments.length; i += 1) {
        html += createEnrollmentCard(enrollments[i]);
    }

    renderCards(list, html, 'Aucune inscription pour le moment.');
}

async function initEnrollmentsPage() {
    const page = document.querySelector('[data-student-enrollments-page]');

    if (!page) {
        return;
    }

    const paymentForm = page.querySelector('[data-payment-form]');
    const courseSelect = page.querySelector('[data-payment-course-select]');
    const paymentMessage = page.querySelector('[data-payment-message]');
    const stripeBox = page.querySelector('[data-stripe-box]');
    const paymentSummary = page.querySelector('[data-payment-summary]');
    const confirmPaymentButton = page.querySelector('[data-confirm-payment]');
    const createEnrollmentButton = page.querySelector('[data-create-enrollment]');
    const enrollmentList = page.querySelector('[data-student-enrollments-list]');
    const enrollmentMessage = page.querySelector('[data-enrollment-message]');
    let currentPayment = null;
    let stripe = null;
    let stripeElements = null;
    let stripeCard = null;

    function getCurrentUserName() {
        const session = getAuthSession();

        if (session && session.user && session.user.name) {
            return session.user.name;
        }

        return 'Étudiant EduFlow';
    }

    function resetStripeBox() {
        currentPayment = null;
        stripeBox.classList.add('hidden');
        paymentSummary.textContent = '';
        createEnrollmentButton.disabled = true;

        if (stripeCard) {
            stripeCard.unmount();
            stripeCard = null;
        }
    }

    function fillPaymentSummary(paymentData) {
        let summary = 'Paiement en attente';
        summary += ' | Montant : ' + formatPrice(paymentData.amount);
        summary += ' | Statut local : ' + paymentData.status;
        paymentSummary.textContent = summary;
    }

    async function loadCourses() {
        try {
            const response = await fetchApi('/courses');
            const courses = response && response.data ? response.data : [];
            fillCourseSelect(courseSelect, courses);
        } catch (error) {
            showMessage(paymentMessage, getErrorMessage(error, 'Impossible de charger les cours.'), 'error');
        }
    }

    async function loadEnrollments() {
        try {
            const enrollments = await fetchApi('/student/enrollments', {
                auth: true,
            });

            renderEnrollments(enrollmentList, enrollments);
        } catch (error) {
            renderCards(enrollmentList, '', 'Impossible de charger les inscriptions.');
            showMessage(enrollmentMessage, getErrorMessage(error, 'Impossible de charger les inscriptions.'), 'error');
        }
    }

    function initStripeCard(clientSecret) {
        const stripeKey = window.eduFlowConfig.stripeKey;

        if (!stripeKey || !window.Stripe) {
            showMessage(paymentMessage, 'Stripe n’est pas configuré côté navigateur. Configure STRIPE_KEY pour continuer.', 'error');
            return false;
        }

        stripe = window.Stripe(stripeKey);
        stripeElements = stripe.elements({
            clientSecret: clientSecret,
        });
        stripeCard = stripeElements.create('payment');
        stripeCard.mount('#stripe-card-element');

        return true;
    }

    paymentForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        hideMessage(paymentMessage);

        const courseId = courseSelect.value;

        if (!courseId) {
            showMessage(paymentMessage, 'Choisis un cours avant de continuer.', 'error');
            return;
        }

        resetStripeBox();

        try {
            currentPayment = await fetchApi('/student/payments/intent', {
                method: 'POST',
                body: {
                    course_id: Number(courseId),
                },
                auth: true,
            });

            fillPaymentSummary(currentPayment);
            stripeBox.classList.remove('hidden');

            if (initStripeCard(currentPayment.client_secret)) {
                showMessage(paymentMessage, 'Intention de paiement créée. Termine le paiement Stripe, puis finalise l’inscription.', 'success');
            }
        } catch (error) {
            showMessage(paymentMessage, getErrorMessage(error, 'Impossible de créer l’intention de paiement.'), 'error');
        }
    });

    confirmPaymentButton.addEventListener('click', async function () {
        hideMessage(paymentMessage);

        if (!currentPayment || !stripe || !stripeElements) {
            showMessage(paymentMessage, 'Crée d’abord une intention de paiement.', 'error');
            return;
        }

        confirmPaymentButton.disabled = true;
        confirmPaymentButton.textContent = 'Paiement en cours...';

        try {
            const result = await stripe.confirmPayment({
                elements: stripeElements,
                confirmParams: {
                    payment_method_data: {
                        billing_details: {
                            name: getCurrentUserName(),
                        },
                    },
                },
                redirect: 'if_required',
            });

            if (result.error) {
                showMessage(paymentMessage, result.error.message || 'Le paiement Stripe a échoué.', 'error');
            } else {
                createEnrollmentButton.disabled = false;
                showMessage(paymentMessage, 'Paiement confirmé côté Stripe. Si le webhook est actif, clique maintenant sur "Finaliser l\'inscription".', 'success');
            }
        } catch (error) {
            showMessage(paymentMessage, getErrorMessage(error, 'Le paiement Stripe a échoué.'), 'error');
        } finally {
            confirmPaymentButton.disabled = false;
            confirmPaymentButton.textContent = 'Payer avec Stripe';
        }
    });

    createEnrollmentButton.addEventListener('click', async function () {
        hideMessage(enrollmentMessage);

        if (!currentPayment) {
            showMessage(enrollmentMessage, 'Crée puis confirme un paiement avant de finaliser.', 'error');
            return;
        }

        createEnrollmentButton.disabled = true;
        createEnrollmentButton.textContent = 'Inscription...';

        try {
            await fetchApi('/student/enrollments', {
                method: 'POST',
                body: {
                    course_id: Number(currentPayment.course_id),
                    payment_id: Number(currentPayment.payment_id),
                },
                auth: true,
            });

            showMessage(enrollmentMessage, 'Inscription finalisée avec succès.', 'success');
            paymentForm.reset();
            resetStripeBox();
            await loadEnrollments();
        } catch (error) {
            showMessage(enrollmentMessage, getErrorMessage(error, 'Impossible de finaliser cette inscription.'), 'error');
        } finally {
            createEnrollmentButton.disabled = false;
            createEnrollmentButton.textContent = "Finaliser l'inscription";
        }
    });

    enrollmentList.addEventListener('click', async function (event) {
        const button = event.target.closest('[data-withdraw-course]');

        if (!button) {
            return;
        }

        const courseId = button.getAttribute('data-withdraw-course');
        const shouldWithdraw = window.confirm('Veux-tu vraiment te retirer de ce cours ?');

        if (!shouldWithdraw) {
            return;
        }

        try {
            await fetchApi('/student/courses/' + courseId + '/withdraw', {
                method: 'POST',
                auth: true,
            });

            showMessage(enrollmentMessage, 'Retrait enregistré avec succès.', 'success');
            await loadEnrollments();
        } catch (error) {
            showMessage(enrollmentMessage, getErrorMessage(error, 'Impossible de se retirer du cours.'), 'error');
        }
    });

    await loadCourses();
    await loadEnrollments();
    resetStripeBox();
}

document.addEventListener('DOMContentLoaded', function () {
    initRecommendationsPage();
    initWishlistPage();
    initEnrollmentsPage();
});
