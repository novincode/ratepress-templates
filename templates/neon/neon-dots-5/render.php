<?php
/**
 * Neon Dots 5 Renderer
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

<div class="ratepress-widget ratepress-neon-dots-5<?php echo $is_js_mode ? ' ratepress-js-mode' : ''; ?>"<?php echo $theme === 'dark' ? ' data-theme="dark"' : ''; ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="scale"
     data-template="neon/neon-dots-5"
     data-size="<?php echo esc_attr($size); ?>"
     data-user-rating="<?php echo esc_attr($user_value); ?>"
     role="group"
     aria-label="<?php _e('Neon Dots 5 rating widget'); ?>">
     
        <div class="dots-5-input" 
             role="radiogroup" 
             aria-label="<?php _e('Rate from 1 to 5 dots'); ?>"
             aria-describedby="stars-info-<?php echo esc_attr($object_id); ?>">
        <?php for ($i = 1; $i <= 5; $i++): 
            $value = $i / 5;
            $isSelected = $user_has_rated && ($user_value * 5) >= $i;
        ?>
            <button class="dot-5-btn <?php echo $isSelected ? 'active' : ''; ?>" 
                    type="button"
                    data-value="<?php echo esc_attr($value); ?>"
                    aria-pressed="<?php echo $isSelected ? 'true' : 'false'; ?>"
                    aria-label="<?php echo sprintf(__('Rate %d dot%s'), $i, $i > 1 ? 's' : ''); ?>"
                    title="<?php echo sprintf(__('Rate %d dot%s'), $i, $i > 1 ? 's' : ''); ?>">
            </button>
        <?php endfor; ?>
    </div>
    
    <?php if ($show_average): ?>
        <div class="stars-info" id="stars-info-<?php echo esc_attr($object_id); ?>">
            <span class="ratepress-average"><?php echo number_format($display_average, 1); ?></span>
            <span class="ratepress-count">(<?php echo htmlspecialchars($total); ?> ratings)</span>
        </div>
    <?php endif; ?>
</div>
