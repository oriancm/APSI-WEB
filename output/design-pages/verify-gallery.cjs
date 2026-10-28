const {chromium}=require('C:/Users/orian/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
const browser=await chromium.launch({headless:true,channel:'chrome'});
const page=await browser.newPage({viewport:{width:1400,height:900}});
const errors=[];page.on('pageerror',e=>errors.push(e.message));
const response=await page.goto('http://127.0.0.1:8088/output/design-pages/index.html');
await page.evaluate(async()=>{for(const i of document.images){i.loading='eager';}await Promise.all(Array.from(document.images).map(i=>i.decode().catch(()=>{})));});
const broken=await page.evaluate(()=>Array.from(document.images).filter(i=>!i.naturalWidth).map(i=>i.src));
await page.screenshot({path:__dirname+'/galerie-apercu.png',fullPage:true});
console.log(JSON.stringify({status:response.status(),images:await page.locator('img').count(),broken,errors}));
await browser.close();if(broken.length||errors.length)process.exit(1);
})().catch(e=>{console.error(e);process.exit(1)});
