<?php
/**
 * Vote Stack Template Configuration
 * 
 * Stack Overflow-style vertical voting
 */

return [
    'slug' => 'modern/vote-stack',
    'name' => 'Vote Stack',
    'description' => 'Clean vertical voting like Stack Overflow',
    'category' => 'bipolar',
        'version' => '1.0.5',
    'author' => 'RatePress',
    'author_url' => 'https://ratepress.com',
    'tags' => ['vote', 'stackoverflow', 'bipolar', 'minimal'],
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
        'category_stats' => [
            'positive' => 456,
            'negative' => 23
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
            'default' => false
        ]
    ]
];
