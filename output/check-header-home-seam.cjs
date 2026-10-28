const {chromium}=require('C:/Users/orian/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
const b=await chromium.launch({headless:true,channel:'chrome'});
const p=await b.newPage({viewport:{width:1400,height:820}});
for(const route of ['/','/aboutUs','/professions','/references','/clients','/contact','/legalNotices','/privacyPolicy']){
await p.goto('http://127.0.0.1:8088'+route,{waitUntil:'networkidle'});
console.log(route,await p.evaluate(()=>({rightGap:document.querySelector('#site-header').getBoundingClientRect().right-document.querySelector('.site-header__nav-cta').getBoundingClientRect().right,overflow:document.documentElement.scrollWidth>innerWidth})));
}
for(const width of [1400,1000,1920]){
await p.setViewportSize({width,height:820});await p.goto('http://127.0.0.1:8088/',{waitUntil:'networkidle'});await p.evaluate(()=>document.fonts.ready);await p.screenshot({path:`output/home-seam-fixed-${width}.png`});
console.log('home',width,await p.evaluate(()=>({rightGap:document.querySelector('#site-header').getBoundingClientRect().right-document.querySelector('.site-header__nav-cta').getBoundingClientRect().right,mask:getComputedStyle(document.querySelector('.home-scene')).maskImage})));
}
await p.setViewportSize({width:390,height:820});await p.goto('http://127.0.0.1:8088/',{waitUntil:'networkidle'});await p.locator('.site-header__toggle').click();console.log('mobile contact visible',await p.locator('.site-header__nav-cta').isVisible());
await b.close();
})().catch(e=>{console.error(e);process.exit(1)});
