window.apsiInitSite = function () {
    const signal = window.apsiPageSignal;
    const header = document.getElementById('site-header');
    const toggle = header?.querySelector('.site-header__toggle');

    // Keep the lower diagonal fixed; turn upward before the contact button.
    const homeFrame = document.querySelector('.home-scene .shared-hero-frame svg');
    function syncHomeFrame() {
        if (!homeFrame || window.innerWidth <= 980) return;
        const scene = homeFrame.closest('.home-scene').getBoundingClientRect();
        const lastLink = header?.querySelector('.site-header__links a:last-child');
        const contactLink = header?.querySelector('.site-header__nav-cta');
        if (!lastLink || !contactLink) return;
        const link = lastLink.getBoundingClientRect();
        const width = scene.width;
        const height = scene.height;
        const lowerSlope = Math.tan(25 * Math.PI / 180);
        const preferredUpperSlope = Math.tan(32 * Math.PI / 180);
        let upperSlope = preferredUpperSlope;
        const bottomX = width / 2 - 180;
        const textRange = document.createRange();
        textRange.selectNodeContents(lastLink);
        const clearY = textRange.getBoundingClientRect().bottom + 6;
        const clearX = link.right - scene.left + 8;
        const upperEdgeEnd = contactLink.getBoundingClientRect().left - scene.left - 12;
        // Match the interior pages' 16px horizontal blue offset on the main edge.
        const lowerOffset = 16;
        const borderWidth = lowerOffset / Math.sqrt(1 + lowerSlope * lowerSlope);
        let topX;
        for (let step = 0; step < 6; step++) {
            const offset = borderWidth * Math.sqrt(1 + 1 / (upperSlope * upperSlope));
            topX = upperEdgeEnd - offset;
            upperSlope = Math.max(preferredUpperSlope, clearY / Math.max(1, topX - clearX));
        }
        const upperOffset = borderWidth * Math.sqrt(1 + 1 / (upperSlope * upperSlope));
        topX = upperEdgeEnd - upperOffset;
        const bendY = Math.max(header.offsetHeight + 32,
            (topX - bottomX - height * lowerSlope) / (1 / upperSlope - lowerSlope) - 110);
        const bendX = bottomX + (height - bendY) * lowerSlope;
        upperSlope = Math.min(upperSlope, (bendY - clearY) / Math.max(1, clearX - bendX));
        topX = bendX + bendY / upperSlope;
        const adjustedUpperOffset = borderWidth * Math.sqrt(1 + 1 / (upperSlope * upperSlope));
        const borderY = bendY + (lowerOffset - adjustedUpperOffset) / (lowerSlope - 1 / upperSlope);
        const borderX = bottomX + lowerOffset + (height - borderY) * lowerSlope;
        homeFrame.setAttribute('viewBox', `0 0 ${width} ${height}`);
        const [border, panel] = homeFrame.querySelectorAll('path');
        // Draw past the clipped scene to avoid a horizontal antialiasing seam.
        const frameBleed = 4;
        const frameBottom = height + frameBleed;
        const frameBottomX = bottomX - frameBleed * lowerSlope;
        panel.setAttribute('d',
            `M0 0H${topX}L${bendX} ${bendY}L${frameBottomX} ${frameBottom}H0Z`);
        border.setAttribute('d',
            `M${topX} 0H${topX + adjustedUpperOffset}L${borderX} ${borderY}L${frameBottomX + lowerOffset} ${frameBottom}H${frameBottomX}L${bendX} ${bendY}Z`);
    }
    syncHomeFrame();
    window.addEventListener('resize', syncHomeFrame, { signal });
    window.addEventListener('load', syncHomeFrame, { signal });
    document.fonts?.ready?.then(syncHomeFrame);

    if (header) {
        const syncHeaderScroll = () => {
            header.classList.toggle('is-scrolled', window.scrollY > 12);
        };
        syncHeaderScroll();
        window.addEventListener('scroll', syncHeaderScroll, { passive: true, signal });
        window.addEventListener('pageshow', syncHeaderScroll, { signal });
    }

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
    window.addEventListener('resize', requestFadeSync, { signal });
    window.addEventListener('load', syncCraneFade, { signal });
    document.fonts?.ready?.then(syncCraneFade);
    window.visualViewport?.addEventListener('resize', requestFadeSync, { signal });

    const fadeAnchor = document.querySelector('.home-shell .home-hero__copy, .about-page-shell .about-hero-copy, .site-shell-bg .page-title, .site-shell-bg .page-subtitle');
    if (fadeAnchor && 'ResizeObserver' in window) {
        const fadeObserver = new ResizeObserver(requestFadeSync);
        signal?.addEventListener('abort', () => fadeObserver.disconnect(), { once: true });
        fadeObserver.observe(fadeAnchor);
        fadeAnchor.querySelectorAll?.('h1, p').forEach((element) => fadeObserver.observe(element));
    }

    if (header && toggle) {
        toggle.addEventListener('click', function () {
            const isOpen = header.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            document.body.classList.toggle('nav-open', isOpen);
        }, { signal });
    }

    // Progressive Scroll Reveal Logic (IntersectionObserver)
    const revealSelector = 'section, .home-service-cards article, .about-card, .about-badges article, .mission-item, .about-experience li, .about-founders-row > div, .profession-card, .reference-tile, .clients-grid article';
    const revealElements = document.querySelectorAll(revealSelector);
    
    // Add class to non-hero elements to prepare them for reveal
    revealElements.forEach((element) => {
        // Skip elements that are above the fold or in a hero so they load instantly
        if (element.closest('.feature-hero, .home-hero, .about-hero, .professions-hero, .references-hero, .clients-hero, .contact-hero') || element.matches('.feature-hero, .home-hero, .about-hero, .professions-hero, .references-hero, .clients-hero, .contact-hero')) {
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

        signal?.addEventListener('abort', () => revealObserver.disconnect(), { once: true });
        revealElements.forEach((el) => {
            if (!el.classList.contains('is-visible')) {
                revealObserver.observe(el);
            }
        });
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        revealElements.forEach(el => el.classList.add('is-visible'));
    }
};

document.addEventListener('DOMContentLoaded', () => window.apsiInitSite());
