<?php
/**
 * Neon Range Template Configuration
 * Futuristic glowing range slider rating
 */

return [
    'slug' => 'neon/neon-range',
    'name' => 'Neon Range',
    'description' => 'Futuristic glowing range slider - smooth 0-10 rating',
    'version' => '1.2.0',
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
        'user_value' => 0.75, // 7.5
        'user_has_rated' => true,
        'category_stats' => [
            'average' => 0.75,
            'total' => 156
        ]
    ],
    'preview' => 'preview.png',
    'preview_theme' => 'dark',
    'preview_background' => '#0a0a0f',
    'preview_zoom' => 2.5,
    
    'settings' => [
        'show_value' => [
            'type' => 'boolean',
            'default' => true
        ],
        'min_value' => [
            'type' => 'number',
            'default' => 0,
            'min' => 0,
            'max' => 10
        ],
        'max_value' => [
            'type' => 'number',
            'default' => 10,
            'min' => 0,
            'max' => 10
        ],
        'step' => [
            'type' => 'number',
            'default' => 0.1,
            'min' => 0.1,
            'max' => 1
        ],
        'color_scheme' => [
            'type' => 'select',
            'default' => 'gradient',
            'options' => [
                'gradient' => 'linear-gradient(135deg, #00f5ff, #a855f7)',
                'cyan' => '#00f5ff',
                'purple' => '#a855f7'
            ]
        ]
    ]
];
