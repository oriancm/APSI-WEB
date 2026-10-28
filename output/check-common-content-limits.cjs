const {chromium}=require('C:/Users/orian/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
const b=await chromium.launch({headless:true,channel:'chrome'});const p=await b.newPage();
const routes=[['home','/','.home-hero__copy','.home-service-cards'],['about','/aboutUs','.feature-hero__inner','.feature-hero__cards'],['professions','/professions','.feature-hero__inner','.feature-hero__cards'],['references','/references','.references-eyebrow','.references-carousel'],['clients','/clients','.feature-hero__inner','.clients-grid'],['contact','/contact','.contact-scene','.contact-content'],['legal','/legalNotices','.legal-hero','.legal-hero'],['privacy','/privacyPolicy','.privacy-hero','.privacy-hero']];
for(const width of [1400,1000,1920,2560]){
await p.setViewportSize({width,height:width===2560?1440:900});
for(const [name,route,leftSelector,rightSelector] of routes){
await p.goto('http://127.0.0.1:8088'+route,{waitUntil:'networkidle'});await p.evaluate(()=>document.fonts.ready);
const m=await p.evaluate(({leftSelector,rightSelector})=>{const h=document.querySelector('#site-header').getBoundingClientRect(),l=document.querySelector('.site-header__logo').getBoundingClientRect(),r=document.querySelector('.site-header__nav-cta').getBoundingClientRect();return{leftInset:l.left-h.left,rightInset:h.right-r.right,leftDelta:document.querySelector(leftSelector).getBoundingClientRect().left-l.left,rightDelta:document.querySelector(rightSelector).getBoundingClientRect().right-r.right}}, {leftSelector,rightSelector});
console.log(width,name,m);if(Object.values(m).some(Number.isNaN)||Math.abs(m.leftInset-m.rightInset)>.1||Math.abs(m.leftDelta)>.1||Math.abs(m.rightDelta)>.1)throw Error('Alignment mismatch');
if(width===1400||width===1000)await p.screenshot({path:`output/common-limits-${name}-${width}.png`});
}
}
await p.setViewportSize({width:390,height:800});await p.goto('http://127.0.0.1:8088/clients',{waitUntil:'networkidle'});await p.locator('.site-header__toggle').click();console.log('mobile nav contact',await p.locator('.site-header__nav-cta').isVisible());await b.close();
})().catch(e=>{console.error(e);process.exit(1)});
