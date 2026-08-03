---
target: wp-content/themes/my_structure/resources/views
total_score: 24
max_score: 32
na_heuristics: 7,10
p0_count: 0
p1_count: 2
timestamp: 2026-07-30T12-48-25Z
slug: wp-content-themes-my-structure-resources-views
---
# Critica Impeccable — PAC UI redesign

## Verdetto di specificità

**7/10.** Il sistema è riconoscibile come PAC grazie a palette, tipografia editoriale, ritmo paper/sand/forest e sequenza bisogno → azione → sostegno. Non raggiunge ancora pienamente la promessa «Quaderno di campo — impatto visibile» perché gran parte della fotografia ACF non è disponibile nell'ambiente locale: la ripetizione del fallback rende le missioni più astratte e meno distinguibili.

## Euristiche Nielsen

| # | Euristica | Punteggio | Evidenza |
|---|---|---:|---|
| 1 | Visibilità dello stato | 3 | Step, `aria-busy`, errori associati e stato live nella donazione; risposta CF7 esposta. |
| 2 | Corrispondenza col mondo reale | 4 | Copy italiano concreto; terminologia Stripe rimossa dall'interfaccia. |
| 3 | Controllo e libertà | 3 | Indietro nel form, drawer chiudibile con Escape e focus restituito; CTA mobile soppressa quando il form è visibile. |
| 4 | Coerenza e standard | 3 | Sistema e CTA coerenti; i diversi ingressi alla donazione richiedono ancora attenzione editoriale. |
| 5 | Prevenzione degli errori | 3 | Importo minimo, validazione inline e controlli disabilitati; il telefono obbligatorio resta un attrito imposto dal contratto esistente. |
| 6 | Riconoscimento anziché ricordo | 3 | Step, label e riepilogo restano nello stesso flusso. |
| 7 | Flessibilità ed efficienza | n/a | Superfici persuasive/editoriali, senza workflow esperto ricorrente. |
| 8 | Design estetico e minimale | 3 | Gerarchia chiara; alcune MissionCard restano dense su mobile. |
| 9 | Recupero dagli errori | 2 | Stati browser e unit test sono presenti, ma rifiuto e recupero Stripe non sono stati provati end-to-end su staging. |
| 10 | Aiuto e documentazione | n/a | Le superfici non richiedono documentazione separata; il form offre aiuto contestuale. |
| **Totale** |  | **24/32** | **Buono; rilascio condizionato da media reali e UAT pagamenti.** |

## Carico cognitivo

Due rischi su otto: le MissionCard espongono molte informazioni e due azioni; la scelta dell'importo presenta quattro preset più il campo libero. La donazione compensa con disclosure progressiva in tre step, label persistenti e riepilogo contestuale.

## Viaggio emotivo

La Home apre con una promessa chiara e procede verso missioni, note dal campo e trasparenza. La singola missione è l'arco più riuscito. Il Diario sostiene meglio il carattere editoriale. Senza fotografie reali distinte, però, il picco emotivo si appiattisce e le quattro missioni appaiono più simili di quanto siano.

## Punti forti

- Sistema visivo disciplinato con Cormorant Garamond, Inter e DM Mono.
- IA narrativa comprensibile e CTA con gerarchia chiara.
- Base accessibile: H1 visibile, skip link, focus, menu dialog, errori form e reduced motion.
- Trasparenza ora formulata in positivo e concentrata sui criteri di pubblicazione.

## Problemi prioritari

### P1 — Media reali e master ufficiale mancanti

Il fallback PAC evita errori e layout shift, ma non può sostituire la prova documentaria. Ripristinare gli upload referenziati da ACF e fornire il master ufficiale del lockup; non generare fotografie, caption o dati sostitutivi.

### P1 — UAT Stripe su staging non eseguita

Unit test, webhook e contratti DOM sono coperti, ma un pagamento test reale con rifiuto, completamento, redirect perso, recupero e email non è stato eseguito perché non è disponibile un ambiente staging autorizzato.

### P2 — MissionCard dense su mobile

Le schede includono immagine, contesto, bisogno, azione e due CTA. Il contenuto è reale e utile, ma il confronto richiede molto scroll. Alla prossima revisione contenuti, sintetizzare bisogno e azione senza perdere dati.

### P2 — Lingue non pronte alla pubblicazione

Il selettore e la struttura sono previsti, ma Polylang e i contenuti tradotti non risultano completi. Non pubblicare lingue parziali come equivalenti.

## Persona red flags

- **Jordan, prima visita:** deve comprendere che «Dona ora» apre la scelta della missione prima del form.
- **Riley, stress tester:** proverà immagini mancanti, rifiuto Stripe, refresh e redirect perso; i primi sono gestiti, gli ultimi richiedono staging.
- **Casey, mobile distratto:** trova un flusso lungo; la CTA fissa ora non compete con banner cookie, form o tastiera, ma le schede restano estese.

## Osservazioni minori

- I conteggi editoriali sono correttamente separati dai risultati d'impatto e non vanno trasformati in metriche di efficacia.
- La pagina Aziende resta necessariamente testuale finché non esistono case study e media autorizzati.
- Il fallback media deve restare dichiarato e non assumere caption, luogo o data.

## Domande aperte

- Quali file negli upload costituiscono i master autorizzati per Home, missioni, Aziende e Diario?
- Qual è il master ufficiale del lockup PAC da usare nelle varianti chiara e scura?
- Quale staging e quali credenziali Stripe test possono essere usati per la UAT completa?
