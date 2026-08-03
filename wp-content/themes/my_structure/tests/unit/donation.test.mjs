import test from 'node:test';
import assert from 'node:assert/strict';
import { readFile } from 'node:fs/promises';

const donationSource = await readFile(
    new URL('../../source/assets/js/donation.js', import.meta.url),
    'utf8',
);
const donationModuleUrl = `data:text/javascript;base64,${Buffer.from(donationSource).toString('base64')}`;
const { default: donationFormData, resumePendingDonation } = await import(donationModuleUrl);

function createSessionStorage(initial = {}) {
    const entries = new Map(Object.entries(initial));

    return {
        getItem: (key) => entries.get(key) ?? null,
        setItem: (key, value) => entries.set(key, value),
        removeItem: (key) => entries.delete(key),
        has: (key) => entries.has(key),
    };
}

test('converts preset and custom donation amounts to cents', () => {
    const form = donationFormData(42, '/grazie/');

    form.selectedAmount = 25;
    assert.equal(form.getAmountInCents(), 2500);

    form.customAmount = '12.34';
    assert.equal(form.getAmountInCents(), 1234);
});

test('rejects empty, negative and non-numeric amounts', () => {
    const form = donationFormData(42, '/grazie/');

    for (const amount of ['', '-1', 'invalid']) {
        form.customAmount = amount;
        assert.equal(form.getAmountInCents(), 0);
        assert.equal(form.isAmountValid(), false);
    }
});

test('builds a normalized donor payload', () => {
    const form = donationFormData(42, '/grazie/');
    form.selectedAmount = 5;
    form.formData = {
        name: ' Ada ',
        surname: ' Lovelace ',
        phone: ' +390000 ',
        email: ' ada@example.test ',
        codiceFiscale: ' ABC ',
    };

    assert.deepEqual(form.buildDonationPayload(), {
        progetto_id: 42,
        expected_amount_cents: 500,
        name: 'Ada',
        surname: 'Lovelace',
        phone: '+390000',
        email: 'ada@example.test',
        codice_fiscale: 'ABC',
    });

    assert.deepEqual(form.buildCreateIntentPayload(), {
        progetto_id: 42,
        expected_amount_cents: 500,
        amount_cents: 500,
        name: 'Ada',
        surname: 'Lovelace',
        phone: '+390000',
        email: 'ada@example.test',
        codice_fiscale: 'ABC',
    });
});

test('exposes a rejected Stripe payment and clears pending state', async () => {
    const sessionStorage = createSessionStorage();
    global.window = {
        sessionStorage,
        location: { origin: 'https://pac.test', href: 'https://pac.test/progetto/test/' },
        pacPayments: {},
    };

    const form = donationFormData(42, '/grazie/');
    form.selectedAmount = 5;
    form.formData = {
        name: 'Ada', surname: 'Lovelace', phone: '+390000', email: 'ada@example.test', codiceFiscale: '',
    };
    form.elements = {};
    form.stripe = {
        confirmPayment: async () => ({ error: { message: 'Carta rifiutata.' } }),
    };

    const originalConsoleError = console.error;
    console.error = () => {};
    try {
        await form.submitForm();
    } finally {
        console.error = originalConsoleError;
    }

    assert.equal(form.loading, false);
    assert.equal(form.statusMessage, '');
    assert.equal(form.errorMessage, 'Carta rifiutata.');
    assert.equal(sessionStorage.has('pac_pending_donation'), false);
});

test('recovers a pending redirected donation through the existing completion action', async () => {
    const calls = [];
    const sessionStorage = createSessionStorage({
        pac_pending_donation: JSON.stringify({
            progetto_id: 42,
            expected_amount_cents: 500,
            name: 'Ada',
            surname: 'Lovelace',
            email: 'ada@example.test',
            phone: '+390000',
            codice_fiscale: '',
        }),
    });
    global.document = { title: 'PAC' };
    global.window = {
        sessionStorage,
        location: {
            origin: 'https://pac.test',
            href: 'https://pac.test/progetto/test/?payment_intent=pi_test&redirect_status=succeeded',
            search: '?payment_intent=pi_test&redirect_status=succeeded',
        },
        history: { replaceState: (...args) => calls.push(['replaceState', ...args]) },
        pacPayments: {
            ajaxUrl: 'https://pac.test/wp-admin/admin-ajax.php',
            nonce: 'nonce',
            actions: { complete: 'pac_complete_donation' },
        },
        ApiService: class {
            async postForm(url, payload) {
                calls.push(['postForm', url, payload]);
                return { success: true, data: { completed: true } };
            }
        },
    };

    await resumePendingDonation();

    const postCall = calls.find(([kind]) => kind === 'postForm');
    assert.equal(postCall[1], 'https://pac.test/wp-admin/admin-ajax.php');
    assert.equal(postCall[2].action, 'pac_complete_donation');
    assert.equal(postCall[2].payment_intent_id, 'pi_test');
    assert.equal(sessionStorage.has('pac_pending_donation'), false);
    assert.ok(calls.some(([kind]) => kind === 'replaceState'));
});
