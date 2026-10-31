const { chromium } = require("playwright");
const assert = require("node:assert/strict");
const base = process.env.APSI_TEST_URL;
assert.match(base || "", /^http:\/\/127\.0\.0\.1:\d+$/);
(async () => {
  const browser = await chromium.launch({ channel: "chrome" });
  try {
    const page = await browser.newPage({ reducedMotion: "reduce" });
    const errors = [];
    page.on("pageerror", (e) => errors.push(e.message));
    for (const [width, height] of [
      [1440, 1000],
      [1000, 720],
      [820, 1180],
      [390, 844],
    ]) {
      await page.setViewportSize({ width, height });
      for (let id = 1; id <= 7; id++) {
        const response = await page.goto(`${base}/reference/${id}`);
        assert.equal(response.status(), 200);
        await page.evaluate(() => document.fonts.ready);
        await page.evaluate(
          () =>
            new Promise((resolve) =>
              requestAnimationFrame(() =>
                requestAnimationFrame(() => requestAnimationFrame(resolve)),
              ),
            ),
        );
        if (width > 980)
          await page
            .waitForFunction(
              () => {
                const gallery = document
                  .querySelector(".reference-main-image-container")
                  .getBoundingClientRect();
                const info = document
                  .querySelector(".reference-showcase > .reference-info-card")
                  .getBoundingClientRect();
                return Math.abs(gallery.bottom - info.bottom) < 1;
              },
              null,
              { timeout: 5000 },
            )
            .catch(async (error) => {
              const metrics = await page.evaluate(() =>
                [
                  ...document.querySelectorAll(
                    ".reference-main-image-container,.reference-showcase>.reference-info-card,.reference-info-measure",
                  ),
                ].map((e) => ({
                  cl: e.className,
                  box: e.getBoundingClientRect().toJSON(),
                  style: e.style.cssText,
                  rows: [
                    ...e.querySelectorAll(
                      ".card-row,.reference-schedule,.reference-stats,.reference-budget",
                    ),
                  ].map((n) => ({
                    cl: n.className,
                    h: n.offsetHeight,
                    txt: n.textContent.trim(),
                  })),
                })),
              );
              console.error(
                "Alignment failed",
                width,
                height,
                id,
                JSON.stringify(metrics),
              );
              throw error;
            });
        const box = await page
          .locator(".reference-main-image-container")
          .boundingBox();
        if (width <= 980)
          assert.ok(
            Math.abs(box.width / box.height - 4 / 3) < 0.005,
            "Mobile keeps its photo format",
          );
        const info = page.locator(".reference-showcase > .reference-info-card");
        if (id === 1) {
          assert.equal(await info.locator(".card-row").count(), 3);
          assert.deepEqual(
            await info.locator(".card-row .row-value").allTextContents(),
            ["XXX", "XXX", "XXX"],
          );
          assert.deepEqual(
            await info.locator(".reference-stat strong").allTextContents(),
            ["XXX", "XXX", "XXX"],
          );
          assert.deepEqual(
            await info.locator(".schedule-item strong").allTextContents(),
            ["XXX", "XXX", "XXX"],
          );
          assert.equal(
            await info.locator(".reference-budget strong").innerText(),
            "XXX",
          );
        }
        if (id === 2) {
          assert.match(
            await info.locator(".card-row .row-value").first().innerText(),
            /Maîtrise d’ouvrage/,
          );
        }
        const fullInfo = await info.boundingBox();
        await info.locator(".card-row").evaluateAll((rows) =>
          rows.slice(1).forEach((row) => {
            row.style.display = "none";
          }),
        );
        await page.evaluate(
          () =>
            new Promise((resolve) =>
              requestAnimationFrame(() => requestAnimationFrame(resolve)),
            ),
        );
        assert.ok(
          (await info.boundingBox()).height < fullInfo.height,
          "Fixture really hides participant labels",
        );
        const afterHide = await page
          .locator(".reference-main-image-container")
          .boundingBox();
        assert.ok(
          Math.abs(afterHide.height - box.height) < 1,
          `Missing labels retain the complete panel baseline: ${width}/${id}, before=${box.height}, after=${afterHide.height}, baseline=${await page.locator(".reference-gallery").getAttribute("style")}`,
        );
        await info.evaluate((el) => {
          el.style.height = "1800px";
          el.textContent = "Informations très longues ".repeat(200);
        });
        await page.evaluate(
          () =>
            new Promise((resolve) =>
              requestAnimationFrame(() => requestAnimationFrame(resolve)),
            ),
        );
        const tall = await page
          .locator(".reference-main-image-container")
          .boundingBox();
        assert.ok(
          Math.abs(tall.height - box.height) < 1,
          `Tall info at ${width}/${id}: ${box.height} -> ${tall.height}, ${await page.locator(".reference-gallery").getAttribute("style")}`,
        );
        await info.evaluate((el) => {
          el.style.height = "30px";
          el.textContent = "XXX";
        });
        await page.evaluate(
          () =>
            new Promise((resolve) =>
              requestAnimationFrame(() => requestAnimationFrame(resolve)),
            ),
        );
        const short = await page
          .locator(".reference-main-image-container")
          .boundingBox();
        assert.ok(
          Math.abs(short.height - box.height) < 1,
          `Short info at ${width}/${id}: ${box.height} -> ${short.height}, ${await page.locator(".reference-gallery").getAttribute("style")}`,
        );
        if (id === 1) {
          await page.locator(".gallery-arrow--right").focus();
          await page.keyboard.press("Enter");
          assert.equal(
            await page.locator("#current-slide-index").innerText(),
            "02",
          );
          await page.locator(".reference-fullscreen").focus();
          await page.keyboard.press("Enter");
          await page.waitForFunction(() => !!document.fullscreenElement);
          assert.equal(
            await page
              .locator("#main-reference-image")
              .evaluate((el) => getComputedStyle(el).objectFit),
            "contain",
          );
          await page.evaluate(() => document.exitFullscreen());
          await page.waitForFunction(() => !document.fullscreenElement);
          await page.evaluate(
            () =>
              new Promise((resolve) =>
                requestAnimationFrame(() => requestAnimationFrame(resolve)),
              ),
          );
          const restored = await page
            .locator(".reference-main-image-container")
            .boundingBox();
          assert.ok(
            Math.abs(restored.height - box.height) < 1,
            "Normal carousel format restored after full screen",
          );
        }
      }
    }
    await page.locator(".back-link").first().click();
    await page.waitForURL("**/references");
    await page.waitForFunction(
      () => !document.documentElement.hasAttribute("aria-busy"),
    );
    assert.equal(
      await page.locator(".reference-info-measure").count(),
      0,
      "Sizing observer cleaned up on navigation",
    );
    await page.locator(".reference-tile a").first().click();
    await page.waitForURL(/\/reference\/\d+/);
    await page.waitForFunction(
      () => !document.documentElement.hasAttribute("aria-busy"),
    );
    assert.equal(
      await page.locator(".reference-info-measure").count(),
      1,
      "One sizing panel after navigation",
    );
    assert.deepEqual(errors, []);
    console.log(
      "PASS: 7 references × 4 viewport sizes; desktop carousel ends at the full info baseline, retains its height with hidden/short/tall information, mobile 4:3, XXX placeholders, slides and full-screen.",
    );
  } finally {
    await browser.close();
  }
})().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
