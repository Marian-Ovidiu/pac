# PAC — Piano asset media

Data inventario: 2026-07-30. Baseline visuale approvata: “Quaderno di campo — impatto visibile”.

## Regola editoriale

Gli asset generati in questa run sono temporanei e illustrativi. Non costituiscono
prova di attività PAC, non sostituiscono fotografie autentiche e non ricevono
luogo, data, nome di persona o progetto non verificati. Il tema non aggiunge
didascalie tecniche automatiche sotto questi media; resta obbligatorio un testo
alternativo coerente con la funzione informativa o decorativa dell'immagine.

## Linguaggio visivo condiviso

- fotografia editoriale sobria, luce naturale e composizioni osservate, non pubblicitarie;
- colore caldo e realistico, verdi vegetali e toni terra coerenti con PAC;
- contrasto moderato, grana fotografica leggera, dettagli fisicamente plausibili;
- un singolo punto focale leggibile anche nel crop 4:5;
- persone assenti o non identificabili; nessun volto, uniforme, distintivo o gesto pietistico;
- nessun testo, logo, marchio, targa, documento leggibile o segnaletica;
- nessuna consegna, intervento medico, salvataggio, pattugliamento o risultato simulato;
- spazio negativo intenzionale e soggetto entro la fascia centrale mobile-safe;
- nessun overlay verde, look stock, patina pubblicitaria o spettacolarizzazione.

Le generazioni partono da master 4:3, sufficientemente ampi per costruire crop
manuali 16:10, 4:5 e 3:2 senza affidarsi al solo centro automatico.

## Classificazione

- **A — Decorativa:** atmosfera o still life senza evento reale.
- **B — Illustrativa:** tema generico; caption IA quando il contesto può creare ambiguità.
- **C — Documentale:** deve testimoniare un'attività reale; non viene generata.
- **D — Brand:** logo, lockup, partner o documenti; non viene generata.

## Slot approvati per generazione

Tutti gli slot usano `resources/views/components/media-figure.blade.php` e
`app/Helpers/utility_helpers.php`. Gli ACF reali, quando torneranno disponibili,
hanno precedenza automatica sull'asset temporaneo.

| ID | Pagina / sezione | Sorgente | Funzione narrativa | Priorità | Classe | Tipo e tono | Orientamento / rapporti | Crop desktop / mobile | Testo / area libera | Consentito | Vietato | Stato attuale | Asset finale | Dicitura IA |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| PAC-M01 | Home / hero | `home.blade.php`, ACF `immagine_1` | Apertura ambientale e respiro | P0 | B | Paesaggio naturale caldo, editoriale | master 4:3; hero 16:10 e 4:5 | sentiero centrale; orizzonte nel terzo alto | nessun overlay; centro mobile-safe | habitat, vegetazione, luce naturale | luogo riconoscibile, persone, fauna spettacolare | fallback; attachment 907 senza file | `pac-home-hero-illustrative` | sì |
| PAC-M02 | Archivio missioni / hero | `archivio-progetto.blade.php`, option `immagine_hero` | Introduce scelta e metodo | P1 | A | Still life di quaderno da campo astratto | 4:3; hero 16:10 e 4:5 | oggetti centrali, carta libera ai bordi | nessun overlay; margini calmi | carta, fibre, erbe, matita non marcata | mappe o note leggibili, loghi | fallback; option vuota | `pac-missions-archive-illustrative` | no, chiaramente allestita |
| PAC-M03 | Home, archivio e missione Nigeria | `mission-card.blade.php`, `single-progetto.blade.php`, ACF/featured assenti | Evoca educazione e comunità senza simulare un evento | P0 | B | Tavolo ombreggiato, quaderni bianchi e sedute vuote | 4:3; hero 16:10/4:5; card 3:2 | tavolo centrale, nessun elemento tagliato | nessun overlay | materiali anonimi, spazio comunitario vuoto | persone, nomi, bandiere, consegne | fallback in tre contesti | `pac-mission-community-table-illustrative` | sì |
| PAC-M04 | Home, archivio e missione K-9 | stessi componenti, ACF/featured assenti | Metafora discreta di collaborazione uomo-cane | P0 | B | Impronte plausibili su terreno asciutto | 4:3; hero 16:10/4:5; card 3:2 | traccia diagonale leggibile nel crop mobile | nessun overlay | terreno, impronte, foglie secche | cani in azione, uniformi, armi, pattuglie | fallback in tre contesti | `pac-mission-k9-tracks-illustrative` | sì |
| PAC-M05 | Home, archivio e missione antibracconaggio | stessi componenti, ACF/featured assenti | Habitat da proteggere, senza scena operativa | P0 | B | Erba alta, acacia e colline lontane | 4:3; hero 16:10/4:5; card 3:2 | acacia nella fascia centrale, orizzonte stabile | nessun overlay | paesaggio disabitato | ranger, armi, animali minacciati o salvataggi | fallback in tre contesti | `pac-mission-habitat-illustrative` | sì |
| PAC-M06 | Home, archivio e missione Ghana | stessi componenti, ACF/featured assenti | Evoca studio e continuità senza mostrare beneficiari | P0 | B | Tavolo da lettura vuoto, quaderno bianco e luce naturale | 4:3; hero 16:10/4:5; card 3:2 | quaderno centrale, finestra fuori asse | nessun overlay | arredi anonimi e materiali non marcati | persone, dormitorio, aiuti consegnati, testo | fallback in tre contesti | `pac-mission-study-space-illustrative` | sì |
| PAC-M07 | Aziende / hero | `aziende.blade.php`, ACF `immagine_hero` | Collaborazione come processo, non case study | P1 | A | Tavolo di lavoro non brandizzato, due sedute, carte bianche | 4:3; hero 16:10 e 4:5 | tavolo centrale, sedute ai margini | nessun overlay | materiali, fibre, campioni naturali | mani in posa stock, loghi, contratti leggibili | fallback; ID 546 non risolvibile | `pac-companies-collaboration-illustrative` | no, chiaramente allestita |
| PAC-M08 | Grazie / hero | `grazie.blade.php`, ACF `immagine` | Chiusura calma e non documentale | P2 | A | Ombre botaniche su carta calda | 4:3; hero 16:10 e 4:5 | ombra morbida centrale, bordo libero | nessun overlay | carta, foglie, luce | persone, denaro, ricevute, carte | fallback; ACF vuoto | `pac-thanks-botanical-decorative` | no, decorativa |

## Slot documentali e brand — nessuna generazione

| ID | Pagina / sezione | Template / sorgente | Funzione | Priorità | Classe | Orientamento / crop | Testo / area | Consentito | Vietato | Stato | Asset finale | Dicitura IA |
|---|---|---|---|---|---|---|---|---|---|---|---|---|
| PAC-C01 | Galleria / hero | `galleria.blade.php`, ACF `immagine_12` o `immagine_1` | Apre un racconto fotografico reale | P0 | C | 16:10 / 4:5, focal point da foto autentica | nessun overlay | fotografia PAC autorizzata | qualsiasi generazione | fallback | fotografia autentica richiesta | no |
| PAC-C02 | Galleria / capitolo 1.1 | ACF `immagine_1` | Prova dal campo | P1 | C | 3:2, crop editoriale | caption solo verificata | originale PAC | generazione | slot vuoto | autentico richiesto | no |
| PAC-C03 | Galleria / capitolo 1.2 | ACF `immagine_2` | Prova dal campo | P1 | C | 3:2 | come sopra | originale PAC | generazione | slot vuoto | autentico richiesto | no |
| PAC-C04 | Galleria / capitolo 1.3 | ACF `immagine_3` | Prova dal campo | P1 | C | 3:2 | come sopra | originale PAC | generazione | slot vuoto | autentico richiesto | no |
| PAC-C05 | Galleria / capitolo 1.4 | ACF `immagine_4` | Prova dal campo | P1 | C | 3:2 | come sopra | originale PAC | generazione | slot vuoto | autentico richiesto | no |
| PAC-C06 | Galleria / capitolo 2.1 | ACF `immagine_5` | Attività K-9 reale | P1 | C | 3:2 | caption verificata | originale PAC | K-9 sintetici in azione | slot vuoto | autentico richiesto | no |
| PAC-C07 | Galleria / capitolo 2.2 | ACF `immagine_6` | Attività K-9 reale | P1 | C | 3:2 | come sopra | originale PAC | generazione | slot vuoto | autentico richiesto | no |
| PAC-C08 | Galleria / capitolo 2.3 | ACF `immagine_7` | Attività K-9 reale | P1 | C | 3:2 | come sopra | originale PAC | generazione | slot vuoto | autentico richiesto | no |
| PAC-C09 | Galleria / capitolo 2.4 | ACF `immagine_8` | Attività K-9 reale | P1 | C | 3:2 | come sopra | originale PAC | generazione | slot vuoto | autentico richiesto | no |
| PAC-C10 | Galleria / capitolo 3.1 | ACF `immagine_9` | Cooperazione reale | P1 | C | 3:2 | caption verificata | originale PAC | generazione | slot vuoto | autentico richiesto | no |
| PAC-C11 | Galleria / capitolo 3.2 | ACF `immagine_10` | Cooperazione reale | P1 | C | 3:2 | come sopra | originale PAC | generazione | slot vuoto | autentico richiesto | no |
| PAC-C12 | Galleria / capitolo 3.3 | ACF `immagine_11` | Cooperazione reale | P1 | C | 3:2 | come sopra | originale PAC | generazione | slot vuoto | autentico richiesto | no |
| PAC-C13 | Galleria / capitolo 3.4 | ACF `immagine_12` | Cooperazione reale e possibile hero | P0 | C | 3:2 + hero | focal point valido nei due usi | originale PAC | generazione | slot vuoto | autentico richiesto | no |
| PAC-C14 | Diario / articolo povertà | featured image post | Identifica un contenuto reale | P1 | C | 3:2 / 16:10 | nessun overlay | immagine editoriale autentica | scena sintetica di intervento | assente | autentico richiesto | no |
| PAC-C15 | Diario / articolo dormitorio | featured image post | Identifica un contenuto reale | P1 | C | 3:2 / 16:10 | nessun overlay | immagine editoriale autentica | dormitorio o beneficiari sintetici | assente | autentico richiesto | no |
| PAC-C16 | Articolo povertà / video 1 | contenuto post, video `17.47.08.mp4` | Testimonianza video | P1 | C | originale | nessun testo aggiunto | video PAC originale | sostituzione sintetica | fallback inline | file autentico richiesto | no |
| PAC-C17 | Articolo povertà / video 2 | contenuto post, video `17.46.38.mp4` | Testimonianza video | P1 | C | originale | nessun testo aggiunto | video PAC originale | sostituzione sintetica | fallback inline | file autentico richiesto | no |
| PAC-C18 | Articolo dormitorio / foto 1 | attachment 867 | Prova del contenuto | P1 | C | originale verticale | alt da redazione | foto PAC | generazione | fallback inline | file autentico richiesto | no |
| PAC-C19 | Articolo dormitorio / foto 2 | attachment 871 | Prova del contenuto | P1 | C | originale verticale | alt da redazione | foto PAC | generazione | fallback inline | file autentico richiesto | no |
| PAC-C20 | Articolo dormitorio / foto 3 | attachment 866 | Prova del contenuto | P1 | C | originale orizzontale | alt da redazione | foto PAC | generazione | fallback inline | file autentico richiesto | no |
| PAC-C21 | Articolo dormitorio / foto 4 | attachment 869 | Prova del contenuto | P1 | C | originale verticale | alt da redazione | foto PAC | generazione | fallback inline | file autentico richiesto | no |
| PAC-C22 | Articolo dormitorio / foto 5 | attachment 872 | Prova del contenuto | P1 | C | originale verticale | alt da redazione | foto PAC | generazione | fallback inline | file autentico richiesto | no |
| PAC-D01 | Header e footer / lockup | `brand-lockup.blade.php`, opzione ACF logo | Identità ufficiale | P0 | D | varianti chiara/scura | nessun crop improprio | master PAC | logo generato o ridisegnato | pittogramma + lockup testuale | master ufficiale richiesto | no |

## Campi condizionali ispezionati, non renderizzati

- 72 campi progetto per immagini di problemi/soluzioni: 18 per ciascuna delle
  quattro missioni. Sono vuoti e devono restare documentali; nessun asset viene
  generato per riempirli.
- Home `immagine_2…4`, immagini legacy di progetto e `immagine_azienda`: non usate
  dal template approvato; non sono slot da riempire.
- Aziende `immagine_banner`: non usata dal template approvato.
- 404 e hero del Diario: non richiedono fotografie; l'assenza è intenzionale.
- Teaser correlati riusano le featured image dei due articoli e non sono nuovi slot.

## Strategia file e responsive

- master selezionati: `assets/masters/pac-generated/`;
- derivati pubblici: `wp-content/themes/my_structure/assets/media/generated/`;
- naming stabile: `<slug>-{hero|mobile|card}-{width}.{avif|webp|jpg}`;
- hero desktop: 800/1200/1600 px, 16:10;
- hero mobile: 480/640/800 px, 4:5;
- card: 600/900/1200 px, 3:2;
- AVIF e WebP via `<picture>`, JPEG come fallback;
- eager + `fetchpriority="high"` soltanto sugli hero sopra la piega;
- lazy loading sugli asset card; dimensioni intrinseche sempre presenti.

## Baseline prima dell'integrazione

- 31 slot semantici analizzati; 8 generabili, 22 documentali, 1 brand.
- 72 campi ACF documentali condizionali aggiuntivi verificati e lasciati vuoti.
- 0 immagini di contenuto caricate nei template principali: ogni slot usa il
  fallback PAC; il solo pittogramma Vite pesa circa 5,73 kB.
- baseline locale già misurata: LCP 180–328 ms; CLS 0–0,0553.
- richiesta terza parte reCAPTCHA può fallire localmente e viene distinta dalle
  richieste asset del tema.

## Selezione

Ispezione visiva completata il 2026-08-03 sugli otto crop `hero-1600`, cioè sui
file effettivamente serviti e non sui soli master. Controlli applicati a ciascun
asset: anatomia, oggetti implausibili, testo accidentale, marchi, stereotipi e
ambiguità documentale.

Esito: **otto asset su otto approvati, nessuno scartato in fase di ispezione**.
Nessuna immagine contiene persone, volti, uniformi, armi, animali in azione,
consegne, testo leggibile, loghi o documenti; le composizioni restano still life
o paesaggi disabitati, coerenti con la classe dichiarata.

| ID | Asset | Master | Soggetto verificato | Focal point | Esito |
|---|---|---|---|---|---|
| PAC-M01 | `pac-home-hero-illustrative` | 2336×1744 | Acacia isolata, sentiero, savana all'alba | acacia leggermente a destra del centro, orizzonte nel terzo alto | approvato |
| PAC-M02 | `pac-missions-archive-illustrative` | 2336×1744 | Quaderno aperto a pagine bianche, erbe secche, matita non marcata | quaderno centrato, bordi liberi | approvato |
| PAC-M03 | `pac-mission-community-table-illustrative` | 2336×1744 | Tavolo in legno all'ombra, quattro quaderni chiusi, panche vuote | piano del tavolo al centro, nessun elemento tagliato | approvato |
| PAC-M04 | `pac-mission-k9-tracks-illustrative` | 2336×1744 | Impronte di scarpone e di zampa su terra asciutta con foglie | traccia diagonale dal centro verso il basso | approvato |
| PAC-M05 | `pac-mission-habitat-illustrative` | 2336×1744 | Gruppo di acacie, erba alta, colline nella foschia | acacie nella fascia centrale, orizzonte stabile | approvato |
| PAC-M06 | `pac-mission-study-space-illustrative` | 2336×1744 | Quaderno a spirale bianco e matita su tavolo, ombre di foglie | quaderno centrale, fogliame fuori asse in alto a sinistra | approvato |
| PAC-M07 | `pac-companies-collaboration-illustrative` | 2336×1744 | Tavolo con due sedute vuote, fogli bianchi, campioni tessili | fogli bianchi al centro, sedute simmetriche sul fondo | approvato |
| PAC-M08 | `pac-thanks-botanical-decorative` | 2336×1744 | Ramo d'ulivo e carta strappata con ombre morbide | ramo a sinistra, ampio spazio negativo a destra | approvato |

### Scarti

I master conservati sono uno per slot: **le varianti alternative non sono state
mantenute su disco**, quindi il confronto A/B non è ricostruibile a posteriori.
Per una futura rigenerazione conviene archiviare anche gli scarti, o annotare qui
il motivo della preferenza al momento della scelta.

### Pesi finali

Ogni slug produce 27 derivati: 9 misure × 3 formati. Hero 800/1200/1600 in 16:10,
mobile 480/640/800 in 4:5, card 600/900/1200 in 3:2. Totale 216 file, 17 MB.

| Formato | File | Peso totale | Ruolo |
|---|---:|---:|---|
| AVIF | 72 | 2,8 MB | servito in pratica a tutti i browser di test |
| WebP | 72 | 5,9 MB | fallback intermedio |
| JPEG | 72 | 8,7 MB | fallback finale e `src` dell'`img` |

Estremi per singolo file AVIF: da 9 kB (`pac-thanks-botanical-decorative-card-600`)
a 124 kB (`pac-mission-k9-tracks-illustrative-hero-1600`).

Payload reale della Home misurato dal browser, 5 richieste per viewport, tutte AVIF:

| Viewport | Richieste | Byte scaricati |
|---|---:|---:|
| 390 px | 5 | 87.842 |
| 1440 px | 5 | 162.951 |

I master 4:3 restano in `assets/masters/pac-generated/` ma **non sono versionati**
(vedi `.gitignore`): servono solo a rifare i crop e pesano circa 51 MB. Non sono
riproducibili identici dai prompt, quindi vanno conservati fuori dal repository.

### Rilievo aperto

`pac-home-hero-illustrative` (PAC-M01) e `pac-mission-habitat-illustrative`
(PAC-M05) sono entrambi paesaggi di savana con acacia e convivono nella stessa
pagina Home, il primo come hero e il secondo come card della missione
antibracconaggio. Non è un errore di conformità e i due asset restano distinti,
ma la ripetizione del soggetto indebolisce la lettura. Se in futuro si rigenera
un solo asset, conviene differenziare PAC-M05, che è quello con la funzione più
specifica.

## Verifica finale locale — 2026-08-03

Eseguita con PHP 8.3.31 e server integrato su `127.0.0.1:8080`:

- build Vite riuscita: CSS 47,59 kB (10,31 kB gzip), JS principale 103,18 kB
  (37,46 kB gzip);
- 5 test unitari JavaScript riusciti;
- 20 test PHP `pac-core` riusciti;
- 27 test Playwright riusciti in Chromium: `media-assets`, `local-ui` e
  `local-performance`, su 320, 390, 768, 1024 e 1440 px;
- nessun overflow orizzontale, immagine rotta o asset di tema fallito;
- conteggio degli asset generati per template rispettato: 5 in Home, 5
  nell'archivio missioni, 1 in missione, aziende e grazie, 0 in galleria, diario
  e articolo, dove restano richiesti media autentici;
- nessuna didascalia tecnica automatica sotto gli asset illustrativi; `alt=""`
  confermato sugli asset decorativi di aziende e grazie;
- AccessLint WCAG A/AA senza violazioni sui template principali.

Web vitals locali desktop, non equivalenti a Lighthouse su staging:

| Superficie | LCP | CLS |
|---|---:|---:|
| Home | 364 ms | 0,0006 |
| Missione | 348 ms | 0,0559 |

I quattro asset documentali della galleria e le featured image del diario
continuano a mostrare il fallback PAC: restano richieste fotografie autentiche,
come da tabella degli slot documentali.
