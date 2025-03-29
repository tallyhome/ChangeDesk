// Configuration du mode sombre
const darkModeConfig = {
    storageKey: 'darkMode',
    themeAttribute: 'data-bs-theme',
    darkClass: 'dark-mode',
    transitionDuration: 200,
    iconSelector: '#theme-icon',
    moonIcon: 'fa-moon',
    sunIcon: 'fa-sun'
};

// Classe principale pour gérer le mode sombre
class DarkModeManager {
    constructor(config) {
        this.config = config;
        this.icon = document.querySelector(config.iconSelector);
        this.isDark = this.getInitialState();
        this.setupEventListeners();
        this.applyTheme();
    }

    getInitialState() {
        const stored = localStorage.getItem(this.config.storageKey);
        if (stored !== null) {
            return stored === 'true';
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    setupEventListeners() {
        // Écouteur pour le bouton de basculement
        document.addEventListener('DOMContentLoaded', () => {
            const themeButton = document.querySelector('button[onclick="toggleTheme()"]');
            if (themeButton) {
                themeButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleTheme();
                });
            }
        });

        // Écouteur pour les changements de préférence système
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem(this.config.storageKey)) {
                this.isDark = e.matches;
                this.applyTheme();
            }
        });
    }

    toggleTheme() {
        this.isDark = !this.isDark;
        localStorage.setItem(this.config.storageKey, this.isDark);
        this.applyTheme();
    }

    applyTheme() {
        // Appliquer le thème immédiatement pour éviter les flashs
        document.documentElement.setAttribute(this.config.themeAttribute, this.isDark ? 'dark' : 'light');
        document.body.classList.toggle(this.config.darkClass, this.isDark);

        // Mettre à jour l'icône avec une animation fluide
        if (this.icon) {
            this.icon.style.transform = 'rotate(360deg)';
            this.icon.style.transition = 'transform 0.3s ease';
            setTimeout(() => {
                this.icon.className = `fas ${this.isDark ? this.config.sunIcon : this.config.moonIcon}`;
                this.icon.style.transform = 'rotate(0deg)';
            }, 150);
        }

        // Déclencher un événement personnalisé pour les autres composants
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { isDark: this.isDark } }));
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    window.darkModeManager = new DarkModeManager(darkModeConfig);
});

// Fonction globale pour la compatibilité
function toggleTheme() {
    if (window.darkModeManager) {
        window.darkModeManager.toggleTheme();
    }
} 