<?php
/**
 * Modern Heart Template Configuration
 * Clean animated heart for binary love ratings
 */

return [
    'slug' => 'modern/heart',
    'name' => 'Modern Heart',
    'description' => 'Smooth animated heart - clean, modern, 2026 style',
        'version' => '1.0.5',
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
        'user_value' => 1, // User loved it
        'user_has_rated' => true,
        'category_stats' => [
            'positive' => 128
        ]
    ],
    'preview' => 'preview.png',
    'preview_theme' => 'dark',
    'preview_background' => '#0a0a0a',
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
                'red' => '#e11d48',
                'pink' => '#e91e63',
                'purple' => '#9b59b6'
            ]
        ]
    ],
    
    'customization' => [
        'colors' => [
            '--heart-primary' => '#ff4757',
            '--heart-secondary' => '#ff3838',
            '--heart-bg' => 'rgba(255, 255, 255, 0.08)',
            '--heart-text' => 'rgba(255, 255, 255, 0.95)',
        ]
    ]
];
