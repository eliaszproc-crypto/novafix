(function() {
    'use strict';

    // Elementy widgetów
    const els = {
        cardTitle:    document.getElementById('heroCardTitle'),
        cardSub:      document.getElementById('heroCardSub'),
        cardFill:     document.getElementById('heroCardFill'),
        cardLabel:    document.getElementById('heroCardLabel'),
        cardValue:    document.getElementById('heroCardValue'),
        float1Title:  document.getElementById('heroFloat1Title'),
        float1Sub:    document.getElementById('heroFloat1Sub'),
        float2Title:  document.getElementById('heroFloat2Title'),
        float2Sub:    document.getElementById('heroFloat2Sub'),
    };

    // Jeśli nie ma widgetów na stronie - nie rób nic
    if (!els.cardTitle) return;

    function timeAgo(dateStr) {
        if (!dateStr) return 'Niedawno';
        const diff = Math.floor((Date.now() - new Date(dateStr).getTime()) / 1000);
        if (diff < 60)   return 'Przed chwilą';
        if (diff < 3600) return Math.floor(diff / 60) + ' min temu';
        if (diff < 86400) return Math.floor(diff / 3600) + ' godz. temu';
        const d = new Date(dateStr);
        return d.toLocaleDateString('pl-PL', { day: 'numeric', month: 'short' });
    }

    function animateValue(el, newVal) {
        if (!el) return;
        el.style.opacity = '0';
        el.style.transform = 'translateY(4px)';
        setTimeout(() => {
            el.textContent = newVal;
            el.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 150);
    }

    function updateWidgets(data) {
        // Karta główna - naprawa w toku
        if (data.active_repair) {
            const r = data.active_repair;
            animateValue(els.cardTitle, 'Naprawa w toku');
            animateValue(els.cardSub, r.rma_number);
            animateValue(els.cardLabel, r.brand ? r.brand + (r.model ? ' ' + r.model : '') : r.device_type);
            animateValue(els.cardValue, 'W naprawie');
            if (els.cardFill) {
                els.cardFill.style.width = '65%';
            }
        } else if (data.in_progress > 0) {
            animateValue(els.cardTitle, 'Naprawy w toku');
            animateValue(els.cardSub, 'Aktywne zlecenia');
            animateValue(els.cardLabel, 'W realizacji');
            animateValue(els.cardValue, data.in_progress + ' szt.');
            if (els.cardFill) els.cardFill.style.width = '50%';
        } else {
            animateValue(els.cardTitle, 'Gotowi na naprawy');
            animateValue(els.cardSub, 'Przyjmujemy zgłoszenia');
            animateValue(els.cardLabel, 'Zakończonych napraw');
            animateValue(els.cardValue, data.completed + '+');
            if (els.cardFill) els.cardFill.style.width = '100%';
        }

        // Float 1 - nowe zgłoszenia dziś
        if (data.new_today > 0) {
            animateValue(els.float1Title, data.new_today === 1 ? 'Nowe zgłoszenie' : data.new_today + ' nowe zgłoszenia');
            animateValue(els.float1Sub, 'Dziś');
        } else {
            animateValue(els.float1Title, 'Przyjmujemy zgłoszenia');
            animateValue(els.float1Sub, 'Online 24/7');
        }

        // Float 2 - ostatnio zaakceptowana wycena
        if (data.last_accepted) {
            const r = data.last_accepted;
            animateValue(els.float2Title, 'Wycena zaakceptowana');
            animateValue(els.float2Sub, timeAgo(r.initial_quote_decided_at));
        } else if (data.last_completed) {
            animateValue(els.float2Title, 'Naprawa zakończona');
            animateValue(els.float2Sub, timeAgo(data.last_completed));
        } else {
            animateValue(els.float2Title, 'Szybka realizacja');
            animateValue(els.float2Sub, 'Średnio 3-5 dni');
        }
    }

    async function fetchStats() {
        try {
            const res  = await fetch('/api/stats?t=' + Date.now());
            if (!res.ok) return;
            const data = await res.json();
            if (!data.error) updateWidgets(data);
        } catch (e) {
            // Cicho ignoruj błędy sieciowe
        }
    }

    // Pierwsze pobranie
    fetchStats();

    // Odświeżaj co 30 sekund
    setInterval(fetchStats, 30000);

})();
