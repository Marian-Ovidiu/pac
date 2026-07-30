import { test, expect } from '@playwright/test';

const requiredEnvironment = [
    'PAC_STAGING_URL',
    'PAC_STAGING_LOGIN_URL',
    'PAC_STAGING_USER',
    'PAC_STAGING_PASSWORD',
    'PAC_STAGING_PROJECT_URL',
    'PAC_STAGING_DONOR_EMAIL',
];

const missingEnvironment = requiredEnvironment.filter((name) => !process.env[name]);

if (process.env.PAC_REQUIRE_STAGING_UAT === '1' && missingEnvironment.length > 0) {
    throw new Error(`Missing required staging variables: ${missingEnvironment.join(', ')}`);
}

test.describe('PAC staging UAT', () => {
    test.skip(missingEnvironment.length > 0, `Missing staging variables: ${missingEnvironment.join(', ')}`);

    test('authenticated administrator can open the dashboard', async ({ page }) => {
        await page.goto(process.env.PAC_STAGING_LOGIN_URL);
        await page.locator('#user_login').fill(process.env.PAC_STAGING_USER);
        await page.locator('#user_pass').fill(process.env.PAC_STAGING_PASSWORD);
        await page.locator('#wp-submit').click();
        await page.goto('/wp-admin/');

        await expect(page.locator('#wpadminbar')).toBeVisible();
        await expect(page.locator('#wpwrap')).toBeVisible();
    });

    for (const path of ['/', '/galleria/', '/aziende/', '/grazie/']) {
        test(`public page ${path} has no critical browser errors`, async ({ page }) => {
            const criticalErrors = [];
            page.on('console', (message) => {
                if (message.type() === 'error') {
                    criticalErrors.push(message.text());
                }
            });
            page.on('pageerror', (error) => criticalErrors.push(error.message));

            const response = await page.goto(path);
            expect(response?.status()).toBeLessThan(400);
            expect(criticalErrors).toEqual([]);
        });
    }

    test('test-mode donation reaches the thank-you page', async ({ page }) => {
        const projectUrl = process.env.PAC_STAGING_PROJECT_URL;
        const donorEmail = process.env.PAC_STAGING_DONOR_EMAIL;

        await page.goto(projectUrl);

        const usesStripeTestMode = await page.evaluate(() => (
            typeof window.pacPayments?.publishableKey === 'string'
            && window.pacPayments.publishableKey.startsWith('pk_test_')
        ));
        expect(usesStripeTestMode, 'Donation E2E must never run with live Stripe keys.').toBe(true);

        const form = page.locator('[x-data="donationFormData"]').first();
        await form.getByRole('button', { name: '5 EUR' }).click();
        await form.getByRole('button', { name: 'Avanti' }).click();
        await form.locator('input[id^="donation-name-"]').fill('PAC');
        await form.locator('input[id^="donation-surname-"]').fill('UAT');
        await form.locator('input[id^="donation-email-"]').fill(donorEmail);
        await form.locator('input[id^="donation-phone-"]').fill('+39000000000');
        await form.getByRole('button', { name: 'Procedi al pagamento' }).click();

        const stripeFrame = page.frameLocator('iframe[title*="Secure payment input frame"]').first();
        await stripeFrame.locator('input[name="number"]').fill('4242424242424242');
        await stripeFrame.locator('input[name="expiry"]').fill('1234');
        await stripeFrame.locator('input[name="cvc"]').fill('123');
        await form.getByRole('button', { name: 'Dona ora' }).click();

        await expect(page).toHaveURL(/\/grazie\//);
    });
});
