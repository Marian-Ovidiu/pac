@props([
    'acf' => null,
    'class' => '',
    'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw',
    'fallbackAlt' => 'Image',
    'fallbackUrl' => '/placeholder.webp',
])

@php
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

@endphp

<figure>
    <img src="{{ $url }}" alt="{{ $alt }}" title="{{ $title }}"
        class="w-full h-auto object-cover {{ $class }}" srcset="{{ $srcset }}" sizes="{{ $sizes }}"
        loading="lazy" decoding="async">

    @if ($caption)
        <figcaption class="sr-only">{{ $caption }}</figcaption>
    @endif

    @if ($description)
        <div class="sr-only">{{ $description }}</div>
    @endif
</figure>
