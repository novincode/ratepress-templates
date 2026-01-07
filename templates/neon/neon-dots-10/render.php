<?php
/**
 * Neon Dots 10 Renderer
 */

namespace RateKit\Templates;

defined( 'ABSPATH' ) || exit;

$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? 0;
$object_type = $data->object_type ?? 'post';

$average = $stats['average'] ?? 0;
$total = $stats['total'] ?? 0;
$display_average = $average * 10; // For 1-10 scale
$user_rating = $user_has_rated ? round($user_value * 10) : 0; // Convert to 1-10 scale

$settings = $config['settings'] ?? [];
$size = $data->size ?? 'medium';
$show_counts = $data->show_counts ?? $settings['show_counts']['default'] ?? true;
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';

if ($is_js_mode) {
    $average = 0;
    $total = 0;
    $display_average = 0;
    $user_rating = 0;
}
?>

<div class="ratekit-widget ratekit-neon-dots-10<?php echo esc_attr( $is_js_mode ? ' ratekit-js-mode' : '' ); ?>"<?php echo esc_attr( $theme === 'dark' ? ' data-theme="dark"' : '' ); ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="scale"
     data-template="neon/neon-dots-10"
     data-size="<?php echo esc_attr($size); ?>"
     data-user-rating="<?php echo esc_attr($user_value); ?>"
     role="group"
     aria-label="<?php esc_attr_e('Neon Dots 10 rating widget', 'ratekit'); ?>">

        <div class="dots-10-input"
             role="radiogroup"
             aria-label="<?php esc_attr_e('Rate from 1 to 10 dots', 'ratekit'); ?>"
             aria-describedby="stars-info-<?php echo esc_attr($object_id); ?>">
        <?php for ($i = 1; $i <= 10; $i++):
            $value = $i / 10;
            $isSelected = $user_has_rated && ($user_value * 10) >= $i;
        ?>
            <button class="dot-10-btn <?php echo esc_attr( $isSelected ? 'active' : '' ); ?>"
                    type="button"
                    data-value="<?php echo esc_attr($value); ?>"
                    aria-pressed="<?php echo esc_attr( $isSelected ? 'true' : 'false' ); ?>"
                    aria-label="<?php echo esc_attr(sprintf(__('Rate %d dot%s', 'ratekit'), $i, $i > 1 ? 's' : '')); ?>"
                    title="<?php echo esc_attr(sprintf(__('Rate %d dot%s', 'ratekit'), $i, $i > 1 ? 's' : '')); ?>">
            </button>
        <?php endfor; ?>
    </div>

    <?php if ($show_counts): ?>
        <div class="stars-info" id="stars-info-<?php echo esc_attr($object_id); ?>">
            <span data-stat="average" data-scale="10"><?php echo esc_html(number_format($display_average, 1)); ?></span>
            <span data-stat="total">(<?php echo esc_html($total); ?> <?php esc_html_e('ratings', 'ratekit'); ?>)</span>
        </div>
    <?php endif; ?>
</div>
