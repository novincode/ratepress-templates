<?php
/**
 * Neon Heart Template Configuration
 * Futuristic glowing heart for binary love ratings
 */

return [
    'slug' => 'neon/neon-heart',
    'name' => 'Neon Heart',
    'description' => 'Futuristic glowing heart - love it with a glowing heart',
    'version' => '1.0.0',
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
        'user_value' => 1, // User loved it
        'user_has_rated' => true,
        'category_stats' => [
            'positive' => 1200
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
            'default' => 'red',
            'options' => [
                'red' => '#ef4444',
                'pink' => '#f472b6',
                'purple' => '#a855f7'
            ]
        ]
    ]
];
