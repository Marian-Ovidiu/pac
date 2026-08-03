import { test, expect } from '@playwright/test';

const routes = [
    ['home', '/'],
    ['missions', '/4-progetti-antibracconaggio-sociale/'],
    ['mission', '/progetto/antibracconaggio/'],
    ['companies', '/aziende/'],
    ['gallery', '/galleria/'],
    ['journal', '/diario-di-bordo/'],
    ['article', '/aiutare-il-ghana-il-nostro-impegno-sociale-contro-la-poverta-e-lemarginazione/'],
    ['thanks', '/grazie/'],
];

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

async function readHead(page) {
    return page.evaluate(() => {
        const meta = (name) => document.querySelector(`meta[name="${name}"]`)?.content ?? null;
        const graph = [];
        for (const node of document.querySelectorAll('script[type="application/ld+json"]')) {
            let parsed;
            try {
                parsed = JSON.parse(node.textContent);
            } catch {
                graph.push({ '@type': '__INVALID__' });
                continue;
            }
            for (const entry of parsed['@graph'] ?? [parsed]) graph.push(entry);
        }
        return {
            titles: [...document.querySelectorAll('title')].map((node) => node.textContent.trim()),
            canonicals: [...document.querySelectorAll('link[rel="canonical"]')].map((node) => node.href),
            robots: meta('robots'),
            description: meta('description'),
            types: graph.flatMap((entry) => [entry['@type']].flat()),
            graph,
        };
    });
}

for (const [name, path] of routes) {
    test(`${name} exposes a coherent indexing contract`, async ({ page }) => {
        const response = await page.goto(path, { waitUntil: 'domcontentloaded' });
        expect(response?.status(), `${name} status`).toBeLessThan(400);
        const head = await readHead(page);

        expect(head.titles, `${name} title count`).toHaveLength(1);
        expect(head.titles[0].length, `${name} title not empty`).toBeGreaterThan(0);
        expect(head.robots, `${name} robots meta`).toBeTruthy();

        // Rank Math omette il canonical sulle pagine noindex: è corretto, non va preteso.
        const expectedCanonicals = head.robots.includes('noindex') ? 0 : 1;
        expect(head.canonicals, `${name} canonical count`).toHaveLength(expectedCanonicals);
        expect(head.types, `${name} JSON-LD parses`).not.toContain('__INVALID__');
    });
}

test('descriptions are never cut mid-word', async ({ page }) => {
    for (const [name, path] of routes) {
        await page.goto(path, { waitUntil: 'domcontentloaded' });
        const { description } = await readHead(page);
        if (!description) continue;

        expect(description.length, `${name} description length`).toBeLessThanOrEqual(160);
        expect(description.trim(), `${name} description trailing punctuation`).not.toMatch(/[,;:–-]$/);
    }
});

test('the organisation is described as a non profit, not a company', async ({ page }) => {
    for (const [name, path] of routes) {
        await page.goto(path, { waitUntil: 'domcontentloaded' });
        const { types, graph } = await readHead(page);
        expect(types, `${name} no bare Organization`).not.toContain('Organization');

        const publisher = graph.find((entry) => [entry['@type']].flat().includes('NGO'));
        if (publisher) {
            expect(publisher.name, `${name} NGO name`).toBeTruthy();
        }
    }
});

test('project pages expose a donation action and a web page node', async ({ page }) => {
    await page.goto('/progetto/antibracconaggio/', { waitUntil: 'domcontentloaded' });
    const { types, graph } = await readHead(page);

    expect(types).toContain('NGO');
    expect(types).toContain('WebPage');
    expect(types).toContain('DonateAction');

    const donation = graph.find((entry) => [entry['@type']].flat().includes('DonateAction'));
    expect(donation.target.urlTemplate).toContain('/progetto/antibracconaggio');
    expect(donation.recipient['@id'], 'donation recipient is the organisation').toBeTruthy();
});

test('journal articles expose BlogPosting with dates and author', async ({ page }) => {
    await page.goto(routes.find(([name]) => name === 'article')[1], { waitUntil: 'domcontentloaded' });
    const { types, graph } = await readHead(page);

    expect(types).toContain('BlogPosting');
    const article = graph.find((entry) => [entry['@type']].flat().includes('BlogPosting'));
    expect(article.headline).toBeTruthy();
    expect(article.datePublished).toMatch(/^\d{4}-\d{2}-\d{2}/);
    expect(article.dateModified).toMatch(/^\d{4}-\d{2}-\d{2}/);
    expect(article.author.name, 'author is exposed').toBeTruthy();
    expect(article.publisher['@id'], 'publisher points at the organisation').toBeTruthy();
});

test('the thank-you page stays out of the index', async ({ page }) => {
    await page.goto('/grazie/', { waitUntil: 'domcontentloaded' });
    const { robots } = await readHead(page);
    expect(robots).toContain('noindex');
});
