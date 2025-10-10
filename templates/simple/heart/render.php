<?php
/**
 * Heart Template Renderer - Binary Category
 */

namespace RatePress\Templates;

// Get template data
$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? ($data->post_id ?? 0);
$object_type = $data->object_type ?? 'post';

// Calculate heart state
$hearts_count = $stats['positive'] ?? 0;
$is_loved = $user_has_rated && $user_value > 0;

// Template settings
$settings = $config['settings'] ?? [];
$size = $data->size ?? 'medium';
$show_counts = $data->show_counts ?? $settings['show_counts']['default'] ?? true;
$is_js_mode = $data->is_js_mode ?? false;

// In JS mode, show placeholders for better caching
if ($is_js_mode) {
    $hearts_count = 0; // Placeholder
    $is_loved = false; // Placeholder
}
?>

<div class="ratepress-widget ratepress-heart-widget<?php echo $is_js_mode ? ' ratepress-js-mode' : ''; ?>"
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="binary"
     data-template="heart"
     data-size="<?php echo esc_attr($size); ?>"
     role="group"
     aria-label="Heart rating widget">
     
    <button class="ratepress-heart-btn <?php echo $is_loved ? 'active' : ''; ?>" 
            type="button"
            data-value="1"
            aria-pressed="<?php echo $is_loved ? 'true' : 'false'; ?>"
            aria-label="<?php echo $is_loved ? 'Remove love' : 'Love this'; ?>"
            aria-describedby="heart-count-<?php echo esc_attr($object_id); ?>"
            title="<?php echo $is_loved ? 'Remove love' : 'Love this'; ?>">
            
        <svg class="ratepress-heart-icon" 
             viewBox="0 0 24 24" 
             fill="none" 
             xmlns="http://www.w3.org/2000/svg"
             aria-hidden="true">
            <path class="heart-outline" 
                  d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" 
                  stroke="currentColor" 
                  stroke-width="1.5" 
                  stroke-linejoin="round"/>
            <path class="heart-fill" 
                  d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" 
                  fill="currentColor"/>
        </svg>
        
        <?php if ($show_counts): ?>
            <span class="ratepress-heart-count" 
                  data-count="positive"
                  id="heart-count-<?php echo esc_attr($object_id); ?>"
                  aria-label="<?php echo $hearts_count; ?> person<?php echo $hearts_count !== 1 ? 's' : ''; ?> love<?php echo $hearts_count === 1 ? 's' : ''; ?> this">
                <?php echo number_format($hearts_count); ?>
            </span>
        <?php endif; ?>
    </button>
</div>