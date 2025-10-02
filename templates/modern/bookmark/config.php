<?php
return [
    'slug' => 'modern/bookmark',
    'name' => 'Modern Bookmark',
    'description' => 'Clean bookmark button for saving content',
    'category' => 'binary',
        'version' => '1.0.2',
    'author' => 'RatePress',
    'author_url' => 'https://ratepress.com',
    'tags' => ['bookmark', 'save', 'binary', 'minimal'],
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
        'user_value' => 1,
        'user_has_rated' => true,
        'category_stats' => ['positive' => 892]
    ],
    'preview_background' => '#0a0a0a',
    'preview_theme' => 'dark',
    'preview_zoom' => 2.5,
    'settings' => [
        'show_counts' => ['type' => 'boolean', 'default' => true]
    ]
];
