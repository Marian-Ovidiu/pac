# PAC — istruzioni di progetto

WordPress con tema custom `my_structure` (Blade/Illuminate View, Vite 6, Sass,
Tailwind 3, Alpine 3, Swiper) e plugin `pac-core` per pagamenti Stripe, webhook,
donatori ed email.

## Runtime

`pac-core` richiede **PHP >= 8.1**; il progetto è verificato su **8.3**. Su questa
macchina `php` nel PATH può risolvere a 7.4: in quel caso WordPress va in fatal
error nel platform check di Composer. Il binario 8.3 è
`/opt/homebrew/Cellar/php@8.3/8.3.31/bin/php`.

## Server locale

Dalla radice del repository, con PHP 8.3 attivo:

```
php -S 127.0.0.1:8080 router.php
```

`router.php` serve i file esistenti e passa tutto il resto a `index.php`, così i
permalink funzionano senza Apache. `wp-config.php` è locale e non versionato.

**Il server integrato di PHP è single-thread.** Playwright esegue più spec file
in parallelo e la contesa rende `local-ui.spec.js` intermittente, su un viewport
diverso a ogni run. Due contromisure, entrambe valide:

```
PHP_CLI_SERVER_WORKERS=4 php -S 127.0.0.1:8080 router.php   # server concorrente
npx playwright test tests/e2e/ --workers=1                  # verificato verde 2 run su 2
```

## Test

Da `wp-content/themes/my_structure/`:

```
npm run build
npm test                    # 5 test JS + 20 test PHP pac-core
PAC_LOCAL_URL=http://127.0.0.1:8080 npx playwright test tests/e2e/
```

Gli spec e2e richiedono il server locale già avviato. `local-ui.spec.js` include
l'audit AccessLint WCAG A/AA; `media-assets.spec.js` verifica conteggi, crop,
formati e payload degli asset generati.

## Vincoli editoriali sui media

Gli asset in `wp-content/themes/my_structure/assets/media/generated/` sono
**illustrativi e temporanei**: non sono prova di attività PAC. Regole in
`docs/media-asset-plan.md`, da rispettare in ogni modifica:

- gli slot documentali (galleria, featured image del diario) non ricevono
  immagini generate, mai: usano il fallback PAC finché non arrivano foto reali;
- gli asset fotorealistici accanto a una missione portano la caption
  "Immagine illustrativa generata con IA.";
- gli asset puramente decorativi (aziende, grazie) hanno `alt=""`;
- gli ACF reali hanno sempre la precedenza tramite `theme_media_or_generated()`.

I master 4:3 in `assets/masters/` non sono versionati (~51 MB) e non sono
riproducibili identici dai prompt: vanno conservati fuori dal repository.

## Convenzioni

- `pac-core` non viene modificato durante il lavoro sul tema; il contratto
  DOM/AJAX del form donazione va preservato.
- Nessuna metrica o dato viene mostrato come reale se non proviene da contenuto
  verificato.
- Il selettore lingua resta nascosto finché Polylang non restituisce permalink
  tradotti reali.
- Gli asset Vite in `public/` restano versionati; `resources/cache/`,
  `test-results/` e `playwright-report/` no.

## Documentazione

- `docs/ui-redesign-execution-plan.md` — redesign UI, stato e residui pre-rilascio.
- `docs/media-asset-plan.md` — inventario slot, selezione asset e verifiche.
- `docs/media-prompts.md` — prompt di generazione per slot.
- `DESIGN.md` e `docs/ui-ux-art-direction.md` — direzione visiva approvata.
