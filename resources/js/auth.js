import {
    clearAuthSession,
    fetchApi,
    redirectToDashboard,
    setAuthSession,
} from './api';

function findMessageBox(form) {
    const parent = form.parentElement;

    if (parent) {
        const parentMessageBox = parent.querySelector('[data-form-message]');

        if (parentMessageBox) {
            return parentMessageBox;
        }
    }

    return form.querySelector('[data-form-message]');
}

function setMessageClasses(messageBox, type) {
    messageBox.classList.remove(
        'hidden',
        'border-rose-200',
        'bg-rose-50',
        'text-rose-700',
        'border-emerald-200',
        'bg-emerald-50',
        'text-emerald-800'
    );

    if (type === 'success') {
        messageBox.classList.add('border-emerald-200', 'bg-emerald-50', 'text-emerald-800');
    } else {
        messageBox.classList.add('border-rose-200', 'bg-rose-50', 'text-rose-700');
    }
}

function showFormMessage(form, message, type) {
    const messageBox = findMessageBox(form);

    if (!messageBox) {
        return;
    }

    messageBox.textContent = message;
    setMessageClasses(messageBox, type || 'error');
}

function getRegisterDomains(form) {
    const checkedInputs = form.querySelectorAll('input[name="domain_ids[]"]:checked');
    const domainIds = [];

    for (let i = 0; i < checkedInputs.length; i += 1) {
        domainIds.push(Number(checkedInputs[i].value));
    }

    return domainIds;
}

function collectFormData(form) {
    const data = {};
    const elements = form.elements;

    for (let i = 0; i < elements.length; i += 1) {
        const element = elements[i];

        if (!element.name) {
            continue;
        }

        if (element.type === 'checkbox' || element.type === 'radio') {
            if (element.checked && element.name !== 'domain_ids[]') {
                data[element.name] = element.value;
            }
            continue;
        }

        data[element.name] = element.value;
    }

    if (form.getAttribute('data-auth-form') === 'register') {
        data.domain_ids = getRegisterDomains(form);
    }

    return data;
}

function setSubmitting(button, loading, defaultText) {
    if (!button) {
        return;
    }

    button.disabled = loading;

    if (loading) {
        button.textContent = 'Veuillez patienter...';
    } else {
        button.textContent = defaultText;
    }
}

function toggleStudentInterests(form) {
    const roleInputs = form.querySelectorAll('[data-role-toggle]');
    const studentInterestsSection = form.querySelector('[data-student-interests]');

    if (!roleInputs.length || !studentInterestsSection) {
        return;
    }

    function updateSection() {
        let selectedRole = '';

        for (let i = 0; i < roleInputs.length; i += 1) {
            if (roleInputs[i].checked) {
                selectedRole = roleInputs[i].value;
            }
        }

        if (selectedRole === 'student') {
            studentInterestsSection.classList.remove('hidden');
        } else {
            studentInterestsSection.classList.add('hidden');
        }
    }

    for (let i = 0; i < roleInputs.length; i += 1) {
        roleInputs[i].addEventListener('change', updateSection);
    }

    updateSection();
}

function getErrorMessage(error) {
    if (error.data && error.data.errors) {
        const errors = error.data.errors;
        let fullMessage = '';

        for (const key in errors) {
            if (fullMessage !== '') {
                fullMessage += ' ';
            }

            fullMessage += errors[key].join(' ');
        }

        return fullMessage;
    }

    if (error.message) {
        return error.message;
    }

    return 'Une erreur est survenue.';
}

async function handleLogin(form, payload) {
    const response = await fetchApi('/auth/login', {
        method: 'POST',
        body: payload,
    });

    setAuthSession(response);
    redirectToDashboard(response.user.role);
}

async function handleRegister(form, payload) {
    const response = await fetchApi('/auth/register', {
        method: 'POST',
        body: payload,
    });

    setAuthSession(response);
    redirectToDashboard(response.user.role);
}

async function handleForgotPassword(form, payload) {
    const response = await fetchApi('/auth/forgot-password', {
        method: 'POST',
        body: payload,
    });

    showFormMessage(form, response.message, 'success');
    form.reset();
}

async function handleResetPassword(payload) {
    const response = await fetchApi('/auth/reset-password', {
        method: 'POST',
        body: payload,
    });

    clearAuthSession();
    window.location.assign(
        window.eduFlowConfig.routes.login +
        '?message=' + encodeURIComponent(response.message) +
        '&type=success'
    );
}

async function submitForm(form) {
    const formType = form.getAttribute('data-auth-form');
    const submitButton = form.querySelector('button[type="submit"]');
    const defaultText = submitButton ? submitButton.getAttribute('data-submit-label') : '';
    const payload = collectFormData(form);

    setSubmitting(submitButton, true, defaultText);

    try {
        if (formType === 'login') {
            await handleLogin(form, payload);
        } else if (formType === 'register') {
            await handleRegister(form, payload);
        } else if (formType === 'forgot-password') {
            await handleForgotPassword(form, payload);
        } else if (formType === 'reset-password') {
            await handleResetPassword(payload);
        }
    } catch (error) {
        showFormMessage(form, getErrorMessage(error), 'error');
    } finally {
        setSubmitting(submitButton, false, defaultText);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('[data-auth-form]');

    for (let i = 0; i < forms.length; i += 1) {
        const form = forms[i];

        if (form.getAttribute('data-auth-form') === 'register') {
            toggleStudentInterests(form);
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitForm(form);
        });
    }
});
