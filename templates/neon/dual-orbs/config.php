<?php
/**
 * Neon Dual Orbs Template Configuration
 * Futuristic glowing dual orbs for bipolar ratings
 */

return [
    'slug' => 'neon/dual-orbs',
    'name' => 'Neon Dual Orbs',
    'description' => 'Futuristic glowing dual orbs - up or down energy',
    'version' => '1.0.1',
    'author' => 'RatePress',
    'category' => 'bipolar',
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
            'positive' => 342,
            'negative' => 28
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
                'small' => '18px',
                'medium' => '20px', 
                'large' => '24px'
            ]
        ],
        'show_counts' => [
            'type' => 'boolean',
            'default' => true
        ],
        'color_scheme' => [
            'type' => 'select',
            'default' => 'purple-pink',
            'options' => [
                'purple-pink' => '#a855f7,#f472b6',
                'cyan-purple' => '#00f5ff,#a855f7',
                'pink-cyan' => '#f472b6,#00f5ff'
            ]
        ]
    ]
];
