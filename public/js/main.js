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

// ---- SLIDER OPINII ----
(function() {
    const track  = document.getElementById('reviewsTrack');
    const prev   = document.getElementById('reviewsPrev');
    const next   = document.getElementById('reviewsNext');
    const dotsEl = document.getElementById('reviewsDots');
    if (!track) return;

    const cards   = track.querySelectorAll('.reviews__card');
    const total   = cards.length / 2; // połowa bo duplikujemy
    let current   = 0;
    let auto;

    // Ustal ile kart na ekranie
    function perView() {
        const w = window.innerWidth;
        if (w < 600) return 1;
        if (w < 900) return 2;
        return 3;
    }

    const steps = total - perView() + 1;

    // Stwórz kropki
    for (let i = 0; i < steps; i++) {
        const dot = document.createElement('button');
        dot.className = 'reviews-dot' + (i === 0 ? ' active' : '');
        dot.addEventListener('click', () => goTo(i));
        dotsEl.appendChild(dot);
    }

    function goTo(n) {
        current = Math.max(0, Math.min(n, steps - 1));
        const cardW = cards[0].offsetWidth + 20; // width + gap
        track.style.transform = `translateX(-${current * cardW}px)`;
        dotsEl.querySelectorAll('.reviews-dot').forEach((d, i) => {
            d.classList.toggle('active', i === current);
        });
    }

    function startAuto() {
        auto = setInterval(() => {
            goTo(current + 1 < steps ? current + 1 : 0);
        }, 4000);
    }

    function stopAuto() {
        clearInterval(auto);
    }

    prev.addEventListener('click', () => { stopAuto(); goTo(current - 1 < 0 ? steps - 1 : current - 1); startAuto(); });
    next.addEventListener('click', () => { stopAuto(); goTo(current + 1 < steps ? current + 1 : 0); startAuto(); });

    // Swipe na mobile
    let touchX = 0;
    track.addEventListener('touchstart', e => { touchX = e.touches[0].clientX; stopAuto(); }, { passive: true });
    track.addEventListener('touchend', e => {
        const dx = e.changedTouches[0].clientX - touchX;
        if (Math.abs(dx) > 40) goTo(dx < 0 ? current + 1 : current - 1);
        startAuto();
    });

    window.addEventListener('resize', () => goTo(0));
    startAuto();
})();
