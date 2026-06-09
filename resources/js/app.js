import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('theme', {
    value: localStorage.getItem('theme') || 'system',
    init() {
        this.apply();
    },
    set(value) {
        this.value = value;
        localStorage.setItem('theme', value);
        this.apply();
    },
    apply() {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const enabled = this.value === 'dark' || (this.value === 'system' && prefersDark);
        document.documentElement.classList.toggle('dark', enabled);
    },
});

Alpine.start();
