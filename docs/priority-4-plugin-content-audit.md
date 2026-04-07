# Priorita 4: Audit plugin, ACF e modello contenuti

## Obiettivo

Ridurre complessita operativa del progetto, chiarire il modello contenuti del tema `my_structure` e definire una sequenza di semplificazione a basso rischio.

## Step-Back

Il principio corretto qui e `content model first`.

- plugin, field group e template devono avere responsabilita chiare e non sovrapposte
- il tema deve dipendere da un solo modello multilingual coerente, non da fallback misti tra pagine duplicate e `pll_*`
- ACF deve essere la fonte di verita dei contenuti strutturati, ma il binding tra group key, template e modelli PHP deve essere verificabile
- i plugin non attivi o parziali non devono influenzare il runtime o la governance del contenuto

## Pianificazione Strategica

1. Fotografare lo stato reale dei plugin attivi nel DB locale, distinguendo attivi, installati ma non attivi e snapshot incompleti.
2. Mappare i field group ACF reali dal DB e collegarli ai modelli/controller/template del tema.
3. Verificare il setup multilingual reale: plugin attivo, pagine duplicate, menu e riferimenti `pll_*`.
4. Evidenziare collisioni tra plugin e tra modelli contenuti.
5. Produrre un piano di semplificazione con precedenze, senza disattivazioni automatiche.

## Baseline verificata

- Tema attivo: [`wp-content/themes/my_structure`](/C:/projects/privati/pac/wp-content/themes/my_structure)
- Front page: pagina `5` con [`template-home.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/template-home.php)
- Plugin attivi nel DB locale:
  - `advanced-custom-fields-pro 2/acf.php`
  - `contact-form-7/wp-contact-form-7.php`
  - `iubenda-cookie-law-solution/iubenda_cookie_solution.php`
  - `maintenance/maintenance.php`
  - `wp-fastest-cache/wpFastestCache.php`
  - `wps-hide-login/wps-hide-login.php`
- Plugin installati ma non attivi:
  - [`wp-content/plugins/polylang`](/C:/projects/privati/pac/wp-content/plugins/polylang)
  - [`wp-content/plugins/seo-by-rank-math`](/C:/projects/privati/pac/wp-content/plugins/seo-by-rank-math)
  - [`wp-content/plugins/woocommerce`](/C:/projects/privati/pac/wp-content/plugins/woocommerce)
  - [`wp-content/plugins/wordpress-importer`](/C:/projects/privati/pac/wp-content/plugins/wordpress-importer)
  - [`wp-content/plugins/wpcat2tag-importer`](/C:/projects/privati/pac/wp-content/plugins/wpcat2tag-importer)
- Snapshot plugin incompleti:
  - [`wp-content/plugins/woocommerce-gateway-stripe`](/C:/projects/privati/pac/wp-content/plugins/woocommerce-gateway-stripe)
  - [`wp-content/plugins/wpforms-lite`](/C:/projects/privati/pac/wp-content/plugins/wpforms-lite)

## Findings Ordinati Per Severita

### Critical

1. ACF Pro e patchato localmente con intercettazione della licenza, quindi il plugin non e uno snapshot upstream affidabile.
   Riferimento: [`acf.php`](/C:/projects/privati/pac/wp-content/plugins/advanced-custom-fields-pro%202/acf.php)

2. Il progetto mantiene due stack pagamento Stripe paralleli: custom theme flow e configurazione WooCommerce Stripe nel DB, ma WooCommerce e il gateway non sono attivi.
   Rischio: decisione architetturale ambigua, credenziali duplicate e governance incoerente dei pagamenti.
   Riferimenti:
   - [`StripePayments.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Classes/StripePayments.php)
   - opzione DB `woocommerce_stripe_settings` presente con chiavi live/test e webhook
   - [`wp-content/plugins/woocommerce-gateway-stripe`](/C:/projects/privati/pac/wp-content/plugins/woocommerce-gateway-stripe)

### High

1. Polylang non e attivo, ma il tema conserva dipendenze applicative e duplicazioni contenutistiche multilingual.
   Impatto:
   - nessuna tassonomia `language` caricata nel runtime locale
   - nessun `pll_languages_list()` disponibile
   - il layout ha ancora rami commentati per widget per lingua
   - il DB contiene pagine duplicate per lingua, ma il controllo lingua applicativo non e operativo
   Riferimenti:
   - [`mainLayout.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php)
   - [`translation_helpers.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Helpers/translation_helpers.php)
   - [`BasePostType.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Core/Bases/BasePostType.php)
   - opzione DB `polylang` ancora presente con `post_types=["progetto"]` e menu tradotti

2. Il modello ACF di `Aziende` e incoerente: [`AziendeFields.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/AziendeFields.php) usa il group key `group_6735fa35e43e1`, che nel DB corrisponde a `Galleria`, non ad `Aziende`.
   Impatto: binding contenuti fragile o nullo sulla pagina [`template-aziende.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/template-aziende.php).

3. Il modello ACF `Grazie` punta a un group key errato.
   Dettaglio:
   - il model usa `group_67542e1849bab` in [`Grazie.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/Grazie.php)
   - il field group reale nel DB e `group_6754378b92aad`
   Impatto: la pagina [`template-grazie.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/template-grazie.php) dipende da un binding non allineato.

4. Esistono modelli ACF senza field group corrispondente nel DB.
   Dettaglio:
   - [`DuoFields.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/DuoFields.php)
   - [`LinearSlider.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/LinearSlider.php)
   entrambi puntano a `group_67ecfc307a8f5`, assente nel DB.
   Impatto: debt strutturale e segnali di feature incompleta o abbandonata.

### Medium

1. Duplicazione del layer form: Contact Form 7 e WPForms Lite convivono nel repository, ma solo Contact Form 7 e attivo.
   Riferimenti:
   - plugin attivo: [`wp-content/plugins/contact-form-7`](/C:/projects/privati/pac/wp-content/plugins/contact-form-7)
   - plugin installato ma non attivo e incompleto: [`wp-content/plugins/wpforms-lite`](/C:/projects/privati/pac/wp-content/plugins/wpforms-lite)

2. Duplicazione del layer SEO: Rank Math e installato e configurato nel DB, ma non attivo; il layout del tema mantiene meta/canonical hardcoded.
   Riferimenti:
   - [`wp-content/plugins/seo-by-rank-math`](/C:/projects/privati/pac/wp-content/plugins/seo-by-rank-math)
   - [`mainLayout.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php)

3. `maintenance`, `wp-fastest-cache` e `wps-hide-login` sono attivi in locale.
   Non e un errore di per se, ma aumentano il rumore operativo in audit, staging e smoke test.
   Riferimenti:
   - [`wp-content/plugins/maintenance`](/C:/projects/privati/pac/wp-content/plugins/maintenance)
   - [`wp-content/plugins/wp-fastest-cache`](/C:/projects/privati/pac/wp-content/plugins/wp-fastest-cache)
   - [`wp-content/plugins/wps-hide-login`](/C:/projects/privati/pac/wp-content/plugins/wps-hide-login)

4. Il tema contiene partial duplicate per lingua, ma il runtime usa sempre il widget base `HeaderMenu` / `FooterMenu`.
   Riferimenti:
   - [`mainLayout.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php)
   - [`MenuWidget.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Widget/MenuWidget.php)
   - [`resources/views/partials`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials)

## Matrice Plugin / Use Case

| Plugin | Stato | Use case reale | Evidenza | Valutazione |
| --- | --- | --- | --- | --- |
| ACF Pro | Attivo | Modello contenuti del tema, option pages, field group custom | [`acf_helpers.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Helpers/acf_helpers.php), [`source/Models`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models) | Critico ma da ripulire |
| Contact Form 7 | Attivo | Form standard, reCAPTCHA | frontend locale e plugin attivo | Da mantenere se unico form plugin |
| Iubenda | Attivo | Cookie/privacy compliance | plugin attivo | Da mantenere se richiesto legalmente |
| Maintenance | Attivo | Pagina manutenzione | plugin attivo | Solo operativo, non core business |
| WP Fastest Cache | Attivo | Cache pagina | plugin attivo | Da validare contro hosting/CDN |
| WPS Hide Login | Attivo | Hardening login | plugin attivo | Utile ma da documentare |
| Polylang | Installato, non attivo | Multilingual di contenuti/menu | opzione `polylang` e riferimenti `pll_*` nel tema | Stato incoerente |
| Rank Math SEO | Installato, non attivo | SEO tecnico e meta | opzioni DB presenti, plugin inattivo | Stato incoerente |
| WooCommerce | Installato, non attivo | eventuale catalogo/gateway | riferimenti controller pagamenti e opzioni Stripe Woo | Stato incoerente |
| WooCommerce Stripe | Snapshot incompleto | gateway WooCommerce | cartella priva di bootstrap root | Non affidabile |
| WPForms Lite | Snapshot incompleto | form builder alternativo | cartella priva di bootstrap root | Ridondante |
| WordPress Importer | Installato, non attivo | import una tantum | plugin utility | Candidato a rimozione |
| Categories to Tags Importer | Installato, non attivo | migrazione una tantum | plugin utility | Candidato a rimozione |

## Matrice ACF / Template / Controller

| Field group | Location reale | Model PHP | Controller / Template | Note |
| --- | --- | --- | --- | --- |
| Homepage | `template-home.php` | [`HomeFields.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/HomeFields.php) | [`HomeController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/HomeController.php), [`home.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/home.blade.php) | Binding coerente |
| Mono-logo | `front_page` | [`MonoFields.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/MonoFields.php) | [`HomeController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/HomeController.php), [`components/mono-logo.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/mono-logo.blade.php) | Binding coerente |
| Galleria | `template-galleria.php` | [`GalleriaFields.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/GalleriaFields.php) | [`PageController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/PageController.php), [`galleria.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/galleria.blade.php) | Coerente |
| Progetti | `template-progetti.php` | [`Progetti.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/Progetti.php) | [`PageController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/PageController.php), [`archivio-progetto.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/archivio-progetto.blade.php) | Coerente |
| Grazie | `template-grazie.php` | [`Grazie.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/Grazie.php) | [`PageController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/PageController.php), [`grazie.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/grazie.blade.php) | Group key errato nel model |
| Opzioni Generali | pagina opzioni `opzioni-generali` | [`OpzioniGlobaliFields.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/Options/OpzioniGlobaliFields.php) | [`generals.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/optionPages/generals.blade.php), header/footer partial | Coerente |
| Opzioni Archvio Progetto | pagina opzioni `opzioni-archivio-progetto` | [`OpzioniArchivioProgettoFields.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/Options/OpzioniArchivioProgettoFields.php) | [`ProgettoController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/ProgettoController.php), [`archivioOpzioniProgetto.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/optionPages/archivioOpzioniProgetto.blade.php) | Nome group nel DB contiene typo `Archvio` |
| Progetto | `post_type = progetto` | [`Progetto.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/Progetto.php) | [`ProgettoController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/ProgettoController.php), [`single-progetto.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/single-progetto.blade.php) | Non usa `BaseGroupAcf`, ma `get_field()` diretto |
| Aziende | `template-aziende.php` pagine presenti nel DB | [`AziendeFields.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/AziendeFields.php) | [`PageController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/PageController.php), [`aziende.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/aziende.blade.php) | Nessun field group dedicato rilevato; model agganciato a `Galleria` |
| Duo / LinearSlider | nessuna location DB trovata | [`DuoFields.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/DuoFields.php), [`LinearSlider.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/LinearSlider.php) | [`HomeController.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Controllers/HomeController.php) importa le classi ma non le usa | Debito da pulire |

## Setup Polylang E Impatti Multilingual

### Stato reale

- Il plugin [`polylang/polylang.php`](/C:/projects/privati/pac/wp-content/plugins/polylang/polylang.php) non e attivo nel DB locale.
- L'opzione `polylang` esiste ancora e mostra una configurazione storica:
  - `default_lang = it`
  - `post_types = ["progetto"]`
  - menu tradotti per `header-menu` e `footer-menu`
- Nel DB esistono ancora pagine duplicate per lingua:
  - homepage: `5`, `265`, `266`, `267`
  - galleria: `248`, `277`, `279`, `281`
  - aziende: `196`, `283`, `286`, `288`
  - progetti: `386`, `388`, `394`, `396`
  - grazie: `412`, `414`, `416`, `418`

### Impatti applicativi

1. Il tema continua a contenere riferimenti a `pll_current_language()`, `pll_get_post()` e `pll_get_the_languages()`, ma con plugin inattivo questi rami non sono affidabili.
2. I partial multilingual sono duplicati nel filesystem, ma il layout usa sempre i widget base, quindi il costo manutentivo esiste senza un beneficio runtime reale.
3. L'architettura corrente e ibrida:
   - pagine duplicate per lingua
   - menu tradotti storicamente
   - field `lingua` custom su `progetto`
   - fallback tema quando Polylang manca
4. SEO e multilingual:
   - senza Polylang attivo non risultano `hreflang` o relazioni lingua gestite dal plugin
   - le pagine duplicate restano come contenuto separato, non come traduzioni collegate

### Conclusione Polylang

Serve una decisione binaria:

1. riattivare e riallineare davvero Polylang come single source of truth del multilingual
2. oppure rimuovere il multilingual applicativo dal tema e trattare il sito come monolingua con contenuti editoriali separati

Tenere lo stato attuale ibrido non e sostenibile.

## Piano Di Semplificazione Con Precedenze

### Fase 1: Decisioni bloccanti

1. Decidere se WooCommerce o Stripe deve restare nel perimetro oppure se il progetto resta su flusso custom puro.
2. Decidere se Polylang deve essere rilanciato oppure dismesso.
3. Decidere quale plugin form resta standard: Contact Form 7 oppure altro.

### Fase 2: Pulizia a rischio basso

1. Rimuovere dal repository i plugin utility non necessari al runtime:
   - [`wordpress-importer`](/C:/projects/privati/pac/wp-content/plugins/wordpress-importer)
   - [`wpcat2tag-importer`](/C:/projects/privati/pac/wp-content/plugins/wpcat2tag-importer)
2. Rimuovere o archiviare gli snapshot incompleti:
   - [`woocommerce-gateway-stripe`](/C:/projects/privati/pac/wp-content/plugins/woocommerce-gateway-stripe)
   - [`wpforms-lite`](/C:/projects/privati/pac/wp-content/plugins/wpforms-lite)
3. Eliminare modelli ACF non collegati:
   - [`DuoFields.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/DuoFields.php)
   - [`LinearSlider.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/LinearSlider.php)

### Fase 3: Riallineamento modello contenuti

1. Correggere i group key errati:
   - [`Grazie.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/Grazie.php)
   - [`AziendeFields.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/AziendeFields.php)
2. Documentare formalmente ogni field group con:
   - location
   - modello PHP
   - controller
   - template
3. Ridurre l'uso di `get_field()` diretto in [`Progetto.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/source/Models/Progetto.php) se si vuole una governance uniforme del contenuto.

### Fase 4: Semplificazione multilingual

1. Se Polylang resta:
   - riattivarlo in staging
   - riallineare pagine, menu, slug e field `lingua`
   - rimuovere i fallback custom non necessari
2. Se Polylang esce:
   - rimuovere i riferimenti `pll_*`
   - eliminare partial duplicate per lingua in [`resources/views/partials`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials)
   - consolidare menu/header/footer in un solo layer

## Backlog Raccomandato

1. Chiudere la decisione architetturale `custom Stripe` vs `WooCommerce Stripe`.
2. Chiudere la decisione `Polylang on` vs `Polylang off`.
3. Correggere i modelli ACF disallineati `Aziende` e `Grazie`.
4. Rimuovere modelli ACF e plugin snapshot orfani.
5. Solo dopo, affrontare Priorita 5 e 6 su SEO, accessibilita e UI.

## Rischi Residui

- L'audit e basato su repository + DB locale `pac` del 2026-04-07; staging e produzione possono divergere.
- L'opzione `woocommerce_stripe_settings` contiene credenziali reali e test nel DB; non le ho riportate integralmente.
- Il notice ACF su caricamento traduzioni troppo presto resta presente durante il bootstrap e indica un ulteriore debt del plugin customizzato.
