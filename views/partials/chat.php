<?php
// $repair_id, $current_user_id, $is_admin muszą być ustawione
$unread = $pdo->prepare("SELECT COUNT(*) FROM repair_messages WHERE repair_id=? AND user_id != ? AND is_read=0");
$unread->execute([$repair_id, $current_user_id]);
$unread_count = $unread->fetchColumn();
?>
<div class="chat-box" id="chatBox">
    <div class="chat-box__header" onclick="toggleChat()">
        <div style="display:flex;align-items:center;gap:10px">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>Czat</span>
            <?php if ($unread_count > 0): ?>
            <span class="chat-badge" id="chatBadge"><?= $unread_count ?></span>
            <?php else: ?>
            <span class="chat-badge" id="chatBadge" style="display:none">0</span>
            <?php endif; ?>
        </div>
        <svg class="chat-chevron" id="chatChevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg>
    </div>
    <div class="chat-box__body" id="chatBody">
        <div class="chat-messages" id="chatMessages">
            <div class="chat-loading">Ładowanie...</div>
        </div>
        <div class="chat-input-wrap">
            <textarea id="chatInput" placeholder="Wpisz wiadomość..." rows="2"></textarea>
            <button id="chatSend" onclick="sendChatMessage()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </button>
        </div>
    </div>
</div>

<script>
var CHAT_REPAIR_ID = <?= (int)$repair_id ?>;
var CHAT_USER_ID   = <?= (int)$current_user_id ?>;
var CHAT_IS_ADMIN  = <?= $is_admin ? 'true' : 'false' ?>;
var CHAT_BASE      = CHAT_IS_ADMIN ? '/admin/naprawa/' : '/panel/naprawa/';
var chatOpen       = false;
var lastMsgId      = 0;
var chatPoll;

function toggleChat() {
    chatOpen = !chatOpen;
    document.getElementById('chatBody').style.display = chatOpen ? 'flex' : 'none';
    document.getElementById('chatChevron').style.transform = chatOpen ? 'rotate(180deg)' : '';
    if (chatOpen) {
        loadMessages();
        markRead();
        startPolling();
    } else {
        clearInterval(chatPoll);
    }
}

function loadMessages() {
    fetch(CHAT_BASE + CHAT_REPAIR_ID + '/wiadomosci')
        .then(r => r.json())
        .then(msgs => {
            renderMessages(msgs);
            if (CHAT_IS_ADMIN) markRead();
        });
}

function renderMessages(msgs) {
    var el = document.getElementById('chatMessages');
    if (msgs.length === 0) {
        el.innerHTML = '<div class="chat-empty">Brak wiadomości. Napisz pierwszy!</div>';
        return;
    }
    var html = '';
    msgs.forEach(function(m) {
        var mine = parseInt(m.user_id) === CHAT_USER_ID;
        var name = m.role === 'admin' ? 'NovaFix' : m.first_name;
        var time = new Date(m.created_at).toLocaleString('pl-PL', {day:'2-digit',month:'2-digit',hour:'2-digit',minute:'2-digit'});
        html += '<div class="chat-msg ' + (mine ? 'chat-msg--mine' : 'chat-msg--theirs') + '">';
        if (!mine) html += '<div class="chat-msg__name">' + name + '</div>';
        html += '<div class="chat-msg__bubble">' + escHtml(m.message) + '</div>';
        html += '<div class="chat-msg__time">' + time + '</div>';
        html += '</div>';
        lastMsgId = Math.max(lastMsgId, parseInt(m.id));
    });
    el.innerHTML = html;
    el.scrollTop = el.scrollHeight;
}

function sendChatMessage() {
    var input = document.getElementById('chatInput');
    var msg = input.value.trim();
    if (!msg) return;
    input.value = '';
    var btn = document.getElementById('chatSend');
    btn.disabled = true;

    fetch(CHAT_BASE + CHAT_REPAIR_ID + '/wiadomosc', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'message=' + encodeURIComponent(msg)
    }).then(function() {
        btn.disabled = false;
        loadMessages();
    });
}

function markRead() {
    if (CHAT_IS_ADMIN) {
        fetch(CHAT_BASE + CHAT_REPAIR_ID + '/przeczytane', {method:'POST'});
    }
    var badge = document.getElementById('chatBadge');
    if (badge) badge.style.display = 'none';
}

function startPolling() {
    clearInterval(chatPoll);
    chatPoll = setInterval(function() {
        if (chatOpen) loadMessages();
    }, 5000);
}

function escHtml(s) {
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
}

// Enter = wyślij, Shift+Enter = nowa linia
document.getElementById('chatInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendChatMessage(); }
});
</script>
