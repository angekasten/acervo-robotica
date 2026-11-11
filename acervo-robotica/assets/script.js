/* Linha do tempo: pop-up ao passar o mouse, focar (teclado) ou tocar. */
(() => {
  'use strict';
  const dadosEl = document.getElementById('acervo-dados');
  const popup = document.getElementById('acervo-popup');
  if (!dadosEl || !popup) return;

  let dados = [];
  try { dados = JSON.parse(dadosEl.textContent); } catch (_) { return; }
  const porAno = new Map(dados.map(d => [String(d.ano), d]));
  const botoes = document.querySelectorAll('.acervo-no__botao');
  const celular = window.matchMedia('(max-width: 720px)');
  let atual = null, fixado = false, timer = null;

  // Todo texto entra com textContent (nunca innerHTML).
  const el = (tag, cls, txt) => {
    const n = document.createElement(tag);
    if (cls) n.className = cls;
    if (txt !== undefined) n.textContent = txt;
    return n;
  };

  function montar(d) {
    popup.replaceChildren();
    const fechar = el('button', 'acervo-popup__fechar', '×');
    fechar.type = 'button';
    fechar.setAttribute('aria-label', 'Fechar');
    fechar.addEventListener('click', () => { const b = atual; fecharPopup(); if (b) b.focus(); });

    const nums = el('ul', 'acervo-popup__numeros');
    [['escolas', d.escolas], ['equipes', d.equipes], ['estudantes', d.estudantes]].forEach(([rot, v]) => {
      const li = el('li');
      li.append(el('strong', null, String(v)), ' ' + rot);
      nums.append(li);
    });

    const dest = el('ul', 'acervo-popup__destaques');
    (d.destaques || []).forEach(t => dest.append(el('li', null, t)));
    if (d.mais > 0) dest.append(el('li', null, `e mais ${d.mais} categorias`));

    const link = el('a', 'acervo-popup__link', 'Ver edição completa →');
    link.href = d.url;

    popup.append(fechar, el('h3', 'acervo-popup__titulo', `${d.edicao} — ${d.ano}`),
      el('p', 'acervo-popup__resumo', d.resumo), nums, dest, link);
  }

  function posicionar(btn) {
    if (celular.matches) { popup.style.left = ''; popup.style.top = ''; return; }
    const r = btn.getBoundingClientRect();
    const p = popup.getBoundingClientRect();
    const left = Math.max(8, Math.min(r.left + r.width / 2 - p.width / 2, window.innerWidth - p.width - 8));
    let top = r.top - p.height - 12;
    if (top < 8) top = r.bottom + 12;
    popup.style.left = left + 'px';
    popup.style.top = top + 'px';
  }

  function abrir(btn) {
    clearTimeout(timer);
    const d = porAno.get(btn.dataset.ano);
    if (!d) return;
    if (atual && atual !== btn) atual.setAttribute('aria-expanded', 'false');
    if (atual !== btn) montar(d);
    atual = btn;
    btn.setAttribute('aria-expanded', 'true');
    popup.hidden = false;
    posicionar(btn);
  }

  function fecharPopup() {
    clearTimeout(timer);
    if (atual) atual.setAttribute('aria-expanded', 'false');
    atual = null; fixado = false; popup.hidden = true;
  }

  function agendarFechar() {
    if (fixado) return;
    clearTimeout(timer);
    timer = setTimeout(fecharPopup, 200);
  }

  botoes.forEach(btn => {
    btn.addEventListener('mouseenter', () => { if (!fixado && !celular.matches) abrir(btn); });
    btn.addEventListener('mouseleave', agendarFechar);
    btn.addEventListener('focus', () => { if (!fixado) abrir(btn); });
    btn.addEventListener('blur', agendarFechar);
    btn.addEventListener('click', e => {
      e.stopPropagation();
      if (atual === btn && fixado) { fecharPopup(); return; }
      abrir(btn);
      fixado = true;
      // Teclado (Enter/Espaço): leva o foco ao link do pop-up.
      if (e.detail === 0) { const a = popup.querySelector('a'); if (a) a.focus(); }
    });
  });

  popup.addEventListener('mouseenter', () => clearTimeout(timer));
  popup.addEventListener('mouseleave', agendarFechar);
  popup.addEventListener('click', e => e.stopPropagation());

  document.addEventListener('click', fecharPopup);
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && atual) { const b = atual; fecharPopup(); b.focus(); }
  });
  document.addEventListener('scroll', () => { if (atual) posicionar(atual); }, true);
  window.addEventListener('resize', () => { if (atual) posicionar(atual); });
})();
