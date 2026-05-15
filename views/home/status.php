<section class="page-hero">
    <div class="page-hero__bg"></div>
    <div class="page-deco">
        <div class="page-deco__ring page-deco__ring--1"></div>
        <div class="page-deco__ring page-deco__ring--2"></div>
        <div class="page-deco__ring page-deco__ring--3"></div>
    </div>
    <div class="container">
        <p class="section__label">Śledzenie</p>
        <h1>Status naprawy</h1>
        <p>Wpisz numer zgłoszenia aby sprawdzić aktualny status swojej naprawy.</p>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:700px">
        <form class="status-check__form" method="GET" action="/status" style="margin-bottom:48px">
            <input type="text" name="rma" value="<?= htmlspecialchars($_GET['rma'] ?? '') ?>"
                   placeholder="Wpisz numer zgłoszenia (np. NF-2025-ABC123)"
                   class="status-check__input">
            <button type="submit" class="btn btn--primary">Sprawdź</button>
        </form>

        <?php if ($error): ?>
            <div class="auth-error">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if ($repair): ?>
        <div class="status-result">
            <div class="status-result__header">
                <div>
                    <h2><?= sanitize($repair['rma_number']) ?></h2>
                    <p><?= sanitize($repair['device_type']) ?></p>
                </div>
                <span class="status-pill" style="background:<?= $repair['status_color'] ?>22;color:<?= $repair['status_color'] ?>;padding:8px 16px;font-size:14px">
                    <?= sanitize($repair['status_label']) ?>
                </span>
            </div>

            <div class="status-steps">
                <?php
                $all_steps = [
                    ['new', 'Zgłoszenie'],
                    ['initial_quote_sent', 'Wstępna wycena'],
                    ['shipping_instructions', 'Wysyłka'],
                    ['parcel_received', 'Paczka odebrana'],
                    ['diagnosis', 'Diagnostyka'],
                    ['final_quote_sent', 'Finalna wycena'],
                    ['in_repair', 'Naprawa'],
                    ['shipped_to_client', 'Wysłano'],
                    ['completed', 'Zakończone'],
                ];
                $current_order = $repair['sort_order'];
                ?>
                <?php foreach ($all_steps as $step): ?>
                <?php
                    global $pdo;
                    $s = $pdo->prepare('SELECT sort_order FROM repair_statuses WHERE code = ?');
                    $s->execute([$step[0]]);
                    $so = $s->fetchColumn();
                    $done    = $so <= $current_order;
                    $current = $so == $current_order;
                ?>
                <div class="status-step <?= $done ? 'done' : '' ?> <?= $current ? 'current' : '' ?>">
                    <div class="status-step__dot">
                        <?php if ($done): ?>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        <?php endif; ?>
                    </div>
                    <span><?= $step[1] ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="status-result__footer">
                <p>Data zgłoszenia: <strong><?= date('d.m.Y', strtotime($repair['created_at'])) ?></strong></p>
                <p>Ostatnia aktualizacja: <strong><?= date('d.m.Y H:i', strtotime($repair['updated_at'])) ?></strong></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
