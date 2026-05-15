// Navbar scroll
const navbar = document.getElementById('navbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 20);
    }, { passive: true });
}

// Mobile burger menu
const burger   = document.getElementById('burgerBtn');
const navMenu  = document.getElementById('navMenu');
const navActs  = document.querySelector('.navbar__actions');

function openMenu() {
    navMenu.classList.add('open');
    if (navActs) navActs.classList.add('open');
    document.body.style.overflow = 'hidden';
    burger.setAttribute('aria-expanded', 'true');
    // Zmień ikonę burgera na X
    burger.innerHTML = '<span style="transform:rotate(45deg) translate(5px,5px)"></span><span style="opacity:0"></span><span style="transform:rotate(-45deg) translate(5px,-5px)"></span>';
}

function closeMenu() {
    navMenu.classList.remove('open');
    if (navActs) navActs.classList.remove('open');
    document.body.style.overflow = '';
    burger.setAttribute('aria-expanded', 'false');
    burger.innerHTML = '<span></span><span></span><span></span>';
}

if (burger && navMenu) {
    burger.addEventListener('click', () => {
        navMenu.classList.contains('open') ? closeMenu() : openMenu();
    });
    navMenu.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', closeMenu);
    });
    if (navActs) {
        navActs.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', closeMenu);
        });
    }
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
