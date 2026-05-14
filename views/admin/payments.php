<div class="admin-stats" style="grid-template-columns:repeat(2,1fr);max-width:500px;margin-bottom:32px">
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(34,197,94,0.1);border-color:rgba(34,197,94,0.2);color:#22c55e">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div>
            <strong><?= formatMoney($total_paid) ?></strong>
            <span>Łączne przychody</span>
        </div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(0,229,255,0.1);border-color:rgba(0,229,255,0.2);color:#00e5ff">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div>
            <strong><?= count($payments) ?></strong>
            <span>Wszystkich płatności</span>
        </div>
    </div>
</div>
<div class="admin-card">
    <?php if (empty($payments)): ?>
        <p style="color:var(--tm);text-align:center;padding:32px">Brak płatności.</p>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>RMA</th><th>Klient</th><th>Kwota</th><th>Metoda</th><th>Status</th><th>Data</th></tr></thead>
            <tbody>
            <?php foreach ($payments as $p): ?>
            <tr>
                <td><strong><?= sanitize($p['rma_number']) ?></strong></td>
                <td><?= sanitize($p['first_name'] . ' ' . $p['last_name']) ?></td>
                <td><strong style="color:var(--c)"><?= formatMoney($p['amount']) ?></strong></td>
                <td><?= sanitize($p['method']) ?></td>
                <td>
                    <?php $pc = $p['status'] === 'paid' ? '#22c55e' : ($p['status'] === 'refunded' ? '#f87171' : '#eab308') ?>
                    <span class="status-pill" style="background:<?= $pc ?>22;color:<?= $pc ?>"><?= sanitize($p['status']) ?></span>
                </td>
                <td><?= date('d.m.Y', strtotime($p['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
