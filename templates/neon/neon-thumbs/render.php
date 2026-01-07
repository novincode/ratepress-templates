<?php
/**
 * Neon Thumbs Renderer
 */

namespace RateKit\Templates;

defined( 'ABSPATH' ) || exit;

$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? 0;
$object_type = $data->object_type ?? 'post';

$likes = $stats['positive'] ?? 0;
$is_liked = $user_has_rated && $user_value > 0;

$settings = $config['settings'] ?? [];
$size = $data->size ?? 'medium';
$show_counts = $data->show_counts ?? $settings['show_counts']['default'] ?? true;
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';

if ($is_js_mode) {
    $likes = 0;
    $is_liked = false;
}
?>

<div class="ratekit-widget ratekit-neon-thumbs<?php echo esc_attr( $is_js_mode ? ' ratekit-js-mode' : '' ); ?>"<?php echo esc_attr( $theme === 'dark' ? ' data-theme="dark"' : '' ); ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="binary"
     data-template="neon/neon-thumbs"
     data-size="<?php echo esc_attr($size); ?>"
     role="group"
     aria-label="<?php esc_attr_e('Neon Thumbs rating widget', 'ratekit'); ?>">
     
    <button class="thumbs-btn <?php echo esc_attr( $is_liked ? 'active' : '' ); ?>" 
            type="button"
            data-value="1"
            aria-pressed="<?php echo esc_attr( $is_liked ? 'true' : 'false' ); ?>"
            aria-label="<?php echo esc_attr($is_liked ? __('Unlike', 'ratekit') : __('Like this', 'ratekit')); ?>"
            aria-describedby="thumbs-count-<?php echo esc_attr($object_id); ?>"
            title="<?php echo esc_attr($is_liked ? __('Unlike', 'ratekit') : __('Like this', 'ratekit')); ?>">
        <svg class="thumbs-icon" viewBox="0 0 24 24" fill="none">
            <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3" 
                  stroke="currentColor" 
                  stroke-width="2" 
                  stroke-linecap="round" 
                  stroke-linejoin="round"
                  fill="currentColor"/>
        </svg>
        <?php if ($show_counts): ?>
            <span class="thumbs-count" 
                  data-count="positive"
                  id="thumbs-count-<?php echo esc_attr($object_id); ?>">
                <?php echo esc_html($likes); ?>
            </span>
        <?php endif; ?>
    </button>
</div>
