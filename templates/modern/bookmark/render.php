<?php
namespace RatePress\Templates;

$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? 0;
$object_type = $data->object_type ?? 'post';

$bookmarks = $stats['positive'] ?? 0;
$is_bookmarked = $user_has_rated && $user_value > 0;

$settings = $config['settings'] ?? [];
$size = $data->size ?? 'medium';
$show_counts = $data->show_counts ?? $settings['show_counts']['default'] ?? true;
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';

if ($is_js_mode) {
    $bookmarks = 0;
    $is_bookmarked = false;
}
?>

<div class="ratepress-widget ratepress-bookmark<?php echo $is_js_mode ? ' ratepress-js-mode' : ''; ?>"<?php echo $theme === 'dark' ? ' data-theme="dark"' : ''; ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="binary"
     data-template="modern/bookmark"
     data-size="<?php echo esc_attr($size); ?>"
     role="group"
     aria-label="<?php esc_attr_e('Bookmark rating widget', 'ratepress'); ?>">
     
    <button class="bookmark-btn <?php echo $is_bookmarked ? 'active' : ''; ?>"
            type="button"
            data-value="1"
            aria-pressed="<?php echo $is_bookmarked ? 'true' : 'false'; ?>"
            aria-label="<?php echo esc_attr($is_bookmarked ? __('Remove bookmark', 'ratepress') : __('Bookmark', 'ratepress')); ?>"
            aria-describedby="bookmark-count-<?php echo esc_attr($object_id); ?>"
            title="<?php echo esc_attr($is_bookmarked ? __('Remove bookmark', 'ratepress') : __('Bookmark', 'ratepress')); ?>">
        <svg viewBox="0 0 24 24" fill="none">
            <path class="bookmark-path" d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" stroke="currentColor" stroke-width="2" fill="currentColor"/>
        </svg>
        <?php if ($show_counts): ?>
            <span class="bookmark-count" 
                  data-count="positive"
                  id="bookmark-count-<?php echo esc_attr($object_id); ?>"
                  aria-label="<?php esc_attr_e('Number of bookmarks:', 'ratepress'); ?> <?php echo esc_attr($bookmarks); ?>">
                <?php echo esc_html(number_format($bookmarks)); ?>
            </span>
        <?php endif; ?>
    </button>
</div>
