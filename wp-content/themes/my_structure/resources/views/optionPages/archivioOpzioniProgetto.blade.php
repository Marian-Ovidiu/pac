<?php
use Models\Options\OpzioniArchivioProgettoFields;
do_action('acf/input/admin_head');
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post">
        <?php
        $options = 'OpzioniArchivioProgettoFields'::get();
        acf_form([
            'post_id'    => 'options',
            'field_groups' => [$options->getGroupKey()],
            'submit_value' => 'Salva le impostazioni',
            'return' => false,
        ]);
        ?>
    </form>
</div>
<?php do_action('acf/input/admin_footer');?>
