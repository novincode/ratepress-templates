<?php
/**
 * Modern Heart Renderer
 */

namespace RateKit\Templates;

$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? 0;
$object_type = $data->object_type ?? 'post';

$loves = $stats['positive'] ?? 0;
$is_loved = $user_has_rated && $user_value > 0;

$settings = $config['settings'] ?? [];
$size = $data->size ?? 'medium';
$show_counts = $data->show_counts ?? $settings['show_counts']['default'] ?? true;
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';

if ($is_js_mode) {
    $loves = 0;
    $is_loved = false;
}
?>

<div class="ratekit-widget ratekit-heart<?php echo $is_js_mode ? ' ratekit-js-mode' : ''; ?>"<?php echo $theme === 'dark' ? ' data-theme="dark"' : ''; ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="binary"
     data-template="modern/heart"
     data-size="<?php echo esc_attr($size); ?>"
     role="group"
     aria-label="<?php esc_attr_e('Heart rating widget', 'ratekit'); ?>">
     
    <button class="heart-btn <?php echo $is_loved ? 'active' : ''; ?>"
            type="button"
            data-value="1"
            aria-pressed="<?php echo $is_loved ? 'true' : 'false'; ?>"
            aria-label="<?php echo esc_attr($is_loved ? __('Unlike', 'ratekit') : __('Love this', 'ratekit')); ?>"
            aria-describedby="heart-count-<?php echo esc_attr($object_id); ?>"
            title="<?php echo esc_attr($is_loved ? __('Unlike', 'ratekit') : __('Love this', 'ratekit')); ?>">
        <svg viewBox="0 0 24 24" fill="none">
            <path class="heart-path" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" stroke="currentColor" stroke-width="2" fill="currentColor"/>
        </svg>
        <?php if ($show_counts): ?>
            <span class="heart-count" 
                  data-count="positive"
                  id="heart-count-<?php echo esc_attr($object_id); ?>"
                  aria-label="<?php esc_attr_e('Number of loves:', 'ratekit'); ?> <?php echo esc_attr($loves); ?>">
                <?php echo esc_html(number_format($loves)); ?>
            </span>
        <?php endif; ?>
    </button>
</div>
