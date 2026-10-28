const {chromium}=require('C:/Users/orian/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
const browser=await chromium.launch({headless:true,channel:'chrome'});
const page=await browser.newPage();const errors=[];page.on('pageerror',e=>errors.push(e.message));
const diagonal=()=>{const hero=document.querySelector('.feature-hero,.references-hero'),rect=hero.getBoundingClientRect(),s=getComputedStyle(hero,'::after');return rect.left+parseFloat(s.left)+parseFloat(s.width)+(rect.top+scrollY)*Math.tan(25*Math.PI/180)};
for(const width of [1400,1000,390]){
 await page.setViewportSize({width,height:720});
 await page.goto('http://127.0.0.1:8088/professions',{waitUntil:'networkidle'});const target=await page.evaluate(diagonal);
 await page.goto('http://127.0.0.1:8088/references',{waitUntil:'networkidle'});await page.evaluate(()=>document.fonts.ready);await page.waitForTimeout(400);
 const actual=await page.evaluate(diagonal);
 console.log(width,await page.evaluate(()=>({eyebrowLeft:document.querySelector('.references-eyebrow').getBoundingClientRect().left,resultsLeft:document.querySelector('.references-results').getBoundingClientRect().left,filterLeft:document.querySelector('.references-filter-wrap').getBoundingClientRect().left,filterWidth:document.querySelector('.references-filter-wrap').getBoundingClientRect().width,overflow:document.documentElement.scrollWidth>innerWidth})),{diagonalDifference:width>980?actual-target:'mobile'});
 await page.screenshot({path:`output/references-adjusted-${width}.png`});
 if(width<=980)await page.locator('.filter-toggle').click();
 await page.locator('[data-filter="5"]').click();console.log('urban',await page.locator('.references-results').innerText());
 if(width<=980)await page.locator('.filter-toggle').click();
 await page.locator('[data-filter="all"]').click();console.log('all',await page.locator('.reference-tile:visible').count());
}
await page.setViewportSize({width:1400,height:720});await page.goto('http://127.0.0.1:8088/references',{waitUntil:'networkidle'});
const before=await page.locator('.references-carousel__slide:not([hidden])').getAttribute('aria-label');await page.waitForTimeout(4100);const after=await page.locator('.references-carousel__slide:not([hidden])').getAttribute('aria-label');console.log('carousel',before,after);
await page.goto('http://127.0.0.1:8088/aboutUs',{waitUntil:'networkidle'});console.log('removed blocks',await page.locator('.about-highlights').count(),'errors',errors);
await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
