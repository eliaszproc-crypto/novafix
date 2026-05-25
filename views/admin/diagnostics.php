<?php
global $pdo;
$nodes = $pdo->query('SELECT * FROM diag_nodes ORDER BY id')->fetchAll();
$nodes_json = json_encode($nodes);

$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';
?>
<?php if ($success): ?><div class="a-alert a-alert--success" style="margin-bottom:16px"><?= sanitize($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="a-alert a-alert--error" style="margin-bottom:16px"><?= sanitize($error) ?></div><?php endif; ?>

<div class="node-editor" id="nodeEditor">
    <!-- Toolbar -->
    <div class="ne-toolbar">
        <button class="ne-tool-btn" onclick="addNode()" title="Dodaj węzeł">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nowy węzeł
        </button>
        <button class="ne-tool-btn" onclick="fitView()" title="Dopasuj widok">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
            Dopasuj
        </button>
        <button class="ne-tool-btn" onclick="deleteSelected()" id="btnDelete" style="display:none;color:#f87171">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
            Usuń węzeł
        </button>
        <div class="ne-toolbar-sep"></div>
        <span style="font-size:12px;color:var(--tm)">Przeciągnij węzły · Kliknij żeby edytować · Ctrl+scroll = zoom</span>
        <div style="margin-left:auto;display:flex;gap:8px;align-items:center">
            <span id="neStatus" style="font-size:12px;color:var(--tm)"></span>
            <a href="/panel/diagnostyka" target="_blank" class="a-btn a-btn-secondary" style="padding:5px 12px;font-size:12px">Podgląd klienta ↗</a>
        </div>
    </div>

    <!-- Canvas -->
    <div class="ne-canvas-wrap" id="neCanvasWrap">
        <canvas id="neCanvas"></canvas>
    </div>

    <!-- Panel edycji węzła -->
    <div class="ne-panel" id="nePanel" style="display:none">
        <div class="ne-panel__header">
            <h3 id="nePanelTitle">Edytuj węzeł</h3>
            <button onclick="closePanel()" class="ne-panel__close">✕</button>
        </div>
        <div class="ne-panel__body">
            <div class="f-group">
                <label>Odpowiedź (tekst przycisku u klienta)</label>
                <input type="text" id="neAnswer" placeholder="np. Lampa LED, Nie świeci wcale...">
            </div>
            <div class="f-group">
                <label>Pytanie / treść węzła *</label>
                <textarea id="neQuestion" rows="3" placeholder="np. Co się dzieje z lampą?"></textarea>
            </div>
            <div class="f-group">
                <label>Wynik diagnozy <span style="color:var(--tm);font-weight:400">(dla węzłów końcowych)</span></label>
                <textarea id="neResult" rows="4" placeholder="Opis usterki i zalecenie..."></textarea>
            </div>
            <div class="f-group">
                <label>Typ wyniku</label>
                <select id="neResultType">
                    <option value="continue">↪ Kontynuuj (ma odpowiedzi)</option>
                    <option value="repair">🔧 Zalecana naprawa</option>
                    <option value="no_repair">✗ Nie nadaje się do naprawy</option>
                    <option value="contact">✉ Skontaktuj się</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button class="a-btn a-btn-primary" onclick="saveNode()">Zapisz</button>
                <button class="a-btn a-btn-secondary" onclick="closePanel()">Anuluj</button>
            </div>
        </div>
        <div class="ne-panel__footer">
            <p style="font-size:12px;color:var(--tm)">💡 Połącz węzły: przeciągnij z dolnego portu rodzica do górnego portu dziecka</p>
        </div>
    </div>
</div>

<style>
.node-editor {
    position: relative;
    width: 100%;
    height: calc(100vh - 140px);
    background: #070d1a;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--bd);
    display: flex;
    flex-direction: column;
}
.ne-toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: var(--bg3);
    border-bottom: 1px solid var(--bd);
    flex-shrink: 0;
    flex-wrap: wrap;
}
.ne-tool-btn {
    display: flex; align-items: center; gap: 6px;
    padding: 6px 12px;
    background: var(--bg4);
    border: 1px solid var(--bd);
    color: var(--t);
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.15s;
}
.ne-tool-btn:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.15); }
.ne-toolbar-sep { width: 1px; height: 20px; background: var(--bd); margin: 0 4px; }
.ne-canvas-wrap {
    flex: 1;
    position: relative;
    overflow: hidden;
    cursor: grab;
}
.ne-canvas-wrap:active { cursor: grabbing; }
#neCanvas { position: absolute; top: 0; left: 0; }
.ne-panel {
    position: absolute;
    top: 52px; right: 16px;
    width: 320px;
    background: var(--bg3);
    border: 1px solid var(--bdc);
    border-radius: 14px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.5);
    z-index: 100;
    display: flex;
    flex-direction: column;
    max-height: calc(100% - 70px);
}
.ne-panel__header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid var(--bd);
}
.ne-panel__header h3 { margin: 0; font-size: 15px; color: #fff; }
.ne-panel__close { background: none; border: none; color: var(--tm); cursor: pointer; font-size: 16px; padding: 2px 6px; }
.ne-panel__close:hover { color: #f87171; }
.ne-panel__body { padding: 18px; overflow-y: auto; flex: 1; }
.ne-panel__footer { padding: 10px 18px; border-top: 1px solid var(--bd); }
.ne-panel .f-group { margin-bottom: 14px; }
.ne-panel .f-group label { display: block; font-size: 12px; color: var(--tm); margin-bottom: 5px; }
.ne-panel input, .ne-panel textarea, .ne-panel select {
    width: 100%; padding: 8px 10px;
    background: var(--bg4); border: 1px solid var(--bd);
    border-radius: 8px; color: var(--t); font-size: 13px;
    outline: none; font-family: inherit; resize: vertical;
    transition: border-color 0.2s; box-sizing: border-box;
}
.ne-panel input:focus, .ne-panel textarea:focus, .ne-panel select:focus { border-color: rgba(0,229,255,0.4); }
</style>

<script>
// ====== DANE Z PHP ======
var DB_NODES = <?= $nodes_json ?>;

// ====== STAŁE ======
var NODE_W = 220, NODE_H_BASE = 70, PORT_R = 7;
var COLORS = { continue:'#94a3b8', repair:'#00e5ff', no_repair:'#f87171', contact:'#8b5cf6' };
var LABELS = { continue:'Kontynuuj', repair:'Naprawa', no_repair:'Brak naprawy', contact:'Kontakt' };

// ====== STAN ======
var nodes = [];       // { id, x, y, data }
var edges = [];       // { from, to }  (parent_id -> child_id)
var selected = null;
var dragging = null;
var dragOffset = { x:0, y:0 };
var pan = { x:0, y:0 };
var zoom = 1;
var connecting = null; // { nodeId, startX, startY, curX, curY }
var canvas, ctx, wrap;

// ====== INICJALIZACJA ======
window.addEventListener('load', function() {
    canvas = document.getElementById('neCanvas');
    ctx    = canvas.getContext('2d');
    wrap   = document.getElementById('neCanvasWrap');

    resize();
    window.addEventListener('resize', resize);

    // Zbuduj węzły z danych PHP
    buildFromDB();
    fitView();
    render();

    // Eventy myszy
    canvas.addEventListener('mousedown', onMouseDown);
    canvas.addEventListener('mousemove', onMouseMove);
    canvas.addEventListener('mouseup',   onMouseUp);
    canvas.addEventListener('dblclick',  onDblClick);
    canvas.addEventListener('wheel',     onWheel, { passive: false });
    canvas.addEventListener('contextmenu', function(e) { e.preventDefault(); });
});

function resize() {
    canvas.width  = wrap.clientWidth;
    canvas.height = wrap.clientHeight;
    render();
}

function buildFromDB() {
    nodes = [];
    edges = [];
    var cols = 4, xGap = 260, yGap = 160, startX = 60, startY = 60;
    DB_NODES.forEach(function(d, i) {
        // Pozycja siatki jeśli nie ma zapisanej
        var col = i % cols, row = Math.floor(i / cols);
        nodes.push({
            id:   parseInt(d.id),
            x:    startX + col * xGap,
            y:    startY + row * yGap,
            data: d
        });
        if (d.parent_id) {
            edges.push({ from: parseInt(d.parent_id), to: parseInt(d.id) });
        }
    });
}

function nodeById(id) {
    return nodes.find(function(n) { return n.id === id; }) || null;
}

function nodeHeight(n) {
    var lines = Math.ceil((n.data.question||'').length / 28);
    return NODE_H_BASE + Math.max(0, lines-1)*14;
}

// ====== RENDER ======
function render() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Siatka tła
    ctx.save();
    ctx.strokeStyle = 'rgba(255,255,255,0.03)';
    ctx.lineWidth = 1;
    var gs = 40 * zoom;
    var ox = (pan.x % gs + gs) % gs, oy = (pan.y % gs + gs) % gs;
    for (var x = ox; x < canvas.width; x += gs) { ctx.beginPath(); ctx.moveTo(x,0); ctx.lineTo(x,canvas.height); ctx.stroke(); }
    for (var y = oy; y < canvas.height; y += gs) { ctx.beginPath(); ctx.moveTo(0,y); ctx.lineTo(canvas.width,y); ctx.stroke(); }
    ctx.restore();

    ctx.save();
    ctx.translate(pan.x, pan.y);
    ctx.scale(zoom, zoom);

    // Połączenia
    edges.forEach(function(e) {
        var from = nodeById(e.from), to = nodeById(e.to);
        if (!from || !to) return;
        var fx = from.x + NODE_W/2, fy = from.y + nodeHeight(from);
        var tx = to.x + NODE_W/2,   ty = to.y;
        drawEdge(fx, fy, tx, ty, COLORS[to.data.result_type] || '#94a3b8');
    });

    // Połączenie w trakcie rysowania
    if (connecting) {
        var sx = (connecting.startX - pan.x) / zoom;
        var sy = (connecting.startY - pan.y) / zoom;
        var ex = (connecting.curX - pan.x) / zoom;
        var ey = (connecting.curY - pan.y) / zoom;
        drawEdge(sx, sy, ex, ey, 'rgba(0,229,255,0.5)');
    }

    // Węzły
    nodes.forEach(function(n) {
        drawNode(n);
    });

    ctx.restore();
}

function drawEdge(x1,y1,x2,y2,color) {
    var cp1y = y1 + Math.abs(y2-y1)*0.5;
    var cp2y = y2 - Math.abs(y2-y1)*0.5;
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.bezierCurveTo(x1, cp1y, x2, cp2y, x2, y2);
    ctx.strokeStyle = color;
    ctx.lineWidth = 2;
    ctx.shadowColor = color;
    ctx.shadowBlur = 4;
    ctx.stroke();
    ctx.shadowBlur = 0;
    // Strzałka
    var angle = Math.atan2(y2 - cp2y, x2 - x1);
    ctx.save();
    ctx.translate(x2, y2);
    ctx.rotate(angle + Math.PI/2);
    ctx.beginPath();
    ctx.moveTo(0,-8); ctx.lineTo(-5,0); ctx.lineTo(5,0); ctx.closePath();
    ctx.fillStyle = color;
    ctx.fill();
    ctx.restore();
}

function drawNode(n) {
    var x = n.x, y = n.y, w = NODE_W, h = nodeHeight(n);
    var color = COLORS[n.data.result_type] || '#94a3b8';
    var isSel = selected === n.id;

    // Cień
    ctx.shadowColor = isSel ? color : 'rgba(0,0,0,0.4)';
    ctx.shadowBlur  = isSel ? 20 : 8;

    // Tło
    ctx.beginPath();
    roundRect(ctx, x, y, w, h, 10);
    ctx.fillStyle = isSel ? 'rgba(15,25,41,0.98)' : 'rgba(15,25,41,0.92)';
    ctx.fill();

    // Ramka
    ctx.strokeStyle = isSel ? color : 'rgba(255,255,255,0.1)';
    ctx.lineWidth = isSel ? 2 : 1;
    ctx.stroke();
    ctx.shadowBlur = 0;

    // Pasek góry z kolorem
    ctx.beginPath();
    ctx.rect(x+1, y+1, w-2, 4);
    ctx.fillStyle = color;
    ctx.fill();

    // ID
    ctx.fillStyle = 'rgba(255,255,255,0.3)';
    ctx.font = '10px Inter';
    ctx.textAlign = 'left';
    ctx.fillText('#' + n.id, x+8, y+20);

    // Badge typ
    ctx.fillStyle = color;
    ctx.font = 'bold 9px Inter';
    ctx.textAlign = 'right';
    ctx.fillText((LABELS[n.data.result_type]||'').toUpperCase(), x+w-8, y+20);

    // Odpowiedź
    if (n.data.answer) {
        ctx.fillStyle = '#00e5ff';
        ctx.font = 'bold 11px Inter';
        ctx.textAlign = 'left';
        ctx.fillText(truncate(n.data.answer, 28), x+8, y+36);
    }

    // Pytanie
    ctx.fillStyle = '#e2e8f4';
    ctx.font = '12px Inter';
    ctx.textAlign = 'left';
    var qy = n.data.answer ? y+52 : y+38;
    wrapText(ctx, n.data.question || '—', x+8, qy, w-16, 14);

    // Port wejściowy (góra)
    drawPort(x + w/2, y, color, false);
    // Port wyjściowy (dół)
    drawPort(x + w/2, y + h, color, true);
}

function drawPort(x, y, color, isOut) {
    ctx.beginPath();
    ctx.arc(x, y, PORT_R, 0, Math.PI*2);
    ctx.fillStyle = isOut ? color : 'rgba(255,255,255,0.15)';
    ctx.fill();
    ctx.strokeStyle = 'rgba(255,255,255,0.3)';
    ctx.lineWidth = 1.5;
    ctx.stroke();
}

function wrapText(ctx, text, x, y, maxW, lineH) {
    var words = text.split(' '), line = '';
    for (var i=0; i<words.length; i++) {
        var test = line + words[i] + ' ';
        if (ctx.measureText(test).width > maxW && i > 0) {
            ctx.fillText(line, x, y); line = words[i]+' '; y += lineH;
        } else { line = test; }
    }
    ctx.fillText(line, x, y);
}

function truncate(s, n) { return s.length > n ? s.slice(0,n)+'…' : s; }

function roundRect(ctx, x, y, w, h, r) {
    ctx.beginPath();
    ctx.moveTo(x+r, y);
    ctx.lineTo(x+w-r, y); ctx.quadraticCurveTo(x+w, y, x+w, y+r);
    ctx.lineTo(x+w, y+h-r); ctx.quadraticCurveTo(x+w, y+h, x+w-r, y+h);
    ctx.lineTo(x+r, y+h); ctx.quadraticCurveTo(x, y+h, x, y+h-r);
    ctx.lineTo(x, y+r); ctx.quadraticCurveTo(x, y, x+r, y);
    ctx.closePath();
}

// ====== INTERAKCJA ======
function worldPos(e) {
    var r = canvas.getBoundingClientRect();
    return {
        x: (e.clientX - r.left - pan.x) / zoom,
        y: (e.clientY - r.top  - pan.y) / zoom
    };
}

function screenPos(e) {
    var r = canvas.getBoundingClientRect();
    return { x: e.clientX - r.left, y: e.clientY - r.top };
}

function hitTest(wx, wy) {
    for (var i = nodes.length-1; i >= 0; i--) {
        var n = nodes[i];
        if (wx >= n.x && wx <= n.x+NODE_W && wy >= n.y && wy <= n.y+nodeHeight(n)) return n;
    }
    return null;
}

function isNearPort(wx, wy, n, isOut) {
    var px = n.x + NODE_W/2;
    var py = isOut ? n.y + nodeHeight(n) : n.y;
    return Math.hypot(wx-px, wy-py) < PORT_R + 4;
}

var panDragging = false, panStart = { x:0, y:0 };

function onMouseDown(e) {
    var wp = worldPos(e), sp = screenPos(e);
    var hit = hitTest(wp.x, wp.y);

    if (e.button === 1 || (e.button === 0 && !hit)) {
        // Pan
        panDragging = true;
        panStart = { x: sp.x - pan.x, y: sp.y - pan.y };
        return;
    }

    if (hit) {
        // Sprawdź czy klik w port wyjściowy
        if (isNearPort(wp.x, wp.y, hit, true)) {
            connecting = { nodeId: hit.id, startX: sp.x, startY: sp.y, curX: sp.x, curY: sp.y };
            return;
        }
        // Zaznacz i zacznij drag
        selected = hit.id;
        dragging = hit;
        dragOffset = { x: wp.x - hit.x, y: wp.y - hit.y };
        document.getElementById('btnDelete').style.display = 'flex';
        render();
    } else {
        selected = null;
        document.getElementById('btnDelete').style.display = 'none';
        render();
    }
}

function onMouseMove(e) {
    var wp = worldPos(e), sp = screenPos(e);

    if (panDragging) {
        pan.x = sp.x - panStart.x;
        pan.y = sp.y - panStart.y;
        render();
        return;
    }

    if (connecting) {
        connecting.curX = sp.x;
        connecting.curY = sp.y;
        render();
        return;
    }

    if (dragging) {
        dragging.x = wp.x - dragOffset.x;
        dragging.y = wp.y - dragOffset.y;
        render();
    }
}

function onMouseUp(e) {
    var wp = worldPos(e);

    if (panDragging) { panDragging = false; return; }

    if (connecting) {
        var hit = hitTest(wp.x, wp.y);
        if (hit && hit.id !== connecting.nodeId && isNearPort(wp.x, wp.y, hit, false)) {
            // Połącz węzły
            saveConnection(connecting.nodeId, hit.id);
        }
        connecting = null;
        render();
        return;
    }

    dragging = null;
}

function onDblClick(e) {
    var wp = worldPos(e);
    var hit = hitTest(wp.x, wp.y);
    if (hit) openPanel(hit);
}

function onWheel(e) {
    e.preventDefault();
    var sp = screenPos(e);
    var delta = e.deltaY > 0 ? 0.9 : 1.1;
    zoom = Math.min(Math.max(zoom * delta, 0.2), 3);
    pan.x = sp.x - (sp.x - pan.x) * delta;
    pan.y = sp.y - (sp.y - pan.y) * delta;
    render();
}

// ====== PANEL EDYCJI ======
var editingNode = null;

function openPanel(n) {
    editingNode = n;
    document.getElementById('nePanelTitle').textContent = 'Węzeł #' + n.id;
    document.getElementById('neAnswer').value     = n.data.answer || '';
    document.getElementById('neQuestion').value   = n.data.question || '';
    document.getElementById('neResult').value     = n.data.result || '';
    document.getElementById('neResultType').value = n.data.result_type || 'continue';
    document.getElementById('nePanel').style.display = 'flex';
}

function closePanel() {
    document.getElementById('nePanel').style.display = 'none';
    editingNode = null;
}

function saveNode() {
    if (!editingNode) return;
    var data = {
        answer:      document.getElementById('neAnswer').value.trim(),
        question:    document.getElementById('neQuestion').value.trim(),
        result:      document.getElementById('neResult').value.trim(),
        result_type: document.getElementById('neResultType').value,
    };
    if (!data.question) { alert('Pytanie jest wymagane'); return; }

    setStatus('Zapisywanie...');
    fetch('/admin/diagnostyka/edytuj/' + editingNode.id, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    }).then(function(r) {
        if (r.ok || r.redirected) {
            // Aktualizuj lokalnie
            Object.assign(editingNode.data, data);
            closePanel();
            render();
            setStatus('Zapisano ✓');
            setTimeout(function() { setStatus(''); }, 2000);
        }
    }).catch(function() { setStatus('Błąd zapisu'); });
}

// ====== NOWY WĘZEŁ ======
window.addNode = function() {
    var cx = (canvas.width/2  - pan.x) / zoom;
    var cy = (canvas.height/2 - pan.y) / zoom;

    setStatus('Tworzenie...');
    fetch('/admin/diagnostyka/dodaj', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ question: 'Nowy węzeł', result_type: 'continue', sort_order: 0 })
    }).then(function(r) { return r.text(); })
    .then(function() {
        // Pobierz odświeżone węzły
        return fetch(location.href);
    }).then(function(r) { return r.text(); })
    .then(function(html) {
        // Wyciągnij nowe id z odpowiedzi - reload strony
        location.reload();
    });
};

// ====== USUŃ WĘZEŁ ======
window.deleteSelected = function() {
    if (!selected) return;
    if (!confirm('Usunąć węzeł #' + selected + ' i wszystkie jego dzieci?')) return;
    setStatus('Usuwanie...');
    fetch('/admin/diagnostyka/usun/' + selected, { method: 'POST' })
    .then(function() { location.reload(); });
};

// ====== POŁĄCZENIE ======
function saveConnection(parentId, childId) {
    // Sprawdź czy już nie istnieje
    var exists = edges.some(function(e) { return e.to === childId; });
    if (exists) {
        // Aktualizuj istniejące połączenie
        edges = edges.filter(function(e) { return e.to !== childId; });
    }
    edges.push({ from: parentId, to: childId });
    render();

    // Zapisz w bazie - zmień parent_id dziecka
    setStatus('Łączenie...');
    var childNode = nodeById(childId);
    if (!childNode) return;
    fetch('/admin/diagnostyka/edytuj/' + childId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            parent_id:   parentId,
            question:    childNode.data.question || '',
            answer:      childNode.data.answer || '',
            result:      childNode.data.result || '',
            result_type: childNode.data.result_type || 'continue',
            sort_order:  childNode.data.sort_order || 0
        })
    }).then(function() {
        childNode.data.parent_id = parentId;
        setStatus('Połączono ✓');
        setTimeout(function() { setStatus(''); }, 2000);
    });
}

// ====== NARZĘDZIA ======
window.fitView = function() {
    if (!nodes.length) return;
    var minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
    nodes.forEach(function(n) {
        minX = Math.min(minX, n.x); minY = Math.min(minY, n.y);
        maxX = Math.max(maxX, n.x+NODE_W); maxY = Math.max(maxY, n.y+nodeHeight(n));
    });
    var pw = canvas.width - 80, ph = canvas.height - 80;
    zoom = Math.min(pw/(maxX-minX), ph/(maxY-minY), 1.5);
    pan.x = (canvas.width  - (maxX+minX)*zoom) / 2;
    pan.y = (canvas.height - (maxY+minY)*zoom) / 2;
    render();
};

function setStatus(msg) {
    document.getElementById('neStatus').textContent = msg;
}
</script>
