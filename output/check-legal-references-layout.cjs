const {chromium}=require('C:/Users/orian/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
const b=await chromium.launch({headless:true,channel:'chrome'});const p=await b.newPage();const errors=[];p.on('pageerror',e=>errors.push(e.message));
for(const [width,height] of [[1400,720],[1920,1080],[1000,720],[390,800]]){
await p.setViewportSize({width,height});
for(const [name,route] of [['references','/references'],['legal','/legalNotices']]){
await p.goto('http://127.0.0.1:8088'+route,{waitUntil:'networkidle'});await p.evaluate(()=>document.fonts.ready);await p.waitForTimeout(900);
await p.screenshot({path:`output/${name}-compact-${width}.png`});
console.log(width,name,await p.evaluate(()=>({overflow:document.documentElement.scrollWidth>innerWidth,firstCardsBottom:Math.max(...[...document.querySelectorAll('.reference-tile')].slice(0,2).map(e=>e.getBoundingClientRect().bottom)),legalCards:[...document.querySelectorAll('.legal-card')].map(e=>({left:e.getBoundingClientRect().left,top:e.getBoundingClientRect().top,bottom:e.getBoundingClientRect().bottom})),heading:document.querySelector('h1').innerText,heroImage:getComputedStyle(document.querySelector('.feature-hero')||document.querySelector('.references-hero'),'::before').display})));
}
}
console.log('errors',errors);await b.close();
})().catch(e=>{console.error(e);process.exit(1)});
