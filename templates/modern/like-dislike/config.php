<?php
/**
 * Modern Like/Dislike Template Configuration
 * Clean, modern binary voting with thumbs up/down
 */

return [
    'slug' => 'modern/like-dislike',
    'name' => 'Modern Like/Dislike',
    'description' => 'Clean like/dislike buttons with modern design - 2026 style',
        'version' => '1.0.1',
    'author' => 'RatePress',
    'category' => 'bipolar',
    'preview' => 'preview.png',
    'preview_theme' => 'dark',
    'preview_background' => '#0a0a0a',
    'preview_zoom' => 2.5,
    
    'settings' => [
        'show_counts' => [
            'type' => 'checkbox',
            'label' => 'Show vote counts',
            'default' => true
        ],
        'show_percentage' => [
            'type' => 'checkbox',
            'label' => 'Show percentage',
            'default' => false
        ],
        'size' => [
            'type' => 'select',
            'label' => 'Button size',
            'options' => [
                'small' => 'Small',
                'medium' => 'Medium',
                'large' => 'Large'
            ],
            'default' => 'medium'
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
