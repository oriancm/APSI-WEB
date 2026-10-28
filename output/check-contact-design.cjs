const {chromium}=require('C:/Users/orian/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
const browser=await chromium.launch({headless:true,channel:'chrome'});
const page=await browser.newPage();const errors=[];page.on('pageerror',e=>errors.push(e.message));
for(const width of [1400,1000,390]){
 await page.setViewportSize({width,height:720});
 await page.goto('http://127.0.0.1:8088/contact',{waitUntil:'networkidle'});
 await page.evaluate(async()=>{await document.fonts.ready;await Promise.all([...document.images].map(i=>i.decode().catch(()=>{})));});
 await page.waitForTimeout(500);
 await page.screenshot({path:`output/contact-redesign-${width}.png`});
 await page.screenshot({path:`output/contact-redesign-full-${width}.png`,fullPage:true});
 console.log(width,await page.evaluate(()=>({heading:document.querySelector('h1').innerText,overflow:document.documentElement.scrollWidth>innerWidth,fields:[...document.querySelectorAll('.contact-form input,.contact-form textarea')].map(i=>i.name),clip:getComputedStyle(document.querySelector('.contact-scene__image')).clipPath,footerBelowForm:document.querySelector('.site-footer').getBoundingClientRect().top>=document.querySelector('form').getBoundingClientRect().bottom})));
}
await page.setViewportSize({width:1400,height:720});
await page.goto('http://127.0.0.1:8088/clients',{waitUntil:'networkidle'});
await page.locator('.site-header__nav-cta').click();await page.waitForURL('**/contact');await page.waitForFunction(()=>!document.documentElement.hasAttribute('aria-busy'));
await page.locator('#fname').fill('Vérification visuelle');await page.locator('#sujet').fill('Aperçu du formulaire');await page.locator('#subject').fill('Vérification de la saisie, sans envoi.');
console.log('navigation and input',await page.locator('#fname').inputValue(),errors);
await page.locator('.contact-privacy-link').click();await page.waitForURL('**/privacyPolicy');
console.log('privacy link OK');
await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
