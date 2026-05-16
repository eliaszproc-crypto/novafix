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
if (burger && navMenu) {
    burger.addEventListener('click', () => {
        const open = navMenu.classList.toggle('open');
        burger.setAttribute('aria-expanded', open);
        document.body.style.overflow = open ? 'hidden' : '';
    });
    navMenu.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            navMenu.classList.remove('open');
            document.body.style.overflow = '';
        });
    });
    document.addEventListener('click', (e) => {
        if (!navbar.contains(e.target)) {
            navMenu.classList.remove('open');
            document.body.style.overflow = '';
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
