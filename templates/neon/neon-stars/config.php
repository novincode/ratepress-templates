<?php
/**
 * Neon Stars Template Configuration
 * Futuristic glowing 5-star rating scale
 */

return [
    'slug' => 'neon/neon-stars',
    'name' => 'Neon Stars',
    'description' => 'Futuristic glowing 5-star rating - classic star rating',
    'version' => '1.1.2',
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
        'user_value' => 0.8, // 4 stars
        'user_has_rated' => true,
        'category_stats' => [
            'average' => 0.84,
            'total' => 189
        ]
    ],
    'preview' => 'preview.png',
    'preview_theme' => 'dark',
    'preview_background' => '#0a0a0f',
    'preview_zoom' => 2,
    
    'settings' => [
        'icon_size' => [
            'type' => 'select',
            'default' => 'medium',
            'options' => [
                'small' => '36px',
                'medium' => '44px', 
                'large' => '52px'
            ]
        ],
        'show_average' => [
            'type' => 'boolean',
            'default' => true
        ],
        'color_scheme' => [
            'type' => 'select',
            'default' => 'yellow',
            'options' => [
                'yellow' => '#fbbf24',
                'cyan' => '#00f5ff',
                'purple' => '#a855f7'
            ]
        ]
    ]
];
