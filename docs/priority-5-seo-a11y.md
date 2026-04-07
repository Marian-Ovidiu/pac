# Priorita 5: SEO tecnico e accessibilita

## Obiettivo

Portare il tema `my_structure` a un baseline moderno di SEO tecnico e accessibilita, con fix ad alto impatto e basso rischio compatibili con WordPress.

## Step-Back

Il principio applicato qui e `semantic shell first`.

- il layout base deve essere corretto prima dei singoli template
- title, meta description, lingua documento e schema devono derivare dal runtime WordPress, non da hardcode fissi
- la semantica di landmark, navigazione e skip link deve funzionare indipendentemente dai contenuti
- i fix devono evitare collisioni future con plugin SEO come Rank Math

## Pianificazione Strategica

1. Audit del layout base per `lang`, `title`, `meta`, canonical, schema e hook WordPress.
2. Audit di header/footer/menu per landmark, struttura liste, tastiera e focus.
3. Audit dei template pubblici piu esposti per immagini, heading, componenti dinamici e form.
4. Implementazione dei fix immediati solo se non richiedono redesign o decisioni editoriali.
5. Separazione esplicita tra fix implementati e backlog residuo.

## Findings Principali

### High

1. Il layout base aveva `lang="it"` fisso, title hardcoded, meta description hardcoded, canonical hardcoded e JSON-LD statico.
   Riferimento iniziale: [`mainLayout.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php)

2. `wp_head()` era chiamato nel `body`, non nella `head`.
   Impatto: output SEO e asset WordPress in posizione non corretta.

3. Header e menu non erano strutturati come liste semantiche e il toggle mobile non esponeva stato/accessibilita corretti.
   Riferimento: [`header-menu.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/header-menu.blade.php)

4. Il footer aveva link non semantici o invalidi:
   - link homepage su `#`
   - mail non in `mailto:`
   - logo senza `alt`
   - testo con encoding degradato
   Riferimento: [`footer-menu.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/footer-menu.blade.php)

### Medium

1. Alcuni template usavano immagini con `alt` placeholder o vuoto.
   Riferimenti:
   - [`galleria.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/galleria.blade.php)
   - [`aziende.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/aziende.blade.php)

2. La pagina `grazie` usava markup invalido con `button` annidato dentro un link.
   Riferimento: [`grazie.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/grazie.blade.php)

3. I componenti slider non esponevano un boundary semantico chiaro lato assistive tech.
   Riferimenti:
   - [`components/slider.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/slider.blade.php)
   - [`components/section.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/section.blade.php)

4. La pagina `aziende` passava al componente hero una struttura dati non coerente con i parametri attesi.
   Riferimento: [`aziende.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/aziende.blade.php)

## Fix Implementati

### Layout base

Modifiche in [`mainLayout.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php):

- `language_attributes()` dinamico al posto di `lang` fisso
- `wp_head()` riportato nella `head`
- `wp_body_open()` aggiunto
- `wp_get_document_title()` usato per il title
- fallback `meta description` generato da WordPress solo se nessun plugin SEO noto e attivo
- rimozione del canonical hardcoded per evitare collisioni con core/plugin SEO
- JSON-LD fallback generato solo in home e solo se nessun plugin SEO noto e attivo
- skip link aggiunto
- `main` con `id="main-content"` e `tabindex="-1"`
- `body_class()` integrato nel layout

### Helper SEO

Modifiche in [`utility_helpers.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/app/Helpers/utility_helpers.php):

- `theme_seo_plugin_active()`
- `theme_meta_description()`
- `theme_schema_graph()`

Questi helper servono a:

- evitare doppio output quando Rank Math o plugin equivalenti sono attivi
- fornire fallback puliti quando il tema lavora senza plugin SEO

### Navigazione e footer

Modifiche in [`header-menu.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/header-menu.blade.php):

- struttura `nav > ul > li` corretta
- toggle mobile con `aria-controls`, `aria-expanded` e label dinamica
- submenu accessibili via hover e `focus-within`
- logo e home link ripuliti

Modifiche in [`footer-menu.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials/footer-menu.blade.php):

- home link corretto
- logo con `alt`
- email convertita in `mailto:`
- navigazione footer con `nav` e label
- social link con label piu chiare e `noopener`
- testo finale normalizzato

### Template e componenti

Modifiche in [`galleria.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/galleria.blade.php):

- sostituito link fittizio `href="#"` con `figure`
- `alt` e `title` immagini derivati dai dati reali, non da placeholder
- `figcaption` usata per il testo visuale

Modifiche in [`aziende.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/aziende.blade.php):

- `alt` hero coerente
- payload componente `aziende` allineato ai parametri reali del componente

Modifiche in [`grazie.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/grazie.blade.php):

- `h1` principale invece di `h2`
- eliminato `button` annidato in `a`

Modifiche in [`components/slider.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/slider.blade.php):

- region slider piu esplicita
- `aria-roledescription="carousel"`
- slide marcate come gruppi con label

Modifiche in [`components/section.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/section.blade.php):

- slider immagini progetto con label piu chiara lato assistive tech

## Backlog SEO/A11y Residuo

### Richiede redesign o decisione contenutistica

1. Heading hierarchy complessiva da ripulire nei componenti riusati:
   - ci sono piu `h2`/`h3` che dipendono dal contesto pagina invece che da un livello semantico dichiarato
2. Hero e overlay testuali:
   - contrasto e leggibilita vanno verificati su contenuti reali, non solo sul markup
3. Form custom donazioni:
   - servono label persistenti, error summary, focus management e annunci stato completi
4. Menu multilingual:
   - fino a quando resta aperta la decisione su Polylang, il modello di navigazione multilingua non e concluso
5. Schema strutturato:
   - il fallback `Organization` e solo una baseline; eventuale schema per pagine/progetti richiede una decisione SEO editoriale

### Richiede revisione template per template

1. [`archivio-progetto.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/archivio-progetto.blade.php)
   - form donazione, testi dinamici e slider
2. [`single-progetto.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/single-progetto.blade.php)
   - lingua, form donazione, contenuti ricchi
3. [`components/home-cards.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/home-cards.blade.php)
   - immagini e heading
4. [`components/home-mobile-cards.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/home-mobile-cards.blade.php)
   - heading e accessibilita card mobile
5. [`components/mono-logo.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/components/mono-logo.blade.php)
   - `alt` immagine hardcoded
6. partial duplicate lingua in [`resources/views/partials`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/partials)
   - da consolidare solo dopo decisione multilingual

## Verifiche Eseguite

- lint PHP helper modificati: `OK`
- bootstrap homepage: `OK`
- bootstrap pagina galleria: `OK`
- bootstrap pagina aziende: `OK`

## Rischi Residui

- Rank Math oggi non e attivo nel DB locale, quindi i fix sono stati progettati per non collidere se verra riattivato, ma non ho validato una convivenza runtime con plugin SEO attivo.
- Il notice ACF sul caricamento traduzioni troppo presto rimane fuori da questo pass.
- Non ho eseguito una validazione browser assistita con screen reader o tastiera completa su tutti i template.
