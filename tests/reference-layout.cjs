const {chromium} = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const base = process.env.APSI_TEST_URL;
assert.match(base || '', /^http:\/\/127\.0\.0\.1:\d+$/);
const output = 'output/reference-layout'; fs.mkdirSync(output, {recursive: true});
(async () => {
  const browser = await chromium.launch({channel: 'chrome'});
  try {
    const page = await browser.newPage({reducedMotion: 'reduce'});
    const errors = []; page.on('pageerror', e => errors.push(e.message));
    for (const [width, height] of [[1440,1000],[1000,720],[820,1180],[390,844]]) {
      await page.setViewportSize({width, height}); let expected = null;
      for (let id = 1; id <= 7; id++) {
        const response = await page.goto(`${base}/reference/${id}`);
        assert.equal(response.status(), 200);
        const box = await page.locator('.reference-main-image-container').boundingBox();
        assert.ok(Math.abs(box.width / box.height - 4 / 3) < .005, `Fixed 4:3 ratio at ${width}, reference ${id}`);
        if (expected) assert.ok(Math.abs(box.height - expected.height) < 1 && Math.abs(box.width - expected.width) < 1, 'Same carousel dimensions across projects');
        expected = box;
        if (id === 1) {
          assert.equal(await page.locator('.card-row').count(), 3);
          assert.deepEqual(await page.locator('.card-row .row-value').allTextContents(), ['XXX','XXX','XXX']);
          assert.deepEqual(await page.locator('.reference-stat strong').allTextContents(), ['XXX','XXX','XXX']);
          assert.deepEqual(await page.locator('.schedule-item strong').allTextContents(), ['XXX','XXX','XXX']);
          assert.equal(await page.locator('.reference-budget strong').innerText(), 'XXX');
          await page.screenshot({path: `${output}/empty-${width}.png`, fullPage: true});
        }
        if (id === 2) {
          assert.match(await page.locator('.card-row .row-value').first().innerText(), /Maîtrise d’ouvrage/);
          await page.screenshot({path: `${output}/complete-${width}.png`, fullPage: true});
        }
        await page.locator('.reference-info-card').evaluate(el => {el.style.height='1800px'; el.textContent='Informations très longues '.repeat(200);});
        const tall = await page.locator('.reference-main-image-container').boundingBox();
        assert.ok(Math.abs(tall.height - box.height) < 1, 'Tall information must not stretch carousel');
        await page.locator('.reference-info-card').evaluate(el => {el.style.height='30px'; el.textContent='XXX';});
        const short = await page.locator('.reference-main-image-container').boundingBox();
        assert.ok(Math.abs(short.height - box.height) < 1, 'Short information must not shrink carousel');
        if (id === 1) {
          await page.locator('.gallery-arrow--right').focus(); await page.keyboard.press('Enter');
          assert.equal(await page.locator('#current-slide-index').innerText(), '02');
          await page.locator('.reference-fullscreen').focus(); await page.keyboard.press('Enter'); await page.waitForFunction(() => !!document.fullscreenElement);
          assert.equal(await page.locator('#main-reference-image').evaluate(el => getComputedStyle(el).objectFit), 'contain');
          await page.evaluate(() => document.exitFullscreen()); await page.waitForFunction(() => !document.fullscreenElement);
          const restored = await page.locator('.reference-main-image-container').boundingBox();
          assert.ok(Math.abs(restored.height - box.height) < 1, 'Normal carousel format restored after full screen');
        }
      }
    }
    assert.deepEqual(errors, []);
    console.log('PASS: 7 references × 4 viewport sizes; identical 4:3 carousel with empty/full/short/tall information, XXX placeholders, slide navigation and full-screen round trip.');
  } finally {await browser.close();}
})().catch(error => {console.error(error); process.exitCode=1;});
