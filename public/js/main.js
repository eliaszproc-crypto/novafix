// Navbar scroll
const navbar = document.getElementById('navbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 20);
    }, { passive: true });
}

// Mobile burger menu
const burger      = document.getElementById('burgerBtn');
const mobileMenu  = document.getElementById('mobileMenu');
const overlay     = document.getElementById('menuOverlay');

function openMenu() {
    if (!mobileMenu) return;
    mobileMenu.classList.add('open');
    burger.classList.add('is-open');
    if (overlay) overlay.classList.add('open');
}

function closeMenu() {
    if (!mobileMenu) return;
    mobileMenu.classList.remove('open');
    burger.classList.remove('is-open');
    if (overlay) overlay.classList.remove('open');
}

if (burger && mobileMenu) {
    burger.addEventListener('click', () => {
        mobileMenu.classList.contains('open') ? closeMenu() : openMenu();
    });
    mobileMenu.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', closeMenu);
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
