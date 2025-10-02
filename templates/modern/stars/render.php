<?php
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

$settings = $config['settings'] ?? [];
$size = $data->size ?? 'medium';
$show_counts = $data->show_counts ?? $settings['show_counts']['default'] ?? true;
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';

if ($is_js_mode) {
    $average = 0;
    $total = 0;
    $display_average = 0;
    $user_value = 0;
    $user_has_rated = false;
}
?>

<div class="ratepress-widget ratepress-stars<?php echo $is_js_mode ? ' ratepress-js-mode' : ''; ?>"<?php echo $theme === 'dark' ? ' data-theme="dark"' : ''; ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="scale"
     data-template="modern/stars"
     data-size="<?php echo esc_attr($size); ?>">
     
    <div class="stars-input">
        <?php for ($i = 1; $i <= 5; $i++): 
            $value = $i / 5;
            $isSelected = $user_has_rated && ($user_value * 5) >= $i;
        ?>
            <button class="star-btn <?php echo $isSelected ? 'active' : ''; ?>" 
                    type="button"
                    data-value="<?php echo esc_attr($value); ?>"
                    data-star="<?php echo $i; ?>"
                    aria-label="<?php printf(__('%d stars', 'ratepress'), $i); ?>">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </button>
        <?php endfor; ?>
    </div>
    
    <?php if ($show_counts && $total > 0): ?>
        <div class="stars-info">
            <span class="average"><?php echo number_format($display_average, 1); ?></span>
            <span class="count">(<?php echo number_format($total); ?>)</span>
        </div>
    <?php endif; ?>
</div>
