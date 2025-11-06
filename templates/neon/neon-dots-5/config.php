<?php
/**
 * Neon Dots 5 Template Configuration
 * Futuristic glowing 5-dot rating scale
 */

return [
    'slug' => 'neon/neon-dots-5',
    'name' => 'Neon Dots (1-5)',
    'description' => 'Futuristic glowing 5-dot rating - precision dots',
    'version' => '1.2.1',
    'author' => 'RateKit',
    'category' => 'scale',
    'styles' => ['style.css'],
    'scripts' => [], // Core JS handles all interactions
    'requires_core_js' => true,
    'supports' => [
        'objects' => ['post', 'comment'],
        'responsive' => true,
        'dark_mode' => true
    ],
    'demo_data' => [
        'user_value' => 0.6, // 3 dots
        'user_has_rated' => true,
        'category_stats' => [
            'average' => 0.76,
            'total' => 92
        ]
    ],
    'preview' => 'preview.png',
    'preview_theme' => 'dark',
    'preview_background' => '#0a0a0f',
    'preview_zoom' => 2,
    
    'settings' => [
        'dot_size' => [
            'type' => 'select',
            'default' => 'medium',
            'options' => [
                'small' => '32px',
                'medium' => '40px', 
                'large' => '48px'
            ]
        ],
        'show_counts' => [
            'type' => 'boolean',
            'default' => true
        ],
        'color_scheme' => [
            'type' => 'select',
            'default' => 'cyan',
            'options' => [
                'cyan' => '#00f5ff',
                'purple' => '#a855f7',
                'pink' => '#f472b6'
            ]
        ]
    ]
];
