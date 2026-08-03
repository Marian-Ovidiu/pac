# PAC Design System

Fonte concettuale: `docs/ui-ux-art-direction.md`.

## Concept approvato

**Quaderno di campo — impatto visibile.**

Il sito è un racconto documentario del lavoro PAC. Ogni pagina presenta un
contesto, spiega l'azione, usa le prove disponibili e propone un passo successivo.
Il carattere editoriale di “Field Dispatch” viene conservato come rigore e
presenza sul campo, senza estetica militare, dati decorativi o inglese gratuito.

## Palette

| Token | Valore | Ruolo |
| --- | --- | --- |
| `pac-moss` | `#84CE59` | accento, focus, elementi ampi ad alto contrasto |
| `pac-green` | `#45752C` | CTA e collegamenti principali |
| `pac-mist` | `#E8FCCF` | superficie informativa chiara |
| `pac-ink` | `#122018` | testo e superficie scura primaria |
| `pac-forest` | `#2F4A2D` | superficie scura secondaria |
| `pac-sand` | `#F5F1E8` | sfondo editoriale |
| `pac-clay` | `#D8C8AE` | bordi e separatori |
| `pac-stone` | `#697261` | testo secondario su fondo chiaro |
| `pac-paper` | `#FFFDF8` | carta e superfici chiare |

Nessun colore visuale esterno a questi token, salvo stati semantici di errore,
warning e successo. Il verde moss non viene usato per testo piccolo su fondo
chiaro.

## Tipografia

- Cormorant Garamond 600/700: H1–H3 editoriali e citazioni.
- Inter 400/500/600/700: corpo, navigazione, UI, form e pulsanti.
- DM Mono 400/500: date, luoghi verificati e micro-label brevi.
- Misura di lettura: 68–72 caratteri; articolo 720–760 px.
- Hero: `clamp(3rem, 7vw, 6.5rem)`, line-height 0.94–1.
- H2: `clamp(2.25rem, 4.5vw, 4.5rem)`.
- Corpo: 17–18 px con line-height circa 1.65.
- Label mono: 11–12 px; niente paragrafi in maiuscolo.

Nunito, Playfair Display e Space Grotesk sono legacy e vengono rimossi dai
template rifattorizzati.

## Griglia e responsive

- Container massimo: 1320 px.
- 12 colonne desktop, 6 tablet, 4 mobile.
- Gutter: 24 px mobile, 40 px tablet, 64 px desktop.
- Breakpoint comportamentali: 640, 768, 1024 e 1280 px.
- La composizione si adatta al contenuto; nessun breakpoint deve dipendere dal
  nome del dispositivo.
- Reflow obbligatorio senza scroll orizzontale a 320 px.
- Hero media: 16:10 desktop e 4:5 mobile; card media: 3:2.

## Spacing

Scala base: 4, 8, 12, 16, 24, 32, 40, 48, 64, 80, 112, 144 px.

- Sezioni: 64–80 px mobile, 112–144 px desktop.
- Più spazio prima di un titolo che dopo.
- Il ritmo alterna passaggi densi, immagini e zone di quiete.
- Nessuna sezione vuota viene mantenuta per conservare un'altezza decorativa.

## Superfici, bordi e radius

- `sand`, `paper` e `forest` costruiscono il ritmo delle pagine.
- Bordo ordinario: 1 px `clay`.
- Radius ordinario: 8–12 px.
- Form e pannello donazione: massimo 16 px.
- Ombre soltanto per elevazione funzionale; nessuna ombra SaaS diffusa.
- Una card esiste soltanto se crea gerarchia o raggruppa un'azione.

## Fotografia e media

- Priorità: persone/ranger/comunità 50%, azione e strumenti 25%, habitat/fauna
  25%, quando questi asset reali sono disponibili.
- Color grading naturale e caldo; niente overlay verde pesante.
- Caption, luoghi, date e nomi soltanto se presenti nei contenuti verificati.
- `width`, `height`, `srcset`, `sizes`, alt e loading appropriati.
- I media critici possono essere eager; gli altri sono lazy.
- Il fallback usa il linguaggio PAC e il pittogramma esistente, senza simulare
  una fotografia o inventare una caption.

## Motion

- Reveal opzionale: opacity + translateY massimo 16 px, 400–500 ms.
- Hover media: scala massima 1.02.
- Link: underline o freccia con transizione breve.
- Vietati typewriter, autoplay, parallasse aggressivo e animazioni decorative.
- Con `prefers-reduced-motion: reduce` transizioni e scroll animato vengono
  disabilitati e tutto il contenuto resta immediatamente visibile.

## Componenti

- `BrandLockup`: lockup testuale attuale e logo ACF quando disponibile.
- `SiteHeader`: navigazione principale e CTA donazione.
- `MobileNavigation`: dialog/drawer a schermo intero, focus trap e accordion.
- `PageHero`: eyebrow, H1, lead, azioni e media opzionale.
- `SectionIntro`: eyebrow, H2 e testo introduttivo.
- `MediaFigure`: media responsive, caption verificata e fallback.
- `ImpactLedger`: elenco di risultati soltanto quando i valori sono disponibili e
  verificati.
- `MissionCard`: missione, sintesi ACF e azioni Scopri/Sostieni.
- `FlagshipProject`: progetto prioritario in evidenza, con località verificata,
  stato della raccolta e accessi a dettaglio e donazione.
- `ProjectProgress`: budget e avanzamento soltanto quando verificati; in loro
  assenza mostra uno stato trasparente senza percentuali simulate.
- `FieldNote`: estratto editoriale o citazione realmente presente.
- `ProofList`: fatti o principi provenienti dal contenuto esistente.
- `ArticleTeaser`: data, categoria, titolo, abstract e media opzionale.
- `DonationPanel`: presentazione del form esistente senza alterarne i contratti.
- `PartnerContact`: contenuto e form CF7 reali.
- `CallToActionBand`: chiusura di pagina con una singola azione primaria.
- `SiteFooter`: missione, navigazione, contatti, legal e social.
- `EmptyState`: spiegazione utile e percorso successivo.

## Stati

- Default, hover, focus-visible, active, disabled e loading.
- Errori form associati ai campi e annunciati; niente soli cambi colore.
- Media mancante con fallback non interattivo.
- Archivio vuoto con `EmptyState`.
- Donazione: importo, dati, caricamento, pagamento, errore, recupero e successo.
- Contenuto non tradotto: nessun link lingua inattendibile.
- 404: “La traccia si interrompe qui.”
- Manutenzione e cookie banner devono rispettare gerarchia, contrasto e reflow.

## Header e footer

- Header: lockup PAC, Missioni, Diario, Galleria, Aziende e CTA “Dona ora”.
- Il sito è solo in italiano e non mostra un selettore lingua.
- Mobile: logo, CTA, pulsante Menu e drawer accessibile.
- Footer: brand, missione breve, navigazione, trasparenza/legal, contatti, social e
  CTA. Nessuna newsletter senza backend funzionante.

## Accessibilità

- WCAG 2.2 AA.
- Un H1 visibile per pagina; heading senza salti arbitrari.
- Landmark `header`, `nav`, `main`, `footer` e skip link.
- Focus ring ad alto contrasto su tutti i controlli.
- Target touch minimo 44×44 px quando possibile.
- Label persistenti, help text e errori collegati con `aria-describedby`.
- `aria-live` per stati asincroni rilevanti.
- Menu, eventuale lightbox, carousel manuale e CTA sticky da tastiera.
- Alt descrittivo per media informativi e alt vuoto per decorazioni.
- Il banner cookie non deve nascondere l'intero primo viewport.

## Anti-pattern ed elementi vietati

- Estetica dashboard/SaaS, griglie di card decorative e pill ripetitive.
- Metriche, badge “ACTIVE”, dispatch e partner hardcoded presentati come fatti.
- Inglese mescolato all'italiano.
- Testo piccolo in verde brillante o footer a basso contrasto.
- Hero generici, H1 nascosti e sezioni duplicate.
- Autore hardcoded, loghi partner placeholder o case study inventati.
- Typewriter, autoplay, gradienti estranei alla palette e ombre decorative.
- Media rotti, contenitori vuoti e fallback che imitano contenuto reale.
