const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const base = process.env.APSI_TEST_URL || 'http://127.0.0.1:8088';
const singlePhotoId = Number(process.env.APSI_SINGLE_PHOTO_ID || 4);

(async () => {
    const browser = await chromium.launch({ channel: 'chrome', headless: true });
    const page = await browser.newPage();
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    try {
        for (const width of [1400, 1000, 390]) {
            await page.setViewportSize({ width, height: 800 });
            await page.goto(base + '/aboutUs', { waitUntil: 'networkidle' });
            const standard = await page.locator('.feature-hero__eyebrow').evaluate(el => ({ top: el.getBoundingClientRect().top, margin: getComputedStyle(el).marginBottom }));
            await page.goto(base + '/contact', { waitUntil: 'networkidle' });
            const contact = await page.locator('.contact-eyebrow').evaluate(el => ({ top: el.getBoundingClientRect().top, margin: getComputedStyle(el).marginBottom }));
            assert.equal(contact.top, standard.top, 'Eyebrow aligns with the other pages');
            assert.equal(contact.margin, standard.margin, 'Eyebrow spacing matches the other pages');
            assert.equal(await page.locator('.contact-content a[href="/privacyPolicy"]').count(), 0);
            assert.equal(await page.locator('#submitBtn svg').count(), 1);
            assert.equal(await page.locator('#submitBtn').getAttribute('aria-label'), 'Envoyer le message');
            const adjacent = await page.evaluate(() => {
                const field = document.querySelector('#subject').getBoundingClientRect();
                const button = document.querySelector('#submitBtn').getBoundingClientRect();
                return button.left > field.right && Math.abs(button.bottom - field.bottom) < 1;
            });
            assert.ok(adjacent, 'Send icon sits to the right of the message');
            await page.goto(base + '/reference/' + singlePhotoId, { waitUntil: 'networkidle' });
            assert.equal((await page.locator('.reference-slide-progress').innerText()).replace(/\s+/g, ' '), '1 sur 1');
            assert.equal(await page.locator('.gallery-arrow').count(), 0);
            assert.equal(await page.locator('.same-domain-grid .reference-tile').count(), 3);
            await page.locator('.same-domain-grid').scrollIntoViewIfNeeded();
            await page.waitForTimeout(900);
            assert.equal(await page.locator('.same-domain-grid .reference-tile__domain').count(), 3);
            assert.equal(await page.locator('.same-domain-grid h3').first().evaluate(el => getComputedStyle(el).whiteSpace), 'normal');
            assert.equal(await page.evaluate(() => document.documentElement.scrollWidth > innerWidth), false);
            console.log('Contact alignment, icon, single photo and shared cards:', width, 'OK');
        }
        await page.clock.install();
        for (const width of [1400, 390]) {
            await page.setViewportSize({ width, height: 800 });
            await page.goto(base + '/references', { waitUntil: 'networkidle' });
            assert.ok(await page.locator('.references-carousel').isVisible());
            assert.equal(await page.locator('.references-carousel__pause').count(), 0);
            const active = () => page.locator('.references-carousel__slide:not([hidden])').getAttribute('aria-label');
            let previous = await active();
            await page.locator('.references-carousel').dispatchEvent('mouseenter');
            for (let index = 0; index < 4; index++) {
                await page.clock.fastForward(4100);
                const next = await active();
                assert.notEqual(next, previous, 'Carousel keeps cycling with the pointer over it');
                previous = next;
            }
            await page.locator('.references-carousel__slide:not([hidden]) a').focus();
            await page.clock.fastForward(4100);
            assert.notEqual(await active(), previous, 'Focus does not stop cycling');
            previous = await active();
            await page.emulateMedia({ reducedMotion: 'reduce' });
            await page.clock.fastForward(4100);
            assert.notEqual(await active(), previous, 'Slides still change without motion transitions');
            // Exercise the same page lifecycle used by the site's client navigation.
            if (width > 980) {
                await page.locator('.site-header__nav-cta').first().click();
                await page.waitForURL('**/contact');
            } else await page.goto(base + '/contact', { waitUntil: 'networkidle' });
            await page.goto(base + '/references', { waitUntil: 'networkidle' });
            previous = await active();
            await page.clock.fastForward(4100);
            assert.notEqual(await active(), previous, 'Cycling restarts after navigation');
            console.log('Continuous carousel with hover, focus and navigation:', width, 'OK');
        }
        assert.deepEqual(errors, []);
    } finally { await browser.close(); }
})().catch(error => { console.error(error); process.exit(1); });
