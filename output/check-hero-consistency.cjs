const {chromium}=require('C:/Users/orian/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
const b=await chromium.launch({headless:true,channel:'chrome'});const p=await b.newPage({deviceScaleFactor:1.25});
for(const width of [1400,1000,1920,2560]){
await p.setViewportSize({width,height:900});let original,typography;
for(const [name,route] of [['home','/'],['about','/aboutUs'],['professions','/professions'],['clients','/clients'],['references','/references']]){
await p.goto('http://127.0.0.1:8088'+route,{waitUntil:'networkidle'});
if(name!=='home')await p.addStyleTag({content:'html {scrollbar-gutter:stable;overflow-y:scroll}'});
await p.evaluate(()=>document.fonts.ready);await p.waitForTimeout(100);
const m=await p.evaluate(()=>{const hero=document.querySelector('.home-scene,.feature-hero,.references-hero'),r=hero.getBoundingClientRect(),s=getComputedStyle(hero,'::before'),h=document.querySelector('h1'),hs=getComputedStyle(h),eye=document.querySelector('.feature-hero__eyebrow,.references-eyebrow'),desc=document.querySelector('.feature-hero__description,.references-hero__description');return {image:{left:r.left+parseFloat(s.left),top:r.top+scrollY+parseFloat(s.top),width:s.width,height:s.height,background:s.backgroundImage},type:eye?{titleSize:hs.fontSize,titleLine:hs.lineHeight,titleMargin:hs.marginBottom,eyebrowTop:eye.getBoundingClientRect().top+scrollY,eyebrowMargin:getComputedStyle(eye).marginBottom,titleTop:h.getBoundingClientRect().top+scrollY,descriptionTop:desc.getBoundingClientRect().top+scrollY}:null};});
if(name==='home')original=m.image;
else if(name!=='references'){if(JSON.stringify(m.image)!==JSON.stringify(original))throw Error('Image mismatch '+width+' '+name);}
if(name==='professions')typography=m.type;
if(name==='references'&&JSON.stringify(m.type)!==JSON.stringify(typography))throw Error('Typography mismatch '+width+' '+JSON.stringify({professions:typography,references:m.type}));
console.log(width,name,name==='references'?m.type:{imageLeft:m.image.left,imageTop:m.image.top});
if(width===1400||width===1000)await p.screenshot({path:`output/hero-consistent-${name}-${width}.png`});
}
}
await b.close();
})().catch(e=>{console.error(e);process.exit(1)});
