(function() {
    var overlay, img, scale = 1, px = 0, py = 0, rot = 0;
    var drag = false, sx, sy, spx, spy;

    function build() {
        overlay = document.createElement('div');
        overlay.style.cssText = 'display:none;position:fixed;inset:0;background:rgba(0,0,0,0.93);z-index:999999;flex-direction:column;align-items:center;justify-content:center;';

        img = document.createElement('img');
        img.style.cssText = 'max-width:90vw;max-height:85vh;object-fit:contain;border-radius:6px;cursor:grab;user-select:none;transform-origin:center;transition:transform 0.08s;';

        var controls = document.createElement('div');
        controls.style.cssText = 'position:fixed;top:12px;right:12px;display:flex;gap:8px;z-index:999999;';
        controls.innerHTML =
            '<button id="lbZI" style="'+bs()+'">＋</button>' +
            '<button id="lbZO" style="'+bs()+'">－</button>' +
            '<button id="lbRE" style="'+bs()+'">↺</button>' +
            '<button id="lbRO" style="'+bs()+'">⟳</button>' +
            '<button id="lbCL" style="'+bs()+'background:rgba(200,0,0,0.5);">✕</button>';

        overlay.appendChild(img);
        overlay.appendChild(controls);
        document.body.appendChild(overlay);

        overlay.addEventListener('click', function(e) { if (e.target === overlay) close(); });
        document.getElementById('lbCL').addEventListener('click', close);
        document.getElementById('lbZI').addEventListener('click', function() { doZoom(1.3); });
        document.getElementById('lbZO').addEventListener('click', function() { doZoom(0.75); });
        document.getElementById('lbRE').addEventListener('click', reset);
        document.getElementById('lbRO').addEventListener('click', function() { rot = (rot + 90) % 360; apply(); });

        img.addEventListener('wheel', function(e) { e.preventDefault(); doZoom(e.deltaY < 0 ? 1.15 : 0.87); }, {passive:false});
        img.addEventListener('mousedown', function(e) { drag=true; sx=e.clientX-px; sy=e.clientY-py; img.style.cursor='grabbing'; e.preventDefault(); });
        window.addEventListener('mousemove', function(e) { if(!drag) return; px=e.clientX-sx; py=e.clientY-sy; apply(); });
        window.addEventListener('mouseup', function() { drag=false; if(img) img.style.cursor='grab'; });

        // Touch pinch
        var td=0;
        img.addEventListener('touchstart', function(e) {
            if(e.touches.length===2) td=Math.hypot(e.touches[0].clientX-e.touches[1].clientX,e.touches[0].clientY-e.touches[1].clientY);
            else if(e.touches.length===1){drag=true;sx=e.touches[0].clientX-px;sy=e.touches[0].clientY-py;}
        },{passive:true});
        img.addEventListener('touchmove', function(e) {
            e.preventDefault();
            if(e.touches.length===2){
                var d=Math.hypot(e.touches[0].clientX-e.touches[1].clientX,e.touches[0].clientY-e.touches[1].clientY);
                if(td>0) doZoom(d/td); td=d;
            } else if(e.touches.length===1&&drag){px=e.touches[0].clientX-sx;py=e.touches[0].clientY-sy;apply();}
        },{passive:false});
        img.addEventListener('touchend', function(){drag=false;td=0;});

        document.addEventListener('keydown', function(e){ if(e.key==='Escape') close(); });
    }

    function bs() { return 'width:38px;height:38px;border-radius:8px;background:rgba(20,30,50,0.9);border:1px solid rgba(255,255,255,0.15);color:#fff;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;'; }

    function open(src) {
        if (!overlay) build();
        scale=1; px=0; py=0;
        img.src = src;
        img.style.transform = 'translate(0,0) scale(1)';
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function close() {
        if (!overlay) return;
        overlay.style.display = 'none';
        document.body.style.overflow = '';
        img.src = '';
        scale=1; px=0; py=0; rot=0;
    }

    function doZoom(f) { scale=Math.min(Math.max(scale*f,0.3),10); apply(); }
    function reset() { scale=1; px=0; py=0; rot=0; apply(); }
    function apply() { img.style.transform='translate('+px+'px,'+py+'px) scale('+scale+') rotate('+rot+'deg)'; }

    document.addEventListener('click', function(e) {
        var a = e.target.closest('a');
        if (!a) return;
        var href = a.getAttribute('href') || '';
        if (/\.(jpg|jpeg|png|webp|gif)(\?|$)/i.test(href)) {
            e.preventDefault();
            open(href);
        }
    });
})();
