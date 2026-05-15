<?php
// Pobierz całe drzewo
$nodes = $pdo->query('SELECT * FROM diag_nodes ORDER BY parent_id ASC, sort_order ASC')->fetchAll();
$nodes_by_parent = [];
foreach ($nodes as $n) {
    $nodes_by_parent[$n['parent_id'] ?? 'root'][] = $n;
}

$action  = $_GET['action'] ?? '';
$edit_id = (int)($_GET['edit'] ?? 0);
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
    <div class="admin-card">
        <div class="admin-card__header">
            <h2>Drzewo diagnostyczne</h2>
            <a href="/admin/diagnostyka?action=add&parent=1" class="a-btn a-btn-primary">+ Dodaj węzeł</a>
        </div>
        <div class="diag-tree">
            <?php
            function renderTree($nodes_by_parent, $parent_id, $level = 0) {
                $children = $nodes_by_parent[$parent_id] ?? [];
                foreach ($children as $node):
                    $indent = $level * 20;
                    $type_colors = ['repair'=>'#00e5ff','no_repair'=>'#f87171','contact'=>'#8b5cf6','continue'=>'#7a8aaa'];
                    $tc = $type_colors[$node['result_type']] ?? '#7a8aaa';
            ?>
            <div class="diag-tree__node" style="margin-left:<?= $indent ?>px">
                <div class="diag-tree__line" style="border-color:<?= $tc ?>22">
                    <div class="diag-tree__dot" style="background:<?= $tc ?>"></div>
                    <div class="diag-tree__content">
                        <?php if ($node['answer']): ?>
                            <span class="diag-tree__answer"><?= sanitize($node['answer']) ?></span>
                        <?php endif; ?>
                        <span class="diag-tree__question"><?= mb_substr(sanitize($node['question']),0,60).(mb_strlen($node['question'])>60?'...':'') ?></span>
                        <?php if ($node['result']): ?>
                            <span class="diag-tree__result" style="color:<?= $tc ?>">→ <?= mb_substr(sanitize($node['result']),0,50) ?>...</span>
                        <?php endif; ?>
                    </div>
                    <div class="diag-tree__actions">
                        <a href="/admin/diagnostyka?edit=<?= $node['id'] ?>" class="a-btn a-btn-secondary" style="padding:4px 10px;font-size:12px">Edytuj</a>
                        <a href="/admin/diagnostyka?action=add&parent=<?= $node['id'] ?>" class="a-btn a-btn-secondary" style="padding:4px 10px;font-size:12px">+ Dodaj</a>
                        <form method="POST" action="/admin/diagnostyka/usun/<?= $node['id'] ?>" style="display:inline"
                              onsubmit="return confirm('Usunąć ten węzeł i wszystkie jego dzieci?')">
                            <button type="submit" class="del-btn">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php renderTree($nodes_by_parent, $node['id'], $level + 1); ?>
            <?php endforeach; }
            renderTree($nodes_by_parent, null);
            ?>
        </div>
    </div>

    <!-- Formularz edycji / dodawania -->
    <div>
        <?php if ($edit_node || $action === 'add'): ?>
        <div class="admin-card">
            <h3><?= $edit_node ? 'Edytuj węzeł' : 'Dodaj węzeł' ?></h3>
            <form method="POST" action="/admin/diagnostyka/<?= $edit_node ? 'edytuj/'.$edit_node['id'] : 'dodaj' ?>" class="admin-form">
                <?php if ($action === 'add'): ?>
                    <input type="hidden" name="parent_id" value="<?= (int)($_GET['parent'] ?? 1) ?>">
                <?php endif; ?>

                <div class="f-group">
                    <label>Odpowiedź (opcja wyboru dla rodzica)</label>
                    <input type="text" name="answer" value="<?= sanitize($edit_node['answer'] ?? '') ?>"
                           placeholder="np. Lampa LED, Nie świeci wcale...">
                </div>
                <div class="f-group">
                    <label>Pytanie / treść węzła *</label>
                    <textarea name="question" rows="3" required placeholder="np. Co się dzieje z lampą?"><?= sanitize($edit_node['question'] ?? '') ?></textarea>
                </div>
                <div class="f-group">
                    <label>Wynik diagnozy (jeśli to liść drzewa)</label>
                    <textarea name="result" rows="4" placeholder="Opis usterki i zalecenie..."><?= sanitize($edit_node['result'] ?? '') ?></textarea>
                </div>
                <div class="f-group">
                    <label>Typ wyniku</label>
                    <select name="result_type">
                        <option value="continue" <?= ($edit_node['result_type']??'continue')==='continue'?'selected':'' ?>>Kontynuuj (ma dzieci)</option>
                        <option value="repair"   <?= ($edit_node['result_type']??'')==='repair'?'selected':'' ?>>Zalecana naprawa</option>
                        <option value="no_repair"<?= ($edit_node['result_type']??'')==='no_repair'?'selected':'' ?>>Nie nadaje się do naprawy</option>
                        <option value="contact"  <?= ($edit_node['result_type']??'')==='contact'?'selected':'' ?>>Skontaktuj się</option>
                    </select>
                </div>
                <div class="f-group">
                    <label>Kolejność</label>
                    <input type="number" name="sort_order" value="<?= $edit_node['sort_order'] ?? 0 ?>" min="0">
                </div>
                <div style="display:flex;gap:10px">
                    <button type="submit" class="a-btn a-btn-primary">Zapisz</button>
                    <a href="/admin/diagnostyka" class="a-btn a-btn-secondary">Anuluj</a>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="admin-card">
            <h3>Jak działa drzewo?</h3>
            <div style="font-size:13px;color:var(--tm);line-height:1.8">
                <p style="margin-bottom:12px">Drzewo diagnostyczne to seria pytań i odpowiedzi które prowadzą klienta do wstępnej diagnozy.</p>
                <p style="margin-bottom:8px"><strong style="color:var(--c)">Węzeł główny (1)</strong> — punkt startowy, pytanie o typ urządzenia</p>
                <p style="margin-bottom:8px"><strong style="color:#7a8aaa">Kontynuuj</strong> — węzeł ma dzieci, prowadzi dalej</p>
                <p style="margin-bottom:8px"><strong style="color:#00e5ff">Zalecana naprawa</strong> — koniec ścieżki, sugestia naprawy</p>
                <p style="margin-bottom:8px"><strong style="color:#f87171">Nie do naprawy</strong> — koniec, brak sensu ekonomicznego</p>
                <p><strong style="color:#8b5cf6">Skontaktuj się</strong> — przekierowanie do kontaktu</p>
            </div>
        </div>
        <div class="admin-card" style="margin-top:0">
            <h3>Podgląd dla klienta</h3>
            <p style="font-size:13px;color:var(--tm);margin-bottom:12px">Sprawdź jak wygląda diagnoza z perspektywy klienta.</p>
            <a href="/panel/diagnostyka" target="_blank" class="a-btn a-btn-secondary">Otwórz podgląd ↗</a>
        </div>
        <?php endif; ?>
    </div>

</div>
