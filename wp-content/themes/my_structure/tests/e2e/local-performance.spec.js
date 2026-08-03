import { expect, test } from '@playwright/test';

const routes = [
    { name: 'home', path: '/' },
    { name: 'project', path: '/progetto/sociale-nigeria/' },
];

const viewports = [
    { name: 'mobile', width: 390, height: 844 },
    { name: 'desktop', width: 1440, height: 1000 },
];

for (const viewport of viewports) {
    for (const route of routes) {
        test(`${route.name} exposes stable local web vitals on ${viewport.name}`, async ({ page }) => {
            await page.setViewportSize(viewport);
            await page.addInitScript(() => {
                window.__pacVitals = { cls: 0, lcp: 0 };

                new PerformanceObserver((list) => {
                    const entries = list.getEntries();
                    const latest = entries.at(-1);
                    if (latest) window.__pacVitals.lcp = latest.startTime;
                }).observe({ type: 'largest-contentful-paint', buffered: true });

                new PerformanceObserver((list) => {
                    list.getEntries().forEach((entry) => {
                        if (!entry.hadRecentInput) window.__pacVitals.cls += entry.value;
                    });
                }).observe({ type: 'layout-shift', buffered: true });
            });

            await page.goto(route.path, { waitUntil: 'networkidle' });
            await page.waitForTimeout(2500);

            const metrics = await page.evaluate(() => {
                const navigation = performance.getEntriesByType('navigation')[0];
                return {
                    ...window.__pacVitals,
                    domContentLoaded: navigation?.domContentLoadedEventEnd ?? 0,
                    load: navigation?.loadEventEnd ?? 0,
                };
            });

            console.log(`[PAC performance] ${route.name}/${viewport.name}`, metrics);
            expect(metrics.cls).toBeLessThan(0.1);
            expect(metrics.lcp).toBeGreaterThan(0);
        });
    }
}
