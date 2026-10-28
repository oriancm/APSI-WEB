window.apsiPageReady(() => {
    const signal = window.apsiPageSignal;
    window.lucide?.createIcons();
    const carousel = document.querySelector('.references-carousel');
    if (carousel) {
        const slides = [...carousel.querySelectorAll('.references-carousel__slide')];
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        const compactScreen = window.matchMedia('(max-width: 980px)');
        let current = 0;
        let timer;
        const start = () => {
            window.clearInterval(timer);
            if (slides.length < 2 || document.hidden || reducedMotion.matches || compactScreen.matches ||
                carousel.contains(document.activeElement)) return;
            timer = window.setInterval(() => {
                current = (current + 1) % slides.length;
                slides.forEach((slide, index) => { slide.hidden = index !== current; });
            }, 4000);
        };
        // Keep a focused project link stable and suspend unused background work.
        carousel.addEventListener('focusin', () => window.clearInterval(timer));
        carousel.addEventListener('focusout', () => window.setTimeout(() => { if (!signal.aborted) start(); }, 0));
        document.addEventListener('visibilitychange', start, { signal });
        signal.addEventListener('abort', () => window.clearInterval(timer), { once: true });
        reducedMotion.addEventListener('change', start, { signal });
        compactScreen.addEventListener('change', start, { signal });
        start();
    }

    const buttons = [...document.querySelectorAll('.references-filter button[data-filter]')];
    const cards = [...document.querySelectorAll('.reference-tile')];
    const panel = document.getElementById('references-filter');
    const toggle = document.querySelector('.filter-toggle');
    const empty = document.querySelector('.references-empty');
    const resultDomain = document.querySelector('.references-results__domain');
    const resultCount = document.querySelector('.references-results__count');
    const close = () => { panel?.classList.remove('is-open'); toggle?.setAttribute('aria-expanded', 'false'); };
    buttons.forEach(button => button.addEventListener('click', () => {
        buttons.forEach(item => {
            const active = item === button;
            item.classList.toggle('active', active);
            item.setAttribute('aria-pressed', String(active));
        });
        cards.forEach(card => {
            card.hidden = button.dataset.filter !== 'all' && card.dataset.domain !== button.dataset.filter;
        });
        const count = cards.filter(card => !card.hidden).length;
        if (empty) empty.hidden = count > 0;
        if (resultDomain) resultDomain.textContent = button.textContent.trim();
        if (resultCount) resultCount.textContent = `${count} résultat${count === 1 ? '' : 's'}`;
        close();
    }));
    toggle?.addEventListener('click', () => {
        const open = panel.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', String(open));
    });
    document.addEventListener('click', event => {
        if (!panel?.contains(event.target) && !toggle?.contains(event.target)) close();
    }, { signal });
    document.addEventListener('keydown', event => { if (event.key === 'Escape') { close(); } }, { signal });
});
