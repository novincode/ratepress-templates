<?php
namespace RatePress\Templates;

$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? 0;
$object_type = $data->object_type ?? 'post';

$likes = $stats['positive'] ?? 0;
$dislikes = $stats['negative'] ?? 0;
$total = $likes + $dislikes;
$percentage = $total > 0 ? round(($likes / $total) * 100) : 0;

$settings = $config['settings'] ?? [];
$size = $data->size ?? 'medium';
$show_counts = $data->show_counts ?? $settings['show_counts']['default'] ?? true;
$show_percentage = $data->show_percentage ?? $settings['show_percentage']['default'] ?? false;
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';

if ($is_js_mode) {
    $likes = 0;
    $dislikes = 0;
    $total = 0;
    $percentage = 0;
    $user_value = 0;
    $user_has_rated = false;
}

$user_liked = $user_has_rated && $user_value > 0;
$user_disliked = $user_has_rated && $user_value < 0;
?>

<div class="ratepress-widget ratepress-likedislike<?php echo $is_js_mode ? ' ratepress-js-mode' : ''; ?>"<?php echo $theme === 'dark' ? ' data-theme="dark"' : ''; ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="bipolar"
     data-template="modern/like-dislike"
     data-size="<?php echo esc_attr($size); ?>">
     
    <div class="likedislike-buttons">
        <button class="like-btn <?php echo $user_liked ? 'active' : ''; ?>" 
                type="button"
                data-value="1"
                aria-label="<?php _e('Like', 'ratepress'); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
            </svg>
            <?php if ($show_counts): ?>
                <span class="count"><?php echo number_format($likes); ?></span>
            <?php endif; ?>
        </button>
        
        <button class="dislike-btn <?php echo $user_disliked ? 'active' : ''; ?>" 
                type="button"
                data-value="-1"
                aria-label="<?php _e('Dislike', 'ratepress'); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/>
            </svg>
            <?php if ($show_counts): ?>
                <span class="count"><?php echo number_format($dislikes); ?></span>
            <?php endif; ?>
        </button>
    </div>
    
    <?php if ($show_percentage && $total > 0): ?>
        <div class="likedislike-percentage">
            <?php echo $percentage; ?>% <?php _e('positive', 'ratepress'); ?>
        </div>
    <?php endif; ?>
</div>
