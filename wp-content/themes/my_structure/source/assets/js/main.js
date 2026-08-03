import Alpine from 'alpinejs';
import axios from 'axios';
import ApiService from './Classes/ApiService.js';
import donationFormData, { resumePendingDonation } from './donation.js';

function mobileNavigation() {
    return {
        open: false,
        previousFocus: null,
        init() {
            this.$watch('open', (isOpen) => {
                document.documentElement.classList.toggle('navigation-is-open', isOpen);
            });
        },
        show() {
            this.previousFocus = document.activeElement;
            this.open = true;
            this.$nextTick(() => this.getFocusable()[0]?.focus());
        },
        close() {
            this.open = false;
            this.$nextTick(() => (this.previousFocus || this.$refs.trigger)?.focus());
        },
        getFocusable() {
            if (!this.$refs.panel) return [];
            return [...this.$refs.panel.querySelectorAll(
                'a[href], button:not([disabled]), summary, input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])',
            )].filter((element) => element.getClientRects().length > 0);
        },
        handleKeydown(event) {
            if (event.key === 'Escape') {
                event.preventDefault();
                this.close();
                return;
            }
            if (event.key !== 'Tab') return;

            const focusable = this.getFocusable();
            if (!focusable.length) return;
            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        },
    };
}

function initializeFieldNoteReveal() {
    const elements = [...document.querySelectorAll('[data-reveal]')];
    if (!elements.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.12 });

    elements.forEach((element) => observer.observe(element));
}

function enhanceCookieInterface() {
    const banner = document.querySelector('#iubenda-cs-banner');
    if (banner) {
        const bannerIsVisible = banner.getClientRects().length > 0
            && window.getComputedStyle(banner).visibility !== 'hidden';
        document.documentElement.classList.toggle('cookie-banner-is-visible', bannerIsVisible);

        banner.querySelectorAll('.iubenda-cs-reject-btn, .iubenda-cs-accept-btn').forEach((button) => {
            button.style.setProperty('background', '#122018', 'important');
            button.style.setProperty('border-color', '#122018', 'important');
            button.style.setProperty('color', '#fffdf8', 'important');
            button.style.setProperty('opacity', '1', 'important');
            if (!button.dataset.pacCookieListener) {
                button.dataset.pacCookieListener = 'true';
                button.addEventListener('click', () => {
                    window.setTimeout(enhanceCookieInterface, 50);
                    window.setTimeout(enhanceCookieInterface, 350);
                });
            }
        });
        banner.querySelectorAll('.iubenda-cs-brand-badge, .iubenda-cs-brand-badge span').forEach((element) => {
            element.style.setProperty('background', '#fffdf8', 'important');
            element.style.setProperty('color', '#122018', 'important');
            element.style.setProperty('opacity', '1', 'important');
        });

        if (!banner.parentElement?.matches('[data-cookie-landmark]')) {
            const landmark = document.createElement('aside');
            landmark.setAttribute('aria-label', 'Preferenze cookie');
            landmark.setAttribute('data-cookie-landmark', '');
            banner.parentNode?.insertBefore(landmark, banner);
            landmark.appendChild(banner);
        }
    } else {
        document.documentElement.classList.remove('cookie-banner-is-visible');
    }

    const preferenceButton = document.querySelector('body > .iubenda-cs-preferences-link');
    if (preferenceButton) {
        const landmark = document.createElement('aside');
        landmark.setAttribute('aria-label', 'Gestione preferenze cookie');
        landmark.setAttribute('data-cookie-preferences-landmark', '');
        preferenceButton.parentNode?.insertBefore(landmark, preferenceButton);
        landmark.appendChild(preferenceButton);
    }
}

function initializeMobileDonationCta() {
    const cta = document.querySelector('.mobile-donation-cta');
    const donation = document.querySelector('#donation');
    if (!cta || !donation) return;

    const updateForFocus = () => {
        cta.classList.toggle('is-suppressed', donation.contains(document.activeElement));
    };
    donation.addEventListener('focusin', updateForFocus);
    donation.addEventListener('focusout', () => window.setTimeout(updateForFocus, 0));

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(([entry]) => {
            cta.classList.toggle('is-suppressed', entry.isIntersecting);
        }, { threshold: 0.08 });
        observer.observe(donation);
    }
}

enhanceCookieInterface();
const cookieObserver = new MutationObserver(enhanceCookieInterface);
cookieObserver.observe(document.documentElement, { childList: true, subtree: true });

window.donationFormData = donationFormData;
window.axios = axios;
window.Alpine = Alpine;
window.ApiService = ApiService;

Alpine.data('mobileNavigation', mobileNavigation);
Alpine.start();
resumePendingDonation();

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initializeFieldNoteReveal();
        initializeMobileDonationCta();
    }, { once: true });
} else {
    initializeFieldNoteReveal();
    initializeMobileDonationCta();
}
