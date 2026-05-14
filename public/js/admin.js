// Aktywny link w sidebar
document.querySelectorAll('.sidebar__link').forEach(link => {
    if (link.href === window.location.href) {
        link.classList.add('active');
    }
});
