<?php
namespace Models;

use Core\Bases\BasePostType;

class Progetto extends BasePostType
{
    public static $postType = 'progetto';
    public $name;
    public $titolo_card;
    public $immagine_hero;
    public $titolo_hero;
    public $testo_hero;
    public $problemi_titolo_1;
    public $problemi_sotto_titolo_1;
    public $problemi_testo_1;
    public $problemi_immagine_1_1;
    public $problemi_immagine_1_2;
    public $problemi_immagine_1_3;
    public $problemi_sotto_titolo_2;
    public $problemi_testo_2;
    public $problemi_immagine_2_1;
    public $problemi_immagine_2_2;
    public $problemi_immagine_2_3;
    public $problemi_sotto_titolo_3;
    public $problemi_testo_3;
    public $problemi_immagine_3_1;
    public $problemi_immagine_3_2;
    public $problemi_immagine_3_3;
    public $soluzioni_titolo_1;
    public $soluzioni_sotto_titolo_1;
    public $soluzioni_testo_1;
    public $soluzioni_immagine_1_1;
    public $soluzioni_immagine_1_2;
    public $soluzioni_immagine_1_3;
    public $soluzioni_sotto_titolo_2;
    public $soluzioni_testo_2;
    public $soluzioni_immagine_2_1;
    public $soluzioni_immagine_2_2;
    public $soluzioni_immagine_2_3;
    public $soluzioni_sotto_titolo_3;
    public $soluzioni_testo_3;
    public $soluzioni_immagine_3_1;
    public $soluzioni_immagine_3_2;
    public $soluzioni_immagine_3_3;
    public $project_template;
    public $project_location;
    public $project_visual_theme;
    public $project_intro;
    public $project_why_title;
    public $project_why_text;
    public $project_objectives;
    public $fundraising_status;
    public $fundraising_target;
    public $fundraising_raised;
    public $fundraising_currency;
    public $fundraising_note;
    public $expected_impact;
    public $project_updates;

    public function __construct($post = null)
    {
        parent::__construct($post);
    }

    public function defineOtherAttributes($post)
    {
        $this->name          = get_field('name', $this->id);
        $this->titolo_card   = get_field('titolo_card', $this->id);
        $this->immagine_hero = get_field('immagine_hero', $this->id);
        $this->titolo_hero   = get_field('titolo_hero', $this->id);
        $this->testo_hero    = get_field('testo_hero', $this->id);

        $this->problemi_titolo_1       = get_field('problemi_titolo_1', $this->id);
        $this->problemi_sotto_titolo_1 = get_field('problemi_sotto_titolo_1', $this->id);
        $this->problemi_testo_1        = get_field('problemi_testo_1', $this->id);
        $this->problemi_immagine_1_1   = get_field('problemi_immagine_1_1', $this->id);
        $this->problemi_immagine_1_2   = get_field('problemi_immagine_1_2', $this->id);
        $this->problemi_immagine_1_3   = get_field('problemi_immagine_1_3', $this->id);

        $this->problemi_sotto_titolo_2 = get_field('problemi_sotto_titolo_2', $this->id);
        $this->problemi_testo_2        = get_field('problemi_testo_2', $this->id);
        $this->problemi_immagine_2_1   = get_field('problemi_immagine_2_1', $this->id);
        $this->problemi_immagine_2_2   = get_field('problemi_immagine_2_2', $this->id);
        $this->problemi_immagine_2_3   = get_field('problemi_immagine_2_3', $this->id);

        $this->problemi_sotto_titolo_3 = get_field('problemi_sotto_titolo_3', $this->id);
        $this->problemi_testo_3        = get_field('problemi_testo_3', $this->id);
        $this->problemi_immagine_3_1   = get_field('problemi_immagine_3_1', $this->id);
        $this->problemi_immagine_3_2   = get_field('problemi_immagine_3_2', $this->id);
        $this->problemi_immagine_3_3   = get_field('problemi_immagine_3_3', $this->id);

        $this->soluzioni_titolo_1       = get_field('soluzioni_titolo_1', $this->id);
        $this->soluzioni_sotto_titolo_1 = get_field('soluzioni_sottotitolo_1', $this->id);
        $this->soluzioni_testo_1        = get_field('soluzioni_testo_1', $this->id);
        $this->soluzioni_immagine_1_1   = get_field('soluzioni_immagine_1_1', $this->id);
        $this->soluzioni_immagine_1_2   = get_field('soluzioni_immagine_1_2', $this->id);
        $this->soluzioni_immagine_1_3   = get_field('soluzioni_immagine_1_3', $this->id);

        $this->soluzioni_sotto_titolo_2 = get_field('soluzioni_sottotitolo_2', $this->id);
        $this->soluzioni_testo_2        = get_field('soluzioni_testo_2', $this->id);
        $this->soluzioni_immagine_2_1   = get_field('soluzioni_immagine_2_1', $this->id);
        $this->soluzioni_immagine_2_2   = get_field('soluzioni_immagine_2_2', $this->id);
        $this->soluzioni_immagine_2_3   = get_field('soluzioni_immagine_2_3', $this->id);

        $this->soluzioni_sotto_titolo_3 = get_field('soluzioni_sottotitolo_3', $this->id);
        $this->soluzioni_testo_3        = get_field('soluzioni_testo_3', $this->id);
        $this->soluzioni_immagine_3_1   = get_field('soluzioni_immagine_3_1', $this->id);
        $this->soluzioni_immagine_3_2   = get_field('soluzioni_immagine_3_2', $this->id);
        $this->soluzioni_immagine_3_3   = get_field('soluzioni_immagine_3_3', $this->id);

        $this->project_template     = $this->getProjectField('pac_project_template', 'standard');
        $this->project_location     = $this->getProjectField('pac_project_location');
        $this->project_visual_theme = $this->getProjectField('pac_project_visual_theme');
        $this->project_intro        = $this->getProjectField('pac_project_intro');
        $this->project_why_title    = $this->getProjectField('pac_project_why_title');
        $this->project_why_text     = $this->getProjectField('pac_project_why_text');
        $this->project_objectives   = $this->getProjectField('pac_project_objectives', []);
        $this->fundraising_status   = $this->getProjectField('pac_fundraising_status', 'planning');
        $this->fundraising_target   = $this->getProjectField('pac_fundraising_target');
        $this->fundraising_raised   = $this->getProjectField('pac_fundraising_raised');
        $this->fundraising_currency = $this->getProjectField('pac_fundraising_currency', 'EUR');
        $this->fundraising_note     = $this->getProjectField('pac_fundraising_note');
        $this->expected_impact      = $this->getProjectField('pac_expected_impact', []);
        $this->project_updates      = $this->getProjectField('pac_project_updates', []);
    }

    private function getProjectField($name, $default = '')
    {
        $value = function_exists('get_field') ? get_field($name, $this->id) : null;

        if ($value === null || $value === false || $value === '') {
            $stored = get_post_meta($this->id, $name, true);
            $value = $stored !== '' ? $stored : $default;
        }

        return $value;
    }

    public function isFlagship(): bool
    {
        return $this->project_template === 'flagship';
    }

    public static function prioritizeFlagship(array $projects): array
    {
        $flagship = array_values(array_filter($projects, static fn ($project) => $project instanceof self && $project->isFlagship()));
        $standard = array_values(array_filter($projects, static fn ($project) => !($project instanceof self) || !$project->isFlagship()));

        return array_merge($flagship, $standard);
    }

    public static function firstFlagship(array $projects): ?self
    {
        foreach ($projects as $project) {
            if ($project instanceof self && $project->isFlagship()) {
                return $project;
            }
        }

        return null;
    }

    public function getObjectives(): array
    {
        return $this->normalizeStructuredItems($this->project_objectives);
    }

    public function getExpectedImpact(): array
    {
        return $this->normalizeStructuredItems($this->expected_impact);
    }

    public function getProjectUpdates(): array
    {
        $ids = [];

        foreach ((array) $this->project_updates as $update) {
            if ($update instanceof \WP_Post) {
                $ids[] = (int) $update->ID;
            } elseif (is_array($update) && isset($update['ID'])) {
                $ids[] = (int) $update['ID'];
            } elseif (is_numeric($update)) {
                $ids[] = (int) $update;
            }
        }

        $ids = array_values(array_filter(array_unique($ids)));
        if ($ids === []) {
            return [];
        }

        return get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => count($ids),
            'post__in' => $ids,
            'orderby' => 'post__in',
        ]);
    }

    public function getFundraising(): array
    {
        $target = max(0.0, (float) $this->fundraising_target);
        $raised = max(0.0, (float) $this->fundraising_raised);
        $statusLabels = [
            'planning' => 'Budget in definizione',
            'fundraising' => 'Raccolta attiva',
            'in_progress' => 'Lavori in corso',
            'completed' => 'Progetto completato',
        ];

        return [
            'status' => $this->fundraising_status ?: 'planning',
            'status_label' => $statusLabels[$this->fundraising_status] ?? $statusLabels['planning'],
            'target' => $target,
            'raised' => $raised,
            'currency' => $this->fundraising_currency ?: 'EUR',
            'percentage' => $target > 0 ? min(100, round(($raised / $target) * 100)) : null,
            'note' => trim((string) $this->fundraising_note),
        ];
    }

    private function normalizeStructuredItems($items): array
    {
        if (!is_array($items)) {
            return [];
        }

        return array_values(array_filter(array_map(static function ($item) {
            if (!is_array($item)) {
                return null;
            }

            $title = trim(wp_strip_all_tags((string) ($item['title'] ?? '')));
            $text = trim((string) ($item['text'] ?? ''));

            return $title !== '' || $text !== '' ? ['title' => $title, 'text' => $text] : null;
        }, $items)));
    }

    public function getProblemi()
    {
        return [
            [
                'sottoTitolo' => $this->problemi_sotto_titolo_1,
                'testo'       => $this->problemi_testo_1,
                'immagini'    => array_filter([$this->problemi_immagine_1_1, $this->problemi_immagine_1_2, $this->problemi_immagine_1_3], fn($img) => isset($img['url'])),
            ],
            [
                'sottoTitolo' => $this->problemi_sotto_titolo_2,
                'testo'       => $this->problemi_testo_2,
                'immagini'    => array_filter([$this->problemi_immagine_2_1, $this->problemi_immagine_2_2, $this->problemi_immagine_2_3], fn($img) => isset($img['url'])),
            ],
            [
                'sottoTitolo' => $this->problemi_sotto_titolo_3,
                'testo'       => $this->problemi_testo_3,
                'immagini'    => array_filter([$this->problemi_immagine_3_1, $this->problemi_immagine_3_2, $this->problemi_immagine_3_3], fn($img) => isset($img['url'])),
            ],
        ];
    }

    public function getSoluzioni()
    {
        return [
            [
                'sottoTitolo' => $this->soluzioni_sotto_titolo_1,
                'testo'       => $this->soluzioni_testo_1,
                'immagini'    => array_filter([$this->soluzioni_immagine_1_1, $this->soluzioni_immagine_1_2, $this->soluzioni_immagine_1_3], fn($img) => isset($img['url'])),
            ],
            [
                'sottoTitolo' => $this->soluzioni_sotto_titolo_2,
                'testo'       => $this->soluzioni_testo_2,
                'immagini'    => array_filter([$this->soluzioni_immagine_2_1, $this->soluzioni_immagine_2_2, $this->soluzioni_immagine_2_3], fn($img) => isset($img['url'])),
            ],
            [
                'sottoTitolo' => $this->soluzioni_sotto_titolo_3,
                'testo'       => $this->soluzioni_testo_3,
                'immagini'    => array_filter([$this->soluzioni_immagine_3_1, $this->soluzioni_immagine_3_2, $this->soluzioni_immagine_3_3], fn($img) => isset($img['url'])),
            ],
        ];
    }

}
