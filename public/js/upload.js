(function() {
    'use strict';

    const MAX = 5;

    document.addEventListener('DOMContentLoaded', () => {
        const areas = document.querySelectorAll('.upload-area');
        areas.forEach(initUploadArea);
    });

    function initUploadArea(area) {
        const input   = area.querySelector('input[type="file"]');
        const label   = area.querySelector('.upload-label');
        const preview = area.querySelector('.upload-preview');
        const counter = area.querySelector('.upload-counter');
        const form    = area.closest('form');

        if (!input || !label || !preview || !form) return;

        // Ukryj oryginalny input — będziemy dodawać ukryte inputy dynamicznie
        input.style.display = 'none';

        let files = []; // { file, dataUrl, inputEl }

        // Klik otwiera dialog
        label.addEventListener('click', () => {
            if (files.length >= MAX) return;
            const tmp = document.createElement('input');
            tmp.type = 'file';
            tmp.multiple = true;
            tmp.accept = 'image/*';
            tmp.onchange = () => addFiles(Array.from(tmp.files));
            tmp.click();
        });

        // Drag & drop
        ['dragenter','dragover'].forEach(e => {
            label.addEventListener(e, ev => { ev.preventDefault(); label.classList.add('drag-over'); });
        });
        ['dragleave','dragend'].forEach(e => {
            label.addEventListener(e, () => label.classList.remove('drag-over'));
        });
        label.addEventListener('drop', ev => {
            ev.preventDefault();
            label.classList.remove('drag-over');
            addFiles(Array.from(ev.dataTransfer.files));
        });

        function addFiles(newFiles) {
            const imgFiles = newFiles.filter(f => f.type.startsWith('image/'));
            const canAdd   = MAX - files.length;
            const toAdd    = imgFiles.slice(0, canAdd);

            toAdd.forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const entry = { file, dataUrl: e.target.result, inputEl: null };
                    files.push(entry);

                    // Stwórz ukryty input z plikiem i dołącz do formularza
                    const hidden = document.createElement('input');
                    hidden.type = 'file';
                    hidden.name = 'photos[]';
                    hidden.style.display = 'none';

                    // Wstaw plik do inputa przez DataTransfer
                    try {
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        hidden.files = dt.files;
                    } catch(err) {
                        console.warn('DataTransfer nie obsługiwany:', err);
                    }

                    form.appendChild(hidden);
                    entry.inputEl = hidden;

                    addThumb(entry);
                    update();
                };
                reader.readAsDataURL(file);
            });

            if (imgFiles.length > canAdd && canAdd === 0) {
                showMsg(`Osiągnięto limit ${MAX} zdjęć.`);
            } else if (imgFiles.length > canAdd) {
                showMsg(`Dodano ${toAdd.length} z ${imgFiles.length}. Limit: ${MAX} zdjęć.`);
            }
        }

        function addThumb(entry) {
            const thumb = document.createElement('div');
            thumb.className = 'upload-thumb';
            thumb.innerHTML = `
                <img src="${entry.dataUrl}" alt="">
                <button type="button" class="upload-thumb__remove" title="Usuń">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            `;
            thumb.querySelector('.upload-thumb__remove').addEventListener('click', () => {
                // Usuń plik z listy i input z formularza
                const idx = files.indexOf(entry);
                if (idx > -1) files.splice(idx, 1);
                if (entry.inputEl) entry.inputEl.remove();
                thumb.remove();
                update();
            });
            preview.appendChild(thumb);
        }

        function update() {
            if (counter) {
                counter.textContent = files.length > 0
                    ? `${files.length}/${MAX} zdjęć`
                    : '';
            }
            label.style.display = files.length >= MAX ? 'none' : 'flex';
        }

        function showMsg(msg) {
            let el = area.querySelector('.upload-info');
            if (!el) { el = document.createElement('p'); el.className = 'upload-info'; area.appendChild(el); }
            el.textContent = msg;
            clearTimeout(el._t);
            el._t = setTimeout(() => el.remove(), 4000);
        }
    }
})();
