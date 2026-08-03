import { test, expect } from '@playwright/test';

const routes = [
    ['home', '/', 5],
    ['missions', '/4-progetti-antibracconaggio-sociale/', 5],
    ['mission', '/progetto/antibracconaggio/', 1],
    ['companies', '/aziende/', 1],
    ['gallery', '/galleria/', 0],
    ['journal', '/diario-di-bordo/', 0],
    ['article', '/aiutare-il-ghana-il-nostro-impegno-sociale-contro-la-poverta-e-lemarginazione/', 0],
    ['thanks', '/grazie/', 1],
];
const widths = [320, 390, 768, 1024, 1440];
const aiCaption = 'Immagine illustrativa generata con IA.';

test.beforeEach(async ({ context }) => {
    await context.route('**/*', async (route) => {
        const requestUrl = new URL(route.request().url());
        if (requestUrl.hostname === 'pac.local') {
            requestUrl.protocol = 'http:';
            requestUrl.hostname = '127.0.0.1';
            requestUrl.port = '8080';
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
    test(`media integration is stable at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 900 });

        for (const [name, path, expectedGenerated] of routes) {
            const failedThemeAssets = [];
            const listener = (response) => {
                if (response.status() >= 400 && response.url().includes('/wp-content/themes/my_structure/')) {
                    failedThemeAssets.push(`${response.status()} ${response.url()}`);
                }
            };
            page.on('response', listener);

            const response = await page.goto(path, { waitUntil: 'networkidle' });
            expect(response?.status(), `${name} status`).toBeLessThan(400);
            await dismissCookieBanner(page);
            await page.locator('main').waitFor();

            const generated = page.locator('[data-generated-illustrative="true"]');
            await expect(generated, `${name} generated asset count`).toHaveCount(expectedGenerated);
            for (let index = 0; index < expectedGenerated; index += 1) {
                const image = generated.nth(index).locator('img');
                await image.scrollIntoViewIfNeeded();
                await expect(image, `${name} generated image ${index + 1}`).toHaveJSProperty('complete', true);
                await expect.poll(() => image.evaluate((element) => element.naturalWidth)).toBeGreaterThan(0);
            }

            const audit = await page.evaluate(() => ({
                scrollWidth: document.documentElement.scrollWidth,
                clientWidth: document.documentElement.clientWidth,
                brokenImages: [...document.images]
                    .filter((image) => image.complete && image.naturalWidth === 0)
                    .map((image) => image.currentSrc || image.src),
                generated: [...document.querySelectorAll('[data-generated-illustrative="true"]')].map((figure) => {
                    const image = figure.querySelector('img');
                    const frame = figure.querySelector('.media-figure__frame');
                    const imageRect = image?.getBoundingClientRect();
                    const frameRect = frame?.getBoundingClientRect();
                    return {
                        asset: figure.dataset.mediaAsset,
                        source: image?.currentSrc || '',
                        naturalWidth: image?.naturalWidth || 0,
                        naturalHeight: image?.naturalHeight || 0,
                        imageWidth: imageRect?.width || 0,
                        imageHeight: imageRect?.height || 0,
                        frameWidth: frameRect?.width || 0,
                        frameHeight: frameRect?.height || 0,
                        alt: image?.getAttribute('alt'),
                    };
                }),
            }));

            expect(audit.scrollWidth, `${name} horizontal overflow`).toBeLessThanOrEqual(audit.clientWidth + 1);
            expect(audit.brokenImages, `${name} broken images`).toEqual([]);
            expect(failedThemeAssets, `${name} failed theme assets`).toEqual([]);

            for (const media of audit.generated) {
                expect(media.source, `${media.asset} responsive source`).toMatch(/\.(avif|webp|jpg)$/);
                expect(media.naturalWidth, `${media.asset} natural width`).toBeGreaterThan(0);
                expect(media.naturalHeight, `${media.asset} natural height`).toBeGreaterThan(0);
                expect(media.imageWidth, `${media.asset} image width`).toBeGreaterThanOrEqual(media.frameWidth - 1);
                expect(media.imageHeight, `${media.asset} image height`).toBeGreaterThanOrEqual(media.frameHeight - 1);
            }

            if (name === 'home' || name === 'missions' || name === 'mission') {
                await expect(page.getByText(aiCaption, { exact: true }).first()).toBeVisible();
            }
            if (name === 'companies') {
                await expect(generated.locator('img')).toHaveAttribute('alt', '');
            }
            if (name === 'thanks') {
                await expect(generated.locator('img')).toHaveAttribute('alt', '');
            }
            if (name === 'gallery') {
                await expect(page.locator('.media-figure.is-missing')).toHaveCount(1);
            }

            await page.screenshot({
                path: testInfo.outputPath(`media-${width}-${name}.png`),
                fullPage: true,
            });
            page.off('response', listener);
        }
    });
}

test('mission assets stay distinct and card crops use responsive derivatives', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto('/4-progetti-antibracconaggio-sociale/', { waitUntil: 'networkidle' });
    await dismissCookieBanner(page);

    const assets = await page.locator('.mission-card [data-media-asset]').evaluateAll((figures) => figures.map((figure) => ({
        asset: figure.dataset.mediaAsset,
        source: figure.querySelector('img')?.currentSrc || '',
    })));
    expect(assets).toHaveLength(4);
    expect(new Set(assets.map(({ asset }) => asset)).size).toBe(4);
    for (const { source } of assets) expect(source).toContain('-card-');
});

test('illustrative and decorative alt text remains honest', async ({ page }) => {
    await page.goto('/');
    await dismissCookieBanner(page);
    const alts = await page.locator('[data-generated-illustrative="true"] img').evaluateAll((images) => images.map((image) => image.alt));
    expect(alts).toEqual([
        'Paesaggio illustrativo con sentiero tra erbe e un’acacia.',
        'Tavolo vuoto con sedute e quaderni chiusi in uno spazio ombreggiato.',
        'Impronte canine e di scarponi affiancate su terreno asciutto.',
        'Erba alta e alberi di acacia in un paesaggio illustrativo.',
        'Quaderno bianco e matita su un tavolo illuminato da ombre di foglie.',
    ]);

    await page.goto('/aziende/');
    await dismissCookieBanner(page);
    await expect(page.locator('[data-generated-illustrative="true"] img')).toHaveAttribute('alt', '');
    await page.goto('/grazie/');
    await dismissCookieBanner(page);
    await expect(page.locator('[data-generated-illustrative="true"] img')).toHaveAttribute('alt', '');
});

test('responsive formats keep the downloaded home payload bounded', async ({ page }) => {
    for (const viewport of [
        { name: 'mobile', width: 390, height: 844 },
        { name: 'desktop', width: 1440, height: 900 },
    ]) {
        await page.setViewportSize(viewport);
        const responses = new Map();
        const listener = (response) => {
            if (response.url().includes('/assets/media/generated/')) responses.set(response.url(), response);
        };
        page.on('response', listener);
        await page.goto('/', { waitUntil: 'networkidle' });
        await dismissCookieBanner(page);

        const images = page.locator('[data-generated-illustrative="true"] img');
        for (let index = 0; index < await images.count(); index += 1) {
            await images.nth(index).scrollIntoViewIfNeeded();
            await expect.poll(() => images.nth(index).evaluate((element) => element.naturalWidth)).toBeGreaterThan(0);
        }
        await page.waitForTimeout(100);

        let bytes = 0;
        for (const response of responses.values()) bytes += (await response.body()).byteLength;
        const sources = await images.evaluateAll((elements) => elements.map((element) => element.currentSrc));
        console.log(`[PAC media] home/${viewport.name}`, { bytes, requests: responses.size, sources });
        expect(responses.size).toBe(5);
        expect(bytes).toBeLessThan(650_000);
        expect(sources.every((source) => source.endsWith('.avif'))).toBe(true);
        page.off('response', listener);
    }
});
