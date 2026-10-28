const {chromium}=require('C:/Users/orian/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
const b=await chromium.launch({headless:true,channel:'chrome'});const p=await b.newPage({isMobile:true,hasTouch:true});const errors=[];p.on('pageerror',e=>errors.push(e.message));
for(const width of [390,820]){
await p.setViewportSize({width,height:800});
for(const [name,route] of [['home','/'],['about','/aboutUs'],['professions','/professions'],['clients','/clients'],['references','/references'],['contact','/contact'],['legal','/legalNotices'],['privacy','/privacyPolicy']]){
await p.goto('http://127.0.0.1:8088'+route,{waitUntil:'networkidle'});await p.evaluate(()=>document.fonts.ready);
const snapshot=()=>{const h=document.querySelector('#site-header'),l=document.querySelector('.site-header__logo'),t=document.querySelector('.site-header__toggle');return {header:h.getBoundingClientRect().toJSON(),logo:l.getBoundingClientRect().toJSON(),toggle:t.getBoundingClientRect().toJSON(),background:getComputedStyle(h).backgroundColor,logoBackground:getComputedStyle(l).backgroundColor,carousel:document.querySelector('.references-carousel')?getComputedStyle(document.querySelector('.references-carousel')).display:null,featureBackdrop:document.querySelector('.feature-hero')?getComputedStyle(document.querySelector('.feature-hero'),'::before').display:null,contactImage:document.querySelector('.contact-scene__image')?getComputedStyle(document.querySelector('.contact-scene__image')).display:null,overflow:document.documentElement.scrollWidth>innerWidth};};
const before=await p.evaluate(snapshot);await p.screenshot({path:`output/mobile-clean-${name}-${width}.png`});
await p.evaluate(()=>scrollTo(0,220));await p.waitForTimeout(250);const after=await p.evaluate(snapshot);
if(JSON.stringify(before.header)!==JSON.stringify(after.header)||JSON.stringify(before.logo)!==JSON.stringify(after.logo)||JSON.stringify(before.toggle)!==JSON.stringify(after.toggle))throw Error('Header moves '+name+' '+width);
if(before.overflow)throw Error('Overflow '+name+' '+width);
console.log(width,name,{headerHeight:before.header.height,logoLeft:before.logo.left,logoTop:before.logo.top,toggleRight:before.toggle.right,carousel:before.carousel,heroBackdrop:before.featureBackdrop,contactImage:before.contactImage});
await p.locator('.site-header__toggle').click();await p.waitForTimeout(300);if(!await p.locator('.site-header__nav-cta').isVisible())throw Error('Missing mobile contact');await p.screenshot({path:`output/mobile-menu-${name}-${width}.png`});
}
}
// Follow an internal menu link with the persistent shared header.
await p.locator('.site-header__links a[href="/clients"]').click();await p.waitForURL('**/clients');await p.waitForFunction(()=>!document.documentElement.hasAttribute('aria-busy'));console.log('navigation menu closed',await p.locator('.site-header__nav').isVisible()===false);
await p.setViewportSize({width:1400,height:900});await p.goto('http://127.0.0.1:8088/',{waitUntil:'networkidle'});await p.evaluate(()=>scrollTo(0,180));await p.waitForTimeout(350);await p.screenshot({path:'output/home-transparent-logo-scroll.png'});console.log('desktop logo',await p.locator('.site-header__logo picture img').getAttribute('src'));
console.log('errors',errors);await b.close();
})().catch(e=>{console.error(e);process.exit(1)});
