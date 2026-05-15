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
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
            setTimeout(() => entry.target.classList.add('visible'), i * 80);
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.steps__item, .features__item, .reviews__card, .bottom-features__item').forEach(el => {
    el.classList.add('reveal');
    revealObserver.observe(el);
});

// Toggle helper
function toggleEl(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

// ---- UPLOAD ZDJĘĆ Z PODGLĄDEM ----
document.addEventListener('DOMContentLoaded', () => {
    const MAX_PHOTOS = 5;

    const uploadAreas = document.querySelectorAll('.upload-area');

    uploadAreas.forEach(area => {
        const input    = area.querySelector('input[type="file"]');
        const label    = area.querySelector('.upload-label');
        const preview  = area.querySelector('.upload-preview');
        const counter  = area.querySelector('.upload-counter');

        if (!input || !label || !preview) return;

        let selectedFiles = [];

        // Klik na label otwiera dialog
        label.addEventListener('click', () => input.click());

        // Drag & drop
        label.addEventListener('dragover', (e) => {
            e.preventDefault();
            label.classList.add('drag-over');
        });
        label.addEventListener('dragleave', () => label.classList.remove('drag-over'));
        label.addEventListener('drop', (e) => {
            e.preventDefault();
            label.classList.remove('drag-over');
            addFiles(Array.from(e.dataTransfer.files));
        });

        // Wybór przez dialog
        input.addEventListener('change', () => {
            addFiles(Array.from(input.files));
            input.value = ''; // reset żeby można wybrać te same pliki
        });

        function addFiles(newFiles) {
            const imageFiles = newFiles.filter(f => f.type.startsWith('image/'));
            const remaining  = MAX_PHOTOS - selectedFiles.length;
            const toAdd      = imageFiles.slice(0, remaining);

            toAdd.forEach(file => {
                selectedFiles.push(file);
                addPreviewThumb(file, selectedFiles.length - 1);
            });

            updateCounter();
            updateInput();

            if (imageFiles.length > remaining) {
                showUploadInfo(`Możesz dodać maksymalnie ${MAX_PHOTOS} zdjęć. Pominięto ${imageFiles.length - remaining} pliki.`);
            }
        }

        function addPreviewThumb(file, index) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const thumb = document.createElement('div');
                thumb.className = 'upload-thumb';
                thumb.dataset.index = index;
                thumb.innerHTML = `
                    <img src="${e.target.result}" alt="">
                    <button type="button" class="upload-thumb__remove" title="Usuń zdjęcie">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                `;
                thumb.querySelector('.upload-thumb__remove').addEventListener('click', () => {
                    selectedFiles.splice(index, 1);
                    // Przenumeruj pozostałe
                    preview.querySelectorAll('.upload-thumb').forEach((t, i) => t.dataset.index = i);
                    thumb.remove();
                    updateCounter();
                    updateInput();
                });
                preview.appendChild(thumb);
            };
            reader.readAsDataURL(file);
        }

        function updateCounter() {
            const count = selectedFiles.length;
            if (counter) {
                counter.textContent = count > 0
                    ? `${count}/${MAX_PHOTOS} zdjęć wybranych`
                    : '';
            }
            // Pokaż/ukryj label
            label.style.display = selectedFiles.length >= MAX_PHOTOS ? 'none' : 'flex';
        }

        function updateInput() {
            // Stwórz nowy DataTransfer z wybranymi plikami
            const dt = new DataTransfer();
            selectedFiles.forEach(f => dt.items.add(f));
            input.files = dt.files;
        }

        function showUploadInfo(msg) {
            let info = area.querySelector('.upload-info');
            if (!info) {
                info = document.createElement('p');
                info.className = 'upload-info';
                area.appendChild(info);
            }
            info.textContent = msg;
            setTimeout(() => info.remove(), 4000);
        }
    });
});
