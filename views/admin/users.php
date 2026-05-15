<?php if ($success): ?><div class="a-alert a-alert--success"><?= sanitize($success) ?></div><?php endif; ?>

<div class="admin-card">
    <h2>Użytkownicy (<?= count($users) ?>)</h2>
    <?php if (empty($users)): ?>
        <p style="color:var(--tm);text-align:center;padding:24px">Brak użytkowników.</p>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr><th>Imię i nazwisko</th><th>Email</th><th>Telefon</th><th>Zgłoszenia</th><th>Rejestracja</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><strong style="color:#fff"><?= sanitize($u['first_name'].' '.$u['last_name']) ?></strong></td>
                <td style="color:var(--tm)"><?= sanitize($u['email']) ?></td>
                <td style="color:var(--tm)"><?= sanitize($u['phone'] ?? '—') ?></td>
                <td><span style="color:var(--c);font-weight:600"><?= $u['repair_count'] ?></span></td>
                <td style="color:var(--tm);font-size:13px"><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                <td>
                    <?php if ($u['repair_count'] == 0): ?>
                    <form method="POST" action="/admin/uzytkownik/<?= $u['id'] ?>/usun"
                          onsubmit="return confirm('Usunąć użytkownika <?= sanitize($u['first_name'].' '.$u['last_name']) ?>?')">
                        <button type="submit" class="del-btn" title="Usuń"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg></button>
                    </form>
                    <?php else: ?>
                        <span style="font-size:12px;color:var(--tm)" title="Nie można usunąć — ma zgłoszenia">🔒</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
