<?php
return [
    'slug' => 'simple/like-dislike',
    'name' => 'Like-Dislike',
    'description' => 'Modern thumbs up/down for bipolar ratings',
    'category' => 'bipolar',
    'version' => '1.0.0',
    'author' => 'RateKit',
    'author_url' => 'https://ratekit.com',
    'tags' => ['thumbs', 'like', 'dislike', 'bipolar', 'simple'],
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
        'user_value' => 1, // User liked it
        'user_has_rated' => true,
        'category_stats' => [
            'positive' => 89,
            'negative' => 12
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
        'layout' => [
            'type' => 'select',
            'default' => 'horizontal',
            'options' => [
                'horizontal' => 'Side by side',
                'vertical' => 'Stacked'
            ]
        ],
        'color_scheme' => [
            'type' => 'select',
            'default' => 'default',
            'options' => [
                'default' => 'Green/Red',
                'blue' => 'Blue/Orange',
                'purple' => 'Purple/Pink'
            ]
        ]
    ]
];