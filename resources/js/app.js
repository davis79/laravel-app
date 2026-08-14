const sidebar = document.querySelector('#sidebar');
const overlay = document.querySelector('#sidebar-overlay');
const toggle = document.querySelector('#sidebar-toggle');

function setSidebar(open) {
    sidebar?.classList.toggle('-translate-x-full', !open);
    overlay?.classList.toggle('hidden', !open);
    document.body.classList.toggle('overflow-hidden', open && window.innerWidth < 1024);
}

toggle?.addEventListener('click', () => setSidebar(sidebar?.classList.contains('-translate-x-full')));
overlay?.addEventListener('click', () => setSidebar(false));
window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
        overlay?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
});

const themePicker = document.querySelector('[data-theme-picker]');
const themeToggle = themePicker?.querySelector('[data-theme-toggle]');
const themeMenu = themePicker?.querySelector('[data-theme-menu]');
const systemTheme = window.matchMedia('(prefers-color-scheme: dark)');

const themeLabels = {
    light: 'Jasny',
    dark: 'Ciemny',
    system: 'System',
};

function currentTheme() {
    return localStorage.getItem('theme') || 'system';
}

function applyTheme(theme) {
    const isDark = theme === 'dark' || (theme === 'system' && systemTheme.matches);

    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.classList.toggle('light', !isDark);
    document.documentElement.dataset.theme = theme;

    themePicker?.querySelectorAll('[data-theme-icon]').forEach((icon) => {
        icon.classList.toggle('hidden', icon.dataset.themeIcon !== theme);
    });
    themePicker?.querySelectorAll('[data-theme-check]').forEach((check) => {
        check.classList.toggle('hidden', check.dataset.themeCheck !== theme);
    });

    const label = themePicker?.querySelector('[data-theme-label]');
    if (label) label.textContent = themeLabels[theme];
}

themeToggle?.addEventListener('click', (event) => {
    event.stopPropagation();
    const opening = themeMenu?.classList.contains('hidden');
    themeMenu?.classList.toggle('hidden');
    themeToggle.setAttribute('aria-expanded', opening ? 'true' : 'false');
});

themePicker?.querySelectorAll('[data-theme-option]').forEach((option) => {
    option.addEventListener('click', () => {
        const theme = option.dataset.themeOption;
        localStorage.setItem('theme', theme);
        applyTheme(theme);
        themeMenu?.classList.add('hidden');
        themeToggle?.setAttribute('aria-expanded', 'false');
    });
});

document.addEventListener('click', (event) => {
    if (!themePicker?.contains(event.target)) {
        themeMenu?.classList.add('hidden');
        themeToggle?.setAttribute('aria-expanded', 'false');
    }
});

systemTheme.addEventListener('change', () => {
    if (currentTheme() === 'system') applyTheme('system');
});

applyTheme(currentTheme());
