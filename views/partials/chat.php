<?php
$unread_stmt = $pdo->prepare("SELECT COUNT(*) FROM repair_messages WHERE repair_id=? AND user_id != ? AND is_read=0");
$unread_stmt->execute([$repair_id, $current_user_id]);
$unread_count = (int)$unread_stmt->fetchColumn();
?>

<!-- Przycisk dymek -->
<button class="chat-bubble" id="chatBubble" onclick="chatToggle()" title="Czat ze zleceniem">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
    </svg>
    <?php if ($unread_count > 0): ?>
    <span class="chat-bubble__badge" id="chatBadge"><?= $unread_count ?></span>
    <?php else: ?>
    <span class="chat-bubble__badge" id="chatBadge" style="display:none"></span>
    <?php endif; ?>
</button>

<!-- Okienko czatu -->
<div class="chat-window" id="chatWindow">
    <div class="chat-window__header">
        <div class="chat-window__title">
            <div class="chat-window__avatar">
                <?= $is_admin ? 'K' : 'NF' ?>
            </div>
            <div>
                <strong><?= $is_admin ? 'Klient' : 'NovaFix' ?></strong>
                <span>Zlecenie <?= sanitize($repair['rma_number'] ?? '') ?></span>
            </div>
        </div>
        <button class="chat-window__close" onclick="chatToggle()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <div class="chat-window__messages" id="chatMessages"></div>
    <div class="chat-window__input">
        <textarea id="chatInput" placeholder="Napisz wiadomość..." rows="1"></textarea>
        <button id="chatSendBtn" onclick="chatSend()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </button>
    </div>
</div>

<style>
.chat-bubble {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 56px; height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0050d0, #00e5ff);
    border: none;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    box-shadow: 0 4px 24px rgba(0,100,255,0.4);
    z-index: 9990;
    transition: transform 0.2s, box-shadow 0.2s;
}
.chat-bubble:hover { transform: scale(1.1); box-shadow: 0 6px 32px rgba(0,100,255,0.5); }
.chat-bubble__badge {
    position: absolute;
    top: -4px; right: -4px;
    background: #ef4444;
    color: #fff;
    font-size: 11px; font-weight: 700;
    min-width: 20px; height: 20px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    padding: 0 5px;
    border: 2px solid #070d1a;
    line-height: 1;
}
.chat-window {
    position: fixed;
    bottom: 96px;
    right: 28px;
    width: 340px;
    height: 460px;
    background: #0f1929;
    border: 1px solid rgba(0,229,255,0.15);
    border-radius: 20px;
    box-shadow: 0 16px 60px rgba(0,0,0,0.6), 0 0 0 1px rgba(0,229,255,0.05);
    z-index: 9989;
    display: none;
    flex-direction: column;
    overflow: hidden;
    transform: translateY(16px);
    opacity: 0;
    transition: transform 0.25s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease;
}
.chat-window.open {
    display: flex;
    transform: translateY(0);
    opacity: 1;
}
.chat-window__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    background: linear-gradient(135deg, rgba(0,50,160,0.5), rgba(0,20,80,0.3));
    border-bottom: 1px solid rgba(0,229,255,0.1);
    flex-shrink: 0;
}
.chat-window__title { display: flex; align-items: center; gap: 10px; }
.chat-window__avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0050d0, #00e5ff);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; color: #fff;
    flex-shrink: 0;
}
.chat-window__title strong { display: block; font-size: 14px; color: #fff; }
.chat-window__title span { font-size: 11px; color: rgba(255,255,255,0.5); }
.chat-window__close {
    background: rgba(255,255,255,0.08);
    border: none;
    color: rgba(255,255,255,0.6);
    width: 30px; height: 30px;
    border-radius: 8px;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.15s;
    flex-shrink: 0;
}
.chat-window__close:hover { background: rgba(239,68,68,0.15); color: #f87171; }
.chat-window__messages {
    flex: 1;
    overflow-y: auto;
    padding: 14px 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    scroll-behavior: smooth;
}
.chat-window__messages::-webkit-scrollbar { width: 3px; }
.chat-window__messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }
.chat-empty {
    margin: auto;
    text-align: center;
    color: rgba(255,255,255,0.3);
    font-size: 13px;
    padding: 20px;
}
.chat-empty svg { margin: 0 auto 10px; display: block; opacity: 0.3; }
.cm { display: flex; flex-direction: column; max-width: 82%; animation: cmIn 0.2s ease; }
.cm--me { align-self: flex-end; align-items: flex-end; }
.cm--them { align-self: flex-start; align-items: flex-start; }
.cm__name { font-size: 10px; color: rgba(0,229,255,0.7); font-weight: 600; margin-bottom: 3px; letter-spacing: 0.3px; }
.cm__bubble {
    padding: 8px 12px;
    border-radius: 16px;
    font-size: 13px;
    line-height: 1.55;
    word-break: break-word;
}
.cm--me .cm__bubble {
    background: linear-gradient(135deg, #0050d0, #0070ff);
    color: #fff;
    border-bottom-right-radius: 4px;
}
.cm--them .cm__bubble {
    background: rgba(255,255,255,0.07);
    color: #e2e8f4;
    border-bottom-left-radius: 4px;
    border: 1px solid rgba(255,255,255,0.06);
}
.cm__time { font-size: 10px; color: rgba(255,255,255,0.25); margin-top: 3px; }
.chat-window__input {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    padding: 10px 12px;
    border-top: 1px solid rgba(255,255,255,0.06);
    background: rgba(0,0,0,0.2);
    flex-shrink: 0;
}
.chat-window__input textarea {
    flex: 1;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    color: #e2e8f4;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    padding: 9px 12px;
    resize: none;
    outline: none;
    max-height: 100px;
    line-height: 1.4;
    transition: border-color 0.2s;
}
.chat-window__input textarea:focus { border-color: rgba(0,229,255,0.3); }
.chat-window__input textarea::placeholder { color: rgba(255,255,255,0.25); }
.chat-window__input button {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #0050d0, #00e5ff);
    border: none;
    color: #fff;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: opacity 0.2s;
}
.chat-window__input button:hover { opacity: 0.85; }
.chat-window__input button:disabled { opacity: 0.35; cursor: not-allowed; }
@keyframes cmIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

@media (max-width: 768px) {
    .chat-bubble { bottom: 20px; right: 20px; width: 50px; height: 50px; }
    .chat-window { width: calc(100vw - 24px); right: 12px; bottom: 82px; height: 420px; }
}
</style>

<script>
(function() {
    var REPAIR_ID   = <?= (int)$repair_id ?>;
    var USER_ID     = <?= (int)$current_user_id ?>;
    var IS_ADMIN    = <?= $is_admin ? 'true' : 'false' ?>;
    var BASE        = IS_ADMIN ? '/admin/naprawa/' : '/panel/naprawa/';
    var isOpen      = false;
    var pollTimer   = null;
    var lastIds     = [];

    var win     = document.getElementById('chatWindow');
    var msgs    = document.getElementById('chatMessages');
    var input   = document.getElementById('chatInput');
    var sendBtn = document.getElementById('chatSendBtn');
    var badge   = document.getElementById('chatBadge');

    window.chatToggle = function() {
        isOpen = !isOpen;
        if (isOpen) {
            win.style.display = 'flex';
            setTimeout(function() { win.classList.add('open'); }, 10);
            loadMessages(true);
            startPoll();
        } else {
            win.classList.remove('open');
            setTimeout(function() { win.style.display = 'none'; }, 250);
            stopPoll();
        }
    };

    window.chatSend = function() {
        var text = input.value.trim();
        if (!text) return;
        input.value = '';
        input.style.height = '';
        sendBtn.disabled = true;

        fetch(BASE + REPAIR_ID + '/wiadomosc', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'message=' + encodeURIComponent(text)
        }).then(function() {
            sendBtn.disabled = false;
            loadMessages(false);
        }).catch(function() {
            sendBtn.disabled = false;
        });
    };

    function loadMessages(scroll) {
        fetch(BASE + REPAIR_ID + '/wiadomosci')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                renderMessages(data, scroll);
                if (IS_ADMIN) markRead();
                // Ukryj badge
                if (badge) badge.style.display = 'none';
            })
            .catch(function() {});
    }

    function renderMessages(data, scroll) {
        if (data.length === 0) {
            msgs.innerHTML = '<div class="chat-empty"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Brak wiadomości.<br>Napisz coś!</div>';
            return;
        }

        // Sprawdź czy są nowe wiadomości
        var newIds = data.map(function(m) { return m.id; });
        var hasNew = newIds.some(function(id) { return lastIds.indexOf(id) === -1; });
        lastIds = newIds;

        if (!hasNew && !scroll) return; // Nic nowego — nie rerenderuj

        var wasAtBottom = msgs.scrollHeight - msgs.scrollTop - msgs.clientHeight < 40;
        var html = '';

        data.forEach(function(m) {
            var mine = parseInt(m.user_id) === USER_ID;
            var name = m.role === 'admin' ? '⚡ NovaFix' : m.first_name;
            var d = new Date(m.created_at.replace(' ', 'T'));
            var time = d.toLocaleDateString('pl-PL',{day:'2-digit',month:'2-digit'}) + ' ' + d.toLocaleTimeString('pl-PL',{hour:'2-digit',minute:'2-digit'});
            html += '<div class="cm ' + (mine ? 'cm--me' : 'cm--them') + '">';
            if (!mine) html += '<div class="cm__name">' + esc(name) + '</div>';
            html += '<div class="cm__bubble">' + esc(m.message) + '</div>';
            html += '<div class="cm__time">' + time + '</div>';
            html += '</div>';
        });

        msgs.innerHTML = html;
        if (scroll || wasAtBottom) msgs.scrollTop = msgs.scrollHeight;
    }

    function markRead() {
        fetch(BASE + REPAIR_ID + '/przeczytane', {method:'POST'}).catch(function(){});
    }

    function startPoll() {
        stopPoll();
        pollTimer = setInterval(function() {
            if (isOpen) loadMessages(false);
        }, 3000);
    }

    function stopPoll() {
        if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
    }

    function esc(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
    }

    // Enter = wyślij
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); chatSend(); }
    });

    // Auto-resize textarea
    input.addEventListener('input', function() {
        this.style.height = '';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });

    // Badge przy nowych wiadomościach (gdy czat zamknięty)
    setInterval(function() {
        if (isOpen) return;
        fetch(BASE + REPAIR_ID + '/wiadomosci')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var unread = data.filter(function(m) {
                    return parseInt(m.user_id) !== USER_ID && m.is_read === '0';
                }).length;
                if (unread > 0) {
                    badge.textContent = unread;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }).catch(function(){});
    }, 8000);
})();
</script>
