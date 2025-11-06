<?php
/**
 * Neon Lightning Bolt Template Configuration
 * Futuristic glowing lightning bolt for binary ratings
 */

return [
    'slug' => 'neon/lightning-bolt',
    'name' => 'Neon Lightning Bolt',
    'description' => 'Futuristic glowing lightning bolt - energize with a single strike',
    'version' => '1.1.0',
    'author' => 'RateKit',
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
        'user_value' => 1, // User energized it
        'user_has_rated' => true,
        'category_stats' => [
            'positive' => 247
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
            'default' => 'cyan',
            'options' => [
                'cyan' => '#00f5ff',
                'purple' => '#a855f7',
                'pink' => '#f472b6'
            ]
        ]
    ]
];
