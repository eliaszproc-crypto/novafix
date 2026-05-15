<?php
global $pdo;
$nodes = $pdo->query('SELECT * FROM diag_nodes ORDER BY parent_id ASC, sort_order ASC')->fetchAll();
$nodes_by_parent = [];
foreach ($nodes as $n) {
    $key = $n['parent_id'] === null ? '__root__' : (int)$n['parent_id'];
    $nodes_by_parent[$key][] = $n;
}

$action   = $_GET['action'] ?? '';
$edit_id  = (int)($_GET['edit'] ?? 0);
$edit_node = null;
if ($edit_id) {
    $stmt = $pdo->prepare('SELECT * FROM diag_nodes WHERE id=?');
    $stmt->execute([$edit_id]);
    $edit_node = $stmt->fetch();
}
$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';
?>

<?php if ($success): ?><div class="a-alert a-alert--success"><?= sanitize($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="a-alert a-alert--error"><?= sanitize($error) ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start">

    <!-- Drzewo -->
    <div class="admin-card" style="overflow:hidden">
        <div class="admin-card__header">
            <h2>Drzewo diagnostyczne</h2>
            <div style="display:flex;gap:8px">
                <button onclick="toggleAll(true)" class="a-btn a-btn-secondary" style="padding:6px 12px;font-size:12px">Rozwiń wszystko</button>
                <button onclick="toggleAll(false)" class="a-btn a-btn-secondary" style="padding:6px 12px;font-size:12px">Zwiń wszystko</button>
                <a href="/admin/diagnostyka?action=add&parent=1" class="a-btn a-btn-primary" style="padding:6px 12px;font-size:12px">+ Dodaj węzeł</a>
            </div>
        </div>
        <div class="dtree" id="dtree">
            <?php
            $type_colors = [
                'repair'    => '#00e5ff',
                'no_repair' => '#f87171',
                'contact'   => '#8b5cf6',
                'continue'  => '#94a3b8',
            ];
            $type_labels = [
                'repair'    => 'Naprawa',
                'no_repair' => 'Brak naprawy',
                'contact'   => 'Kontakt',
                'continue'  => 'Kontynuuj',
            ];

            function renderDTree($nodes_by_parent, $parent_key, $type_colors, $type_labels, $level = 0) {
                $children = $nodes_by_parent[$parent_key] ?? [];
                if (empty($children)) return;
                foreach ($children as $node):
                    $has_children = isset($nodes_by_parent[(int)$node['id']]);
                    $tc = $type_colors[$node['result_type']] ?? '#94a3b8';
                    $tl = $type_labels[$node['result_type']] ?? '';
                    $node_id = 'node-'.$node['id'];
                ?>
                <div class="dtree__item" data-level="<?= $level ?>">
                    <div class="dtree__row" onclick="<?= $has_children ? "toggleNode('$node_id')" : '' ?>" style="<?= $has_children ? 'cursor:pointer' : '' ?>">
                        <div class="dtree__indent" style="width:<?= $level * 24 ?>px;flex-shrink:0"></div>

                        <!-- Toggle ikona -->
                        <div class="dtree__toggle" style="opacity:<?= $has_children ? 1 : 0 ?>">
                            <svg class="dtree__arrow" id="arrow-<?= $node['id'] ?>" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </div>

                        <!-- Dot -->
                        <div class="dtree__dot" style="background:<?= $tc ?>"></div>

                        <!-- Treść -->
                        <div class="dtree__content">
                            <?php if ($node['answer']): ?>
                                <span class="dtree__answer"><?= sanitize($node['answer']) ?></span>
                            <?php endif; ?>
                            <span class="dtree__question"><?= sanitize($node['question']) ?></span>
                            <?php if ($node['result']): ?>
                                <span class="dtree__result" style="color:<?= $tc ?>">
                                    <?= mb_substr(sanitize($node['result']), 0, 70).(mb_strlen($node['result'])>70?'...':'') ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Badge typ -->
                        <span class="dtree__badge" style="background:<?= $tc ?>18;color:<?= $tc ?>;border-color:<?= $tc ?>30"><?= $tl ?></span>

                        <!-- Akcje -->
                        <div class="dtree__actions" onclick="event.stopPropagation()">
                            <a href="/admin/diagnostyka?edit=<?= $node['id'] ?>" class="dtree__btn" title="Edytuj">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <a href="/admin/diagnostyka?action=add&parent=<?= $node['id'] ?>" class="dtree__btn" title="Dodaj dziecko">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </a>
                            <form method="POST" action="/admin/diagnostyka/usun/<?= $node['id'] ?>" style="margin:0"
                                  onsubmit="return confirm('Usunąć węzeł<?= $has_children ? ' i wszystkie jego dzieci' : '' ?>?')">
                                <button type="submit" class="dtree__btn dtree__btn--del" title="Usuń">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <?php if ($has_children): ?>
                    <div class="dtree__children" id="<?= $node_id ?>">
                        <?php renderDTree($nodes_by_parent, (int)$node['id'], $type_colors, $type_labels, $level + 1); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach;
            }
            renderDTree($nodes_by_parent, '__root__', $type_colors, $type_labels);
            ?>
        </div>
    </div>

    <!-- Panel edycji -->
    <div style="position:sticky;top:80px">
        <?php if ($edit_node || $action === 'add'): ?>
        <div class="admin-card">
            <h3><?= $edit_node ? 'Edytuj węzeł #'.$edit_node['id'] : 'Dodaj węzeł' ?></h3>
            <?php if ($action === 'add'): ?>
                <?php
                $parent_id = (int)($_GET['parent'] ?? 1);
                $parent_node = $pdo->prepare('SELECT question FROM diag_nodes WHERE id=?');
                $parent_node->execute([$parent_id]);
                $parent_q = $parent_node->fetchColumn();
                ?>
                <p style="font-size:12px;color:var(--tm);margin-bottom:16px;padding:8px 12px;background:var(--bg4);border-radius:8px">
                    Rodzic: <strong style="color:var(--t)"><?= sanitize(mb_substr($parent_q,0,60)) ?></strong>
                </p>
            <?php endif; ?>
            <form method="POST" action="/admin/diagnostyka/<?= $edit_node ? 'edytuj/'.$edit_node['id'] : 'dodaj' ?>" class="admin-form">
                <?php if ($action === 'add'): ?>
                    <input type="hidden" name="parent_id" value="<?= $parent_id ?>">
                <?php endif; ?>
                <div class="f-group">
                    <label>Odpowiedź — tekst przycisku wyboru</label>
                    <input type="text" name="answer" value="<?= sanitize($edit_node['answer'] ?? '') ?>"
                           placeholder="np. Lampa LED, Nie świeci wcale...">
                </div>
                <div class="f-group">
                    <label>Pytanie / treść <span style="color:#f87171">*</span></label>
                    <textarea name="question" rows="3" required placeholder="np. Co się dzieje z lampą?"><?= sanitize($edit_node['question'] ?? '') ?></textarea>
                </div>
                <div class="f-group">
                    <label>Wynik diagnozy <span style="color:var(--tm);font-weight:400">(tylko dla liści)</span></label>
                    <textarea name="result" rows="4" placeholder="Opis usterki i zalecenie dla klienta..."><?= sanitize($edit_node['result'] ?? '') ?></textarea>
                </div>
                <div class="f-group">
                    <label>Typ wyniku</label>
                    <select name="result_type">
                        <option value="continue"  <?= ($edit_node['result_type']??'continue')==='continue' ?'selected':''?>>↪ Kontynuuj (ma odpowiedzi-dzieci)</option>
                        <option value="repair"    <?= ($edit_node['result_type']??'')==='repair'    ?'selected':''?>>🔧 Zalecana naprawa</option>
                        <option value="no_repair" <?= ($edit_node['result_type']??'')==='no_repair' ?'selected':''?>>✗ Nie nadaje się do naprawy</option>
                        <option value="contact"   <?= ($edit_node['result_type']??'')==='contact'   ?'selected':''?>>✉ Skontaktuj się</option>
                    </select>
                </div>
                <div class="f-group">
                    <label>Kolejność</label>
                    <input type="number" name="sort_order" value="<?= $edit_node['sort_order'] ?? 0 ?>" min="0" style="width:100px">
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap">
                    <button type="submit" class="a-btn a-btn-primary">Zapisz</button>
                    <a href="/admin/diagnostyka" class="a-btn a-btn-secondary">Anuluj</a>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="admin-card">
            <h3>Legenda</h3>
            <div style="display:flex;flex-direction:column;gap:10px;font-size:13px">
                <div style="display:flex;align-items:center;gap:10px"><span style="width:10px;height:10px;border-radius:50%;background:#94a3b8;flex-shrink:0"></span><span style="color:var(--tm)"><strong style="color:var(--t)">Kontynuuj</strong> — węzeł z pytaniem i odpowiedziami</span></div>
                <div style="display:flex;align-items:center;gap:10px"><span style="width:10px;height:10px;border-radius:50%;background:#00e5ff;flex-shrink:0"></span><span style="color:var(--tm)"><strong style="color:var(--t)">Naprawa</strong> — koniec ścieżki, zalecenie naprawy</span></div>
                <div style="display:flex;align-items:center;gap:10px"><span style="width:10px;height:10px;border-radius:50%;background:#f87171;flex-shrink:0"></span><span style="color:var(--tm)"><strong style="color:var(--t)">Brak naprawy</strong> — nieopłacalne lub poza zakresem</span></div>
                <div style="display:flex;align-items:center;gap:10px"><span style="width:10px;height:10px;border-radius:50%;background:#8b5cf6;flex-shrink:0"></span><span style="color:var(--tm)"><strong style="color:var(--t)">Kontakt</strong> — przekierowanie do kontaktu</span></div>
            </div>
        </div>
        <div class="admin-card" style="margin-top:0">
            <h3>Podgląd klienta</h3>
            <p style="font-size:13px;color:var(--tm);margin-bottom:12px">Przetestuj jak wygląda diagnoza z perspektywy klienta.</p>
            <a href="/panel/diagnostyka" target="_blank" class="a-btn a-btn-secondary">Otwórz podgląd ↗</a>
        </div>
        <?php endif; ?>
    </div>

</div>

<style>
.dtree { padding: 8px 0; }
.dtree__item { }
.dtree__row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    border-radius: 8px;
    transition: background 0.15s;
    min-height: 40px;
}
.dtree__row:hover { background: rgba(255,255,255,0.04); }
.dtree__toggle {
    width: 20px; height: 20px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: var(--tm);
}
.dtree__arrow { transition: transform 0.2s ease; }
.dtree__arrow.open { transform: rotate(90deg); }
.dtree__dot {
    width: 8px; height: 8px;
    border-radius: 50%; flex-shrink: 0;
}
.dtree__content {
    flex: 1; display: flex; flex-direction: column;
    gap: 1px; min-width: 0;
}
.dtree__answer {
    font-size: 11px; font-weight: 700;
    color: #00e5ff; text-transform: uppercase; letter-spacing: 0.5px;
}
.dtree__question {
    font-size: 13px; color: var(--t);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.dtree__result {
    font-size: 11px; color: var(--tm);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.dtree__badge {
    font-size: 10px; font-weight: 600;
    padding: 2px 8px; border-radius: 100px;
    border: 1px solid; white-space: nowrap;
    flex-shrink: 0;
}
.dtree__actions {
    display: none;
    align-items: center;
    gap: 4px;
    flex-shrink: 0;
}
.dtree__row:hover .dtree__actions { display: flex; }
.dtree__btn {
    width: 26px; height: 26px;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,0.06);
    border: 1px solid var(--bd);
    color: var(--tm);
    cursor: pointer;
    transition: all 0.15s;
    text-decoration: none;
}
.dtree__btn:hover { background: rgba(255,255,255,0.12); color: var(--t); }
.dtree__btn--del:hover { background: rgba(239,68,68,0.15); color: #f87171; border-color: rgba(239,68,68,0.3); }
.dtree__children {
    border-left: 2px solid rgba(255,255,255,0.05);
    margin-left: 28px;
}
.dtree__children.collapsed { display: none; }
</style>

<script>
// Inicjalizuj — rozwiń tylko poziom 1 (bezpośrednie dzieci korzenia)
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dtree__children').forEach((el, i) => {
        const parentRow = el.previousElementSibling;
        const level = parseInt(parentRow?.closest('.dtree__item')?.dataset?.level ?? 0);
        if (level >= 1) {
            el.classList.add('collapsed');
        } else {
            const arrow = parentRow?.querySelector('.dtree__arrow');
            if (arrow) arrow.classList.add('open');
        }
    });
});

function toggleNode(id) {
    const el = document.getElementById(id);
    if (!el) return;
    const collapsed = el.classList.toggle('collapsed');
    const nodeId = id.replace('node-', '');
    const arrow = document.getElementById('arrow-' + nodeId);
    if (arrow) arrow.classList.toggle('open', !collapsed);
}

function toggleAll(expand) {
    document.querySelectorAll('.dtree__children').forEach(el => {
        el.classList.toggle('collapsed', !expand);
        const nodeId = el.id.replace('node-', '');
        const arrow = document.getElementById('arrow-' + nodeId);
        if (arrow) arrow.classList.toggle('open', expand);
    });
}
</script>
