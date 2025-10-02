<?php
/**
 * Modern Heart Template Configuration
 *
 * A modern glassmorphism heart template for binary ratings
 * Uses clean binary logic: 0 (no rating) or 1 (love it)
 */

return [
    // Basic Information
    'slug' => 'modern/heart',
    'name' => 'Modern Heart',
    'description' => 'Glassmorphism heart icon with animations for binary love ratings',
    'category' => 'binary', // 'binary', 'bipolar', or 'scale'
    'version' => '1.0.2',

    // Author & Attribution
    'author' => 'RatePress',
    'author_url' => 'https://ratepress.com',

    // Discovery & Distribution
    'tags' => [
        'heart',
        'love',
        'binary',
        'glassmorphism',
        'modern',
        'animated'
    ],
    'preview' => 'https://raw.githubusercontent.com/novincode/ratepress-templates/main/templates/modern/heart/preview.png',
    'download_url' => 'https://github.com/novincode/ratepress-templates/archive/refs/heads/main.zip',

    // Version Requirements
    'min_ratepress_version' => '1.0.0',

    // Technical Requirements
    'styles' => ['style.css'],
    'scripts' => [], // Leave empty if using core JS
    'requires_core_js' => true, // RatePress core JS handles all interactions

    // Features & Capabilities
    'supports' => [
        'objects' => ['post', 'comment'], // Supported object types
        'responsive' => true,             // Mobile-friendly design
        'dark_mode' => true               // Supports dark mode
    ],

    // Demo & Preview Data
    'demo_data' => [
        'user_value' => 1,        // User loved it (1 = loved, 0 = not rated)
        'user_has_rated' => true, // User has submitted a rating
        'category_stats' => [
            'positive' => 128     // Number of positive ratings
        ]
    ],

    // Customization Settings
    'settings' => [
        'icon_size' => [
            'type' => 'select',
            'label' => 'Icon Size',
            'description' => 'Choose the size of the heart icon',
            'default' => 'medium',
            'options' => [
                'small' => 'Small (20px)',
                'medium' => 'Medium (24px)',
                'large' => 'Large (28px)'
            ]
        ],

        'show_counts' => [
            'type' => 'boolean',
            'label' => 'Show Rating Counts',
            'description' => 'Display the number of ratings next to the heart',
            'default' => true
        ],

        'color_scheme' => [
            'type' => 'select',
            'label' => 'Color Scheme',
            'description' => 'Choose the color theme for the heart',
            'default' => 'red',
            'options' => [
                'red' => 'Red (#e11d48)',
                'pink' => 'Pink (#e91e63)',
                'purple' => 'Purple (#9b59b6)'
            ]
        ]
    ]
];
