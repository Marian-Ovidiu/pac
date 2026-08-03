import { test, expect } from '@playwright/test';
import {
    accesslintAudit,
    waitForPageSettle,
} from '../../../../../node_modules/@accesslint/playwright/dist/audit.js';

const routes = [
    ['home', '/'],
    ['projects', '/4-progetti-antibracconaggio-sociale/'],
    ['project', '/progetto/antibracconaggio/'],
    ['flagship project', '/progetto/tetto-scuola-ghana'],
    ['companies', '/aziende/'],
    ['gallery', '/galleria/'],
    ['journal', '/diario-di-bordo/'],
    ['article', '/aiutare-il-ghana-il-nostro-impegno-sociale-contro-la-poverta-e-lemarginazione/'],
    ['thanks', '/grazie/'],
];
const widths = [320, 390, 768, 1024, 1440];
const localUrl = new URL(process.env.PAC_LOCAL_URL || 'http://127.0.0.1:8080');

test.beforeEach(async ({ context }) => {
    await context.route('**/*', async (route) => {
        const requestUrl = new URL(route.request().url());
        if (requestUrl.hostname === 'pac.local') {
            requestUrl.protocol = localUrl.protocol;
            requestUrl.hostname = localUrl.hostname;
            requestUrl.port = localUrl.port;
            await route.continue({ url: requestUrl.toString() });
            return;
        }
        await route.continue();
    });
});

async function dismissCookieBanner(page) {
    const rejectButton = page.locator('#iubenda-cs-banner .iubenda-cs-reject-btn');
    if (await rejectButton.isVisible().catch(() => false)) {
        await rejectButton.click();
        await expect(page.locator('#iubenda-cs-banner')).toBeHidden();
    }
}

for (const width of widths) {
    test(`all main templates are stable at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 900 });

        for (const [name, path] of routes) {
            const consoleErrors = [];
            const failedLocalResources = [];
            // reCAPTCHA inserisce un iframe google.com e il browser segnala una
            // violazione CSP report-only sul framing, che compare o no a seconda
            // dei tempi di caricamento. È rumore di terze parti, non un errore del
            // tema, e rendeva questo test intermittente su viewport casuali.
            const isThirdPartyCspNoise = (text) => /report-only Content Security Policy/i.test(text)
                && /google\.com/i.test(text);
            const consoleListener = (message) => {
                if (message.type() === 'error' && !isThirdPartyCspNoise(message.text())) {
                    consoleErrors.push(message.text());
                }
            };
            const responseListener = (response) => {
                const url = response.url();
                if (
                    response.status() >= 400
                    && response.request().resourceType() !== 'document'
                    && (url.startsWith('http://127.0.0.1:8080') || url.includes('pac.local'))
                ) {
                    failedLocalResources.push(`${response.status()} ${url}`);
                }
            };
            page.on('console', consoleListener);
            page.on('response', responseListener);

            const response = await page.goto(path, { waitUntil: 'domcontentloaded' });
            expect(response?.status(), `${name} status`).toBeLessThan(400);
            await dismissCookieBanner(page);
            await page.waitForTimeout(150);

            await expect(page.locator('h1:visible'), `${name} must have exactly one visible H1`).toHaveCount(1);
            const pageAudit = await page.evaluate(() => ({
                scrollWidth: document.documentElement.scrollWidth,
                clientWidth: document.documentElement.clientWidth,
                brokenImages: [...document.images]
                    .filter((image) => image.complete && image.naturalWidth === 0)
                    .map((image) => image.currentSrc || image.src),
            }));
            expect(pageAudit.scrollWidth, `${name} horizontal overflow`).toBeLessThanOrEqual(pageAudit.clientWidth + 1);
            expect(pageAudit.brokenImages, `${name} broken images`).toEqual([]);
            expect(failedLocalResources, `${name} failed local resources`).toEqual([]);
            expect(consoleErrors, `${name} console errors`).toEqual([]);
            await page.screenshot({ path: testInfo.outputPath(`${width}-${name}.png`), fullPage: true });

            page.off('console', consoleListener);
            page.off('response', responseListener);
        }
    });
}

test('mobile menu traps focus, closes with Escape and returns focus', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/');
    await dismissCookieBanner(page);

    const trigger = page.getByRole('button', { name: 'Menu' });
    await trigger.click();
    const dialog = page.getByRole('dialog', { name: 'Menu principale' });
    await expect(dialog).toBeVisible();
    await expect(trigger).toHaveAttribute('aria-expanded', 'true');
    await expect(page.locator('html')).toHaveClass(/navigation-is-open/);
    await expect(page.getByRole('button', { name: 'Chiudi' })).toBeFocused();
    await page.keyboard.press('Shift+Tab');
    await expect(dialog.getByRole('link', { name: 'Sostieni una missione' })).toBeFocused();
    await page.keyboard.press('Tab');
    await expect(page.getByRole('button', { name: 'Chiudi' })).toBeFocused();
    await page.keyboard.press('Escape');
    await expect(dialog).toBeHidden();
    await expect(trigger).toBeFocused();
    await expect(trigger).toHaveAttribute('aria-expanded', 'false');
    await expect(page.locator('html')).not.toHaveClass(/navigation-is-open/);
});

test('keyboard focus is visible and the skip link reaches main content', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto('/');
    await dismissCookieBanner(page);
    await page.reload();
    await page.keyboard.press('Tab');

    const skipLink = page.getByRole('link', { name: 'Salta al contenuto principale' });
    await expect(skipLink).toBeFocused();
    await expect(skipLink).toBeVisible();
    const focusStyle = await skipLink.evaluate((element) => {
        const style = getComputedStyle(element);
        return { outline: style.outlineStyle, width: style.outlineWidth };
    });
    expect(focusStyle.outline).not.toBe('none');
    expect(focusStyle.width).not.toBe('0px');
    await skipLink.press('Enter');
    await expect(page.locator('#main-content')).toBeFocused();
});

test('cookie banner remains complete but does not consume the viewport', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/');
    const banner = page.getByRole('alertdialog', { name: 'Informativa' });
    await expect(banner).toBeVisible();
    await expect(banner.getByRole('button', { name: 'Scopri di più' })).toBeVisible();
    await expect(banner.getByRole('button', { name: 'Rifiuta tutto' })).toBeVisible();
    await expect(banner.getByRole('button', { name: 'Accetta tutto' })).toBeVisible();
    const contentHeight = await page.locator('#iubenda-cs-banner .iubenda-cs-content').evaluate((element) => element.getBoundingClientRect().height);
    expect(contentHeight).toBeLessThanOrEqual(844 * 0.58);
    await waitForPageSettle(page);
    const result = await accesslintAudit(page, { includeFrames: false, includeShadowDom: true });
    expect(result.violations, JSON.stringify(result.violations, null, 2)).toEqual([]);
});

test('donation validation retains the existing payment contract', async ({ page }) => {
    await page.setViewportSize({ width: 1024, height: 900 });
    await page.goto('/progetto/antibracconaggio/');
    await dismissCookieBanner(page);
    const form = page.locator('[x-data="donationFormData"]').first();
    await expect(form).toBeVisible();
    await form.getByRole('button', { name: 'Avanti' }).click();
    await expect(form.getByRole('alert')).toContainText('importo valido');
    await form.getByRole('button', { name: '5 EUR', exact: true }).click();
    await form.getByRole('button', { name: 'Avanti' }).click();
    await expect(form.locator('input[id^="donation-name-"]')).toHaveAccessibleName('Nome');
    await expect(form.locator('input[id^="donation-surname-"]')).toHaveAccessibleName('Cognome');
    await expect(form.locator('input[id^="donation-email-"]')).toHaveAccessibleName(/e-?mail/i);
    await expect(form.locator('input[id^="donation-phone-"]')).toHaveAccessibleName('Telefono');
    await expect(form.getByRole('button', { name: 'Procedi al pagamento' })).toBeDisabled();

    const contract = await page.evaluate(() => ({
        createIntent: window.pacPayments?.actions?.createIntent,
        complete: window.pacPayments?.actions?.complete,
        ajaxUrl: window.pacPayments?.ajaxUrl,
        init: document.querySelector('[x-data="donationFormData"]')?.getAttribute('x-init'),
    }));
    expect(contract.createIntent).toBe('pac_create_payment_intent');
    expect(contract.complete).toBe('pac_complete_donation');
    expect(contract.ajaxUrl).toContain('/wp-admin/admin-ajax.php');
    expect(contract.init).toContain('init(');
    expect(contract.init).toContain('/grazie/');
});

test('the flagship project explains need, objectives, transparent progress and expected impact', async ({ page }) => {
    await page.setViewportSize({ width: 1024, height: 900 });
    const response = await page.goto('/progetto/tetto-scuola-ghana', { waitUntil: 'domcontentloaded' });
    expect(response?.status()).toBe(200);
    await dismissCookieBanner(page);

    await expect(page.getByRole('heading', { level: 1, name: 'Una scuola al riparo dalla pioggia.' })).toBeVisible();
    await expect(page.getByRole('heading', { level: 2, name: 'Quando piove, imparare diventa difficile' })).toBeVisible();
    await expect(page.getByRole('heading', { level: 2, name: 'La continuità scolastica conta ogni giorno' })).toBeVisible();
    await expect(page.getByRole('heading', { level: 2, name: 'Cosa vogliamo realizzare' })).toBeVisible();
    await expect(page.getByRole('heading', { level: 2, name: 'A che punto siamo' })).toBeVisible();
    await expect(page.getByRole('heading', { level: 2, name: 'L’impatto atteso' })).toBeVisible();
    await expect(page.getByRole('heading', { level: 2, name: 'Aggiornamenti del progetto' })).toBeVisible();
    await expect(page.getByText('Raccolta attiva', { exact: true })).toBeVisible();
    await expect(page.getByText('10.000 EUR', { exact: true })).toBeVisible();
    await expect(page.locator('.project-progress progress')).toHaveAttribute('max', '10000');
    await expect(page.locator('.project-progress progress')).toHaveAttribute('value', '0');
    await expect(page.locator('[x-data="donationFormData"]').first()).toBeVisible();

    await waitForPageSettle(page);
    const result = await accesslintAudit(page, { includeFrames: false, includeShadowDom: true });
    const firstPartyViolations = result.violations.filter((violation) => !violation.selector.includes('iubenda.com/it/cookie-solution'));
    expect(firstPartyViolations, JSON.stringify(firstPartyViolations, null, 2)).toEqual([]);
});

test('partnership form exposes labelled fields and its response region', async ({ page }) => {
    await page.goto('/aziende/');
    await dismissCookieBanner(page);
    const form = page.locator('.partner-contact__form .wpcf7-form');
    await expect(form).toBeVisible();
    await expect(form.locator('input[name="your-name"]')).toHaveAccessibleName(/Nome/i);
    await expect(form.locator('input[name="your-email"]')).toHaveAccessibleName(/Email/i);
    await expect(form.locator('textarea[name="your-message"]')).toHaveAccessibleName(/Messaggio/i);
    await expect(form.locator('.wpcf7-response-output')).toHaveAttribute('aria-hidden', 'true');
});

test('thank-you page ignores query-string amount and personal data', async ({ page }) => {
    await page.goto('/grazie/?amount=999999&email=private%40example.test&name=Private');
    await dismissCookieBanner(page);
    await expect(page.locator('main')).not.toContainText('999999');
    await expect(page.locator('main')).not.toContainText('private@example.test');
    await expect(page.locator('main')).not.toContainText('Private');
    await expect(page.locator('h1:visible')).toHaveCount(1);
});

test('missing media use the PAC fallback without broken requests', async ({ page }) => {
    await page.goto('/galleria/');
    await dismissCookieBanner(page);
    const fallback = page.locator('.media-figure.is-missing .media-figure__fallback').first();
    await expect(fallback).toBeVisible();
    await expect(fallback).toHaveAttribute('role', 'img');
    await expect(fallback).toContainText('Documentazione fotografica non disponibile');
});

test('empty search and 404 expose intentional states', async ({ page }) => {
    await page.goto('/?s=nessun-contenuto-pac-xyz');
    await dismissCookieBanner(page);
    await expect(page.getByRole('heading', { level: 2, name: 'Nessun contenuto trovato' })).toBeVisible();
    const response = await page.goto('/pagina-inesistente-pac/');
    expect(response?.status()).toBe(404);
    await expect(page.getByRole('heading', { level: 1, name: 'La traccia si interrompe qui.' })).toBeVisible();
});

test('reduced motion removes authored reveal animation', async ({ browser }) => {
    const context = await browser.newContext({ reducedMotion: 'reduce' });
    const page = await context.newPage();
    await page.goto(process.env.PAC_LOCAL_URL || 'http://127.0.0.1:8080/');
    await dismissCookieBanner(page);
    const duration = await page.locator('[data-reveal]').first().evaluate((element) => getComputedStyle(element).animationDuration);
    expect(Number.parseFloat(duration)).toBeLessThanOrEqual(0.00001);
    await context.close();
});

test('AccessLint reports no WCAG A/AA violations on primary templates', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    for (const [name, path] of routes) {
        await page.goto(path, { waitUntil: 'domcontentloaded' });
        await dismissCookieBanner(page);
        await waitForPageSettle(page);
        const result = await accesslintAudit(page, { includeFrames: false, includeShadowDom: true });
        expect(result.violations, `${name}: ${JSON.stringify(result.violations, null, 2)}`).toEqual([]);
    }
});
