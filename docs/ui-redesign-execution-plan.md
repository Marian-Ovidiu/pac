# PAC UI Redesign — Piano di esecuzione

Data baseline: 2026-07-30.

## Obiettivo

Implementare la direzione approvata “Quaderno di campo — impatto visibile” su
tutti i template pubblici del tema `my_structure`, preservando contenuti,
WordPress, ACF, Alpine e i contratti di `pac-core`.

## Stato attuale verificato

Stack:

- WordPress custom theme con Blade/Illuminate View;
- ACF per pagine strutturate e progetti;
- Vite 6, Sass e Tailwind 3;
- Alpine 3, Axios e Swiper;
- Stripe Payment Element caricato esternamente;
- `pac-core` per pagamenti, webhook, donatori ed email;
- Playwright installato nel tema;
- nessun AccessLint, axe, pa11y o Lighthouse dichiarato nel progetto.

Baseline automatica:

- 8 template × 5 viewport (320, 390, 768, 1024, 1440): 40 render HTTP 200;
- build Vite riuscita;
- 3 test JS donazione riusciti;
- 20 test PHP `pac-core` riusciti.

Finding iniziali:

- overflow Galleria a 320 px;
- due H1 in Galleria e quattro H1 nel singolo articolo;
- media rotti su Home, archivio progetti, singolo progetto e Diario;
- quattro attachment ACF mancanti fisicamente da `uploads`;
- fallback media del tema punta a un URL inesistente;
- Home, header, footer e archivio mostrano metriche/dispatch/badge hardcoded;
- Aziende contiene label inglesi e spazi logo non reali;
- Diario contiene autore hardcoded;
- Galleria e archivio progetti usano typewriter;
- slider Home e progetto usano autoplay;
- menu mobile basato su `details`, senza focus trap, Escape affidabile, focus
  return o scroll lock;
- banner Iubenda usa un container grande quanto il viewport e nasconde il
  contenuto su mobile;
- CSS globale monolitico: circa 7.576 righe e 169 kB non compressi;
- JS globale circa 100 kB non compresso; Swiper viene caricato globalmente;
- traduzioni duplicate presenti ma Polylang inattivo e flusso incompleto.

Le baseline sono in `/tmp/pac-ui-baseline/{width}/{template}.png`; il report DOM è
in `/tmp/pac-ui-baseline/audit.json`.

## Componenti coinvolti

Foundation:

- token, reset, tipografia, container, griglia, spacing, focus, reduced motion;
- button, link, input, textarea, select, checkbox, errori e stati loading;
- media responsive e fallback PAC.

Globali:

- BrandLockup, SiteHeader, MobileNavigation, SiteFooter;
- PageHero, SectionIntro, MediaFigure, CallToActionBand, EmptyState.

Contenuto e conversione:

- MissionCard, FieldNote, ProofList, ArticleTeaser;
- DonationPanel e PartnerContact.

## File principali

- `source/assets/scss/style.scss`
- `source/assets/js/main.js`
- `source/assets/js/donation.js`
- `source/assets/js/homeSlider.js`
- `source/assets/js/progettoSlider.js`
- `tailwind.config.js`
- `resources/views/layouts/mainLayout.blade.php`
- `resources/views/partials/header-menu.blade.php`
- `resources/views/partials/footer-menu.blade.php`
- `resources/views/components/*.blade.php`
- `resources/views/home.blade.php`
- `resources/views/archivio-progetto.blade.php`
- `resources/views/single-progetto.blade.php`
- `resources/views/aziende.blade.php`
- `resources/views/galleria.blade.php`
- `resources/views/archivio-post.blade.php`
- `resources/views/singolo-post.blade.php`
- `resources/views/grazie.blade.php`
- controller e helper del tema soltanto quando necessari a dati/media/stati;
- nuovi template 404/empty state e test Playwright locali.

## Rischi

1. Modifiche visuali al form potrebbero rompere selettori o binding Alpine.
2. Media locali assenti impediscono una validazione fotografica definitiva.
3. Il field group Aziende non è affidabile; il form CF7 può non essere presente.
4. Iubenda è markup di terza parte: il tema può limitarne l'invasività, ma non
   sostituirne il comportamento legale.
5. Lo stato multilingua è incompleto; un selettore lingua sempre visibile sarebbe
   ingannevole.
6. Il CSS legacy può causare collisioni durante una migrazione incrementale.
7. Stripe locale non dispone necessariamente di chiavi test, quindi il browser
   può verificare il contratto e gli stati pre-payment ma non un pagamento reale.
8. Performance e Core Web Vitals definitivi richiedono staging, media reali e un
   audit Lighthouse su hosting comparabile alla produzione.

## Dipendenze e decisioni conservative

- Nessuna nuova dipendenza frontend.
- `pac-core` non viene modificato.
- I campi ACF esistenti vengono riusati; le sezioni senza contenuto non rendono.
- Il lockup testuale resta il fallback logo ufficiale.
- Nessuna metrica appare se non proviene da contenuto verificato.
- Le lingue appaiono soltanto se Polylang restituisce collegamenti reali.
- Il fallback media è una superficie PAC esplicita, non una fotografia simulata.

## Sequenza di implementazione

### Tranche 1 — Foundation e shell

1. Nuovo layer di token e componenti condivisi.
2. Helper e componente media con fallback affidabile.
3. Layout base, font consentiti e direction contract.
4. Header/footer e menu mobile accessibile.
5. Build, browser desktop/mobile, tastiera e screenshot.

### Tranche 2 — Conversione e missioni

1. Home.
2. Archivio progetti.
3. Singolo progetto.
4. DonationPanel senza alterare binding o payload.
5. Test unitari, PHP, Playwright e screenshot.

### Tranche 3 — Contenuti secondari

1. Aziende e form CF7.
2. Galleria senza typewriter/autoplay e senza vuoti.
3. Diario e singolo articolo.
4. Grazie.
5. Test, browser e screenshot.

### Tranche 4 — Stati e hardening

1. 404, ricerca/archivio vuoto e media fallback.
2. Stati partnership e donazione esposti dal frontend.
3. Cookie banner responsive.
4. Reduced motion, focus, errori, loading e contenuto non tradotto.
5. Test dedicati e audit WCAG.

### Tranche 5 — Critica, polish e performance

1. Critica Impeccable contro `DESIGN.md`.
2. Correzione unica dei finding materiali.
3. Seconda e ultima acquisizione visuale completa.
4. Audit finale, detector Impeccable, build e test.
5. Documentazione di media, dati, rischi e azioni staging residue.

## Test richiesti

Automatici:

- `npm test`;
- `npm run build`;
- lint PHP del codice custom;
- bootstrap WordPress;
- Playwright a 320, 390, 768, 1024 e 1440 px;
- un H1 visibile, lang, landmark, assenza overflow e media rotti;
- console/page errors e richieste locali fallite;
- menu mobile: apertura, focus trap, Escape, focus return e scroll lock;
- media fallback;
- empty state e 404;
- form partnership presente o stato onesto;
- form donazione: step, validazione, loading ed errori senza modifiche al payload;
- pagina Grazie priva di dati query non attendibili;
- `prefers-reduced-motion`.

Manuali:

- ordine heading e lettura;
- contrasto e focus;
- tastiera completa;
- touch target e CTA sticky;
- banner cookie;
- ritmo, allineamenti e densità;
- fallback in assenza di contenuti e immagini.

## Criteri di completamento

- Tutti gli otto template principali e gli stati speciali usano lo stesso sistema.
- Un solo H1 visibile, nessun overflow e nessun media rotto.
- Menu mobile accessibile e verificato da tastiera.
- Nessun dato hardcoded presentato come reale e nessun inglese spurio.
- Form donazione e `pac-core` senza regressioni; suite completa verde.
- Build di produzione riuscita e asset dal manifest Vite.
- Due round visuali reali completati secondo il limite Impeccable.
- Audit automatico/manuale documentato, senza violazioni note nascoste.
- Target performance/accessibilità staging misurato oppure residuo spiegato con
  causa precisa e azione richiesta.

## Verifica finale locale — 2026-07-30

Implementazione verificata:

- build Vite riuscita; CSS principale 47,53 kB (10,30 kB gzip), JS principale
  103,18 kB (37,46 kB gzip);
- 5 test unitari JavaScript e 20 test PHP `pac-core`: tutti riusciti;
- lint PHP riuscito su tema, plugin e dipendenze PHP incluse nel plugin;
- 15 test Playwright UI riusciti in Chromium;
- 8 template principali × 5 larghezze: 40 screenshot full-page reali;
- nessun overflow, immagine rotta, errore console rilevante o richiesta locale
  fallita nei template verificati;
- menu mobile verificato per focus trap, Escape, focus return e scroll lock;
- CTA donazione mobile soppressa quando il form o il banner cookie occupano il
  viewport;
- form partnership con label reali e regione di risposta;
- contratto DOM/AJAX della donazione preservato e validazione browser verificata;
- pagina Grazie non usa importi o dati personali da query string;
- fallback media, 404, ricerca vuota e reduced motion verificati;
- AccessLint WCAG A/AA: zero violazioni sui template principali e sul banner
  cookie, senza regole disabilitate;
- detector Impeccable eseguito una sola volta sulle view: zero finding;
- critica Impeccable archiviata in `.impeccable/critique/` con punteggio 24/32.

Misure locali Playwright, non equivalenti a Lighthouse staging:

| Superficie | Viewport | LCP locale | CLS |
|---|---:|---:|---:|
| Home | 390 px | 328 ms | 0 |
| Missione | 390 px | 192 ms | 0,0152 |
| Home | 1440 px | 180 ms | 0,0006 |
| Missione | 1440 px | 200 ms | 0,0553 |

Tutti i CLS locali sono sotto 0,1. Questi valori beneficiano del server locale,
della cache e dei fallback leggeri: non dimostrano i target di staging.

Screenshot finali:

- `wp-content/themes/my_structure/test-results/local-ui-all-main-templates-are-stable-at-{width}px-chromium/`;
- nomi: `{width}-{home|projects|project|companies|gallery|journal|article|thanks}.png`;
- viewport: 320, 390, 768, 1024 e 1440 px.

## Addendum media — 2026-08-03

Dopo la verifica del 30 luglio è stata completata l'integrazione degli asset
illustrativi generati, documentata in `docs/media-asset-plan.md`. Otto slot
approvati usano ora derivati responsive AVIF/WebP/JPEG al posto del fallback; gli
slot documentali di galleria e diario restano volutamente sul fallback PAC.

Suite ricontrollata per intero con PHP 8.3: build Vite riuscita, 5 test JS, 20
test PHP `pac-core` e **27 test Playwright** (`media-assets`, `local-ui`,
`local-performance`), tutti verdi. I 15 test UI citati sopra sono confluiti nel
totale insieme ai nuovi spec sui media.

## Residui prima del rilascio

1. Ripristinare gli upload ACF mancanti, almeno
   `2026/04/Paesaggio-Vilaggio-Africa.png` e
   `2026/04/Primo-Piano-Ranger.png`, e confermare gli altri master fotografici.
   Il fallback PAC evita media rotti ma non sostituisce la fotografia reale.
2. Fornire il master ufficiale del lockup PAC. Il pittogramma esistente e il
   lockup testuale sono preservati come fallback, senza ridisegno.
3. Eseguire su staging un pagamento Stripe test completo: successo, rifiuto,
   webhook, redirect perso, recupero, email e pagina Grazie.
4. Eseguire Lighthouse sullo staging con media reali e verificare accessibilità
   >= 95, performance >= 90, CLS < 0,1 e LCP < 2,5 s.
5. Verificare invio reale del form partnership e deliverability email sul server.
6. Completare contenuti e permalink tradotti prima di attivare il selettore lingua.
7. La pagina categoria vuota segue oggi il fallback 404 perché l'archivio
   categorie non è un percorso editoriale pubblicato; la manutenzione resta
   responsabilità del relativo plugin/hosting.
8. Archiviare fuori dal repository i master 4:3 in `assets/masters/`: non sono
   versionati, non sono riproducibili identici dai prompt e senza backup i crop
   non sono più rifacibili.
9. Valutare la rigenerazione di `pac-mission-habitat-illustrative`: condivide il
   soggetto savana/acacia con l'hero della Home e i due asset convivono nella
   stessa pagina. Dettagli in `docs/media-asset-plan.md`.
