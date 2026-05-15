<?php
global $pdo;
$items = $pdo->query('SELECT * FROM pricing_items WHERE is_active=1 ORDER BY sort_order,id')->fetchAll();
$by_cat = [];
foreach ($items as $item) $by_cat[$item['category']][] = $item;

$cat_icons = [
    'Diagnostyka'              => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
    'Lampy LED'                => '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>',
    'Sterowniki i elektronika' => '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
    'Dozowniki i automatyka'   => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>',
    'Sprzęt po kontakcie z wodą' => '<path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/>',
];
?>

<section class="page-hero">
    <div class="page-hero__bg"></div>
    <div class="page-deco">
        <div class="page-deco__ring page-deco__ring--1"></div>
        <div class="page-deco__ring page-deco__ring--2"></div>
        <div class="page-deco__ring page-deco__ring--3"></div>
    </div>
    <div class="container">
        <p class="section__label">Przejrzyste ceny</p>
        <h1>Cennik</h1>
        <p>Ceny orientacyjne — dokładna wycena zawsze po diagnostyce. Naprawiam dopiero po Twojej akceptacji kosztu.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="pricing-notice">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p>Ceny są <strong>orientacyjne</strong>. Ostateczna wycena zależy od stopnia uszkodzenia i modelu urządzenia. Zawsze akceptujesz koszt przed naprawą — nigdy nie zaskakuję rachunkiem.</p>
        </div>

        <?php foreach ($by_cat as $cat => $items): ?>
        <div class="pricing-section">
            <div class="pricing-section__header">
                <div class="pricing-section__icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <?= $cat_icons[$cat] ?? '<circle cx="12" cy="12" r="10"/>' ?>
                    </svg>
                </div>
                <h2><?= sanitize($cat) ?></h2>
            </div>
            <div class="pricing-table-wrap">
                <table class="pricing-table">
                    <thead>
                        <tr><th>Usługa</th><th>Cena</th><th class="hide-mobile">Uwagi</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><strong style="color:#fff"><?= sanitize($item['name']) ?></strong></td>
                        <td class="price">
                            od <?= number_format((float)$item['price_from'], 0, '.', '') ?> zł
                            <?php if ($item['unit']): ?><span style="font-size:12px;font-weight:400;color:var(--tm)"> / <?= sanitize($item['unit']) ?></span><?php endif; ?>
                        </td>
                        <td class="hide-mobile"><?= sanitize($item['note'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="no-repair-box" style="margin-top:48px">
            <h3>⚠ Czego nie naprawiam</h3>
            <div class="no-repair-grid">
                <div class="no-repair-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    <div><strong>Grzałki</strong><span>Naprawa nieopłacalna — nowa grzałka kosztuje mniej</span></div>
                </div>
                <div class="no-repair-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    <div><strong>Części mechaniczne pomp i cyrkulatorów</strong><span>Wirniki, magnesy, uszczelnienia — hermetyczne elementy pracujące pod wodą</span></div>
                </div>
                <div class="no-repair-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    <div><strong>Sprzęt bez wartości ekonomicznej</strong><span>Jeśli koszt naprawy przekracza wartość urządzenia — informuję o tym uczciwie</span></div>
                </div>
            </div>
        </div>

        <div class="pricing-info-grid" style="margin-top:48px">
            <div class="pricing-info-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <div><h4>Diagnostyka od 50 zł</h4><p>Wstępna ocena usterki bez opłat wstępnych — płacisz po diagnostyce.</p></div>
            </div>
            <div class="pricing-info-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <div><h4>Gwarancja</h4><p>Na każdą naprawę. Wyjątek: sprzęt po kontakcie z wodą.</p></div>
            </div>
            <div class="pricing-info-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <div><h4>Pełna kontrola kosztów</h4><p>Wstępna wycena + koszt finalny. Zawsze akceptujesz przed naprawą.</p></div>
            </div>
            <div class="pricing-info-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                <div><h4>Paczkomat SCZ04M</h4><p>Wysyłasz na paczkomat w Szczecinku — wygodnie i bezpiecznie.</p></div>
            </div>
        </div>

    </div>
</section>
