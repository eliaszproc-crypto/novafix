// Navbar scroll
const navbar = document.getElementById('navbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 20);
    });
}

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

// Toggle helper
function toggleEl(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

// File upload - podgląd zdjęć + drag&drop
document.addEventListener('DOMContentLoaded', () => {
    const fileInputs = document.querySelectorAll('input[type="file"][name="photos[]"]');

    fileInputs.forEach(input => {
        const label = input.nextElementSibling;
        if (!label) return;

        // Podgląd po wyborze plików
        input.addEventListener('change', () => showPreviews(input));

        // Drag & drop
        label.addEventListener('dragover', (e) => {
            e.preventDefault();
            label.classList.add('drag-over');
        });
        label.addEventListener('dragleave', () => label.classList.remove('drag-over'));
        label.addEventListener('drop', (e) => {
            e.preventDefault();
            label.classList.remove('drag-over');
            input.files = e.dataTransfer.files;
            showPreviews(input);
        });
    });

    function showPreviews(input) {
        // Usuń stary podgląd
        const existing = input.parentElement.querySelector('.file-upload__preview');
        if (existing) existing.remove();

        if (!input.files.length) return;

        const preview = document.createElement('div');
        preview.className = 'file-upload__preview';

        Array.from(input.files).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });

        // Aktualizuj tekst labela
        const span = input.nextElementSibling?.querySelector('span');
        if (span) span.textContent = `Wybrano ${input.files.length} plik(ów)`;

        input.parentElement.appendChild(preview);
    }
});
