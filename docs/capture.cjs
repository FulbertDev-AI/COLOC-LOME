const puppeteer = require('puppeteer-core');
const fs = require('fs');
const path = require('path');

const BASE = 'http://127.0.0.1:8080';
const OUT = path.join(__dirname, 'images');
const CHROME = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';

function sleep(ms) {
    return new Promise((r) => setTimeout(r, ms));
}

async function shot(page, url, file, wait = 900) {
    await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 20000 });
    await sleep(wait);
    await page.screenshot({
        path: path.join(OUT, file),
        fullPage: true,
        type: 'png',
    });
    console.log('OK', file);
}

async function login(page, email) {
    await page.goto(BASE + '/connexion', { waitUntil: 'domcontentloaded' });
    await page.$eval('input[name="email"]', (el) => { el.value = ''; });
    await page.$eval('input[name="password"]', (el) => { el.value = ''; });
    await page.type('input[name="email"]', email);
    await page.type('input[name="password"]', 'password');
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 15000 }).catch(() => {}),
        page.click('button[type="submit"]'),
    ]);
    await sleep(800);
}

(async () => {
    fs.mkdirSync(OUT, { recursive: true });
    const browser = await puppeteer.launch({
        executablePath: CHROME,
        headless: 'new',
        defaultViewport: { width: 1440, height: 900, deviceScaleFactor: 1 },
        args: ['--hide-scrollbars', '--disable-gpu'],
    });

    const publicPage = await browser.newPage();
    await shot(publicPage, BASE + '/', 'home.png');
    await shot(publicPage, BASE + '/recherche', 'search.png');
    await shot(publicPage, BASE + '/logement/1', 'listing.png');
    await shot(publicPage, BASE + '/connexion', 'login.png');
    await publicPage.close();

    const student = await browser.newPage();
    await login(student, 'etudiant@coloclome.tg');
    await shot(student, BASE + '/messages', 'messages.png', 1100);
    await shot(student, BASE + '/candidatures', 'applications.png', 1100);
    await shot(student, BASE + '/preferences', 'preferences.png', 900);
    await student.close();

    const host = await browser.newPage();
    await login(host, 'koffi.mensah@coloclome.tg');
    await shot(host, BASE + '/hote', 'host-dashboard.png', 1100);
    await shot(host, BASE + '/publier', 'publish.png', 900);
    await host.close();

    await browser.close();
})().catch((err) => {
    console.error(err);
    process.exit(1);
});
