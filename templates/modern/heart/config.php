<?php
/**
 * Modern Heart Template Configuration - Binary Category
 * 
 * A modern glassmorphism heart template for binary ratings
 * Uses clean binary logic: 0 (no rating) or 1 (love it)
 */

return [
    'slug' => 'modern/heart',
    'name' => 'Modern Heart',
    'description' => 'Glassmorphism heart icon with animations for binary love ratings',
    'category' => 'binary',
    'version' => '1.0.0',
    'author' => 'RatePress',
    'author_url' => 'https://ratepress.com',
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
    ]
];
