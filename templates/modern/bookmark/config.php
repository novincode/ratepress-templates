<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

return [
    'slug' => 'modern/bookmark',
    'name' => 'Modern Bookmark',
    'description' => 'Clean bookmark button for saving content',
    'category' => 'binary',
        'version' => '1.0.5',
    'author' => 'RateKit',
    'author_url' => 'https://ratekit.com',
    'tags' => ['bookmark', 'save', 'binary', 'minimal'],
    'min_ratekit_version' => '1.0.0',
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
        'icon_size' => [
            'type' => 'select',
            'default' => 'medium',
            'options' => [
                'small' => '20px',
                'medium' => '24px',
                'large' => '28px'
            ]
        ],
        'show_counts' => ['type' => 'boolean', 'default' => true]
    ]
];
