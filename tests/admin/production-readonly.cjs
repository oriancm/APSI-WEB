// Verification after deployment. Does not create, edit or delete production references.
const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const base = 'https://apsi-btp.fr';
const output = path.resolve('output/backoffice');

(async () => {
  assert.ok(process.env.APSI_ADMIN_PASSWORD, 'Provide the existing admin password via environment.');
  fs.mkdirSync(output, {recursive: true});
  const browser = await chromium.launch({channel: 'chrome'});
  const context = await browser.newContext({viewport: {width: 1440, height: 1000}, reducedMotion: 'reduce'});
  const page = await context.newPage(); const errors = [];
  page.on('pageerror', error => errors.push(error.message));
  page.on('console', message => {if (/Content Security Policy|Refused to/.test(message.text())) errors.push(message.text());});
  page.on('dialog', dialog => dialog.accept());
  try {
    for (const url of ['/secrets/private-key', '/backups/db.serialized', '/admin/.env/extra', '/admin/db.php/extra', '/admin/editor.php/extra', '/admin/db.php', '/admin/editor.php', '/admin/reference_service.php', '/.env', '/admin/.env', '/tests/admin/run.py']) {
      const response = await context.request.get(base + url, {maxRedirects: 0}); assert.equal(response.status(), 403, url);
    }
    await page.goto(base + '/admin/login.php');
    await page.locator('#login').fill('admin'); await page.locator('#pass').fill(process.env.APSI_ADMIN_PASSWORD);
    await page.getByRole('button', {name: /Accéder à mon espace/}).click(); await page.waitForURL('**/admin/admin.php');
    const cookie = (await context.cookies()).find(cookie => cookie.name === 'APSI_ADMIN');
    assert.ok(cookie.secure && cookie.httpOnly && cookie.sameSite === 'Strict');
    const count = await page.locator('.project-card').count(); assert.ok(count >= 7);
    await page.screenshot({path: path.join(output, 'production-dashboard-desktop.png'), fullPage: true});
    for (const image of await page.locator('.project-cover img').all()) {
      await image.scrollIntoViewIfNeeded(); await image.evaluate(image => image.decode());
    }
    const rejected = await context.request.post(base + '/admin/admin.php', {form: {csrf: 'invalid-check', delete_id: '1', confirmation: 'SUPPRIMER'}, maxRedirects: 0});
    assert.equal(rejected.status(), 403);
    await page.goto(base + '/admin/add.php');
    await page.locator('#postal_code').fill('84000');
    await page.waitForFunction(() => document.querySelector('#city').value === 'Avignon', null, {timeout: 15000});
    await page.locator('#city').fill('Apt');
    await page.waitForFunction(() => document.querySelector('#postal_code').value === '84400', null, {timeout: 15000});
    await page.goto(base + '/admin/mod.php?id=3');
    assert.equal(await page.locator('#titre').inputValue(), 'L’hôtel des Monnaies-Niel');
    assert.equal(await page.locator('.photo-tile').count(), 9);
    await page.screenshot({path: path.join(output, 'production-editor-desktop.png'), fullPage: true});
    await page.setViewportSize({width: 390, height: 844});
    await page.goto(base + '/admin/admin.php');
    assert.equal(await page.evaluate(() => document.documentElement.scrollWidth > innerWidth), false);
    await page.screenshot({path: path.join(output, 'production-dashboard-mobile.png'), fullPage: true});
    await page.goto(base + '/admin/mod.php?id=3');
    assert.equal(await page.evaluate(() => document.documentElement.scrollWidth > innerWidth), false);
    await page.screenshot({path: path.join(output, 'production-editor-mobile.png'), fullPage: true});
    await page.goto(base + '/admin/change_password.php');
    await page.getByRole('button', {name: 'Se déconnecter'}).click(); await page.waitForURL('**/admin/login.php');
    const protectedPage = await context.request.get(base + '/admin/admin.php', {maxRedirects: 0}); assert.equal(protectedPage.status(), 303);
    assert.deepEqual(errors, []);
    console.log(`PASS production: ${count} projects, existing 9-photo gallery, live city/postal lookup, desktop/mobile, secure cookies, CSRF rejection, private modules, no browser/CSP errors. No reference data modified.`);
  } finally {await browser.close();}
})().catch(error => {console.error(error); process.exitCode = 1;});
