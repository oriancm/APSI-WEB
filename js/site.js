document.addEventListener('DOMContentLoaded', function () {
    const header = document.getElementById('site-header');
    const toggle = header?.querySelector('.site-header__toggle');

    function clamp(value, min, max) {
        return Math.min(Math.max(value, min), max);
    }

    function getTextRightEdge(element) {
        const range = document.createRange();
        let right = 0;

        element.childNodes.forEach((node) => {
            if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
                range.selectNodeContents(node);
                Array.from(range.getClientRects()).forEach((rect) => {
                    right = Math.max(right, rect.right);
                });
            } else if (node.nodeType === Node.ELEMENT_NODE && node.tagName !== 'BR') {
                right = Math.max(right, getTextRightEdge(node));
            }
        });

        range.detach();

        if (!right) {
            const rect = element.getBoundingClientRect();
            right = rect.width > 0 && rect.height > 0 ? rect.right : 0;
        }

        return right;
    }

    function syncCraneFade() {
        const shell = document.querySelector('.home-shell, .about-page-shell, .site-shell-bg');
        if (!shell) return;

        const anchor = shell.querySelector('.home-hero__copy, .about-hero-copy, .page-subtitle, .page-title');
        if (!anchor) return;

        const measured = anchor.matches('.home-hero__copy, .about-hero-copy')
            ? anchor.querySelectorAll('h1, p')
            : [anchor];

        let right = 0;
        measured.forEach((element) => {
            right = Math.max(right, getTextRightEdge(element));
        });

        const viewport = window.innerWidth || document.documentElement.clientWidth;
        if (!right) return;

        const style = window.getComputedStyle(shell);
        const isHome = shell.classList.contains('home-shell');
        const sceneSide = parseFloat(style.getPropertyValue('--apsi-scene-side')) || 0;
        const craneHeight = parseFloat(style.getPropertyValue('--apsi-crane-height')) || 0;
        const craneRatio = parseFloat(style.getPropertyValue('--apsi-crane-ratio')) || 2.9986;
        const craneLeft = viewport - sceneSide - (craneHeight * craneRatio);

        let start = clamp(right - clamp(viewport * 0.045, 46, 96), 0, viewport);
        let mid = start + clamp(viewport * 0.055, 58, 116);
        let end = start + clamp(viewport * 0.16, 118, 260);

        if (craneLeft > 0) {
            const edgeLead = clamp(viewport * 0.055, 68, 140);
            const edgeCore = clamp(viewport * 0.04, 48, 100);
            const edgeTail = clamp(viewport * 0.1, 110, 210);

            start = Math.min(start, craneLeft - edgeLead);
            mid = Math.max(mid, craneLeft + edgeCore);
            end = Math.max(end, craneLeft + edgeTail);
        }

        if (isHome) {
            const maxHomeFade = clamp(viewport * 0.2, 140, 310);
            const minHomeFade = clamp(viewport * 0.1, 96, 180);
            end = clamp(end, start + minHomeFade, start + maxHomeFade);
            mid = clamp(start + ((end - start) * 0.38), start, end);
        }

        start = clamp(start, 0, viewport);
        mid = clamp(mid, start, viewport + 360);
        end = clamp(end, mid, viewport + 560);

        shell.style.setProperty('--apsi-bg-fade-start', `${start}px`);
        shell.style.setProperty('--apsi-bg-fade-mid', `${mid}px`);
        shell.style.setProperty('--apsi-bg-fade-end', `${end}px`);
    }

    let resizeFrame = 0;
    function requestFadeSync() {
        window.cancelAnimationFrame(resizeFrame);
        resizeFrame = window.requestAnimationFrame(syncCraneFade);
    }

    syncCraneFade();
    window.addEventListener('resize', requestFadeSync);
    window.addEventListener('load', syncCraneFade);
    document.fonts?.ready?.then(syncCraneFade);
    window.visualViewport?.addEventListener('resize', requestFadeSync);

    const fadeAnchor = document.querySelector('.home-shell .home-hero__copy, .about-page-shell .about-hero-copy, .site-shell-bg .page-title, .site-shell-bg .page-subtitle');
    if (fadeAnchor && 'ResizeObserver' in window) {
        const fadeObserver = new ResizeObserver(requestFadeSync);
        fadeObserver.observe(fadeAnchor);
        fadeAnchor.querySelectorAll?.('h1, p').forEach((element) => fadeObserver.observe(element));
    }

    if (header && toggle) {
        toggle.addEventListener('click', function () {
            const isOpen = header.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            document.body.classList.toggle('nav-open', isOpen);
        });
    }

    // Progressive Scroll Reveal Logic (IntersectionObserver)
    const revealSelector = 'section, .home-service-cards article, .about-card, .about-badges article, .mission-item, .about-experience li, .about-founders-row > div, .profession-card, .reference-tile, .clients-grid article';
    const revealElements = document.querySelectorAll(revealSelector);
    
    // Add class to non-hero elements to prepare them for reveal
    revealElements.forEach((element) => {
        // Skip elements that are above the fold or in a hero so they load instantly
        if (element.closest('.home-hero, .about-hero, .professions-hero, .references-hero, .clients-hero, .contact-hero') || element.matches('.home-hero, .about-hero, .professions-hero, .references-hero, .clients-hero, .contact-hero')) {
            element.classList.add('scroll-reveal', 'is-visible');
            return;
        }
        element.classList.add('scroll-reveal');
    });

    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            // Filter elements that are intersecting
            const intersecting = entries.filter(entry => entry.isIntersecting);
            
            intersecting.forEach((entry, index) => {
                const el = entry.target;
                // If multiple elements intersect at once (e.g. grids, lists), stagger them beautifully
                if (intersecting.length > 1) {
                    el.style.transitionDelay = `${index * 80}ms`;
                } else {
                    el.style.transitionDelay = '0ms';
                }
                el.classList.add('is-visible');
                observer.unobserve(el);
            });
        }, {
            threshold: 0.05,
            rootMargin: '0px 0px -20px 0px'
        });

        revealElements.forEach((el) => {
            if (!el.classList.contains('is-visible')) {
                revealObserver.observe(el);
            }
        });
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        revealElements.forEach(el => el.classList.add('is-visible'));
    }
});
