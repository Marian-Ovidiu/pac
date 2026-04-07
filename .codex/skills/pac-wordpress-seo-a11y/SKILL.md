# pac-wordpress-seo-a11y

## Quando usarla

Usa questa skill quando devi fare audit o refactor su layout, semantica, SEO tecnico e accessibilita del tema custom.

## Input minimi

- [`wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php`](/C:/projects/privati/pac/wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php)
- template principali e partial header/footer

## Workflow

1. Controlla `lang`, title, meta description, canonical, schema e hook WordPress.
2. Analizza heading hierarchy, landmark, menu, tastiera, focus, immagini e form.
3. Identifica collisioni tra hardcode del tema e plugin SEO.
4. Applica prima i fix a basso rischio e alto impatto.
5. Separa i fix immediati da quelli che richiedono redesign o cambi contenutistici.

## Checklist

- `language_attributes()` o equivalente dinamico
- nessun canonical hardcoded non necessario
- immagini con `alt` coerente
- form con label e feedback accessibili

## Output atteso

- patch SEO/a11y ad alto impatto
- backlog residue per template specifici
