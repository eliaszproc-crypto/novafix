<?php if ($success): ?><div class="a-alert a-alert--success"><?= sanitize($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="a-alert a-alert--error"><?= sanitize($error) ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start">

    <!-- Lista cennika -->
    <div>
        <?php foreach ($by_cat as $cat => $items): ?>
        <div class="admin-card" style="margin-bottom:16px">
            <div class="admin-card__header">
                <h3 style="margin-bottom:0"><?= sanitize($cat) ?></h3>
                <a href="/admin/cennik?action=add&category=<?= urlencode($cat) ?>" class="a-btn a-btn-secondary" style="padding:5px 12px;font-size:12px">+ Dodaj</a>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead><tr><th>Usługa</th><th>Cena</th><th>Jedn.</th><th>Uwagi</th><th>Aktywna</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr style="<?= $item['is_active'] ? '' : 'opacity:0.45' ?>">
                        <td><strong style="color:#fff"><?= sanitize($item['name']) ?></strong></td>
                        <td style="white-space:nowrap">
                            <span style="color:var(--c);font-family:'Outfit',sans-serif;font-weight:700">
                                od <?= number_format($item['price_from'],0,'.','')?> zł
                                <?php if ($item['price_to']): ?>– <?= number_format($item['price_to'],0,'.','') ?> zł<?php endif; ?>
                            </span>
                        </td>
                        <td style="color:var(--tm)"><?= sanitize($item['unit'] ?? '—') ?></td>
                        <td style="color:var(--tm);font-size:12px"><?= sanitize($item['note'] ?? '') ?></td>
                        <td>
                            <?php if ($item['is_active']): ?>
                                <span style="color:#22c55e;font-size:12px">✓</span>
                            <?php else: ?>
                                <span style="color:#f87171;font-size:12px">✗</span>
                            <?php endif; ?>
                        </td>
                        <td style="display:flex;gap:6px;align-items:center">
                            <a href="/admin/cennik?edit=<?= $item['id'] ?>" class="a-btn a-btn-secondary" style="padding:4px 10px;font-size:12px">Edytuj</a>
                            <form method="POST" action="/admin/cennik/usun/<?= $item['id'] ?>" style="margin:0"
                                  onsubmit="return confirm('Usunąć tę pozycję?')">
                                <button type="submit" class="del-btn">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="admin-card">
            <a href="/admin/cennik?action=add" class="a-btn a-btn-primary">+ Dodaj nową pozycję</a>
        </div>
    </div>

    <!-- Formularz edycji -->
    <div style="position:sticky;top:80px">
        <?php if ($edit_item || isset($_GET['action'])): ?>
        <div class="admin-card">
            <h3><?= $edit_item ? 'Edytuj pozycję' : 'Nowa pozycja' ?></h3>
            <form method="POST" action="/admin/cennik/<?= $edit_item ? 'edytuj/'.$edit_item['id'] : 'dodaj' ?>" class="admin-form">
                <div class="f-group">
                    <label>Kategoria *</label>
                    <input type="text" name="category" required list="categories"
                           value="<?= sanitize($edit_item['category'] ?? urldecode($_GET['category'] ?? '')) ?>"
                           placeholder="np. Lampy LED">
                    <datalist id="categories">
                        <?php foreach (array_keys($by_cat) as $cat): ?>
                            <option value="<?= sanitize($cat) ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="f-group">
                    <label>Nazwa usługi *</label>
                    <input type="text" name="name" required value="<?= sanitize($edit_item['name'] ?? '') ?>" placeholder="np. Wymiana drivera LED">
                </div>
                <div class="f-row">
                    <div class="f-group">
                        <label>Cena od (zł) *</label>
                        <input type="number" name="price_from" required step="1" min="0"
                               value="<?= $edit_item['price_from'] ?? '' ?>" placeholder="80">
                    </div>
                    <div class="f-group">
                        <label>Cena do (opcja)</label>
                        <input type="number" name="price_to" step="1" min="0"
                               value="<?= $edit_item['price_to'] ?? '' ?>" placeholder="200">
                    </div>
                </div>
                <div class="f-group">
                    <label>Jednostka (opcja)</label>
                    <input type="text" name="unit" value="<?= sanitize($edit_item['unit'] ?? '') ?>" placeholder="np. szt.">
                </div>
                <div class="f-group">
                    <label>Uwagi / opis</label>
                    <input type="text" name="note" value="<?= sanitize($edit_item['note'] ?? '') ?>" placeholder="Krótki opis">
                </div>
                <div class="f-row">
                    <div class="f-group">
                        <label>Kolejność</label>
                        <input type="number" name="sort_order" min="0" value="<?= $edit_item['sort_order'] ?? 0 ?>">
                    </div>
                    <div class="f-group" style="justify-content:flex-end;padding-top:20px">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                            <input type="checkbox" name="is_active" value="1" <?= ($edit_item['is_active'] ?? 1) ? 'checked' : '' ?>>
                            Aktywna
                        </label>
                    </div>
                </div>
                <div style="display:flex;gap:10px">
                    <button type="submit" class="a-btn a-btn-primary">Zapisz</button>
                    <a href="/admin/cennik" class="a-btn a-btn-secondary">Anuluj</a>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="admin-card">
            <h3>Wskazówki</h3>
            <div style="font-size:13px;color:var(--tm);line-height:1.8">
                <p>Zmiany w cenniku są widoczne na stronie publicznej od razu po zapisaniu.</p>
                <p style="margin-top:8px">Pozycje nieaktywne (✗) nie są wyświetlane klientom.</p>
                <p style="margin-top:8px">Możesz tworzyć nowe kategorie wpisując własną nazwę.</p>
            </div>
            <div style="margin-top:16px">
                <a href="/cennik" target="_blank" class="a-btn a-btn-secondary">Zobacz cennik publiczny ↗</a>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>
