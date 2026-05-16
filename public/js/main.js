// Navbar scroll
const navbar = document.getElementById('navbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 20);
    }, { passive: true });
}

// Mobile burger menu
const burger  = document.getElementById('burgerBtn');
const navMenu = document.getElementById('navMenu');

function openMenu() {
    navMenu.classList.add('open');
    burger.classList.add('is-open');
}

function closeMenu() {
    navMenu.classList.remove('open');
    burger.classList.remove('is-open');
}

if (burger && navMenu) {
    burger.addEventListener('click', () => {
        navMenu.classList.contains('open') ? closeMenu() : openMenu();
    });
    navMenu.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', closeMenu);
    });
    // Zamknij przez kliknięcie w nakładkę (::after nie łapie zdarzeń - używamy click na dokumencie)
    document.addEventListener('click', (e) => {
        if (navMenu.classList.contains('open') && !navMenu.contains(e.target) && e.target !== burger && !burger.contains(e.target)) {
            closeMenu();
        }
    });
}

// Scroll reveal — IntersectionObserver
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
            setTimeout(() => entry.target.classList.add('visible'), i * 60);
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll(
    '.steps__item, .features__item, .reviews__card, .bottom-features__item, .service-card, .contact-card, .pricing-info-item'
).forEach(el => {
    el.classList.add('reveal');
    revealObserver.observe(el);
});

// Toggle helper
function toggleEl(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
