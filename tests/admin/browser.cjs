const { chromium } = require("playwright");
const { AxeBuilder } = require("@axe-core/playwright");
const assert = require("node:assert/strict");
const fs = require("node:fs");
const path = require("node:path");

const base = process.env.APSI_TEST_URL;
if (!/^http:\/\/127\.0\.0\.1:\d+$/.test(base || ""))
  throw new Error(
    "Browser mutation tests may run only on the disposable localhost fixture.",
  );
const output = path.resolve("output/backoffice");
fs.mkdirSync(output, { recursive: true });

(async () => {
  const browser = await chromium.launch({ channel: "chrome" });
  const context = await browser.newContext({
    viewport: { width: 1440, height: 1000 },
    reducedMotion: "reduce",
  });
  const page = await context.newPage();
  const errors = [];
  let auditing = false;
  page.on("pageerror", (error) => errors.push(error.message));
  // Axe temporarily injects inline styles for its contrast probes. The application itself must not.
  page.on("console", (message) => {
    if (!auditing && /Content Security Policy|Refused to/.test(message.text()))
      errors.push(message.text());
  });
  const accessibility = async (label) => {
    assert.equal(
      await page.locator('[style]:not([style=""])').count(),
      0,
      "Application has no inline styles blocked by CSP",
    );
    auditing = true;
    try {
      const result = await new AxeBuilder({ page })
        .withTags(["wcag2a", "wcag2aa", "wcag21aa"])
        .analyze();
      assert.deepEqual(
        result.violations.map((v) => ({
          id: v.id,
          nodes: v.nodes.map((n) => ({
            target: n.target,
            summary: n.failureSummary,
          })),
        })),
        [],
        label,
      );
    } finally {
      auditing = false;
    }
  };
  const noOverflow = async () =>
    assert.equal(
      await page.evaluate(
        () => document.documentElement.scrollWidth > innerWidth,
      ),
      false,
      "No horizontal overflow",
    );
  try {
    await page.goto(base + "/admin/login.php");
    await page.screenshot({
      path: path.join(output, "login-desktop.png"),
      fullPage: true,
    });
    await accessibility("Login accessibility");
    await page.locator("#login").fill("admin");
    await page.locator("#pass").fill(process.env.APSI_TEST_PASSWORD);
    await page.getByRole("button", { name: "Afficher", exact: true }).click();
    assert.equal(await page.locator("#pass").getAttribute("type"), "text");
    await page.getByRole("button", { name: /Accéder à mon espace/ }).click();
    await page.waitForURL("**/admin/admin.php");
    assert.equal(await page.locator(".project-card").count(), 7);
    await accessibility("Dashboard accessibility");
    await noOverflow();
    await page.screenshot({
      path: path.join(output, "dashboard-desktop.png"),
      fullPage: true,
    });
    await page.locator("#reference-search").fill("cezanne");
    assert.equal(await page.locator(".project-card:visible").count(), 1);
    await page.locator("#reference-search").fill("zzzzzz");
    await page.locator("#empty-results").waitFor({ state: "visible" });
    await page.locator("#reference-search").fill("");
    await page.locator("#domain-filter").selectOption("4");
    assert.equal(await page.locator(".project-card:visible").count(), 2);
    await page.locator("#domain-filter").selectOption("");
    await page.locator("#status-filter").selectOption("1");
    assert.equal(await page.locator(".project-card:visible").count(), 3);
    await page.locator("#status-filter").selectOption("");
    await page.locator('[data-delete-id="1"]').click();
    await page.locator("#delete-dialog").waitFor({ state: "visible" });
    await page.getByRole("button", { name: "Annuler", exact: true }).click();
    assert.equal(await page.locator(".project-card").count(), 7);
    await page.goto(base + "/admin/mod.php?id=3");
    await accessibility("Editor accessibility");
    await noOverflow();
    assert.equal(await page.locator(".photo-tile").count(), 9);
    const originalFirst = await page
      .locator(".photo-tile")
      .first()
      .getAttribute("data-photo-key");
    await page
      .locator(".photo-tile")
      .nth(1)
      .locator('[data-photo-move="-1"]')
      .click();
    assert.notEqual(
      await page.locator(".photo-tile").first().getAttribute("data-photo-key"),
      originalFirst,
    );
    await page
      .locator(".photo-tile")
      .first()
      .locator("[data-photo-remove]")
      .click();
    assert.equal(await page.locator(".photo-tile").count(), 8);
    await page
      .getByRole("button", { name: "Annuler le dernier retrait" })
      .click();
    assert.equal(await page.locator(".photo-tile").count(), 9);
    const beforeDrag = await page
      .locator(".photo-tile")
      .first()
      .getAttribute("data-photo-key");
    await page
      .locator(".photo-tile")
      .first()
      .dragTo(page.locator(".photo-tile").nth(2));
    assert.notEqual(
      await page.locator(".photo-tile").first().getAttribute("data-photo-key"),
      beforeDrag,
    );
    await page.evaluate(() => {
      document.activeElement?.blur();
      window.scrollTo(0, 0);
    });
    await page.screenshot({
      path: path.join(output, "editor-desktop.png"),
      fullPage: true,
    });
    page.on("dialog", (dialog) => dialog.accept()); // Accept only the browser's leave-with-unsaved-changes warning.
    await page.goto(base + "/admin/add.php");
    await page.route("https://geo.api.gouv.fr/communes?**", async (route) => {
      const params = new URL(route.request().url()).searchParams;
      const city = params.get("nom"),
        postal = params.get("codePostal");
      if (city === "Erreur")
        return route.fulfill({
          status: 503,
          body: "Unavailable",
          headers: { "Access-Control-Allow-Origin": "*" },
        });
      let data = [
        {
          nom: "Avignon",
          codesPostaux: ["84000"],
          codeDepartement: "84",
          code: "84007",
        },
      ];
      if (postal === "84220")
        data = [
          { nom: "Gordes", codesPostaux: ["84220"], codeDepartement: "84" },
          { nom: "Roussillon", codesPostaux: ["84220"], codeDepartement: "84" },
        ];
      if (city === "Marseille")
        data = [
          {
            nom: "Marseille",
            codesPostaux: ["13001", "13002"],
            codeDepartement: "13",
          },
        ];
      if (city === "Apt")
        data = [{ nom: "Apt", codesPostaux: ["84400"], codeDepartement: "84" }];
      if (city === "Avi")
        await new Promise((resolve) => setTimeout(resolve, 800));
      return route.fulfill({
        contentType: "application/json",
        body: JSON.stringify(data),
        headers: { "Access-Control-Allow-Origin": "*" },
      });
    });
    await page.locator("#postal_code").fill("84000");
    await page.waitForFunction(
      () => document.querySelector("#city").value === "Avignon",
    );
    await page.locator("#city").fill("Apt");
    await page.waitForFunction(
      () => document.querySelector("#postal_code").value === "84400",
    );
    await page.locator("#postal_code").fill("84220");
    await page.locator("#location-results button").first().waitFor();
    assert.equal(await page.locator("#city").inputValue(), "");
    await page
      .locator("#location-results button")
      .filter({ hasText: "Gordes" })
      .click();
    assert.equal(await page.locator("#city").inputValue(), "Gordes");
    assert.equal(await page.locator("#postal_code").inputValue(), "84220");
    await page.locator("#city").fill("Marseille");
    await page.locator("#location-results button").first().waitFor();
    assert.equal(await page.locator("#location-results button").count(), 2);
    await page.locator("#city").press("ArrowDown");
    await page.keyboard.press("ArrowDown");
    await page.keyboard.press("Enter");
    assert.equal(await page.locator("#postal_code").inputValue(), "13002");
    await page.locator("#city").fill("Avi");
    await page.waitForRequest((request) => request.url().includes("nom=Avi&"));
    await page.locator("#city").fill("Apt");
    await page.waitForFunction(
      () => document.querySelector("#postal_code").value === "84400",
    );
    await page.waitForTimeout(900);
    assert.equal(
      await page.locator("#postal_code").inputValue(),
      "84400",
      "Late API response cannot overwrite the last city",
    );
    await page.locator("#city").fill("Erreur");
    await page.waitForFunction(() =>
      document
        .querySelector("#location-help")
        .textContent.includes("indisponible"),
    );
    await page.locator("#city").fill("Avignon");
    await page.waitForFunction(
      () => document.querySelector("#postal_code").value === "84000",
    );
    await page
      .locator("#titre")
      .fill("Extension du groupe scolaire Les Oliviers");
    await page.locator("#domaine").selectOption("4");
    await page
      .locator("#description")
      .fill(
        "Création de nouvelles salles de classe et réaménagement des espaces extérieurs. APSI assure la coordination et le suivi de l’opération.",
      );
    const imageResponse = await context.request.get(
      base + "/pic/refectoire_de_coudoux_1.webp",
    );
    const image = await imageResponse.body();
    await page.locator("#image-files").setInputFiles([
      { name: "chantier.webp", mimeType: "image/webp", buffer: image },
      { name: "chantier.webp", mimeType: "image/webp", buffer: image },
    ]);
    assert.equal(await page.locator(".photo-tile").count(), 2);
    assert.equal(
      JSON.parse(await page.locator("#photo-order").inputValue()).length,
      2,
    );
    await page
      .locator(".photo-tile")
      .last()
      .locator('[data-photo-move="-1"]')
      .click();
    await page.screenshot({
      path: path.join(output, "create-desktop.png"),
      fullPage: true,
    });
    const logout = await context.request.post(base + "/admin/logout.php", {
      form: { csrf: await page.locator('#reference-form [name="csrf"]').inputValue() },
      maxRedirects: 0,
    });
    assert.equal(logout.status(), 303);
    await page
      .getByRole("button", { name: "Enregistrer et publier", exact: true })
      .click();
    await page.locator("#session-warning").waitFor({ state: "visible" });
    assert.equal(
      await page.locator(".photo-tile").count(),
      2,
      "Session expiry preserves queued files",
    );
    assert.equal(
      await page.locator("#titre").inputValue(),
      "Extension du groupe scolaire Les Oliviers",
    );
    const reconnect = await context.newPage();
    await reconnect.goto(base + "/admin/login.php");
    await reconnect.locator("#login").fill("admin");
    await reconnect.locator("#pass").fill(process.env.APSI_TEST_PASSWORD);
    await reconnect
      .getByRole("button", { name: /Accéder à mon espace/ })
      .click();
    await reconnect.waitForURL("**/admin/admin.php");
    await reconnect.close();
    await page
      .getByRole("button", { name: "Enregistrer et publier", exact: true })
      .click();
    await page.waitForURL(/\/admin\/mod\.php\?id=\d+/);
    const createdURL = page.url();
    assert.match(
      await page.locator(".notice.success").innerText(),
      /maintenant en ligne/,
    );
    assert.equal(await page.locator(".photo-tile").count(), 2);
    await page
      .locator("#description")
      .fill("Mise à jour enregistrée au clavier.");
    const saved = page.waitForResponse(
      (response) =>
        response.request().method() === "POST" && response.url() === createdURL,
    );
    await page.keyboard.press("Control+Enter");
    assert.equal((await saved).status(), 303);
    await page.waitForLoadState("domcontentloaded");
    await page.waitForURL(createdURL);
    await page.locator(".notice.success").waitFor();
    await page.reload();
    assert.equal(
      await page.locator("#description").inputValue(),
      "Mise à jour enregistrée au clavier.",
    );
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto(base + "/admin/admin.php");
    await noOverflow();
    await accessibility("Mobile dashboard accessibility");
    await page.screenshot({
      path: path.join(output, "dashboard-mobile.png"),
      fullPage: true,
    });
    await page.goto(base + "/admin/mod.php?id=3");
    await noOverflow();
    await accessibility("Mobile editor accessibility");
    await page.evaluate(() => {
      document.activeElement?.blur();
      window.scrollTo(0, 0);
    });
    await page.screenshot({
      path: path.join(output, "editor-mobile.png"),
      fullPage: true,
    });
    await page.goto(base + "/admin/change_password.php");
    await accessibility("Account security accessibility");
    await noOverflow();
    await page.getByRole("button", { name: "Se déconnecter" }).click();
    await page.waitForURL("**/admin/login.php");
    await noOverflow();
    await accessibility("Mobile login accessibility");
    await page.screenshot({
      path: path.join(output, "login-mobile.png"),
      fullPage: true,
    });
    assert.deepEqual(errors, []);
    console.log(
      "PASS: desktop/mobile, city↔postal code, ambiguous codes, keyboard choices, API outage and race, filters, gallery order/removal/undo, duplicate filenames, real WebP upload, session expiry and reauthentication without losing files, create/edit, keyboard save, logout, accessibility and CSP.",
    );
  } finally {
    await browser.close();
  }
})().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
