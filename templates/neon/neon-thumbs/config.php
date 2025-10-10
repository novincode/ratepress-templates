<?php
/**
 * Neon Thumbs Template Configuration
 * Futuristic glowing thumbs up for binary ratings
 */

return [
    'slug' => 'neon/neon-thumbs',
    'name' => 'Neon Thumbs',
    'description' => 'Futuristic glowing thumbs up - simple like with glow',
    'version' => '1.0.3',
    'author' => 'RatePress',
    'category' => 'binary',
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
            'positive' => 856
        ]
    ],
    'preview' => 'preview.png',
    'preview_theme' => 'dark',
    'preview_background' => '#0a0a0f',
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
            'default' => 'green',
            'options' => [
                'green' => '#22c55e',
                'cyan' => '#00f5ff',
                'purple' => '#a855f7'
            ]
        ]
    ]
];
