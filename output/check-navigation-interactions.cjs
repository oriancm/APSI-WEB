const {chromium}=require('C:/Users/orian/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
const browser=await chromium.launch({headless:true,channel:'chrome'});
const page=await browser.newPage({viewport:{width:1400,height:700}}); const errors=[]; page.on('pageerror',e=>errors.push(e.message));
await page.goto('http://127.0.0.1:8088/'); await page.evaluate(()=>scrollTo(0,300));await page.waitForTimeout(200);
console.log('home',await page.evaluate(()=>({scroll:scrollY,scrolled:document.querySelector('header').classList.contains('is-scrolled'),after:getComputedStyle(document.querySelector('header'),'::after').display,white:getComputedStyle(document.querySelector('.site-header__logo-white')).opacity})));
await page.screenshot({path:'output/common-nav-home-scroll.png'});
const go=async route=>{await page.locator(`a[href="${route}"]`).first().click();await page.waitForURL('http://127.0.0.1:8088'+route);await page.waitForFunction(()=>!document.documentElement.hasAttribute('aria-busy'));};
await go('/aboutUs'); const navStyle=()=>page.evaluate(()=>{const h=document.querySelector('header');return [h.getBoundingClientRect().height,getComputedStyle(h).gridTemplateColumns,getComputedStyle(h,'::after').backgroundImage,getComputedStyle(h.querySelector('.site-header__nav')).gap]});const about=await navStyle();
for(const route of ['/clients','/contact','/legalNotices','/privacyPolicy','/professions','/references']){await go(route);console.log('same-nav',route,JSON.stringify(about)===JSON.stringify(await navStyle()));}
await page.locator('.references-filter button[data-filter]').nth(1).click();console.log('filter',await page.locator('.references-filter button.active').getAttribute('data-filter'));
await go('/professions');await page.locator('.opc-pillar').nth(1).click();console.log('profession-tab',await page.locator('.opc-pillar.active').count());
await go('/references');let ref=await page.locator('a[href^="/reference/"]').first().getAttribute('href');if(ref){await go(ref);await page.locator('.gallery-arrow--right').click();console.log('gallery',await page.locator('#current-slide-index').textContent());}
await page.setViewportSize({width:390,height:844});await page.goto('http://127.0.0.1:8088/aboutUs');
for(const route of ['/clients','/contact','/professions','/references','/aboutUs']){
 await page.locator('.site-header__toggle').click();await go(route);console.log('mobile',route,await page.locator('.site-header__toggle').getAttribute('aria-expanded'),await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth));
}
await page.screenshot({path:'output/common-nav-mobile.png'});
console.log('ERRORS',errors);await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
