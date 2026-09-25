import { chromium } from 'playwright';
const base=process.env.NEXA_TEST_BASE_URL||'http://127.0.0.1:8080';
const browser=await chromium.launch({headless:true});const page=await browser.newPage({viewport:{width:1440,height:1000}});
const must=async(c,n)=>{if(!c)throw new Error('FAIL '+n);console.log('PASS '+n)};
await page.goto(base+'/login.php');await page.locator('input[name=email]').fill('owner-ci@nexa.local');await page.locator('input[name=password]').fill('ProductionTest!2026');await page.locator('button[type=submit]').click();await page.waitForURL(/index\.php/);await must((await page.locator('body').innerText()).includes('NEXA'),'login owner');
const pages={dashboard:'Executive',companies:'Badan Usaha','daily-income':'Pendapatan Harian',transactions:'Transaksi',approvals:'Approval',arap:'AR / AP',reconciliation:'Rekonsiliasi',periods:'Closing',reports:'Laporan Keuangan',cashbank:'Cash & Bank',budget:'Budget',assets:'Aset',intercompany:'Intercompany',tax:'Pajak',fx:'Multi-Currency',analytics:'Analytics',integrations:'Integrasi',backup:'Backup',audit:'Audit',settings:'Pengaturan'};
for(const [slug,text] of Object.entries(pages)){await page.goto(base+`/index.php?page=${slug}`);await must((await page.locator('body').innerText()).includes(text),`page ${slug}`);}
await page.goto(base+'/index.php?page=dashboard');await must(await page.locator('canvas').count()>=2,'dashboard charts rendered');
await page.locator('#themeBtn').click();await must(await page.locator('body').evaluate(el=>el.classList.contains('dark')),'dark mode toggle');
await page.goto(base+'/index.php?page=daily-income');await must(await page.locator('#dailyIncomeCompany').count()===1,'daily income company selector');await must(await page.locator('.daily-income-value').count()>=1,'daily income categories render');
await page.screenshot({path:'artifacts/dashboard.png',fullPage:true});
await page.goto(base+'/index.php?page=reports&report_view=pl');await page.screenshot({path:'artifacts/report-pl.png',fullPage:true});
await browser.close();console.log('PASS browser E2E UAT');
