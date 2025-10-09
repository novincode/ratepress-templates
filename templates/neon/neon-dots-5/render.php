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
$count = $stats['count'] ?? 0;
$user_rating = $user_has_rated ? round($user_value * 5) : 0; // Convert to 1-5 scale

$settings = $config['settings'] ?? [];
$size = $data->size ?? 'medium';
$show_average = $data->show_average ?? $settings['show_average']['default'] ?? true;
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';

if ($is_js_mode) {
    $average = 0;
    $count = 0;
    $user_rating = 0;
}
?>

<div class="ratepress-widget ratepress-neon-dots-5<?php echo $is_js_mode ? ' ratepress-js-mode' : ''; ?>"<?php echo $theme === 'dark' ? ' data-theme="dark"' : ''; ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="scale"
     data-template="neon/neon-dots-5"
     data-size="<?php echo esc_attr($size); ?>"
     role="group"
     aria-label="<?php _e('Neon Dots 5 rating widget'); ?>">
     
    <div class="dots-5-input">
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <button class="dot-5-btn <?php echo $user_rating >= $i ? 'active' : ''; ?>" 
                    type="button"
                    data-value="<?php echo $i / 5; ?>"
                    aria-pressed="<?php echo $user_rating >= $i ? 'true' : 'false'; ?>"
                    aria-label="<?php echo sprintf(__('Rate %d dot%s'), $i, $i > 1 ? 's' : ''); ?>"
                    title="<?php echo sprintf(__('Rate %d dot%s'), $i, $i > 1 ? 's' : ''); ?>">
            </button>
        <?php endfor; ?>
    </div>
    
    <?php if ($show_average): ?>
        <div class="stars-info">
            <span class="stars-average"><?php echo number_format($average, 1); ?></span>
            <span class="stars-count">(<?php echo htmlspecialchars($count); ?> ratings)</span>
        </div>
    <?php endif; ?>
</div>
