import axios from 'axios';

// Définir axios comme une variable globale pour pouvoir l'utiliser partout
window.axios = axios;

// Ajout de l'en-tête "X-Requested-With" pour indiquer que c'est une requête AJAX
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Intercepteur pour inclure automatiquement le token CSRF
window.axios.interceptors.request.use((config) => {
    // Extraire le token CSRF du cookie XSRF-TOKEN
    const token = document.cookie
        .split('; ')
        .find(row => row.startsWith('XSRF-TOKEN='))
        ?.split('=')[1];

    if (token) {
        // Ajouter le token CSRF dans les en-têtes
        config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token);
    }

    return config;
}, (error) => {
    // Gérer les erreurs lors de la configuration de la requête
    return Promise.reject(error);
});

// Intercepteur pour gérer les erreurs liées au CSRF ou aux sessions expirées
window.axios.interceptors.response.use(
    (response) => response, // Renvoyer la réponse directement si elle est réussie
    (error) => {
        if (error.response?.status === 419) {
            alert('Votre session a expiré. Veuillez recharger la page.');
            window.location.reload();
        }

        return Promise.reject(error);
    }
);
