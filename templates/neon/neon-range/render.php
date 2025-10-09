<?php
/**
 * Neon Range Renderer
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
$user_rating = $user_has_rated ? $user_value * 10 : 7.5; // Default to 7.5

$settings = $config['settings'] ?? [];
$show_value = $data->show_value ?? $settings['show_value']['default'] ?? true;
$min_value = $settings['min_value']['default'] ?? 0;
$max_value = $settings['max_value']['default'] ?? 10;
$step = $settings['step']['default'] ?? 0.1;
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';

if ($is_js_mode) {
    $average = 0;
    $count = 0;
    $user_rating = 7.5;
}
?>

<div class="ratepress-widget ratepress-neon-range<?php echo $is_js_mode ? ' ratepress-js-mode' : ''; ?>"<?php echo $theme === 'dark' ? ' data-theme="dark"' : ''; ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="scale"
     data-template="neon/neon-range"
     role="group"
     aria-label="<?php _e('Neon Range rating widget'); ?>">
     
    <div class="range-container">
        <?php if ($show_value): ?>
            <div class="range-value"><?php echo number_format($user_rating, 1); ?></div>
        <?php endif; ?>
        <input type="range" 
               class="range-input" 
               min="<?php echo esc_attr($min_value); ?>" 
               max="<?php echo esc_attr($max_value); ?>" 
               step="<?php echo esc_attr($step); ?>" 
               value="<?php echo esc_attr($user_rating); ?>"
               data-value="<?php echo esc_attr($user_value); ?>"
               aria-label="<?php _e('Rate with slider'); ?>"
               title="<?php _e('Rate with slider'); ?>">
        <div class="range-labels">
            <span><?php echo htmlspecialchars($min_value); ?></span>
            <span><?php echo htmlspecialchars($max_value); ?></span>
        </div>
    </div>
</div>
