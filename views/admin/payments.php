<?php if ($success): ?><div class="a-alert a-alert--success"><?= sanitize($success) ?></div><?php endif; ?>

<div class="admin-stats" style="grid-template-columns:repeat(2,1fr);max-width:460px;margin-bottom:20px">
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(34,197,94,0.1);border-color:rgba(34,197,94,0.2);color:#22c55e">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div><strong><?= formatMoney((float)$total_paid) ?></strong><span>Łączne przychody</span></div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(0,229,255,0.1);border-color:rgba(0,229,255,0.2);color:#00e5ff">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div><strong><?= count($payments) ?></strong><span>Płatności</span></div>
    </div>
</div>

<div class="admin-card">
    <?php if (empty($payments)): ?>
        <p style="color:var(--tm);text-align:center;padding:24px">Brak płatności.</p>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>RMA</th><th>Klient</th><th>Kwota</th><th>Metoda</th><th>Data</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($payments as $p): ?>
            <tr>
                <td><strong style="color:#fff"><?= sanitize($p['rma_number']) ?></strong></td>
                <td><?= sanitize($p['first_name'].' '.$p['last_name']) ?></td>
                <td><strong style="color:#00e5ff"><?= formatMoney((float)$p['amount']) ?></strong></td>
                <td style="color:var(--tm)"><?= sanitize($p['method']) ?></td>
                <td style="color:var(--tm);font-size:12px" title="<?= date('d.m.Y H:i', strtotime($p['created_at'])) ?>"><?= formatDate($p['created_at']) ?></td>
                <td>
                    <form method="POST" action="/admin/platnosc/<?= $p['id'] ?>/usun"
                          onsubmit="return confirm('Usunąć tę płatność z historii?')">
                        <button type="submit" class="del-btn" title="Usuń"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
