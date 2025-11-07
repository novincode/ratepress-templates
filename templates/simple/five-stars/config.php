<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

return [
    'slug' => 'simple/five-stars',
    'name' => 'Five Stars',
    'description' => 'Modern star rating for scale ratings (1-5 stars)',
    'category' => 'scale',
    'version' => '1.0.0',
    'author' => 'RateKit',
    'author_url' => 'https://ratekit.com',
    'tags' => ['stars', 'rating', 'scale', '5-star', 'simple'],
    'min_ratekit_version' => '1.0.0',
    'styles' => ['style.css'],
    'scripts' => [], // Core JS handles all interactions
    'requires_core_js' => true,
    'supports' => [
        'objects' => ['post', 'comment'],
        'responsive' => true,
        'dark_mode' => true
    ],
    'demo_data' => [
        'user_value' => 0.6, // 3 stars
        'user_has_rated' => true,
        'category_stats' => [
            'average' => 0.82, // 4.1 stars
            'total' => 247
        ]
    ],
    'preview_background' => '#0a0a0a',
    'preview_theme' => 'dark',
    'preview_zoom' => 2.5,
    'settings' => [
        'icon_size' => [
            'type' => 'select',
            'default' => 'medium',
            'options' => [
                'small' => '18px',
                'medium' => '20px',
                'large' => '24px'
            ]
        ],
        'show_counts' => [
            'type' => 'boolean',
            'default' => true
        ],
        'allow_half_stars' => [
            'type' => 'boolean',
            'default' => false
        ],
        'color_scheme' => [
            'type' => 'select',
            'default' => 'gold',
            'options' => [
                'gold' => '#f59e0b',
                'blue' => '#3b82f6',
                'purple' => '#8b5cf6',
                'green' => '#10b981'
            ]
        ]
    ]
];