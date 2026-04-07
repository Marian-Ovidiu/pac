# pac-php8-runtime-fixer

## Quando usarla

Usa questa skill quando devi correggere incompatibilita PHP 8+, parse error, warning runtime e bug strutturali del tema custom.

## Input minimi

- [`wp-content/themes/my_structure`](/C:/projects/privati/pac/wp-content/themes/my_structure)
- [`docs/modernization-roadmap.md`](/C:/projects/privati/pac/docs/modernization-roadmap.md)

## Workflow

1. Esegui lint del tema escludendo `vendor/` e `node_modules/`.
2. Correggi blocker di sintassi e warning PHP 8.
3. Rivedi magic methods, bootstrap e input non validati.
4. Verifica controller, helper e classi con superglobals o payload raw.
5. Esegui smoke test minimo sui template principali.

## Checklist

- `ProgettoController` senza parse error
- `Singleton` compatibile con PHP 8
- nessun warning evidente da CLI
- encoding non corrotto nei file toccati

## Output atteso

- tema avviabile sotto PHP 8.3
- elenco file corretti
- stato lint finale
