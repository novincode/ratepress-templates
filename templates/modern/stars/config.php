<?php
return [
    'slug' => 'modern/stars',
    'name' => 'Modern Stars',
    'description' => 'Smooth 5-star rating with hover effects',
    'category' => 'scale',
    'version' => '1.0.0',
    'author' => 'RatePress',
    'author_url' => 'https://ratepress.com',
    'tags' => ['stars', 'rating', 'scale', '5-star'],
    'min_ratepress_version' => '1.0.0',
    'styles' => ['style.css'],
    'scripts' => [],
    'requires_core_js' => true,
    'supports' => [
        'objects' => ['post', 'comment'],
        'responsive' => true,
        'dark_mode' => true
    ],
    'demo_data' => [
        'user_value' => 0.8,
        'user_has_rated' => true,
        'category_stats' => ['average' => 0.86, 'total' => 1543]
    ],
    'preview_background' => '#0a0a0a',
    'preview_theme' => 'dark',
    'preview_zoom' => 2.5,
    'settings' => [
        'show_counts' => ['type' => 'boolean', 'default' => true]
    ]
];
