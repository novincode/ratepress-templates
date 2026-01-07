<?php
/**
 * Neon Lightning Bolt Renderer
 */

namespace RateKit\Templates;

defined( 'ABSPATH' ) || exit;

$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? 0;
$object_type = $data->object_type ?? 'post';

$energized = $stats['positive'] ?? 0;
$is_energized = $user_has_rated && $user_value > 0;

$settings = $config['settings'] ?? [];
$size = $data->size ?? 'medium';
$show_counts = $data->show_counts ?? $settings['show_counts']['default'] ?? true;
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';

if ($is_js_mode) {
    $energized = 0;
    $is_energized = false;
}
?>

<div class="ratekit-widget ratekit-lightning-bolt<?php echo esc_attr( $is_js_mode ? ' ratekit-js-mode' : '' ); ?>"<?php echo esc_attr( $theme === 'dark' ? ' data-theme="dark"' : '' ); ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="binary"
     data-template="neon/lightning-bolt"
     data-size="<?php echo esc_attr($size); ?>"
     role="group"
     aria-label="<?php esc_attr_e('Lightning Bolt rating widget', 'ratekit'); ?>">
     
    <button class="lightning-btn <?php echo esc_attr( $is_energized ? 'active' : '' ); ?>" 
            type="button"
            data-value="1"
            aria-pressed="<?php echo esc_attr( $is_energized ? 'true' : 'false' ); ?>"
            aria-label="<?php echo esc_attr($is_energized ? __('Unenergize', 'ratekit') : __('Energize this', 'ratekit')); ?>"
            aria-describedby="lightning-count-<?php echo esc_attr($object_id); ?>"
            title="<?php echo esc_attr($is_energized ? __('Unenergize', 'ratekit') : __('Energize this', 'ratekit')); ?>">
        <svg class="lightning-icon" viewBox="0 0 24 24" fill="none">
            <path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z" 
                  fill="currentColor" 
                  stroke="currentColor" 
                  stroke-width="2" 
                  stroke-linejoin="round"/>
        </svg>
        <?php if ($show_counts): ?>
            <span class="lightning-count" 
                  data-count="positive"
                  id="lightning-count-<?php echo esc_attr($object_id); ?>">
                <?php echo esc_html($energized); ?>
            </span>
        <?php endif; ?>
    </button>
</div>
