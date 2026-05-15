<!-- HERO -->
<section class="hero">
    <div class="hero__bg"></div>
    <?php
    $hero_img = file_exists(ROOT_PATH.'/public/images/hero/hero-bg.jpg') ? '/images/hero/hero-bg.jpg'
              : (file_exists(ROOT_PATH.'/public/images/hero/hero-bg.webp') ? '/images/hero/hero-bg.webp'
              : (file_exists(ROOT_PATH.'/public/images/hero/hero-bg.png') ? '/images/hero/hero-bg.png' : null));
    ?>
    <?php if ($hero_img): ?>
    <div class="hero__img" style="background-image:url('<?= $hero_img ?>')"></div>
    <?php else: ?>
    <div class="hero__img"></div>
    <?php endif; ?>
    <div class="hero__grid"></div>
    <div class="container hero__inner">
        <div class="hero__content">
            <div class="hero__badge">
                <span class="hero__badge-dot"></span>
                Inżynier elektronik z wieloletnim doświadczeniem
            </div>
            <h1 class="hero__title">
                Serwis elektroniki<br>
                <span class="hero__title--accent">akwarystycznej</span>
            </h1>
            <p class="hero__desc">
                Naprawa lamp LED, sterowników, falowników, dozowników<br>
                i automatyki akwariowej. Skupiam się wyłącznie na elektronice.
            </p>
            <div class="hero__actions">
                <a href="/panel/nowe-zgloszenie" class="btn btn--primary btn--lg">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Zgłoś urządzenie
                </a>
                <a href="/status" class="btn btn--ghost btn--lg">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Sprawdź status
                </a>
            </div>
            <div class="hero__stats">
                <div class="hero__stat"><strong>15+</strong><span>lat doświadczenia</span></div>
                <div class="hero__stat-divider"></div>
                <div class="hero__stat"><strong>5</strong><span>centrów nauki</span></div>
                <div class="hero__stat-divider"></div>
                <div class="hero__stat"><strong>100%</strong><span>skupienie na elektronice</span></div>
            </div>
        </div>
        <div class="hero__visual">
            <div class="hero__orb hero__orb--1"></div>
            <div class="hero__orb hero__orb--2"></div>
            <div class="hero__orb hero__orb--3"></div>
            <div class="hero__orb hero__orb--4"></div>
            <div class="hero__card" id="heroCard">
                <div class="hero__card-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                </div>
                <div class="hero__card-title" id="heroCardTitle">Naprawa w toku</div>
                <div class="hero__card-sub" id="heroCardSub">Ładowanie...</div>
                <div class="hero__card-bar"><div class="hero__card-fill" id="heroCardFill"></div></div>
                <div class="hero__card-label">
                    <span id="heroCardLabel">Aktywne naprawy</span>
                    <span id="heroCardValue" style="color:var(--c)">—</span>
                </div>
            </div>
            <div class="hero__float hero__float--1" id="heroFloat1">
                <div class="hero__float-dot hero__float-dot--green"></div>
                <div class="hero__float-text">
                    <strong id="heroFloat1Title">Nowe zgłoszenie</strong>
                    <span id="heroFloat1Sub">Dziś</span>
                </div>
            </div>
            <div class="hero__float hero__float--2" id="heroFloat2">
                <div class="hero__float-dot hero__float-dot--cyan"></div>
                <div class="hero__float-text">
                    <strong id="heroFloat2Title">Wycena zaakceptowana</strong>
                    <span id="heroFloat2Sub">Niedawno</span>
                </div>
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
            <p class="section__desc">Cały proces online — od zgłoszenia do odesłania naprawionego sprzętu.</p>
        </div>
        <div class="steps__grid">
            <div class="steps__item">
                <div class="steps__number">01</div>
                <div class="steps__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
                <h3>Zgłoszenie online</h3>
                <p>Wypełniasz formularz, opisujesz problem i dodajesz zdjęcia. Otrzymujesz wstępną wycenę.</p>
                <div class="steps__connector">→</div>
            </div>
            <div class="steps__item">
                <div class="steps__number">02</div>
                <div class="steps__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
                <h3>Wysyłka na paczkomat</h3>
                <p>Wysyłasz sprzęt na paczkomat SCZ04M w Szczecinku (78-400). Starannie zapakuj urządzenie.</p>
                <div class="steps__connector">→</div>
            </div>
            <div class="steps__item">
                <div class="steps__number">03</div>
                <div class="steps__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></div>
                <h3>Diagnostyka i wycena</h3>
                <p>Wykonuję szczegółową diagnostykę elektroniki i przesyłam dokładną wycenę naprawy do akceptacji.</p>
                <div class="steps__connector">→</div>
            </div>
            <div class="steps__item">
                <div class="steps__number">04</div>
                <div class="steps__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div>
                <h3>Naprawa i odesłanie</h3>
                <p>Po akceptacji wyceny naprawiam sprzęt i odsyłam z powrotem. Śledzisz status online.</p>
            </div>
        </div>
    </div>
</section>

<!-- ZDJĘCIE + CTA -->
<section class="photo-section">
    <?php
    $cta_img = file_exists(ROOT_PATH.'/public/images/sections/aquarium-cta.jpg') ? '/images/sections/aquarium-cta.jpg'
             : (file_exists(ROOT_PATH.'/public/images/sections/aquarium-cta.webp') ? '/images/sections/aquarium-cta.webp'
             : 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=1600&q=80');
    ?>
    <img class="photo-section__img" src="<?= $cta_img ?>" alt="Akwarium">
    <div class="photo-section__overlay"></div>
    <div class="photo-section__content container">
        <div class="photo-section__text">
            <h2>Zgłoś urządzenie do serwisu</h2>
            <p>Wypełnij formularz online — opisz problem, dodaj zdjęcia. Odezwę się ze wstępną wyceną i dalszymi krokami.</p>
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
                <h2>Sprawdź status naprawy</h2>
                <p>Wpisz numer zgłoszenia i sprawdź na jakim etapie jest Twój sprzęt.</p>
                <form class="status-check__form" action="/status" method="GET">
                    <input type="text" name="rma" placeholder="Wpisz numer zgłoszenia (np. NF-2025-ABC123)" class="status-check__input">
                    <button type="submit" class="btn btn--primary">Sprawdź</button>
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
                <div><h4>Wykształcony inżynier</h4><p>Elektronik z wykształcenia i praktyki. Projektuję układy elektroniczne od ponad 15 lat.</p></div>
            </div>
            <div class="features__item">
                <div class="features__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg></div>
                <div><h4>Tylko elektronika</h4><p>Skupiam się wyłącznie na naprawie elektroniki — płyty główne, drivery, sterowniki, układy scalone.</p></div>
            </div>
            <div class="features__item">
                <div class="features__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <div><h4>Gwarancja na naprawę</h4><p>Na każdą wykonaną naprawę udzielam gwarancji. Wyjątek: sprzęt zalany lub po kontakcie z wodą.</p></div>
            </div>
            <div class="features__item">
                <div class="features__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <div><h4>Uczciwa wycena</h4><p>Najpierw wstępna wycena, potem diagnostyka i koszt finalny. Naprawiam tylko po Twojej akceptacji.</p></div>
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
                <p>"Lampa AI Hydra przestała działać po zalaniu. Eliasz ją przywrócił do życia w tydzień. Profesjonalne podejście i szczery kontakt."</p>
                <div class="reviews__author"><div class="reviews__avatar">M</div><div><strong>Marek</strong><span>Reefkeeper, Warszawa</span></div></div>
            </div>
            <div class="reviews__card">
                <div class="reviews__stars">★★★★★</div>
                <p>"Sterownik GHL naprawiony ekspresowo. Pełna komunikacja na każdym etapie — wiedziałem co się dzieje z moim sprzętem."</p>
                <div class="reviews__author"><div class="reviews__avatar">A</div><div><strong>Anna</strong><span>Akwarystka, Kraków</span></div></div>
            </div>
            <div class="reviews__card">
                <div class="reviews__stars">★★★★★</div>
                <p>"Dozownik Balling przestał dozować. Diagnoza, wycena i naprawa sprawnie. Cena uczciwa, sprzęt działa jak nowy."</p>
                <div class="reviews__author"><div class="reviews__avatar">T</div><div><strong>Tomasz</strong><span>Reef tank, Gdańsk</span></div></div>
            </div>
        </div>
    </div>
</section>
