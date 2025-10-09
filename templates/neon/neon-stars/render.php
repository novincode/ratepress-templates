<?php
/**
 * Neon Stars Renderer
 */

namespace RatePress\Templates;

$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? 0;
$object_type = $data->object_type ?? 'post';

$average = $stats['average'] ?? 0;
$total = $stats['total'] ?? 0;
$display_average = $average * 5;
$user_rating = $user_has_rated ? round($user_value * 5) : 0; // Convert to 1-5 scale

$settings = $config['settings'] ?? [];
$size = $data->size ?? 'medium';
$show_average = $data->show_average ?? $settings['show_average']['default'] ?? true;
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';

if ($is_js_mode) {
    $average = 0;
    $total = 0;
    $display_average = 0;
    $user_rating = 0;
}
?>

<div class="ratepress-widget ratepress-neon-stars<?php echo $is_js_mode ? ' ratepress-js-mode' : ''; ?>"<?php echo $theme === 'dark' ? ' data-theme="dark"' : ''; ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="scale"
     data-template="neon/neon-stars"
     data-size="<?php echo esc_attr($size); ?>"
     data-user-rating="<?php echo esc_attr($user_value); ?>"
     role="group"
     aria-label="<?php _e('Neon Stars rating widget'); ?>">
     
        <div class="stars-input" 
         role="radiogroup" 
         aria-label="<?php _e('Rate from 1 to 5 stars'); ?>"
         aria-describedby="stars-info-<?php echo esc_attr($object_id); ?>">
        <?php for ($i = 1; $i <= 5; $i++): 
            $value = $i / 5;
            $isSelected = $user_has_rated && ($user_value * 5) >= $i;
        ?>
            <button class="star-btn <?php echo $isSelected ? 'active' : ''; ?>" 
                    type="button"
                    data-value="<?php echo esc_attr($value); ?>"
                    data-star="<?php echo $i; ?>"
                    role="radio"
                    aria-checked="<?php echo $isSelected ? 'true' : 'false'; ?>"
                    aria-label="<?php echo sprintf(__('Rate %d star%s'), $i, $i > 1 ? 's' : ''); ?>"
                    title="<?php echo sprintf(__('Rate %d star%s'), $i, $i > 1 ? 's' : ''); ?>">
                <svg class="star-icon" viewBox="0 0 24 24" fill="none">
                    <path class="star-outline" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" 
                          stroke="currentColor" stroke-width="2"/>
                    <path class="star-fill" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" 
                          fill="currentColor"/>
                </svg>
            </button>
        <?php endfor; ?>
    </div>
    
    <?php if ($show_average): ?>
        <div class="stars-info" id="stars-info-<?php echo esc_attr($object_id); ?>">
            <span class="stars-average"><?php echo number_format($display_average, 1); ?></span>
            <span class="stars-count">(<?php echo htmlspecialchars($total); ?> ratings)</span>
        </div>
    <?php endif; ?>
</div>
