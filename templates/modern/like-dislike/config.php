<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Modern Like/Dislike Template Configuration
 * Clean, modern binary voting with thumbs up/down
 */

return [
    'slug' => 'modern/like-dislike',
    'name' => 'Modern Like/Dislike',
    'description' => 'Clean like/dislike buttons with modern design - 2026 style',
        'version' => '1.0.5',
    'author' => 'RateKit',
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
            'positive' => 89,
            'negative' => 12
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
        'layout' => [
            'type' => 'select',
            'default' => 'horizontal',
            'options' => [
                'horizontal' => 'Side by side',
                'vertical' => 'Stacked'
            ]
        ],
        'color_scheme' => [
            'type' => 'select',
            'default' => 'default',
            'options' => [
                'default' => 'Green/Red',
                'blue' => 'Blue/Orange',
                'purple' => 'Purple/Pink'
            ]
        ]
    ],
    
    'customization' => [
        'colors' => [
            '--likedislike-like' => '#22c55e',
            '--likedislike-like-hover' => '#16a34a',
            '--likedislike-dislike' => '#ef4444',
            '--likedislike-dislike-hover' => '#dc2626',
            '--likedislike-bg' => 'rgba(255, 255, 255, 0.08)',
            '--likedislike-text' => 'rgba(255, 255, 255, 0.95)',
        ]
    ]
];
