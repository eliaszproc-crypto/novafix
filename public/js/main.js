// Navbar scroll
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
});

// Mobile burger
const burger = document.getElementById('burgerBtn');
const navMenu = document.getElementById('navMenu');
if (burger && navMenu) {
    burger.addEventListener('click', () => navMenu.classList.toggle('open'));
}

// Scroll reveal
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
            setTimeout(() => entry.target.classList.add('visible'), i * 80);
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.steps__item, .features__item, .reviews__card, .bottom-features__item').forEach(el => {
    el.classList.add('reveal');
    observer.observe(el);
});
