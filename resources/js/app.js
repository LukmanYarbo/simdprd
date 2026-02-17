import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;
import '../css/app.scss';

// Sidebar & Components Toggle
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebarClose = document.getElementById('sidebarClose');

    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });
    }
});

// Theme Toggler
const getStoredTheme = () => localStorage.getItem('theme')
const setStoredTheme = theme => localStorage.setItem('theme', theme)

const getPreferredTheme = () => {
  const storedTheme = getStoredTheme()
  if (storedTheme) {
    return storedTheme
  }

  return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
}

const setTheme = theme => {
  if (theme === 'auto') {
    document.documentElement.setAttribute('data-bs-theme', (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'))
  } else {
    document.documentElement.setAttribute('data-bs-theme', theme)
  }

  const themeIcon = document.getElementById('theme-icon');
  if (themeIcon) {
    // Reset icons
    themeIcon.classList.remove('bi-sun-fill', 'bi-moon-stars-fill', 'bi-droplet-fill');
    
    if (theme === 'dark') {
        themeIcon.classList.add('bi-moon-stars-fill');
        themeIcon.title = "Dark Mode";
    } else if (theme === 'transparent') {
        themeIcon.classList.add('bi-droplet-fill'); // Icon for transparent/glass theme
        themeIcon.title = "Transparent Mode";
    } else {
        themeIcon.classList.add('bi-sun-fill');
        themeIcon.title = "Light Mode";
    }
  }
}

setTheme(getPreferredTheme())

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
  const storedTheme = getStoredTheme()
  if (storedTheme !== 'light' && storedTheme !== 'dark' && storedTheme !== 'transparent') {
    setTheme(getPreferredTheme())
  }
})

window.addEventListener('DOMContentLoaded', () => {
  const themeToggleFn = document.querySelector('#theme-toggle')
  
  if(themeToggleFn) {
      themeToggleFn.addEventListener('click', () => {
        const currentTheme = getStoredTheme() || 'light';
        let newTheme = 'light';
        
        if (currentTheme === 'light') {
            newTheme = 'dark';
        } else if (currentTheme === 'dark') {
            newTheme = 'transparent';
        } else if (currentTheme === 'transparent') {
            newTheme = 'light';
        }
        
        setStoredTheme(newTheme)
        setTheme(newTheme)
      })
  }
})
