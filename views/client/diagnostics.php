<section class="panel-section">
<div class="container" style="max-width:800px">
    <div class="panel-header">
        <div>
            <h1>Diagnoza AI</h1>
            <p>Opisz problem ze swoim sprzętem akwarystycznym — asystent pomoże wstępnie zidentyfikować usterkę.</p>
        </div>
        <a href="/panel" class="btn btn--ghost">← Panel</a>
    </div>

    <div class="diag-chat" id="diagChat">
        <div class="diag-messages" id="diagMessages">
            <div class="diag-msg diag-msg--bot">
                <div class="diag-msg__avatar">AI</div>
                <div class="diag-msg__bubble">
                    Cześć! Jestem asystentem diagnostycznym NovaFix. Specjalizuję się w sprzęcie akwarystycznym — lampach LED, falownikach, sterownikach i pompach.<br><br>
                    Opisz mi co się dzieje z Twoim urządzeniem, a postaram się pomóc wstępnie zidentyfikować problem. Możesz też od razu
                    <a href="/panel/nowe-zgloszenie" style="color:var(--c)">złożyć zlecenie naprawy</a>.
                </div>
            </div>
        </div>
        <div class="diag-input-wrap">
            <textarea id="diagInput" placeholder="Np. Lampa AI Hydra przestała działać, migają tylko dwie diody..." rows="2"></textarea>
            <button id="diagSend" class="btn btn--primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </button>
        </div>
        <p class="diag-disclaimer">Diagnoza AI ma charakter poglądowy. Ostateczną diagnozę wykonujemy po otrzymaniu sprzętu.</p>
    </div>

    <div class="panel-card" style="margin-top:24px">
        <h3>Gotowy do wysłania sprzętu?</h3>
        <p style="color:var(--tm);font-size:14px;margin-bottom:16px">Złóż zlecenie online — opisz problem, wyślij zdjęcia, a my zajmiemy się resztą.</p>
        <a href="/panel/nowe-zgloszenie" class="btn btn--primary">Zgłoś urządzenie do naprawy →</a>
    </div>
</div>
</section>

<script>
const messages = [];
const diagMessages = document.getElementById('diagMessages');
const diagInput   = document.getElementById('diagInput');
const diagSend    = document.getElementById('diagSend');

const SYSTEM = `Jesteś asystentem diagnostycznym firmy NovaFix, która naprawia sprzęt elektroniczny do akwariów morskich i słodkowodnych. 
Specjalizujesz się w: lampach LED (AI, Kessil, Hydra, Maxspect), falownikach (Ecotech, Tunze, Jebao), sterownikach (Neptune Apex, GHL, CoralBox), pompach, skimmerach, chiller'ach.

Twoja rola:
- Zadawaj pytania diagnostyczne żeby zidentyfikować usterkę
- Sugeruj możliwe przyczyny problemu
- Podpowiedz co klient może sprawdzić samodzielnie (bezpieczne czynności)
- Jeśli problem wymaga naprawy - zachęć do złożenia zlecenia w NovaFix
- Odpowiadaj po polsku, zwięźle i konkretnie
- Nie sugeruj napraw wymagających lutowania lub otwierania urządzenia - to zostawia specjalistom

Zawsze pytaj o: markę i model urządzenia, opis objawów, od kiedy problem występuje, co było robione przed awarią.`;

async function sendMessage() {
    const text = diagInput.value.trim();
    if (!text) return;

    diagInput.value = '';
    diagSend.disabled = true;

    // Dodaj wiadomość użytkownika
    addMsg('user', text);
    messages.push({ role: 'user', content: text });

    // Wskaźnik ładowania
    const loadingEl = addMsg('bot', '...');

    try {
        const res = await fetch('https://api.anthropic.com/v1/messages', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                model: 'claude-sonnet-4-20250514',
                max_tokens: 1000,
                system: SYSTEM,
                messages: messages
            })
        });

        const data = await res.json();
        const reply = data.content?.[0]?.text || 'Przepraszam, wystąpił błąd. Spróbuj ponownie.';

        loadingEl.querySelector('.diag-msg__bubble').innerHTML = reply.replace(/\n/g, '<br>');
        messages.push({ role: 'assistant', content: reply });

    } catch (err) {
        loadingEl.querySelector('.diag-msg__bubble').textContent = 'Błąd połączenia. Spróbuj ponownie.';
    }

    diagSend.disabled = false;
    diagInput.focus();
}

function addMsg(type, text) {
    const el = document.createElement('div');
    el.className = `diag-msg diag-msg--${type === 'user' ? 'user' : 'bot'}`;
    el.innerHTML = type === 'user'
        ? `<div class="diag-msg__bubble">${escHtml(text)}</div><div class="diag-msg__avatar">Ty</div>`
        : `<div class="diag-msg__avatar">AI</div><div class="diag-msg__bubble">${escHtml(text)}</div>`;
    diagMessages.appendChild(el);
    diagMessages.scrollTop = diagMessages.scrollHeight;
    return el;
}

function escHtml(s) {
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

diagSend.addEventListener('click', sendMessage);
diagInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
});
</script>
