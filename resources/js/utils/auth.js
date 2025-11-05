// pequenos helpers de autenticação usados pela interface cliente
export function isLoggedIn() {
    try {
        // prefere a presença do token como indicação de sessão autenticada no cliente
        return !!localStorage.getItem("token");
    } catch (e) {
        return false;
    }
}

// armazena um caminho pretendido para que, após o login, a app possa redirecionar de volta
export function saveIntended(path) {
    try {
        localStorage.setItem("intendedPath", path);
    } catch (e) {}
}

export function getIntended() {
    try {
        const p = localStorage.getItem("intendedPath");
        localStorage.removeItem("intendedPath");
        return p;
    } catch (e) {
        return null;
    }
}

export function requireLogin(navigateFn, intendedPath) {
    if (isLoggedIn()) return true;
    if (intendedPath) saveIntended(intendedPath);
    // navigateFn deve executar uma navegação para a tela de login (ex.: () => window.location.href = '/login')
    try {
        if (typeof navigateFn === "function") navigateFn();
    } catch (e) {}
    return false;
}
