<?php
/**
 * Neon Dual Orbs Renderer
 */

namespace RatePress\Templates;

$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? 0;
$object_type = $data->object_type ?? 'post';

$ups = $stats['positive'] ?? 0;
$downs = $stats['negative'] ?? 0;
$user_liked = $user_has_rated && $user_value > 0;
$user_disliked = $user_has_rated && $user_value < 0;

$settings = $config['settings'] ?? [];
$size = $data->size ?? 'medium';
$show_counts = $data->show_counts ?? $settings['show_counts']['default'] ?? true;
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';

if ($is_js_mode) {
    $ups = 0;
    $downs = 0;
    $user_liked = false;
    $user_disliked = false;
}
?>

<div class="ratepress-widget ratepress-dual-orbs<?php echo $is_js_mode ? ' ratepress-js-mode' : ''; ?>"<?php echo $theme === 'dark' ? ' data-theme="dark"' : ''; ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="bipolar"
     data-template="neon/dual-orbs"
     data-size="<?php echo esc_attr($size); ?>"
     role="group"
     aria-label="<?php esc_attr_e('Dual Orbs rating widget', 'ratepress'); ?>">
     
    <button class="orb-btn orb-up <?php echo $user_liked ? 'active' : ''; ?>" 
            type="button"
            data-value="1"
            aria-pressed="<?php echo $user_liked ? 'true' : 'false'; ?>"
            aria-label="<?php echo esc_attr($user_liked ? __('Unlike', 'ratepress') : __('Like this', 'ratepress')); ?>"
            aria-describedby="orb-up-count-<?php echo esc_attr($object_id); ?>"
            title="<?php echo esc_attr($user_liked ? __('Unlike', 'ratepress') : __('Like this', 'ratepress')); ?>">
        <svg class="orb-icon" viewBox="0 0 24 24" fill="none">
            <path d="M12 5l0 14M5 12l7-7 7 7" 
                  stroke="currentColor" 
                  stroke-width="3" 
                  stroke-linecap="round" 
                  stroke-linejoin="round"/>
        </svg>
        <?php if ($show_counts): ?>
            <span class="orb-count" 
                  data-count="positive"
                  id="orb-up-count-<?php echo esc_attr($object_id); ?>">
                <?php echo esc_html($ups); ?>
            </span>
        <?php endif; ?>
    </button>
    
    <button class="orb-btn orb-down <?php echo $user_disliked ? 'active' : ''; ?>" 
            type="button"
            data-value="-1"
            aria-pressed="<?php echo $user_disliked ? 'true' : 'false'; ?>"
            aria-label="<?php echo esc_attr($user_disliked ? __('Undislike', 'ratepress') : __('Dislike this', 'ratepress')); ?>"
            aria-describedby="orb-down-count-<?php echo esc_attr($object_id); ?>"
            title="<?php echo esc_attr($user_disliked ? __('Undislike', 'ratepress') : __('Dislike this', 'ratepress')); ?>">
        <svg class="orb-icon" viewBox="0 0 24 24" fill="none">
            <path d="M12 19l0-14M5 12l7 7 7-7" 
                  stroke="currentColor" 
                  stroke-width="3" 
                  stroke-linecap="round" 
                  stroke-linejoin="round"/>
        </svg>
        <?php if ($show_counts): ?>
            <span class="orb-count" 
                  data-count="negative"
                  id="orb-down-count-<?php echo esc_attr($object_id); ?>">
                <?php echo esc_html($downs); ?>
            </span>
        <?php endif; ?>
    </button>
</div>
