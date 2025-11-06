<?php
/**
 * Neon Range Renderer
 */

namespace RateKit\Templates;

$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? 0;
$object_type = $data->object_type ?? 'post';

$average = $stats['average'] ?? 0;
$total = $stats['total'] ?? 0;
$display_average = $average * 10; // For 0-10 scale
$user_rating = $user_has_rated ? $user_value * 10 : 7.5; // Default to 7.5

$settings = $config['settings'] ?? [];
$show_value = $data->show_value ?? $settings['show_value']['default'] ?? true;
$show_counts = $data->show_counts ?? $settings['show_counts']['default'] ?? true;
$min_value = $settings['min_value']['default'] ?? 0;
$max_value = $settings['max_value']['default'] ?? 10;
$step = $settings['step']['default'] ?? 0.1;
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';
$size = $data->size ?? 'medium';

if ($is_js_mode) {
    $average = 0;
    $total = 0;
    $display_average = 0;
    $user_rating = 7.5;
}
?>

<div class="ratekit-widget ratekit-neon-range<?php echo $is_js_mode ? ' ratekit-js-mode' : ''; ?>"<?php echo $theme === 'dark' ? ' data-theme="dark"' : ''; ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="scale"
     data-template="neon/neon-range"
     data-size="<?php echo esc_attr($size); ?>"
     data-user-rating="<?php echo esc_attr($user_value); ?>"
     role="group"
     aria-label="<?php esc_attr_e('Neon Range rating widget', 'ratekit'); ?>">
     
    <div class="range-container">
        <?php if ($show_value): ?>
            <div class="range-value" data-display="value"><?php echo number_format($user_rating, 1); ?></div>
        <?php endif; ?>
        <input type="range" 
               class="range-input" 
               min="<?php echo esc_attr($min_value); ?>" 
               max="<?php echo esc_attr($max_value); ?>" 
               step="<?php echo esc_attr($step); ?>" 
               value="<?php echo esc_attr($user_rating); ?>"
               data-value="<?php echo esc_attr($user_value); ?>"
               aria-label="<?php esc_attr_e('Rate with slider', 'ratekit'); ?>"
               title="<?php esc_attr_e('Rate with slider', 'ratekit'); ?>">
        <div class="range-labels">
            <span><?php echo esc_html($min_value); ?></span>
            <span><?php echo esc_html($max_value); ?></span>
        </div>
    </div>

    <?php if ($show_counts && !$is_js_mode): ?>
    <div class="range-stats">
        <span data-stat="average" data-scale="10"><?php echo esc_html(number_format($display_average, 1)); ?>/10</span>
        <span data-stat="total">(<?php echo esc_html($total); ?> <?php esc_e('ratings', 'ratekit'); ?>)</span>
    </div>
    <?php endif; ?>
</div>
