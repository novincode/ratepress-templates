<?php
/**
 * Neon Dots 10 Template Configuration
 * Futuristic glowing 10-dot rating scale
 */

return [
    'slug' => 'neon/neon-dots-10',
    'name' => 'Neon Dots (1-10)',
    'description' => 'Futuristic glowing 10-dot rating - maximum precision',
    'version' => '1.3.1',
    'author' => 'RatePress',
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
        'user_value' => 0.7, // 7 dots
        'user_has_rated' => true,
        'category_stats' => [
            'average' => 0.78,
            'total' => 234
        ]
    ],
    'preview' => 'preview.png',
    'preview_theme' => 'dark',
    'preview_background' => '#0a0a0f',
    'preview_zoom' => 1.5,
    
    'settings' => [
        'dot_size' => [
            'type' => 'select',
            'default' => 'medium',
            'options' => [
                'small' => '24px',
                'medium' => '32px', 
                'large' => '36px'
            ]
        ],
        'show_counts' => [
            'type' => 'boolean',
            'default' => true
        ],
        'color_scheme' => [
            'type' => 'select',
            'default' => 'gradient',
            'options' => [
                'gradient' => 'linear-gradient(135deg, #a855f7, #f472b6)',
                'cyan' => '#00f5ff',
                'purple' => '#a855f7'
            ]
        ]
    ]
];
