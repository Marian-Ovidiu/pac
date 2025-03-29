<?php $attributes = $attributes->exceptProps([
    'acf' => null,
    'class' => '',
    'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw',
    'fallbackAlt' => 'Image',
    'fallbackUrl' => '/placeholder.webp',
]); ?>
<?php foreach (array_filter(([
    'acf' => null,
    'class' => '',
    'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw',
    'fallbackAlt' => 'Image',
    'fallbackUrl' => '/placeholder.webp',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $url = $acf['url'] ?? $fallbackUrl;
    $alt = $acf['alt'] ?? $fallbackAlt;
    $title = $acf['title'] ?? '';
    $caption = $acf['caption'] ?? '';
    $description = $acf['description'] ?? '';
    $srcset = '';

    if (isset($acf['sizes']) && is_array($acf['sizes'])) {
        $srcset = collect([
            'medium' => $acf['sizes']['medium'] ?? null,
            'medium_large' => $acf['sizes']['medium_large'] ?? null,
            'large' => $acf['sizes']['large'] ?? null,
            '2048x2048' => $acf['sizes']['2048x2048'] ?? $url,
        ])
            ->filter()
            ->map(function ($src, $key) {
                switch ($key) {
                    case 'medium':
                        return "{$src} 300w";
                    case 'medium_large':
                        return "{$src} 768w";
                    case 'large':
                        return "{$src} 1024w";
                    default:
                        return "{$src} 1600w";
                }
            })
            ->implode(', ');
    }

?>

<figure>
    <img src="<?php echo e($url); ?>" alt="<?php echo e($alt); ?>" title="<?php echo e($title); ?>"
        class="w-full h-auto object-cover <?php echo e($class); ?>" srcset="<?php echo e($srcset); ?>" sizes="<?php echo e($sizes); ?>"
        loading="lazy" decoding="async">

    <?php if($caption): ?>
        <figcaption class="sr-only"><?php echo e($caption); ?></figcaption>
    <?php endif; ?>

    <?php if($description): ?>
        <div class="sr-only"><?php echo e($description); ?></div>
    <?php endif; ?>
</figure>
<?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/components/img.blade.php ENDPATH**/ ?>