const {chromium}=require('C:/Users/orian/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
const browser=await chromium.launch({headless:true,channel:'chrome'});
const page=await browser.newPage();
for(const width of [1400,1000,390]){
 await page.setViewportSize({width,height:720});
 await page.goto('http://127.0.0.1:8088/clients',{waitUntil:'networkidle'});
 await page.evaluate(async()=>{await document.fonts.ready;await Promise.all([...document.images].map(i=>i.decode().catch(()=>{})));});
 await page.waitForTimeout(500);
 await page.screenshot({path:`output/clients-updated-${width}.png`});
 console.log(width,await page.evaluate(()=>({heading:document.querySelector('h1').innerText,blue:getComputedStyle(document.querySelector('h1 span')).color,logos:document.querySelectorAll('.clients-grid article').length,footerTop:document.querySelector('.site-footer').getBoundingClientRect().top,lastLogoBottom:document.querySelector('.clients-grid article:last-child').getBoundingClientRect().bottom,overflow:document.documentElement.scrollWidth>innerWidth,image:getComputedStyle(document.querySelector('.feature-hero'),'::before').backgroundImage,diagonal:getComputedStyle(document.querySelector('.feature-hero'),'::after').transform})));
 await page.evaluate(()=>scrollTo(0,document.body.scrollHeight));await page.waitForTimeout(700);
 await page.screenshot({path:`output/clients-updated-bottom-${width}.png`});
}
await page.setViewportSize({width:1400,height:720});
await page.goto('http://127.0.0.1:8088/professions',{waitUntil:'networkidle'});
await page.locator('.site-header__links a[href="/clients"]').click();
await page.waitForURL('**/clients');await page.waitForFunction(()=>!document.documentElement.hasAttribute('aria-busy'));
console.log('navigation',await page.locator('h1').innerText());
await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
