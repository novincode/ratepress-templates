<?php
/**
 * Modern Heart Template Configuration
 * Clean animated heart for binary love ratings
 */

return [
    'slug' => 'modern/heart',
    'name' => 'Modern Heart',
    'description' => 'Smooth animated heart - clean, modern, 2026 style',
        'version' => '1.0.1',
    'author' => 'RatePress',
    'category' => 'binary',
    'preview' => 'preview.png',
    'preview_theme' => 'dark',
    'preview_background' => '#0a0a0a',
    'preview_zoom' => 2.5,
    
    'settings' => [
        'show_counts' => [
            'type' => 'checkbox',
            'label' => 'Show love count',
            'default' => true
        ],
        'size' => [
            'type' => 'select',
            'label' => 'Heart size',
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
            '--heart-primary' => '#ff4757',
            '--heart-secondary' => '#ff3838',
            '--heart-bg' => 'rgba(255, 255, 255, 0.08)',
            '--heart-text' => 'rgba(255, 255, 255, 0.95)',
        ]
    ]
];
