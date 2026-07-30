import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
    testDir: './tests/e2e',
    timeout: 90_000,
    expect: { timeout: 15_000 },
    fullyParallel: false,
    retries: 1,
    reporter: [['list'], ['html', { open: 'never' }]],
    use: {
        baseURL: process.env.PAC_STAGING_URL,
        trace: 'retain-on-failure',
        screenshot: 'only-on-failure',
        video: 'retain-on-failure',
    },
    projects: [
        {
            name: 'chromium',
            use: { ...devices['Desktop Chrome'] },
        },
    ],
});
