(function() {
    'use strict';

    // Dodaj klasę loaded po załadowaniu
    document.documentElement.classList.add('page-loading');

    window.addEventListener('load', () => {
        document.documentElement.classList.remove('page-loading');
        document.documentElement.classList.add('page-loaded');
    });

    // Fade out przy kliknięciu w link
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link) return;
        const href = link.getAttribute('href');
        if (!href) return;
        // Tylko linki wewnętrzne, nie formularze, nie anchory, nie nowe zakładki
        if (href.startsWith('#') || href.startsWith('http') || href.startsWith('mailto') || href.startsWith('tel')) return;
        if (link.target === '_blank') return;
        if (e.ctrlKey || e.metaKey || e.shiftKey) return;

        e.preventDefault();
        document.documentElement.classList.add('page-leaving');

        setTimeout(() => {
            window.location.href = href;
        }, 250);
    });

    // Fade in przy powrocie (back/forward)
    window.addEventListener('pageshow', (e) => {
        if (e.persisted) {
            document.documentElement.classList.remove('page-leaving');
            document.documentElement.classList.add('page-loaded');
        }
    });
})();
