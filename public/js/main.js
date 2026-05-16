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
let menuScrollY = 0;

function lockScroll() {
    menuScrollY = window.scrollY;
    document.body.style.position = 'fixed';
    document.body.style.top = '-' + menuScrollY + 'px';
    document.body.style.left = '0';
    document.body.style.right = '0';
    document.body.style.overflow = 'hidden';
}

function unlockScroll() {
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.left = '';
    document.body.style.right = '';
    document.body.style.overflow = '';
    window.scrollTo(0, menuScrollY);
}

if (burger && navMenu) {
    burger.addEventListener('click', () => {
        const isOpen = navMenu.classList.contains('open');
        if (!isOpen) {
            lockScroll();
            requestAnimationFrame(() => {
                navMenu.classList.add('open');
                burger.setAttribute('aria-expanded', 'true');
            });
        } else {
            navMenu.classList.remove('open');
            burger.setAttribute('aria-expanded', 'false');
            unlockScroll();
        }
    });
    navMenu.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            navMenu.classList.remove('open');
            unlockScroll();
        });
    });
    document.addEventListener('click', (e) => {
        if (navMenu.classList.contains('open') && !navbar.contains(e.target)) {
            navMenu.classList.remove('open');
            unlockScroll();
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
