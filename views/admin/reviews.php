<?php if ($success): ?><div class="a-alert a-alert--success"><?= sanitize($success) ?></div><?php endif; ?>

<div class="admin-card">
    <h2>Opinie (<?= count($reviews) ?>)</h2>
    <?php if (empty($reviews)): ?>
        <p style="color:var(--tm);padding:24px;text-align:center">Brak opinii.</p>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Autor</th><th>Ocena</th><th>Treść</th><th>Zlecenie</th><th>Data</th><th>Typ</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($reviews as $r): ?>
            <tr style="<?= !$r['is_visible'] ? 'opacity:0.45' : '' ?>">
                <td><strong style="color:#fff"><?= sanitize($r['author']) ?></strong></td>
                <td><span style="color:#eab308"><?= str_repeat('★', $r['rating']) ?></span></td>
                <td style="font-size:12px;color:var(--tm);max-width:280px"><?= sanitize(mb_substr($r['content'],0,100)).'...' ?></td>
                <td style="font-size:12px;color:var(--c)"><?= $r['rma_number'] ? sanitize($r['rma_number']) : '—' ?></td>
                <td style="font-size:12px;color:var(--tm)"><?= formatDate($r['created_at']) ?></td>
                <td>
                    <?php if ($r['is_fake']): ?>
                        <span style="font-size:11px;padding:2px 8px;background:rgba(99,102,241,0.1);color:#818cf8;border-radius:6px;border:1px solid rgba(99,102,241,0.2)">przykładowa</span>
                    <?php else: ?>
                        <span style="font-size:11px;padding:2px 8px;background:rgba(34,197,94,0.1);color:#22c55e;border-radius:6px;border:1px solid rgba(34,197,94,0.2)">prawdziwa</span>
                    <?php endif; ?>
                </td>
                <td style="display:flex;gap:6px;align-items:center">
                    <form method="POST" action="/admin/opinia/<?= $r['id'] ?>/widocznosc" style="margin:0">
                        <button type="submit" class="a-btn a-btn-secondary" style="padding:4px 10px;font-size:12px" title="<?= $r['is_visible'] ? 'Ukryj' : 'Pokaż' ?>">
                            <?= $r['is_visible'] ? '👁 Ukryj' : '👁 Pokaż' ?>
                        </button>
                    </form>
                    <form method="POST" action="/admin/opinia/<?= $r['id'] ?>/usun" onsubmit="return confirm('Usunąć opinię?')" style="margin:0">
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
    <?php endif; ?>
</div>
