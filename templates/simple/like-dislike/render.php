<?php
/**
 * Like-Dislike Template Renderer - Bipolar Category
 * 
 * Renders modern like/dislike buttons with ghostly aesthetics
 * Bipolar logic: -1 (dislike), 0 (neutral), 1 (like)
 */

namespace RatePress\Templates;

use const RatePress\TEXT_DOMAIN;

// Get template data
$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? ($data->post_id ?? 0);
$object_type = $data->object_type ?? 'post';

// Calculate like/dislike state
$likes_count = $stats['positive'] ?? 0;
$dislikes_count = $stats['negative'] ?? 0;
$user_liked = $user_has_rated && $user_value > 0;
$user_disliked = $user_has_rated && $user_value < 0;

// Template settings
$settings = $config['settings'] ?? [];
$size = $data->size ?? 'medium';
$show_counts = $data->show_counts ?? $settings['show_counts']['default'] ?? true;
$layout = $settings['layout']['default'] ?? 'horizontal';
$is_js_mode = $data->is_js_mode ?? false;

// In JS mode, show placeholders for better caching
if ($is_js_mode) {
    $likes_count = 0; // Placeholder
    $dislikes_count = 0; // Placeholder
    $user_liked = false; // Placeholder
    $user_disliked = false; // Placeholder
}

// Color scheme
$color_scheme = $settings['color_scheme']['default'] ?? 'default';
$color_schemes = [
    'default' => ['like' => '#10b981', 'dislike' => '#ef4444'],
    'blue' => ['like' => '#3b82f6', 'dislike' => '#f97316'],
    'purple' => ['like' => '#8b5cf6', 'dislike' => '#ec4899']
];
$colors = $color_schemes[$color_scheme] ?? $color_schemes['default'];
?>

<div class="ratepress-widget ratepress-like-dislike-widget<?php echo $is_js_mode ? ' ratepress-js-mode' : ''; ?>"
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="bipolar"
     data-template="like-dislike"
     data-size="<?php echo esc_attr($size); ?>"
     data-layout="<?php echo esc_attr($layout); ?>"
     role="group"
     aria-label="Like or dislike rating widget">
     
    <div class="ratepress-buttons-container" data-layout="<?php echo esc_attr($layout); ?>">
        <!-- Like Button -->
        <button class="ratepress-like-btn <?php echo $user_liked ? 'active' : ''; ?>" 
                type="button"
                data-value="1"
                aria-pressed="<?php echo $user_liked ? 'true' : 'false'; ?>"
                aria-label="<?php echo $user_liked ? 'Remove like' : 'Like this'; ?>"
                aria-describedby="like-count-<?php echo esc_attr($object_id); ?>"
                title="<?php echo $user_liked ? 'Remove like' : 'Like this'; ?>">
                
            <svg class="ratepress-like-icon" 
                 viewBox="0 0 24 24" 
                 fill="none" 
                 xmlns="http://www.w3.org/2000/svg"
                 aria-hidden="true">
                <path class="like-outline" 
                      d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3" 
                      stroke="currentColor" 
                      stroke-width="1.5" 
                      stroke-linecap="round" 
                      stroke-linejoin="round"/>
                <path class="like-fill" 
                      d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3" 
                      fill="currentColor"/>
            </svg>
            
            <?php if ($show_counts): ?>
                <span class="ratepress-like-count" 
                      data-count="positive"
                      id="like-count-<?php echo esc_attr($object_id); ?>"
                      aria-label="<?php echo $likes_count; ?> like<?php echo $likes_count !== 1 ? 's' : ''; ?>">
                    <?php echo number_format($likes_count); ?>
                </span>
            <?php endif; ?>
        </button>
        
        <!-- Dislike Button -->
        <button class="ratepress-dislike-btn <?php echo $user_disliked ? 'active' : ''; ?>" 
                type="button"
                data-value="-1"
                aria-pressed="<?php echo $user_disliked ? 'true' : 'false'; ?>"
                aria-label="<?php echo $user_disliked ? 'Remove dislike' : 'Dislike this'; ?>"
                aria-describedby="dislike-count-<?php echo esc_attr($object_id); ?>"
                title="<?php echo $user_disliked ? 'Remove dislike' : 'Dislike this'; ?>">
                
            <svg class="ratepress-dislike-icon" 
                 viewBox="0 0 24 24" 
                 fill="none" 
                 xmlns="http://www.w3.org/2000/svg"
                 aria-hidden="true">
                <path class="dislike-outline" 
                      d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-3" 
                      stroke="currentColor" 
                      stroke-width="1.5" 
                      stroke-linecap="round" 
                      stroke-linejoin="round"/>
                <path class="dislike-fill" 
                      d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-3" 
                      fill="currentColor"/>
            </svg>
            
            <?php if ($show_counts): ?>
                <span class="ratepress-dislike-count" 
                      data-count="negative"
                      id="dislike-count-<?php echo esc_attr($object_id); ?>"
                      aria-label="<?php echo $dislikes_count; ?> dislike<?php echo $dislikes_count !== 1 ? 's' : ''; ?>">
                    <?php echo number_format($dislikes_count); ?>
                </span>
            <?php endif; ?>
        </button>
    </div>
</div>