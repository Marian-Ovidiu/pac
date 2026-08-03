# PAC UI/UX — Direzione artistica e piano di redesign

Stato: approvata come direzione di lavoro, da implementare per fasi.

## Principi non negoziabili

- Preservare logo e pittogramma PAC senza ridisegni.
- Preservare la palette PAC: `#84CE59`, `#45752C`, `#E8FCCF`, `#122018`,
  `#2F4A2D`, `#F5F1E8`, `#D8C8AE`, `#697261`, `#FFFDF8`.
- Non inventare dati, partner, risultati, luoghi o testimonianze.
- Non modificare i contratti tecnici del form donazione o il plugin `pac-core`.
- Progettare tutti gli stati desktop, tablet e mobile, non soltanto la Home.

Il master ufficiale del logo non è presente nella libreria media locale e il campo
ACF globale è vuoto. Nel tema è disponibile il pittogramma Africa/elefante in
`source/assets/images/pittogramma.png` e `.webp`; fino al recupero del master si
mantiene anche il lockup testuale esistente “PAC / Project Africa Conservation”.

## Concept: Quaderno di campo — impatto visibile

PAC deve apparire come un'organizzazione presente sul territorio, competente,
umana e trasparente. Ogni pagina è un capitolo di un quaderno di campo:

1. presenta una situazione umana o ambientale;
2. mostra cosa fa PAC;
3. porta prove verificabili;
4. propone un'azione chiara.

Il carattere editoriale del recente “Field Dispatch” resta il punto di partenza,
ma vengono eliminati estetica militare, dati decorativi hardcoded e inglese
mescolato all'italiano.

Messaggio guida:

> Proteggiamo chi protegge l'Africa.
> Con ranger, unità K-9 e comunità locali trasformiamo il sostegno in presenza,
> strumenti e futuro.

- CTA primaria: **Sostieni una missione**
- CTA secondaria: **Scopri cosa facciamo**

## Sistema visivo

### Tipografia

- Cormorant Garamond 600/700: titoli editoriali e citazioni.
- Inter 400/500/600/700: corpo, navigazione, UI e form.
- DM Mono 400/500: date, luoghi, dati verificati e micro-etichette.

Nunito, Playfair Display e Space Grotesk vengono rimossi dall'interfaccia.

### Griglia e superfici

- 12 colonne desktop, 6 tablet, 4 mobile.
- Contenitore massimo 1320 px.
- Gutter 24 px mobile, 40 px tablet, 64 px desktop.
- Spacing su multipli di 8.
- Sezioni 112–144 px desktop e 64–80 px mobile.
- Bordi clay sottili, radius 8–12 px; 16 px per form e pannelli donazione.
- Card solo quando aggiungono una gerarchia reale; niente estetica SaaS.
- Alternanza di sand, warm white e forest per il ritmo editoriale.

### Fotografia

- 50% persone, ranger e comunità.
- 25% strumenti, addestramento e azione.
- 25% habitat e fauna.
- Color grading naturale e caldo; niente pesanti overlay verdi.
- Hero desktop 16:10, mobile 4:5; card 3:2.
- Caption con nome, luogo e data solo quando verificati e autorizzati.
- Dimensioni esplicite, `srcset`, alt pertinente e fallback PAC per media mancanti.

### Movimento

- Reveal: opacità e spostamento verticale massimo 16 px, 400–500 ms.
- Hover immagini con scala massima 1.02.
- Niente typewriter o caroselli automatici.
- Caroselli manuali accessibili con controlli e tastiera.
- Rispetto completo di `prefers-reduced-motion`.

## Architettura delle pagine

### Header e navigazione

- Lockup ufficiale, nav Missioni/Diario/Galleria/Aziende, selettore lingua e CTA
  permanente **Dona ora**.
- I dati operativi si mostrano solo se verificati e amministrabili.
- Mobile: logo, CTA Dona, menu a tutto schermo e sottomenu ad accordion.
- Gestione corretta di focus, Escape e ritorno del focus al trigger.

### Home

1. Hero con H1 visibile, fotografia e due CTA.
2. Ledger di risultati verificati.
3. Come lavoriamo: comunità, ranger, K-9 e supporto sociale.
4. Quattro missioni attive.
5. Una nota dal campo.
6. Dove va il contributo.
7. Partnership aziendali.
8. CTA finale alla donazione.

### Archivio progetti

1. Introduzione e aree di intervento.
2. Quattro missioni con immagine, luogo, bisogno, azione e risultato atteso.
3. Azioni separate **Scopri** e **Sostieni**.
4. Trasparenza e aggiornamenti dal Diario.

Con quattro progetti non servono filtri.

### Singolo progetto

1. Hero con missione, luogo, sintesi e immagine.
2. Perché serve.
3. Cosa facciamo.
4. Prove e immagini dal campo.
5. Dove va il contributo.
6. Aggiornamenti collegati.
7. Donazione.
8. Altri modi per sostenere.

Il pannello donazione è sticky su desktop; su mobile una CTA sticky porta al form.
Restano invariati `x-data="donationFormData"`, project ID, thank-you URL, binding,
payload, azioni AJAX, Stripe Payment Element e recupero delle donazioni pendenti.

### Aziende

1. Hero B2B con proposta concreta.
2. Impatto ottenibile.
3. Tre modalità di collaborazione.
4. Processo in quattro passaggi.
5. Case study reale, quando disponibile.
6. Rendicontazione e trasparenza.
7. FAQ.
8. Form di contatto.

### Galleria

1. Hero visivo.
2. Capitoli tematici.
3. Griglia editoriale con caption.
4. Lightbox accessibile, se necessaria.
5. Breve storia tra i capitoli.
6. CTA finale a una missione.

Le sezioni senza immagini non devono generare vuoti nel layout.

### Diario di bordo

- Ultimo articolo in evidenza, lista degli altri articoli, data, categoria,
  abstract, paginazione, stato vuoto e CTA alle missioni.
- Nessun autore hardcoded.

### Singolo articolo

- Categoria, data, tempo di lettura, H1, lead image e articolo largo 720–760 px.
- Figure, caption, citazioni, progetto collegato, correlati e CTA finale.
- Eliminare il doppio `@extends`/`@section` presente nella view attuale.

### Pagina Grazie

- Conferma, email/ricevuta, cosa accade ora, missione sostenuta quando disponibile,
  contatto assistenza e CTA al Diario o agli altri progetti.
- Nessun dato personale o importo proveniente da query string non attendibile.

### Footer

- Logo, missione breve, Missioni, Organizzazione, Trasparenza, Contatti, social,
  dati legali reali e CTA Dona.
- Newsletter solo con flusso funzionante; nessun dato o placeholder hardcoded.

## Stati speciali

- 404: “La traccia si interrompe qui.”
- Ricerca, categoria e archivio vuoto.
- Media mancante.
- Form partnership inviato/errore.
- Donazione in caricamento, pagamento rifiutato, recuperato e completato.
- Contenuto non tradotto.
- Banner cookie desktop/mobile.
- Manutenzione.

## Problemi da eliminare durante il refactor

- Monolite SCSS da oltre 7.500 righe e stili di epoche diverse.
- Sei famiglie tipografiche senza gerarchia comune.
- Numeri, metriche e stati hardcoded presentati come reali.
- Microcopy italiana mescolata all'inglese.
- H1 nascosto nella Home.
- Verde brillante usato per testo piccolo e footer a basso contrasto.
- Autori, partner e label placeholder.
- Media ACF referenziati ma assenti da `uploads`.
- Cookie banner che copre larga parte del primo viewport.

## Componenti condivisi

`BrandLockup`, `SiteHeader`, `MobileNavigation`, `PageHero`, `SectionIntro`,
`MediaFigure`, `ImpactLedger`, `MissionCard`, `FieldNote`, `ProofList`,
`ArticleTeaser`, `DonationPanel`, `PartnerContact`, `CallToActionBand`,
`SiteFooter`, `EmptyState`.

## Sequenza di implementazione

1. Asset logo, media mancanti e tabella dei dati verificati.
2. Token, tipografia, griglia, header, nav, footer, button, form e media.
3. Home.
4. Archivio progetti.
5. Singolo progetto e form donazione.
6. Aziende.
7. Galleria.
8. Diario e singolo articolo.
9. Grazie, 404 e stati speciali.
10. Lingue e contenuti localizzati.
11. Motion, responsive, accessibilità e performance.
12. Visual UAT su staging.

## Criteri di accettazione

- Un H1 visibile per pagina.
- WCAG AA e navigazione completa da tastiera.
- `prefers-reduced-motion`.
- Nessun dato, partner o risultato inventato.
- Nessun media rotto.
- Layout verificato a 320, 390, 768, 1024 e 1440 px.
- Nessun colore fuori dai token approvati, salvo stati semantici.
- Nessuna regressione nel form donazione.
- Pagine equivalenti nelle lingue effettivamente pubblicabili.
- Cookie banner verificato su desktop e mobile.
- Lighthouse staging: accessibilità >= 95, performance >= 90, CLS < 0,1 e
  LCP < 2,5 s.
