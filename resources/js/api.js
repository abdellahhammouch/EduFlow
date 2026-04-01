const AUTH_STORAGE_KEY = 'eduflow.auth';

function safeParseJson(text) {
    try {
        return JSON.parse(text);
    } catch (error) {
        return null;
    }
}

export function getAuthSession() {
    const savedSession = window.localStorage.getItem(AUTH_STORAGE_KEY);

    if (!savedSession) {
        return null;
    }

    return safeParseJson(savedSession);
}

export function setAuthSession(payload) {
    const session = {
        token: payload.access_token,
        tokenType: payload.token_type ? payload.token_type : 'bearer',
        expiresIn: payload.expires_in ? payload.expires_in : null,
        user: payload.user ? payload.user : null,
    };

    window.localStorage.setItem(AUTH_STORAGE_KEY, JSON.stringify(session));

    return session;
}

export function updateAuthUser(user) {
    const session = getAuthSession();

    if (!session) {
        return;
    }

    session.user = user;
    window.localStorage.setItem(AUTH_STORAGE_KEY, JSON.stringify(session));
}

export function clearAuthSession() {
    window.localStorage.removeItem(AUTH_STORAGE_KEY);
}

export function getAccessToken() {
    const session = getAuthSession();

    if (!session) {
        return null;
    }

    return session.token;
}

export function getCurrentRole() {
    const session = getAuthSession();

    if (!session || !session.user) {
        return null;
    }

    return session.user.role;
}

export function dashboardRouteForRole(role) {
    if (role === 'teacher') {
        return window.eduFlowConfig.routes.teacherDashboard;
    }

    if (role === 'student') {
        return window.eduFlowConfig.routes.studentDashboard;
    }

    return window.eduFlowConfig.routes.login;
}

export function redirectToDashboard(role) {
    let finalRole = role;

    if (!finalRole) {
        finalRole = getCurrentRole();
    }

    window.location.assign(dashboardRouteForRole(finalRole));
}

function buildHeaders(options) {
    const headers = {
        Accept: 'application/json',
    };

    if (options && options.headers) {
        for (const key in options.headers) {
            headers[key] = options.headers[key];
        }
    }

    if (options && options.auth) {
        const token = getAccessToken();

        if (token) {
            headers.Authorization = 'Bearer ' + token;
        }
    }

    return headers;
}

export async function fetchApi(path, options) {
    const requestOptions = options || {};
    const method = requestOptions.method ? requestOptions.method : 'GET';
    const headers = buildHeaders(requestOptions);
    const config = {
        method: method,
        headers: headers,
    };

    if (requestOptions.body !== undefined) {
        headers['Content-Type'] = 'application/json';
        config.body = JSON.stringify(requestOptions.body);
    }

    let finalPath = path;

    if (finalPath.charAt(0) !== '/') {
        finalPath = '/' + finalPath;
    }

    const response = await fetch(window.eduFlowConfig.apiBaseUrl + finalPath, config);
    const contentType = response.headers.get('content-type');
    let data = null;

    if (contentType && contentType.indexOf('application/json') !== -1) {
        data = await response.json();
    }

    if (!response.ok) {
        const error = new Error('Une erreur est survenue.');

        if (data && data.message) {
            error.message = data.message;
        }

        error.status = response.status;
        error.data = data;
        throw error;
    }

    return data;
}

export async function fetchCurrentUser() {
    return await fetchApi('/auth/me', {
        auth: true,
    });
}
