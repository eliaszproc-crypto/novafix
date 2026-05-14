<!-- HERO -->
<section class="hero">
    <div class="hero__bg"></div>
    <?php if (file_exists(ROOT_PATH . '/public/images/hero/hero-bg.jpg') || file_exists(ROOT_PATH . '/public/images/hero/hero-bg.png') || file_exists(ROOT_PATH . '/public/images/hero/hero-bg.webp')): ?>
    <div class="hero__img" style="background-image:url('/images/hero/hero-bg.<?= file_exists(ROOT_PATH.'/public/images/hero/hero-bg.webp') ? 'webp' : (file_exists(ROOT_PATH.'/public/images/hero/hero-bg.png') ? 'png' : 'jpg') ?>')"></div>
    <?php else: ?>
    <div class="hero__img"></div>
    <?php endif; ?>
    <div class="hero__grid"></div>
    <div class="container hero__inner">
        <div class="hero__content">
            <div class="hero__badge">
                <span class="hero__badge-dot"></span>
                Serwis akwarystyczny nr 1 w Polsce
            </div>
            <h1 class="hero__title">
                Profesjonalny<br>
                serwis sprzętu<br>
                <span class="hero__title--accent">akwarystycznego</span>
            </h1>
            <p class="hero__desc">
                Naprawiamy lampy LED, falowniki, sterowniki,<br>
                pompy i inną automatykę akwariową.
            </p>
            <div class="hero__actions">
                <a href="/panel/nowe-zgloszenie" class="btn btn--primary btn--lg">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Zgłoś naprawę
                </a>
                <a href="/status" class="btn btn--ghost btn--lg">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Sprawdź status
                </a>
            </div>
            <div class="hero__stats">
                <div class="hero__stat"><strong>15+</strong><span>lat doświadczenia</span></div>
                <div class="hero__stat-divider"></div>
                <div class="hero__stat"><strong>2000+</strong><span>napraw rocznie</span></div>
                <div class="hero__stat-divider"></div>
                <div class="hero__stat"><strong>98%</strong><span>zadowolonych klientów</span></div>
            </div>
        </div>
        <div class="hero__visual">
            <div class="hero__orb hero__orb--1"></div>
            <div class="hero__orb hero__orb--2"></div>
            <div class="hero__orb hero__orb--3"></div>
            <div class="hero__orb hero__orb--4"></div>
            <div class="hero__card">
                <div class="hero__card-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                </div>
                <div class="hero__card-title">Naprawa w toku</div>
                <div class="hero__card-sub">NF-2025-A8F2K1</div>
                <div class="hero__card-bar"><div class="hero__card-fill"></div></div>
                <div class="hero__card-label"><span>Diagnostyka</span><span>72%</span></div>
            </div>
            <div class="hero__float hero__float--1">
                <div class="hero__float-dot hero__float-dot--green"></div>
                <div class="hero__float-text"><strong>Nowe zgłoszenie</strong><span>Przed chwilą</span></div>
            </div>
            <div class="hero__float hero__float--2">
                <div class="hero__float-dot hero__float-dot--cyan"></div>
                <div class="hero__float-text"><strong>Wycena zaakceptowana</strong><span>2 min temu</span></div>
            </div>
        </div>
    </div>
</section>

<!-- JAK TO DZIAŁA -->
<section class="steps section">
    <div class="container">
        <div class="section__header">
            <p class="section__label">Prosty proces</p>
            <h2 class="section__title">Jak to działa?</h2>
            <p class="section__desc">Od zgłoszenia do naprawy — cały proces online, bez wychodzenia z domu.</p>
        </div>
        <div class="steps__grid">
            <div class="steps__item">
                <div class="steps__number">01</div>
                <div class="steps__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
                <h3>Zgłoszenie</h3>
                <p>Wypełniasz formularz online w kilka minut. Opisujesz problem i dodajesz zdjęcia urządzenia.</p>
                <div class="steps__connector">→</div>
            </div>
            <div class="steps__item">
                <div class="steps__number">02</div>
                <div class="steps__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
                <h3>Wysyłka</h3>
                <p>Otrzymujesz numer RMA i instrukcję. Pakujesz i wysyłasz urządzenie do nas.</p>
                <div class="steps__connector">→</div>
            </div>
            <div class="steps__item">
                <div class="steps__number">03</div>
                <div class="steps__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></div>
                <h3>Diagnostyka</h3>
                <p>Sprawdzamy usterkę i kontaktujemy się z Tobą. Przedstawiamy szczegółową wycenę naprawy.</p>
                <div class="steps__connector">→</div>
            </div>
            <div class="steps__item">
                <div class="steps__number">04</div>
                <div class="steps__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div>
                <h3>Naprawa i wysyłka</h3>
                <p>Naprawiamy i odsyłamy. Śledzisz status naprawy online przez cały czas.</p>
            </div>
        </div>
    </div>
</section>

<!-- ZDJĘCIE + CTA -->
<section class="photo-section">
    <?php
    $cta_img = file_exists(ROOT_PATH . '/public/images/sections/aquarium-cta.jpg') ? '/images/sections/aquarium-cta.jpg'
             : (file_exists(ROOT_PATH . '/public/images/sections/aquarium-cta.webp') ? '/images/sections/aquarium-cta.webp'
             : 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=1600&q=80');
    ?>
    <img class="photo-section__img" src="<?= $cta_img ?>" alt="Akwarium">
    <div class="photo-section__overlay"></div>
    <div class="photo-section__content container">
        <div class="photo-section__text">
            <h2>Zgłoś swoje urządzenie do serwisu</h2>
            <p>Wypełnij formularz online, a my zajmiemy się resztą. Bezpieczna wysyłka, szybka diagnoza, uczciwa wycena.</p>
            <a href="/panel/nowe-zgloszenie" class="btn btn--primary btn--lg">Przejdź do formularza</a>
        </div>
    </div>
</section>

<!-- STATUS -->
<section class="status-check section">
    <div class="container">
        <div class="status-check__inner">
            <div class="status-check__icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div class="status-check__content">
                <h2>Śledź status naprawy</h2>
                <p>Wpisz numer zgłoszenia i sprawdź na jakim etapie jest Twoja naprawa.</p>
                <form class="status-check__form" action="/status" method="GET">
                    <input type="text" name="rma" placeholder="Wpisz numer zgłoszenia (np. NF-2025-ABC123)" class="status-check__input">
                    <button type="submit" class="btn btn--primary">Sprawdź status</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ZALETY -->
<section class="features section">
    <div class="container">
        <div class="features__grid">
            <div class="features__item">
                <div class="features__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                <div><h4>Doświadczenie</h4><p>Naprawiamy od 2010 roku. Setki zadowolonych klientów.</p></div>
            </div>
            <div class="features__item">
                <div class="features__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div>
                <div><h4>Profesjonalizm</h4><p>Korzystamy z najlepszych narzędzi i oryginalnych części.</p></div>
            </div>
            <div class="features__item">
                <div class="features__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <div><h4>Gwarancja</h4><p>Na każdą wykonaną naprawę udzielamy gwarancji.</p></div>
            </div>
            <div class="features__item">
                <div class="features__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <div><h4>Szybka obsługa</h4><p>Wiemy że ważny jest Twój czas i sprawny sprzęt.</p></div>
            </div>
        </div>
    </div>
</section>

<!-- OPINIE -->
<section class="reviews section">
    <div class="container">
        <div class="section__header">
            <p class="section__label">Co mówią klienci</p>
            <h2 class="section__title">Opinie naszych klientów</h2>
        </div>
        <div class="reviews__grid">
            <div class="reviews__card">
                <div class="reviews__stars">★★★★★</div>
                <p>"Szybka diagnoza, świetny kontakt i profesjonalna naprawa mojej lampy LED. Sprzęt działa jak nowy!"</p>
                <div class="reviews__author"><div class="reviews__avatar">M</div><div><strong>Marek</strong><span>Klient od 2022</span></div></div>
            </div>
            <div class="reviews__card">
                <div class="reviews__stars">★★★★★</div>
                <p>"Falownik naprawiony ekspresowo. Pełna komunikacja na każdym etapie, polecam każdemu akwaryście!"</p>
                <div class="reviews__author"><div class="reviews__avatar">A</div><div><strong>Anna</strong><span>Klientka od 2023</span></div></div>
            </div>
            <div class="reviews__card">
                <div class="reviews__stars">★★★★★</div>
                <p>"Sterownik GHL naprawiony w ciągu tygodnia. Cena uczciwa, jakość na najwyższym poziomie."</p>
                <div class="reviews__author"><div class="reviews__avatar">T</div><div><strong>Tomasz</strong><span>Klient od 2021</span></div></div>
            </div>
        </div>
    </div>
</section>
