<?php
global $pdo;
// Pobierz węzeł startowy lub wybrany
$node_id = (int)($_GET['node'] ?? 1);
$history = $_GET['history'] ?? '';

$node = $pdo->prepare('SELECT * FROM diag_nodes WHERE id=?');
$node->execute([$node_id]);
$node = $node->fetch();

$children = [];
if ($node && $node['result_type'] === 'continue') {
    $stmt = $pdo->prepare('SELECT * FROM diag_nodes WHERE parent_id=? ORDER BY sort_order');
    $stmt->execute([$node_id]);
    $children = $stmt->fetchAll();
}

// Historia ścieżki
$path = [];
if ($history) {
    $ids = array_filter(array_map('intval', explode(',', $history)));
    if ($ids) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT id, answer FROM diag_nodes WHERE id IN ($placeholders) ORDER BY FIELD(id,".implode(',',$ids).")");
        $stmt->execute($ids);
        $path = $stmt->fetchAll();
    }
}
?>
<section class="panel-section">
<div class="container" style="max-width:760px">
    <div class="panel-header">
        <div>
            <h1>Diagnoza online</h1>
            <p>Odpowiedz na pytania — system pomoże określić możliwą przyczynę usterki.</p>
        </div>
        <a href="/panel" class="btn btn--ghost">← Panel</a>
    </div>

    <!-- Ścieżka -->
    <?php if (!empty($path)): ?>
    <div class="diag-path">
        <a href="/panel/diagnostyka" class="diag-path__item">Start</a>
        <?php foreach ($path as $p): ?>
            <span class="diag-path__sep">›</span>
            <span class="diag-path__item diag-path__item--done"><?= sanitize($p['answer']) ?></span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($node): ?>
    <div class="diag-card">

        <?php if ($node['result_type'] === 'continue' && !empty($children)): ?>
            <!-- Pytanie z odpowiedziami -->
            <div class="diag-question">
                <div class="diag-question__icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <h2><?= sanitize(lang()==='en' && $node['question_en'] ? $node['question_en'] : $node['question']) ?></h2>
            </div>
            <div class="diag-answers">
                <?php foreach ($children as $child): ?>
                <?php
                    $new_history = $history ? $history.','.$child['id'] : $child['id'];
                    $url = '/panel/diagnostyka?node='.$child['id'].'&history='.$new_history;
                ?>
                <a href="<?= $url ?>" class="diag-answer">
                    <div class="diag-answer__text"><?= sanitize(lang()==='en' && $child['answer_en'] ? $child['answer_en'] : $child['answer']) ?></div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
                <?php endforeach; ?>
            </div>

        <?php elseif ($node['result']): ?>
            <!-- Wynik diagnozy -->
            <?php
            $colors = [
                'repair'     => ['#00e5ff', 'rgba(0,229,255,0.08)', 'rgba(0,229,255,0.2)'],
                'no_repair'  => ['#f87171', 'rgba(239,68,68,0.08)', 'rgba(239,68,68,0.2)'],
                'contact'    => ['#8b5cf6', 'rgba(139,92,246,0.08)', 'rgba(139,92,246,0.2)'],
                'continue'   => ['#00e5ff', 'rgba(0,229,255,0.08)', 'rgba(0,229,255,0.2)'],
            ];
            $c = $colors[$node['result_type']] ?? $colors['repair'];
            ?>
            <div class="diag-result" style="border-color:<?= $c[2] ?>;background:<?= $c[1] ?>">
                <div class="diag-result__icon" style="color:<?= $c[0] ?>">
                    <?php if ($node['result_type'] === 'repair'): ?>
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    <?php else: ?>
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <?php endif; ?>
                </div>
                <h3>Wstępna diagnoza</h3>
                <p><?= nl2br(sanitize(lang()==='en' && $node['result_en'] ? $node['result_en'] : $node['result'])) ?></p>

                <?php if ($node['result_type'] === 'repair'): ?>
                <div class="diag-result__actions">
                    <a href="/panel/nowe-zgloszenie" class="btn btn--primary">Zgłoś urządzenie do naprawy →</a>
                    <a href="/panel/diagnostyka" class="btn btn--ghost">Zacznij od nowa</a>
                </div>
                <?php elseif ($node['result_type'] === 'contact'): ?>
                <div class="diag-result__actions">
                    <a href="/kontakt" class="btn btn--primary">Napisz do nas</a>
                    <a href="/panel/diagnostyka" class="btn btn--ghost">Zacznij od nowa</a>
                </div>
                <?php else: ?>
                <div class="diag-result__actions">
                    <a href="/panel/diagnostyka" class="btn btn--ghost">Zacznij od nowa</a>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
    <?php endif; ?>

    <div style="text-align:center;padding:16px 0;color:var(--tm);font-size:13px">
        Diagnoza ma charakter poglądowy. Nie zastąpi oceny specjalisty po otrzymaniu sprzętu.
    </div>

</div>
</section>
