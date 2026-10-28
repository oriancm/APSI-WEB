(() => {
    let pageController = new AbortController();
    let pendingRequest;
    let navigationId = 0;
    window.apsiPageSignal = pageController.signal;
    window.apsiPageReady = (initialize) => {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initialize, { once: true, signal: window.apsiPageSignal });
        } else {
            initialize();
        }
    };

    const isPublicPage = (url) => url.origin === location.origin &&
        /^\/(?:index(?:\.php)?|aboutUs|professions|references|clients|contact|legalNotices|privacyPolicy|reference(?:\/\d+)?)?\/?$/.test(url.pathname);

    async function navigate(url, { historyEntry = true, scroll = [0, 0] } = {}) {
        const id = ++navigationId;
        pendingRequest?.abort();
        pendingRequest = new AbortController();
        document.documentElement.setAttribute('aria-busy', 'true');
        try {
            const response = await fetch(url.href, { signal: pendingRequest.signal });
            if (!response.ok || !response.headers.get('content-type')?.includes('text/html')) throw new Error('Invalid page');
            const next = new DOMParser().parseFromString(await response.text(), 'text/html');
            const nextHeader = next.getElementById('site-header');
            const header = document.getElementById('site-header');
            if (!header || !nextHeader || !next.querySelector('main')) throw new Error('Missing page layout');

            // Prepare page behavior before changing the visible document.
            const scripts = await Promise.all([...next.body.querySelectorAll('script')]
                .filter(script => !script.type || script.type === 'text/javascript')
                .map(async script => {
                    if (!script.src) return script.textContent;
                    const source = new URL(script.getAttribute('src'), response.url);
                    if (source.origin !== location.origin || source.pathname !== '/js/references.js') throw new Error('Unsupported page script');
                    const result = await fetch(source, { signal: pendingRequest.signal });
                    if (!result.ok) throw new Error('Missing page script');
                    return result.text();
                }));
            if (id !== navigationId) return;

            pageController.abort();
            pageController = new AbortController();
            window.apsiPageSignal = pageController.signal;
            header.className = nextHeader.className;
            header.querySelector('.site-header__toggle')?.setAttribute('aria-expanded', 'false');
            header.querySelectorAll('a').forEach(link => {
                const active = nextHeader.querySelector(`a[href="${link.getAttribute('href')}"]`)?.classList.contains('active');
                link.classList.toggle('active', !!active);
                if (active) link.setAttribute('aria-current', 'page');
                else link.removeAttribute('aria-current');
            });
            nextHeader.replaceWith(header);
            const footer = document.querySelector('.site-footer');
            if (footer && next.querySelector('.site-footer')) next.querySelector('.site-footer').replaceWith(footer);

            // Keep shared fonts and libraries; update page styles and search metadata.
            const pageHead = 'style, meta[name="description"], meta[name="keywords"], meta[property], meta[name^="twitter:"], link[rel="canonical"], script[type="application/ld+json"]';
            document.head.querySelectorAll(pageHead).forEach(node => node.remove());
            next.head.querySelectorAll(pageHead).forEach(node => document.head.append(node));
            document.title = next.title;
            next.body.querySelectorAll('script').forEach(script => script.remove());
            document.body.className = next.body.className;
            document.body.replaceChildren(...next.body.childNodes);
            if (historyEntry) history.pushState({ scroll: [0, 0] }, '', url);

            window.scrollTo({ left: scroll[0], top: scroll[1], behavior: 'instant' });
            window.apsiInitSite();
            for (const code of scripts) {
                const script = document.createElement('script');
                script.textContent = `(() => {\n${code}\n})();`;
                document.body.append(script);
                script.remove();
            }
            document.querySelectorAll('.hidden-until-loaded').forEach(node => node.classList.add('show-after-load'));
            window.lucide?.createIcons();
            const heading = document.querySelector('h1');
            heading?.setAttribute('tabindex', '-1');
            heading?.focus({ preventScroll: true });
            if (url.hash) document.getElementById(decodeURIComponent(url.hash.slice(1)))?.scrollIntoView();
        } catch (error) {
            if (error.name !== 'AbortError' && id === navigationId) location.assign(url.href);
        } finally {
            if (id === navigationId) document.documentElement.removeAttribute('aria-busy');
        }
    }

    document.addEventListener('click', event => {
        const link = event.target.closest('a[href]');
        if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey ||
            link.hasAttribute('download') || (link.target && link.target !== '_self')) return;
        const url = new URL(link.href);
        if (!isPublicPage(url) || (url.pathname === location.pathname && url.search === location.search)) return;
        event.preventDefault();
        navigate(url);
    });
    history.scrollRestoration = 'manual';
    history.replaceState({ ...history.state, scroll: [window.scrollX, window.scrollY] }, '');
    window.addEventListener('scroll', () => {
        history.replaceState({ ...history.state, scroll: [window.scrollX, window.scrollY] }, '');
    }, { passive: true });
    window.addEventListener('popstate', event => {
        const url = new URL(location.href);
        if (isPublicPage(url)) navigate(url, { historyEntry: false, scroll: event.state?.scroll || [0, 0] });
        else location.reload();
    });
})();
