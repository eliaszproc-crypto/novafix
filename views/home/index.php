<!-- HERO -->
<section class="hero">
    <div class="hero__bg"></div>
    <?php
    $hero_img = null;
    foreach (['hero-bg.jpg','hero-bg.webp','hero-bg.png'] as $f) {
        $check = [ROOT_PATH.'/public/images/hero/'.$f, $_SERVER['DOCUMENT_ROOT'].'/images/hero/'.$f];
        foreach ($check as $p) { if (file_exists($p)) { $hero_img = '/images/hero/'.$f; break 2; } }
    }
    ?>
    <?php if ($hero_img): ?><div class="hero__img" style="background-image:url('<?= $hero_img ?>')"></div><?php else: ?><div class="hero__img"></div><?php endif; ?>
    <div class="hero__grid"></div>
    <div class="container hero__inner">
        <div class="hero__content">
            <div class="hero__badge"><span class="hero__badge-dot"></span><?= t('hero.badge') ?></div>
            <h1 class="hero__title"><?= t('hero.title1') ?><br><span class="hero__title--accent"><?= t('hero.title2') ?></span></h1>
            <p class="hero__desc"><?= t('hero.desc') ?></p>
            <div class="hero__actions">
                <a href="/panel/nowe-zgloszenie" class="btn btn--primary btn--lg">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    <?= t('hero.btn.submit') ?>
                </a>
                <a href="/status" class="btn btn--ghost btn--lg">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <?= t('hero.btn.status') ?>
                </a>
            </div>
            <div class="hero__stats">
                <div class="hero__stat"><strong>6+</strong><span><?= t('hero.stat1') ?></span></div>
                <div class="hero__stat-divider"></div>
                <div class="hero__stat"><strong>50+</strong><span><?= t('hero.stat2') ?></span></div>
                <div class="hero__stat-divider"></div>
                <div class="hero__stat"><strong>95%</strong><span><?= t('hero.stat3') ?></span></div>
            </div>
        </div>
        <div class="hero__visual">
            <div class="hero__orb hero__orb--1"></div>
            <div class="hero__orb hero__orb--2"></div>
            <div class="hero__orb hero__orb--3"></div>
            <div class="hero__orb hero__orb--4"></div>
            <div class="hero__card" id="heroCard">
                <div class="hero__card-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div>
                <div class="hero__card-title" id="heroCardTitle">...</div>
                <div class="hero__card-sub" id="heroCardSub">...</div>
                <div class="hero__card-bar"><div class="hero__card-fill" id="heroCardFill"></div></div>
                <div class="hero__card-label"><span id="heroCardLabel">...</span><span id="heroCardValue" style="color:var(--c)">—</span></div>
            </div>
            <div class="hero__float hero__float--1" id="heroFloat1">
                <div class="hero__float-dot hero__float-dot--green"></div>
                <div class="hero__float-text"><strong id="heroFloat1Title">...</strong><span id="heroFloat1Sub">...</span></div>
            </div>
            <div class="hero__float hero__float--2" id="heroFloat2">
                <div class="hero__float-dot hero__float-dot--cyan"></div>
                <div class="hero__float-text"><strong id="heroFloat2Title">...</strong><span id="heroFloat2Sub">...</span></div>
            </div>
        </div>
    </div>
</section>

<!-- JAK TO DZIAŁA -->
<section class="steps section">
    <div class="container">
        <div class="section__header">
            <p class="section__label"><?= t('steps.label') ?></p>
            <h2 class="section__title"><?= t('steps.title') ?></h2>
            <p class="section__desc"><?= t('steps.desc') ?></p>
        </div>
        <div class="steps__grid">
            <div class="steps__item">
                <div class="steps__number">01</div>
                <div class="steps__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
                <h3><?= t('steps.1.title') ?></h3>
                <p><?= t('steps.1.desc') ?></p>
                <div class="steps__connector">→</div>
            </div>
            <div class="steps__item">
                <div class="steps__number">02</div>
                <div class="steps__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
                <h3><?= t('steps.2.title') ?></h3>
                <p><?= t('steps.2.desc') ?></p>
                <div class="steps__connector">→</div>
            </div>
            <div class="steps__item">
                <div class="steps__number">03</div>
                <div class="steps__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></div>
                <h3><?= t('steps.3.title') ?></h3>
                <p><?= t('steps.3.desc') ?></p>
                <div class="steps__connector">→</div>
            </div>
            <div class="steps__item">
                <div class="steps__number">04</div>
                <div class="steps__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div>
                <h3><?= t('steps.4.title') ?></h3>
                <p><?= t('steps.4.desc') ?></p>
            </div>
        </div>
    </div>
</section>

<!-- CTA ZDJĘCIE -->
<section class="photo-section">
    <?php
    $cta_paths = ['/images/sections/aquarium-cta.jpg','/images/sections/aquarium-cta.webp'];
    $cta_img = 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=1600&q=80';
    foreach ($cta_paths as $cp) {
        $check = [ROOT_PATH.'/public'.$cp, $_SERVER['DOCUMENT_ROOT'].$cp];
        foreach ($check as $p) { if (file_exists($p)) { $cta_img = $cp; break 2; } }
    }
    ?>
    <img class="photo-section__img" src="<?= $cta_img ?>" alt="Aquarium">
    <div class="photo-section__overlay"></div>
    <div class="photo-section__content container">
        <div class="photo-section__text">
            <h2><?= t('hero.btn.submit') ?></h2>
            <p><?= t('repair.desc') ?></p>
            <a href="/panel/nowe-zgloszenie" class="btn btn--primary btn--lg"><?= t('repair.submit') ?></a>
        </div>
    </div>
</section>

<!-- STATUS -->
<section class="status-check section">
    <div class="container">
        <div class="status-check__inner">
            <div class="status-check__icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
            <div class="status-check__content">
                <h2><?= t('status.title') ?></h2>
                <p><?= t('status.desc') ?></p>
                <form class="status-check__form" action="/status" method="GET">
                    <input type="text" name="rma" placeholder="<?= t('status.placeholder') ?>" class="status-check__input">
                    <button type="submit" class="btn btn--primary"><?= t('status.btn') ?></button>
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
                <div><h4><?= t('feat.1.title') ?></h4><p><?= t('feat.1.desc') ?></p></div>
            </div>
            <div class="features__item">
                <div class="features__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg></div>
                <div><h4><?= t('feat.2.title') ?></h4><p><?= t('feat.2.desc') ?></p></div>
            </div>
            <div class="features__item">
                <div class="features__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <div><h4><?= t('feat.3.title') ?></h4><p><?= t('feat.3.desc') ?></p></div>
            </div>
            <div class="features__item">
                <div class="features__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <div><h4><?= t('feat.4.title') ?></h4><p><?= t('feat.4.desc') ?></p></div>
            </div>
        </div>
    </div>
</section>

<!-- OPINIE -->
<section class="reviews section">
    <div class="container">
        <div class="section__header">
            <p class="section__label"><?= t('reviews.label') ?></p>
            <h2 class="section__title"><?= t('reviews.title') ?></h2>
        </div>
        <?php
        global $pdo;
        $reviews_db = $pdo->query('SELECT * FROM reviews WHERE is_visible=1 ORDER BY is_fake ASC, created_at DESC')->fetchAll();
        ?>
        <div class="reviews-slider" id="reviewsSlider">
            <div class="reviews-track" id="reviewsTrack">
                <?php foreach ($reviews_db as $r): ?>
                <div class="reviews__card">
                    <div class="reviews__stars"><?= str_repeat('★', (int)$r['rating']) ?></div>
                    <p>"<?= sanitize($r['content']) ?>"</p>
                    <div class="reviews__author">
                        <div class="reviews__avatar"><?= strtoupper(mb_substr($r['author'],0,1)) ?></div>
                        <div><strong><?= sanitize($r['author']) ?></strong></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="reviews-controls">
            <button class="reviews-btn" id="reviewsPrev"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg></button>
            <div class="reviews-dots" id="reviewsDots"></div>
            <button class="reviews-btn" id="reviewsNext"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
        </div>
    </div>
</section>
