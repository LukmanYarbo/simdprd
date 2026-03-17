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
    // Reset icons - remove both filled and standard Tabler icon classes
    themeIcon.classList.remove(
      'ti-sun-filled', 'ti-moon-stars-filled', 'ti-glass-full', 
      'ti-sun', 'ti-moon-stars', 'ti-moon-filled', 'ti-droplet', 'ti-glass'
    );
    
    if (theme === 'dark') {
        themeIcon.classList.add('ti-moon-stars'); // Using standard icon for better compatibility
        themeIcon.title = "Dark Mode";
    } else if (theme === 'transparent') {
        themeIcon.classList.add('ti-glass'); // Using standard icon
        themeIcon.title = "Premium Mode";
    } else {
        themeIcon.classList.add('ti-sun'); // Using standard icon
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
