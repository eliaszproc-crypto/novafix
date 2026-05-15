<?php
// Zakres dat
$period = $_GET['period'] ?? 'month';
$custom_from = $_GET['from'] ?? '';
$custom_to   = $_GET['to']   ?? '';

switch ($period) {
    case 'today':
        $from = date('Y-m-d 00:00:00');
        $to   = date('Y-m-d 23:59:59');
        $label = 'Dziś';
        break;
    case 'week':
        $from = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $to   = date('Y-m-d 23:59:59');
        $label = 'Ten tydzień';
        break;
    case 'year':
        $from = date('Y-01-01 00:00:00');
        $to   = date('Y-12-31 23:59:59');
        $label = 'Ten rok ('.date('Y').')';
        break;
    case 'custom':
        $from  = $custom_from ? $custom_from.' 00:00:00' : date('Y-m-01 00:00:00');
        $to    = $custom_to   ? $custom_to.' 23:59:59'   : date('Y-m-d 23:59:59');
        $label = 'Zakres: '.date('d.m.Y', strtotime($from)).' – '.date('d.m.Y', strtotime($to));
        break;
    default: // month
        $from = date('Y-m-01 00:00:00');
        $to   = date('Y-m-t 23:59:59');
        $label = 'Ten miesiąc ('.date('F Y', strtotime($from)).')';
}

// Statystyki ogólne
$total_repairs   = $pdo->query("SELECT COUNT(*) FROM repairs WHERE created_at BETWEEN '$from' AND '$to'")->fetchColumn();
$completed       = $pdo->query("SELECT COUNT(*) FROM repairs r JOIN repair_statuses rs ON r.status_id=rs.id WHERE rs.code='completed' AND r.updated_at BETWEEN '$from' AND '$to'")->fetchColumn();
$total_revenue   = $pdo->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE paid_at BETWEEN '$from' AND '$to'")->fetchColumn();
$avg_repair_val  = $pdo->query("SELECT COALESCE(AVG(amount),0) FROM payments WHERE paid_at BETWEEN '$from' AND '$to'")->fetchColumn();
$pending_payment = $pdo->query("SELECT COUNT(*) FROM repairs r JOIN repair_statuses rs ON r.status_id=rs.id WHERE rs.code='awaiting_payment'")->fetchColumn();
$active_now      = $pdo->query("SELECT COUNT(*) FROM repairs r JOIN repair_statuses rs ON r.status_id=rs.id WHERE rs.code NOT IN ('completed','initial_quote_rejected','final_quote_rejected','return_in_progress')")->fetchColumn();

// Naprawy wg statusu
$by_status = $pdo->query("
    SELECT rs.label, rs.color, COUNT(r.id) as cnt
    FROM repairs r
    JOIN repair_statuses rs ON r.status_id=rs.id
    WHERE r.created_at BETWEEN '$from' AND '$to'
    GROUP BY rs.id ORDER BY cnt DESC
")->fetchAll();

// Najpopularniejsze typy urządzeń
$by_device = $pdo->query("
    SELECT dt.name, COUNT(r.id) as cnt
    FROM repairs r
    JOIN device_types dt ON r.device_type_id=dt.id
    WHERE r.created_at BETWEEN '$from' AND '$to'
    GROUP BY dt.id ORDER BY cnt DESC LIMIT 6
")->fetchAll();

// Przychody dzień po dniu (ostatnie 30 dni lub zakres)
$daily = $pdo->query("
    SELECT DATE(paid_at) as day, SUM(amount) as total, COUNT(*) as cnt
    FROM payments
    WHERE paid_at BETWEEN '$from' AND '$to'
    GROUP BY DATE(paid_at) ORDER BY day ASC
")->fetchAll();

// Zgłoszenia dzień po dniu
$daily_repairs = $pdo->query("
    SELECT DATE(created_at) as day, COUNT(*) as cnt
    FROM repairs
    WHERE created_at BETWEEN '$from' AND '$to'
    GROUP BY DATE(created_at) ORDER BY day ASC
")->fetchAll();

// Ostatnie płatności w okresie
$recent_payments = $pdo->query("
    SELECT p.*, r.rma_number, u.first_name, u.last_name
    FROM payments p JOIN repairs r ON p.repair_id=r.id JOIN users u ON r.user_id=u.id
    WHERE p.paid_at BETWEEN '$from' AND '$to'
    ORDER BY p.paid_at DESC LIMIT 10
")->fetchAll();

$max_daily = max(array_merge([1], array_column($daily, 'total')));
$max_repairs = max(array_merge([1], array_column($daily_repairs, 'cnt')));
?>

<!-- Filtr okresu -->
<div class="stats-filter">
    <a href="/admin/statystyki?period=today"  class="stats-period <?= $period==='today'  ?'active':'' ?>">Dziś</a>
    <a href="/admin/statystyki?period=week"   class="stats-period <?= $period==='week'   ?'active':'' ?>">Tydzień</a>
    <a href="/admin/statystyki?period=month"  class="stats-period <?= $period==='month'  ?'active':'' ?>">Miesiąc</a>
    <a href="/admin/statystyki?period=year"   class="stats-period <?= $period==='year'   ?'active':'' ?>">Rok</a>
    <form method="GET" style="display:flex;gap:8px;align-items:center">
        <input type="hidden" name="period" value="custom">
        <input type="date" name="from" value="<?= $custom_from ?>" class="filter-input" style="width:140px;padding:7px 10px">
        <span style="color:var(--tm)">—</span>
        <input type="date" name="to" value="<?= $custom_to ?>" class="filter-input" style="width:140px;padding:7px 10px">
        <button type="submit" class="a-btn a-btn-secondary" style="padding:7px 14px">Szukaj</button>
    </form>
</div>

<p style="color:var(--tm);font-size:13px;margin-bottom:24px"><?= $label ?></p>

<!-- Główne statystyki -->
<div class="admin-stats" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px">
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(0,229,255,0.1);border-color:rgba(0,229,255,0.2);color:#00e5ff">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
        </div>
        <div><strong><?= $total_repairs ?></strong><span>Nowych zgłoszeń</span></div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(34,197,94,0.1);border-color:rgba(34,197,94,0.2);color:#22c55e">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div><strong><?= $completed ?></strong><span>Zakończonych napraw</span></div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(234,179,8,0.1);border-color:rgba(234,179,8,0.2);color:#eab308">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div><strong><?= formatMoney((float)$total_revenue) ?></strong><span>Przychód w okresie</span></div>
    </div>
</div>

<div class="admin-stats" style="grid-template-columns:repeat(3,1fr);margin-bottom:28px">
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(99,102,241,0.1);border-color:rgba(99,102,241,0.2);color:#818cf8">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div><strong><?= $active_now ?></strong><span>Aktywnych napraw</span></div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(249,115,22,0.1);border-color:rgba(249,115,22,0.2);color:#f97316">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div><strong><?= $pending_payment ?></strong><span>Oczekuje na płatność</span></div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__icon" style="background:rgba(20,184,166,0.1);border-color:rgba(20,184,166,0.2);color:#14b8a6">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div><strong><?= $avg_repair_val > 0 ? formatMoney((float)$avg_repair_val) : '—' ?></strong><span>Śr. wartość naprawy</span></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">

    <!-- Wykres przychodów -->
    <div class="admin-card">
        <h3>Przychody dzień po dniu</h3>
        <?php if (empty($daily)): ?>
            <p style="color:var(--tm);text-align:center;padding:24px 0">Brak danych w wybranym okresie.</p>
        <?php else: ?>
        <div class="bar-chart">
            <?php foreach ($daily as $d):
                $h = max(4, round(($d['total'] / $max_daily) * 120));
            ?>
            <div class="bar-chart__col">
                <div class="bar-chart__bar" style="height:<?= $h ?>px" title="<?= formatMoney((float)$d['total']) ?>"></div>
                <div class="bar-chart__label"><?= date('d.m', strtotime($d['day'])) ?></div>
                <div class="bar-chart__val"><?= number_format((float)$d['total'],0,'.','')?> zł</div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Wykres zgłoszeń -->
    <div class="admin-card">
        <h3>Zgłoszenia dzień po dniu</h3>
        <?php if (empty($daily_repairs)): ?>
            <p style="color:var(--tm);text-align:center;padding:24px 0">Brak danych w wybranym okresie.</p>
        <?php else: ?>
        <div class="bar-chart">
            <?php foreach ($daily_repairs as $d):
                $h = max(4, round(($d['cnt'] / $max_repairs) * 120));
            ?>
            <div class="bar-chart__col">
                <div class="bar-chart__bar bar-chart__bar--cyan" style="height:<?= $h ?>px" title="<?= $d['cnt'] ?> zgłoszeń"></div>
                <div class="bar-chart__label"><?= date('d.m', strtotime($d['day'])) ?></div>
                <div class="bar-chart__val"><?= $d['cnt'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">

    <!-- Typy urządzeń -->
    <div class="admin-card">
        <h3>Typy urządzeń</h3>
        <?php if (empty($by_device)): ?>
            <p style="color:var(--tm);font-size:13px">Brak danych.</p>
        <?php else: ?>
        <?php $max_dev = max(array_column($by_device,'cnt')); ?>
        <div style="display:flex;flex-direction:column;gap:10px">
            <?php foreach ($by_device as $d): ?>
            <div>
                <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:13px">
                    <span style="color:var(--t)"><?= sanitize($d['name']) ?></span>
                    <strong style="color:var(--c)"><?= $d['cnt'] ?></strong>
                </div>
                <div style="height:6px;background:rgba(255,255,255,0.05);border-radius:4px;overflow:hidden">
                    <div style="height:100%;width:<?= round($d['cnt']/$max_dev*100) ?>%;background:linear-gradient(90deg,var(--c),#0060ff);border-radius:4px;transition:width .5s ease"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Statusy -->
    <div class="admin-card">
        <h3>Zgłoszenia wg statusu</h3>
        <?php if (empty($by_status)): ?>
            <p style="color:var(--tm);font-size:13px">Brak danych.</p>
        <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:8px">
            <?php $max_st = max(array_column($by_status,'cnt')); ?>
            <?php foreach ($by_status as $s): ?>
            <div style="display:flex;align-items:center;gap:10px;font-size:13px">
                <span style="width:8px;height:8px;border-radius:50%;background:<?= $s['color'] ?>;flex-shrink:0"></span>
                <span style="flex:1;color:var(--t)"><?= sanitize($s['label']) ?></span>
                <div style="width:80px;height:5px;background:rgba(255,255,255,0.05);border-radius:4px;overflow:hidden">
                    <div style="height:100%;width:<?= round($s['cnt']/$max_st*100) ?>%;background:<?= $s['color'] ?>;border-radius:4px"></div>
                </div>
                <strong style="color:<?= $s['color'] ?>;width:20px;text-align:right"><?= $s['cnt'] ?></strong>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</div>

<!-- Ostatnie płatności -->
<?php if (!empty($recent_payments)): ?>
<div class="admin-card">
    <h3>Płatności w wybranym okresie</h3>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>RMA</th><th>Klient</th><th>Kwota</th><th>Data</th></tr></thead>
            <tbody>
            <?php foreach ($recent_payments as $p): ?>
            <tr>
                <td><a href="/admin/naprawa/<?= $p['repair_id'] ?>" class="table-link"><?= sanitize($p['rma_number']) ?></a></td>
                <td><?= sanitize($p['first_name'].' '.$p['last_name']) ?></td>
                <td><strong style="color:#22c55e"><?= formatMoney((float)$p['amount']) ?></strong></td>
                <td style="color:var(--tm);font-size:12px" title="<?= $p['paid_at'] ?>"><?= formatDate($p['paid_at']) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
