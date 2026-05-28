(function() {
'use strict';

var lb, lbImg, lbClose, lbZoomIn, lbZoomOut, lbReset;
var scale = 1, posX = 0, posY = 0;
var dragging = false, startX, startY, startPosX, startPosY;
var lastTouchDist = 0;

function init() {
    // Stwórz overlay
    lb = document.createElement('div');
    lb.id = 'lightbox';
    lb.innerHTML = `
        <div class="lb-overlay"></div>
        <div class="lb-container">
            <img class="lb-img" id="lbImg" src="" alt="">
        </div>
        <div class="lb-controls">
            <button class="lb-btn" id="lbZoomIn" title="Powiększ">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
            </button>
            <button class="lb-btn" id="lbZoomOut" title="Pomniejsz">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
            </button>
            <button class="lb-btn" id="lbReset" title="Resetuj zoom">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            </button>
            <button class="lb-btn lb-btn--close" id="lbClose" title="Zamknij">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>`;

    document.body.appendChild(lb);

    lbImg    = document.getElementById('lbImg');
    lbClose  = document.getElementById('lbClose');
    lbZoomIn = document.getElementById('lbZoomIn');
    lbZoomOut= document.getElementById('lbZoomOut');
    lbReset  = document.getElementById('lbReset');

    // Eventy
    lb.querySelector('.lb-overlay').addEventListener('click', close);
    lbClose.addEventListener('click', close);
    lbZoomIn.addEventListener('click', function() { zoom(1.3); });
    lbZoomOut.addEventListener('click', function() { zoom(0.77); });
    lbReset.addEventListener('click', reset);

    // Drag
    lbImg.addEventListener('mousedown', onDragStart);
    window.addEventListener('mousemove', onDragMove);
    window.addEventListener('mouseup', onDragEnd);

    // Scroll zoom
    lbImg.addEventListener('wheel', function(e) {
        e.preventDefault();
        zoom(e.deltaY < 0 ? 1.15 : 0.87);
    }, { passive: false });

    // Touch zoom (pinch)
    lbImg.addEventListener('touchstart', onTouchStart, { passive: true });
    lbImg.addEventListener('touchmove', onTouchMove, { passive: false });
    lbImg.addEventListener('touchend', onDragEnd);

    // Klawisz Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') close();
    });
}

function open(src) {
    if (!lb) init();
    scale = 1; posX = 0; posY = 0;
    lbImg.src = src;
    lbImg.style.transform = 'translate(0,0) scale(1)';
    lbImg.style.cursor = 'grab';
    lb.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function close() {
    lb.classList.remove('active');
    document.body.style.overflow = '';
    setTimeout(function() { lbImg.src = ''; }, 300);
}

function zoom(factor) {
    scale = Math.min(Math.max(scale * factor, 0.5), 8);
    applyTransform();
}

function reset() {
    scale = 1; posX = 0; posY = 0;
    applyTransform();
}

function applyTransform() {
    lbImg.style.transform = 'translate(' + posX + 'px,' + posY + 'px) scale(' + scale + ')';
}

function onDragStart(e) {
    dragging = true;
    startX = e.clientX - posX;
    startY = e.clientY - posY;
    lbImg.style.cursor = 'grabbing';
    e.preventDefault();
}

function onDragMove(e) {
    if (!dragging) return;
    posX = e.clientX - startX;
    posY = e.clientY - startY;
    applyTransform();
}

function onDragEnd() {
    dragging = false;
    if (lbImg) lbImg.style.cursor = scale > 1 ? 'grab' : 'grab';
}

function onTouchStart(e) {
    if (e.touches.length === 2) {
        lastTouchDist = Math.hypot(
            e.touches[0].clientX - e.touches[1].clientX,
            e.touches[0].clientY - e.touches[1].clientY
        );
    } else if (e.touches.length === 1) {
        dragging = true;
        startX = e.touches[0].clientX - posX;
        startY = e.touches[0].clientY - posY;
    }
}

function onTouchMove(e) {
    e.preventDefault();
    if (e.touches.length === 2) {
        var dist = Math.hypot(
            e.touches[0].clientX - e.touches[1].clientX,
            e.touches[0].clientY - e.touches[1].clientY
        );
        if (lastTouchDist > 0) zoom(dist / lastTouchDist);
        lastTouchDist = dist;
    } else if (e.touches.length === 1 && dragging) {
        posX = e.touches[0].clientX - startX;
        posY = e.touches[0].clientY - startY;
        applyTransform();
    }
}

// Przechwytuj kliknięcia w linki ze zdjęciami
document.addEventListener('click', function(e) {
    var link = e.target.closest('a[href]');
    if (!link) return;
    var href = link.getAttribute('href');
    if (!href) return;
    var ext = href.split('.').pop().toLowerCase().split('?')[0];
    if (['jpg','jpeg','png','webp','gif'].indexOf(ext) === -1) return;
    e.preventDefault();
    open(href);
});

// Inicjalizuj po załadowaniu
document.addEventListener('DOMContentLoaded', init);
})();
