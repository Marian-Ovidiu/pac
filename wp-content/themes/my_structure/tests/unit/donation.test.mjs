import test from 'node:test';
import assert from 'node:assert/strict';
import { readFile } from 'node:fs/promises';

const donationSource = await readFile(
    new URL('../../source/assets/js/donation.js', import.meta.url),
    'utf8',
);
const donationModuleUrl = `data:text/javascript;base64,${Buffer.from(donationSource).toString('base64')}`;
const { default: donationFormData } = await import(donationModuleUrl);

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
