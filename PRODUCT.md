# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

- Persone che vogliono comprendere e sostenere missioni PAC con una donazione.
- Aziende che valutano una collaborazione responsabile e rendicontabile.
- Lettori interessati agli aggiornamenti dal campo, alle comunità locali, ai
  ranger, alle unità K-9 e alla tutela della fauna.
- Redattori e amministratori WordPress che gestiscono contenuti strutturati con
  ACF e contenuti editoriali standard.

## Product Purpose

Il sito ufficiale di PAC — Project Africa Conservation presenta le missioni
dell'associazione, spiega i bisogni e le azioni sul campo, pubblica aggiornamenti
e consente di sostenere un progetto tramite una donazione Stripe.

Il successo del sito consiste nel far capire rapidamente cosa fa PAC, costruire
fiducia con contenuti verificabili e accompagnare l'utente verso un'azione
consapevole: approfondire una missione, donare o proporre una partnership.

## Positioning

PAC mette in relazione protezione della fauna, supporto a ranger e unità K-9 e
progetti sociali con comunità locali. Il sito deve rendere visibile questo lavoro
come presenza concreta e continuativa, senza ridurlo a una raccolta fondi
generica o a dichiarazioni non documentate.

## Operating Context

I flussi principali sono:

1. scoprire PAC dalla Home;
2. confrontare le missioni e aprire un singolo progetto;
3. leggere bisogno, attività e contenuti disponibili;
4. scegliere un importo, inserire i dati e completare il pagamento Stripe;
5. ricevere conferma nella pagina Grazie e tramite email;
6. leggere il Diario o consultare la Galleria;
7. inviare una richiesta di partnership dalla pagina Aziende;
8. gestire pagine, post, progetti, menu e campi strutturati da WordPress.

## Capabilities and Constraints

- WordPress con tema custom `my_structure`, Blade, ACF, Alpine e Vite.
- Il plugin `pac-core` possiede PaymentIntent, webhook firmato, idempotenza,
  donatori ed email transazionali.
- Il tema possiede presentazione, navigazione, contenuti e UI della donazione.
- Devono restare invariati `donationFormData`, project ID, thank-you URL, payload,
  action AJAX, Stripe Payment Element e recupero delle donazioni pendenti.
- I contenuti strutturati esistenti restano governati da ACF; il redesign non
  richiede nuovi field group per poter funzionare.
- Il sito contiene pagine IT, EN, FR e DE, ma Polylang non è attivo e le
  traduzioni dei singoli progetti non sono complete. Le lingue incomplete non
  devono essere presentate come un flusso pienamente disponibile.
- Contact Form 7 è il form builder attivo per il contatto partnership.
- Iubenda gestisce il consenso cookie e deve restare operativo.
- Rank Math è installato ma non attivo; il fallback SEO del tema non deve
  duplicare canonical, meta o schema quando un plugin SEO è attivo.

## Brand Commitments

- Nome: PAC — Project Africa Conservation.
- Conservare logo e pittogramma Africa/elefante senza ridisegni.
- Conservare il lockup testuale attuale finché non è disponibile il master.
- Direzione approvata: “Quaderno di campo — impatto visibile”.
- Voce: umana, chiara, documentaria, sobria e concreta.
- Non mescolare inglese e italiano senza una ragione linguistica reale.
- Palette e regole visuali sono definite in `DESIGN.md` e
  `docs/ui-ux-art-direction.md`.

## Evidence on Hand

- Quattro progetti italiani pubblici: Sociale Ghana, Sociale Nigeria,
  Antibracconaggio e Cani K-9.
- Due articoli pubblici nel Diario.
- Contenuti ACF per Home, progetti, Galleria e Grazie.
- Pittogramma PAC in
  `wp-content/themes/my_structure/source/assets/images/pittogramma.*`.
- Il master ufficiale del logo non è presente nella libreria media locale.
- Quattro attachment ACF sono registrati nel database ma assenti fisicamente da
  `wp-content/uploads`.
- La pagina Aziende non ha un field group ACF locale affidabile; il contenuto
  presente nel template non va presentato come prova o case study.
- Non sono disponibili case study aziendali, loghi partner, testimonianze o
  metriche operative con una fonte verificabile nel repository.

## Product Principles

1. Rendere comprensibili bisogno, azione e modo di contribuire.
2. Mostrare soltanto contenuti, dati e prove realmente disponibili.
3. Mantenere il flusso di donazione affidabile, accessibile e indipendente dal
   redesign del tema.
4. Dare pari dignità a persone, comunità, ranger, animali e territori.
5. Offrire un'esperienza coerente e utilizzabile su ogni viewport e modalità di
   input.

## Accessibility & Inclusion

Il target minimo è WCAG 2.2 livello AA. Il sito deve supportare tastiera,
screen reader, focus visibile, riduzione del movimento, zoom e reflow a 320 px.
La comunicazione e la fotografia devono preservare dignità e agency delle
comunità locali e non usare persone o condizioni di fragilità come decorazione.

## Non-obiettivi

- Ridisegnare logo o pittogramma.
- Modificare il comportamento pubblico di `pac-core`.
- Inventare metriche, partner, testimonianze, luoghi, risultati o caption.
- Introdurre un secondo framework UI o nuove dipendenze evitabili.
- Riattivare o rifondare il sistema multilingua durante il redesign.
- Trasformare il sito in una dashboard, un'app SaaS o un catalogo e-commerce.

## Acceptance Criteria

- Ogni pagina pubblica ha un solo H1 visibile e landmark corretti.
- Tutti i template principali funzionano a 320, 390, 768, 1024 e 1440 px.
- Nessun overflow, media rotto o errore console rilevante nei flussi core.
- Header, menu mobile, footer, CTA e form sono utilizzabili da tastiera.
- Il menu mobile gestisce focus trap, Escape, ritorno del focus e scroll lock.
- Il form donazione conserva contratti e test automatici esistenti.
- Palette, tipografia, spacing e motion rispettano `DESIGN.md`.
- I dati non verificati non vengono mostrati come reali.
- Staging target: accessibilità >= 95, performance >= 90, CLS < 0,1 e
  LCP < 2,5 s.
