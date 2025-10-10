<?php
return [
    'slug' => 'simple/heart',
    'name' => 'Heart',
    'description' => 'Modern heart icon for binary love ratings',
    'category' => 'binary',
    'version' => '1.0.0',
    'author' => 'RatePress',
    'author_url' => 'https://ratepress.com',
    'tags' => ['heart', 'love', 'binary', 'simple'],
    'min_ratepress_version' => '1.0.0',
    'styles' => ['style.css'],
    'scripts' => [], // Core JS handles all interactions
    'requires_core_js' => true,
    'supports' => [
        'objects' => ['post', 'comment'],
        'responsive' => true,
        'dark_mode' => true
    ],
    'demo_data' => [
        'user_value' => 1, // User loved it
        'user_has_rated' => true,
        'category_stats' => [
            'positive' => 128
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
                'small' => '20px',
                'medium' => '24px',
                'large' => '28px'
            ]
        ],
        'show_counts' => [
            'type' => 'boolean',
            'default' => true
        ],
        'color_scheme' => [
            'type' => 'select',
            'default' => 'red',
            'options' => [
                'red' => '#e11d48',
                'pink' => '#e91e63',
                'purple' => '#9b59b6'
            ]
        ]
    ]
];