import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;
import '../css/app.scss';

/**
 * Theme Management System
 * Handles Dark, Light, and Transparent (Premium) modes
 * Supports Persistence and Livewire (SPA) navigation
 */
const getStoredTheme = () => localStorage.getItem('theme');
const setStoredTheme = theme => localStorage.setItem('theme', theme);

const getPreferredTheme = () => {
    const storedTheme = getStoredTheme();
    if (storedTheme) return storedTheme;
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

const setTheme = theme => {
    if (theme === 'auto') {
        document.documentElement.setAttribute('data-bs-theme', (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
    } else {
        document.documentElement.setAttribute('data-bs-theme', theme);
    }

    const themeIcon = document.getElementById('theme-icon');
    if (themeIcon) {
        themeIcon.classList.remove(
            'ti-sun-filled', 'ti-moon-stars-filled', 'ti-glass-full', 
            'ti-sun', 'ti-moon-stars', 'ti-moon-filled', 'ti-droplet', 'ti-glass'
        );
        
        if (theme === 'dark') {
            themeIcon.classList.add('ti-moon-stars');
            themeIcon.title = "Dark Mode";
        } else if (theme === 'transparent') {
            themeIcon.classList.add('ti-glass');
            themeIcon.title = "Premium Mode";
        } else {
            themeIcon.classList.add('ti-sun');
            themeIcon.title = "Light Mode";
        }
    }
};

/**
 * Initial Theme Set
 */
setTheme(getPreferredTheme());

/**
 * Re-initialize UI Listeners
 * This function is called on initial load and after every Livewire navigation
 */
function initUI() {
    // 1. Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebarClose = document.getElementById('sidebarClose');

    if (sidebarCollapse && sidebar) {
        sidebarCollapse.onclick = () => sidebar.classList.toggle('active');
    }
    if (sidebarClose && sidebar) {
        sidebarClose.onclick = () => sidebar.classList.remove('active');
    }

    // 2. Theme Toggle Listener
    const themeToggleBtn = document.querySelector('#theme-toggle');
    if (themeToggleBtn) {
        themeToggleBtn.onclick = () => {
            const currentTheme = getStoredTheme() || 'light';
            let newTheme = 'light';
            
            if (currentTheme === 'light') newTheme = 'dark';
            else if (currentTheme === 'dark') newTheme = 'transparent';
            else if (currentTheme === 'transparent') newTheme = 'light';
            
            setStoredTheme(newTheme);
            setTheme(newTheme);
        };
    }

    // 3. Re-apply current theme (fixes Livewire's attribute syncing)
    setTheme(getPreferredTheme());
}

// Global Listeners
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    const storedTheme = getStoredTheme();
    if (storedTheme !== 'light' && storedTheme !== 'dark' && storedTheme !== 'transparent') {
        setTheme(getPreferredTheme());
    }
});

// Run on standard page load
document.addEventListener('DOMContentLoaded', initUI);

// Run on Livewire SPA navigation
document.addEventListener('livewire:navigated', initUI);
