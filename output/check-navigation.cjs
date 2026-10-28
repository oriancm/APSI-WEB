const { chromium } = require('C:/Users/orian/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
 const browser=await chromium.launch({headless:true, channel:"chrome"});
 const page=await browser.newPage({viewport:{width:1400,height:900}});
 const errors=[];page.on('pageerror',e=>errors.push(e.message));
 await page.goto('http://127.0.0.1:8088/aboutUs');
 await page.waitForTimeout(500);
 await page.evaluate(()=>{window.originalHeader=document.getElementById('site-header');window.originalFooter=document.querySelector('.site-footer');window.testMarker=42;});
 for(const route of ['/clients','/contact','/professions','/references','/legalNotices','/privacyPolicy','/aboutUs','/']){
  await page.locator(`a[href="${route}"]`).first().click();
  await page.waitForURL('http://127.0.0.1:8088'+route);
  await page.waitForFunction(()=>!document.documentElement.hasAttribute('aria-busy'));
  console.log(route,await page.evaluate(()=>({persistent:window.originalHeader===document.getElementById('site-header'),footer:window.originalFooter===document.querySelector('.site-footer'),marker:window.testMarker,heading:document.querySelector('h1')?.textContent.trim(),navs:document.querySelectorAll('#site-header').length,overflow:document.documentElement.scrollWidth>innerWidth})));
 }
 await page.evaluate(()=>scrollTo(0,300));await page.waitForTimeout(200);
 await page.screenshot({path:'output/common-nav-home-scroll.png'});
 console.log('home-scroll',await page.evaluate(()=>({scrolled:document.querySelector('header').className,after:getComputedStyle(document.querySelector('header'),'::after').display,whiteLogo:getComputedStyle(document.querySelector('.site-header__logo-white')).opacity})));
 await page.goBack(); await page.waitForFunction(()=>!document.documentElement.hasAttribute('aria-busy'));console.log('back',await page.title());
 console.log('ERRORS',errors);
 await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});

