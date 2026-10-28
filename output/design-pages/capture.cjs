const { chromium } = require('C:/Users/orian/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
const fs = require('fs');
const path = require('path');
(async()=>{
 const out=__dirname; fs.mkdirSync(path.join(out,'captures'),{recursive:true});
 const browser=await chromium.launch({headless:true,channel:'chrome'});
 const page=await browser.newPage({viewport:{width:1400,height:720},deviceScaleFactor:1});
 const routes=[['accueil','/'],['qui-sommes-nous','/aboutUs'],['nos-metiers','/professions'],['nos-references','/references'],['nos-clients','/clients'],['nous-contacter','/contact'],['mentions-legales','/legalNotices'],['politique-confidentialite','/privacyPolicy']];
 const manifest=[];
 for(const [name,route] of routes){
  const response=await page.goto('http://127.0.0.1:8088'+route,{waitUntil:'networkidle'});
  await page.evaluate(async()=>{await document.fonts.ready;await Promise.all(Array.from(document.images).map(i=>i.decode().catch(()=>{})));});
  await page.waitForTimeout(650);
  await page.screenshot({path:path.join(out,'captures',name+'-1400x720.png')});
  await page.screenshot({path:path.join(out,'captures',name+'-page-complete.png'),fullPage:true});
  manifest.push({name,route,status:response.status(),title:await page.title()});
  console.log(name,response.status());
 }
 fs.writeFileSync(path.join(out,'captures','manifest.json'),JSON.stringify(manifest,null,2));
 await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
