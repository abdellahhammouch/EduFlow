import {
    clearAuthSession,
    dashboardRouteForRole,
    fetchCurrentUser,
    getAuthSession,
    updateAuthUser,
} from './api';

function hideElement(element) {
    if (element) {
        element.classList.remove('flex');
        element.classList.add('hidden');
    }
}

function showElement(element) {
    if (element) {
        element.classList.remove('hidden');

        if (element.hasAttribute('data-authenticated-nav')) {
            element.classList.add('flex');
        }
    }
}

function setTextOnElements(selector, text) {
    const elements = document.querySelectorAll(selector);

    for (let i = 0; i < elements.length; i += 1) {
        elements[i].textContent = text;
    }
}

function renderNavigation() {
    const session = getAuthSession();
    const user = session ? session.user : null;
    const authNav = document.querySelector('[data-authenticated-nav]');
    const dashboardLink = document.querySelector('[data-dashboard-link]');
    const mobileDashboardLink = document.querySelector('[data-mobile-dashboard-link]');
    const mobileLogoutButton = document.querySelector('[data-mobile-logout-trigger]');
    const loginLinks = document.querySelectorAll('a[href="' + window.eduFlowConfig.routes.login + '"]');
    const registerLinks = document.querySelectorAll('a[href="' + window.eduFlowConfig.routes.register + '"]');

    if (user) {
        showElement(authNav);
        showElement(dashboardLink);
        showElement(mobileDashboardLink);
        showElement(mobileLogoutButton);

        for (let i = 0; i < loginLinks.length; i += 1) {
            hideElement(loginLinks[i]);
        }

        for (let i = 0; i < registerLinks.length; i += 1) {
            hideElement(registerLinks[i]);
        }

        setTextOnElements('[data-auth-user-name], [data-page-user-name]', user.name);

        if (dashboardLink) {
            dashboardLink.href = dashboardRouteForRole(user.role);
        }

        if (mobileDashboardLink) {
            mobileDashboardLink.href = dashboardRouteForRole(user.role);
        }
    } else {
        hideElement(authNav);
        hideElement(dashboardLink);
        hideElement(mobileDashboardLink);
        hideElement(mobileLogoutButton);
    }
}

function initMobileNav() {
    const toggleButton = document.querySelector('[data-mobile-nav-toggle]');
    const mobileNav = document.querySelector('[data-mobile-nav]');

    if (!toggleButton || !mobileNav) {
        return;
    }

    toggleButton.addEventListener('click', function () {
        if (mobileNav.classList.contains('hidden')) {
            mobileNav.classList.remove('hidden');
        } else {
            mobileNav.classList.add('hidden');
        }
    });
}

function logoutAndRedirect() {
    const session = getAuthSession();
    let token = '';

    if (session && session.token) {
        token = session.token;
    }

    fetch(window.eduFlowConfig.apiBaseUrl + '/auth/logout', {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            Authorization: 'Bearer ' + token,
        },
    }).finally(function () {
        clearAuthSession();
        window.location.assign(window.eduFlowConfig.routes.login);
    });
}

function bindLogoutButtons() {
    const buttons = document.querySelectorAll('[data-logout-trigger], [data-mobile-logout-trigger]');

    for (let i = 0; i < buttons.length; i += 1) {
        buttons[i].addEventListener('click', function () {
            logoutAndRedirect();
        });
    }
}

function setMessageStyles(container, type) {
    container.classList.remove(
        'hidden',
        'border-emerald-200',
        'bg-emerald-50',
        'text-emerald-800',
        'border-rose-200',
        'bg-rose-50',
        'text-rose-700'
    );

    if (type === 'error') {
        container.classList.add('border-rose-200', 'bg-rose-50', 'text-rose-700');
    } else {
        container.classList.add('border-emerald-200', 'bg-emerald-50', 'text-emerald-800');
    }
}

function showGlobalMessage(message, type) {
    const container = document.querySelector('[data-global-message]');

    if (!container || !message) {
        return;
    }

    container.textContent = message;
    setMessageStyles(container, type || 'success');
}

async function protectRolePage() {
    const requiredRole = document.body.getAttribute('data-guard-role');

    if (!requiredRole) {
        return;
    }

    const session = getAuthSession();

    if (!session || !session.token || !session.user || session.user.role !== requiredRole) {
        clearAuthSession();
        window.location.assign(window.eduFlowConfig.routes.login);
        return;
    }

    try {
        const payload = await fetchCurrentUser();
        updateAuthUser(payload.user);
        renderNavigation();
    } catch (error) {
        clearAuthSession();
        showGlobalMessage('Votre session a expiré. Merci de vous reconnecter.', 'error');
        window.location.assign(window.eduFlowConfig.routes.login);
    }
}

function showFlashMessageFromQuery() {
    const url = new URL(window.location.href);
    const message = url.searchParams.get('message');
    const type = url.searchParams.get('type');

    if (message) {
        showGlobalMessage(message, type);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    renderNavigation();
    initMobileNav();
    bindLogoutButtons();
    protectRolePage();
    showFlashMessageFromQuery();
});
