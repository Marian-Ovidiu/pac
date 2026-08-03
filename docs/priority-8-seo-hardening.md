# Priorità 8: SEO tecnico — audit e hardening

Data audit: 2026-08-03. Verificato sull'HTML renderizzato dal server locale
(`127.0.0.1:8080`, PHP 8.3), non solo sul codice sorgente.

## Premessa sull'ambiente

Il database locale è un dump parziale: contiene **5 attachment, di cui 1 solo con
file su disco**. Di conseguenza alcuni sintomi osservati in locale **non sono bug**
e non vanno inseguiti:

- `canonical`, `og:url` e gli `@id` dello schema puntano a `127.0.0.1:8080`
  perché `siteurl` è `pac.local`;
- i media rotti e l'assenza di `og:image` renderizzato dipendono dagli upload
  mancanti, non dalla configurazione.

Ogni finding qui sotto è marcato come **codice** (versionabile e deployabile) o
**configurazione** (opzione nel DB WordPress: va rifatta a mano in produzione,
perché il DB locale non viene promosso).

## Cosa funziona già

Rank Math è attivo e copre correttamente la base:

- `canonical` presente e coerente su tutti i template verificati;
- `title` unici e descrittivi, con `%sep% %sitename%` su pagine, progetti e post;
- `meta robots` `index, follow` con `max-image-preview:large`;
- `BreadcrumbList` su contenuti singoli;
- `Organization`, `WebSite` e `SearchAction` sulla home; `CollectionPage` sul Diario;
- sitemap index funzionante per `page` e `progetto`;
- `robots.txt` fisico curato, con sitemap dichiarata e Disallow mirati;
- archivi `category` e `post_tag` disattivati in modo coerente su tre livelli:
  404 nel tema, `Disallow` in robots.txt ed esclusione dalla sitemap;
- permalink `/%postname%`;
- pagina Grazie correttamente `noindex, nofollow`;
- Core Web Vitals locali e accessibilità WCAG A/AA già verificati (priorità 5 e
  redesign UI): LCP 348–364 ms, CLS < 0,06, zero violazioni AccessLint.

## Findings

### Critico

**S-01 — Undici pagine tradotte orfane, indicizzabili e in sitemap.** *(configurazione + contenuto)*

Polylang è **disattivo**, ma restano pubblicate 11 pagine in inglese, francese e
tedesco:

```
/homepage-english    /homepage-francais     /homepage-deutsch
/projects            /projets               /projekte
/companies-english   /entreprises-francais  /unternehmen-deutsch
/galleria-english    /galerie-francais      /galerie-deutsch
```

Stato verificato:

- tutte restituiscono HTTP 200 e `meta robots: index, follow`;
- tutte compaiono in `page-sitemap.xml`;
- tutte vengono servite con **`<html lang="it-IT">`**, quindi contenuto inglese,
  francese e tedesco dichiarato come italiano;
- **nessun `hreflang`**, perché senza Polylang attivo non esistono relazioni di
  traduzione da cui generarlo;
- **nessun link interno dal frontend**: il selettore lingua è nascosto per scelta
  di design, ma le voci restano nei menu in database.

È la combinazione peggiore possibile: pagine senza link interni, quindi
assimilabili a doorway pages, ma attivamente sottoposte all'indicizzazione via
sitemap, in duplicazione diretta di Home, Progetti, Galleria e Aziende, con la
lingua dichiarata sbagliata.

Richiede una decisione editoriale, non un fix tecnico. Opzioni:

1. riattivare Polylang, completare traduzioni e permalink e generare hreflang reali;
2. mantenere le pagine ma `noindex` ed escluderle dalla sitemap, in attesa di 1;
3. eliminarle e ripristinarle quando il multilingua sarà completo.

**S-02 — Nessuno schema sui contenuti singoli.** *(codice)*

Le pagine progetto e gli articoli emettono **solo `BreadcrumbList`**. Nessun
`Article`, `BlogPosting`, `WebPage` o `Service`.

Causa verificata: **nessun contenuto ha postmeta `rank_math_schema_*`**. I default
per tipo di contenuto sono impostati (`progetto` → `service`, `post` → `off`,
`page` → `off`) ma Rank Math li applica soltanto al salvataggio dall'editor, non
retroattivamente in rendering. Sono quindi inerti su tutto il contenuto esistente.

Inoltre `service` sarebbe comunque il tipo sbagliato: i progetti PAC non sono
servizi commerciali.

**S-03 — L'ente è dichiarato come azienda generica.** *(codice)*

`knowledgegraph_type` è `company`, quindi lo schema rende `Organization`. PAC è
una no-profit: il tipo corretto è `NGO`. Manca inoltre qualsiasi
`DonateAction`, benché la donazione sia l'obiettivo primario del sito.

### Alto

**S-04 — Post indicizzabili ma esclusi dalla sitemap.** *(configurazione)*

`post-sitemap.xml` restituisce **404** perché `pt_post_sitemap = off`, ma i due
articoli del Diario rendono `meta robots: index, follow`. Sono quindi
indicizzabili e non dichiarati.

**S-05 — `noindex` dormiente su tutti i post.** *(configurazione)*

`pt_post_robots` vale `["noindex"]` mentre `pt_post_custom_robots` è `off`. Oggi
l'impostazione è inerte e i post rendono `index`, ma chiunque attivi quel toggle
dall'admin deindicizza l'intero blog senza capire perché. Va riallineata al
comportamento desiderato invece di restare in contraddizione.

**S-06 — WooCommerce attivo senza prodotti.** *(risolto il 2026-08-03)*

WooCommerce era attivo con **0 prodotti pubblicati** e generava comunque `/shop`,
`/cart`, `/checkout`, `/my-account`, coperti solo da `Disallow` in `robots.txt`,
che impedisce la scansione ma **non l'indicizzazione** di URL linkati
dall'esterno. `pt_product_sitemap` era inoltre `on`.

Il plugin era stato installato per due motivi entrambi decaduti: modellare i
progetti come prodotti, approccio poi abbandonato, e collegare Stripe. Dopo
l'estrazione dei pagamenti in `pac-core`, Stripe non passa più da WooCommerce.

WooCommerce e `woocommerce-gateway-stripe` sono stati rimossi. Nel database non
c'era nulla da preservare: 0 prodotti, 0 ordini, 0 coupon e nessuna pagina Woo
configurata. Dettaglio della rimozione più sotto.

### Medio

**S-07 — Meta description automatiche, troncate o assenti.** *(codice + contenuto)*

Tutti i contenuti usano `%excerpt%` e **nessuno ha una description manuale**. Ne
derivano tre difetti distinti, tutti verificati sull'HTML renderizzato:

1. **Troncamento a metà parola**, perché il taglio è a lunghezza fissa e non al
   confine di frase. Pagina Antibracconaggio, prima del fix:

   > «…strumenti di sorveglianza non invasivi, per»

2. **Description del tutto assente** su **Aziende, Galleria e Diario di bordo**:
   non hanno excerpt, quindi Rank Math non emette il meta tag. Google compone la
   snippet da solo, senza controllo editoriale.

3. **Description spazzatura sull'archivio progetti**, che ripete il titolo
   dell'archivio: `Progetti Archive - PAC - Project Africa Conservation`.

Va aggiunto un quarto caso minore: l'articolo Ghana usa come description
l'excerpt «Il nostro progetto ha tre obiettivi chiave:», che introduce un elenco
e lascia due punti penzolanti in SERP.

**S-08 — Immagine Open Graph di default inadatta.** *(configurazione)*

`open_graph_image` punta al **logo condensato**. Un logo rende male come card
social 1200×630. Poiché progetti e articoli non hanno featured image, ogni
condivisione ricade su quell'immagine. Serve un'immagine OG dedicata.

Nota: l'assenza di `og:image` nel render locale dipende dall'attachment mancante
nel dump, non dalla configurazione. Va riverificata in produzione.

**S-09 — Profili social incompleti.** *(configurazione)*

`sameAs` contiene **solo Facebook**. Instagram, YouTube e LinkedIn sono `NULL`.
Il knowledge graph resta più debole del necessario.

**S-10 — `blogdescription` vuota.** *(configurazione)*

La tagline del sito non è impostata.

**S-11 — Contenuto sottile sul Diario.** *(contenuto)*

Due soli articoli pubblicati. Nessun fix tecnico: è un tema editoriale.

## Fix implementati in codice — 2026-08-03

Applicati in `app/Helpers/theme_helpers.php`, versionati e deployabili, tramite
filtri Rank Math pubblici e verificati nel plugin installato:

| ID | Fix | Hook |
|---|---|---|
| S-03 | `Organization` diventa `NGO` | `rank_math/json_ld` |
| S-03 | `DonateAction` sulle pagine progetto, dove vive il form di donazione | `rank_math/json_ld` |
| S-02 | `BlogPosting` sugli articoli, con headline, date, autore e publisher | `rank_math/json_ld` |
| S-02 | `WebPage` sulle pagine progetto, collegato a `WebSite` e all'ente | `rank_math/json_ld` |
| S-07 | Troncamento della description al confine di frase o parola, mai a metà parola | `rank_math/frontend/description` |
| S-07 | Rimozione della punteggiatura penzolante in coda alla description | `rank_math/frontend/description` |

Il fix S-07 corregge la **forma** della description, non la sua assenza: le tre
pagine senza excerpt continuano a non emettere il meta tag finché non ricevono
una description scritta a mano. È un intervento editoriale, elencato sotto.

I fix non introducono dipendenze e degradano in modo sicuro: se Rank Math viene
disattivato, i filtri semplicemente non vengono eseguiti.

### Rimozione di WooCommerce — S-06

Rimossi dal repository `wp-content/plugins/woocommerce/` e
`wp-content/plugins/woocommerce-gateway-stripe/`, per 6.227 file.

Ripulito anche il codice del tema che esisteva solo per contenere WooCommerce:

- eliminato `app/Helpers/woocommerce_helpers.php`, che disattivava pagine, asset
  e feature del plugin, e rimosso dall'autoload in `composer.json`;
- rimossi gli hook `disable_woocommerce_features`, `disable_woocommerce_pages`,
  `disable_woocommerce_assets`, `tp_redirect` e `custom_load_textdomain` da
  `app/Core/App.php` e `app/Helpers/theme_helpers.php`;
- rimossa da `ProgettoController` la lettura dei gateway di pagamento Woo e la
  variabile `pagamenti_disponibili`, che nessuna view usava più dopo il redesign;
- ripulito `robots.txt` dalle regole diventate morte.

Le chiamate a `\WC()` erano già protette da `function_exists`, quindi la
rimozione non poteva provocare fatal error. Verificato dopo la rimozione: sette
template rispondono 200 senza errori PHP, build riuscita, 5 test JS, 20 test PHP
e 40 test Playwright verdi.

**Ordine obbligatorio in produzione:** disattivare WooCommerce e
`woocommerce-gateway-stripe` dall'admin **prima** di distribuire questa versione.
Se i file spariscono mentre i plugin risultano ancora attivi in database,
WordPress li disattiva da solo ma con un avviso di errore.

Restano in database **20 tabelle `wc_*`** e le directory
`uploads/wc-logs/`, `uploads/woocommerce_uploads/` e
`uploads/woocommerce_transient_files/`. Sono inerti e la loro pulizia è una
scelta di manutenzione separata, da fare solo dopo un backup.

## Azioni richieste in produzione — non deployabili

Queste sono opzioni nel database WordPress. Il DB locale è un dump e non viene
promosso, quindi vanno rifatte a mano nell'admin di produzione.

1. **S-01** — decidere il destino delle 11 pagine tradotte. In attesa della
   decisione restano indicizzabili e in sitemap.
2. **S-04** — attivare `Rank Math → Sitemap → Post` per allineare la sitemap ai
   post indicizzabili, oppure rendere i post `noindex` se il Diario non deve
   essere indicizzato. Le due impostazioni devono concordare.
3. **S-05** — azzerare `pt_post_robots` o allinearlo alla decisione presa in S-04,
   così che il toggle `custom_robots` non nasconda più un `noindex` inatteso.
4. **S-06** — disattivare WooCommerce e `woocommerce-gateway-stripe` dall'admin
   **prima** di distribuire la versione che ne rimuove i file.
5. **S-08** — caricare un'immagine Open Graph dedicata 1200×630 e impostarla come
   default in `Rank Math → Impostazioni generali → Social`. Verificare in
   produzione che `og:image` venga effettivamente emesso.
6. **S-09** — compilare gli URL social mancanti.
7. **S-10** — impostare la tagline del sito.
8. **S-07** — scrivere description manuali. Priorità nell'ordine: **Aziende,
   Galleria e Diario di bordo**, che oggi non emettono alcun meta tag; poi
   l'**archivio progetti**, che ripete il titolo; poi i quattro progetti e
   l'articolo Ghana, che oggi usano un excerpt tagliato. Il fix in codice evita
   il troncamento brutto ma non sostituisce una description scritta.

## Verifica

Copertura automatica in `tests/e2e/seo.spec.js`:

- un solo `title` non vuoto e un solo `canonical` per pagina;
- `meta robots` presente e coerente con l'intento del template;
- JSON-LD sintatticamente valido su tutti i template principali;
- `NGO` presente nel grafo e nessun `Organization` generico residuo;
- `BlogPosting` sugli articoli e `WebPage` sulle pagine progetto;
- `DonateAction` presente sulle pagine progetto;
- description non troncata a metà parola e entro i limiti di lunghezza;
- pagina Grazie che resta `noindex`.
