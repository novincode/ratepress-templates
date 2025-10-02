<?php
namespace RatePress\Templates;

$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? 0;
$object_type = $data->object_type ?? 'post';

$upvotes = $stats['positive'] ?? 0;
$downvotes = $stats['negative'] ?? 0;
$score = $upvotes - $downvotes;
$user_upvoted = $user_has_rated && $user_value > 0;
$user_downvoted = $user_has_rated && $user_value < 0;

$size = $data->size ?? 'medium';
$is_js_mode = $data->is_js_mode ?? false;
$theme = $data->theme ?? 'light';

if ($is_js_mode) {
    $score = 0;
    $user_upvoted = false;
    $user_downvoted = false;
}
?>

<div class="rp-vote-stack<?php echo $is_js_mode ? ' rp-js-mode' : ''; ?>"<?php echo $theme === 'dark' ? ' data-theme="dark"' : ''; ?>
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="bipolar"
     data-template="modern/vote-stack"
     data-size="<?php echo esc_attr($size); ?>"
     role="group"
     aria-label="<?php _e('Vote', 'ratepress'); ?>">
     
    <button class="rp-vote-btn rp-vote-up<?php echo $user_upvoted ? ' active' : ''; ?>" 
            type="button"
            data-value="1"
            aria-pressed="<?php echo $user_upvoted ? 'true' : 'false'; ?>"
            aria-label="<?php _e('Upvote', 'ratepress'); ?>">
        <svg viewBox="0 0 24 24" fill="none"><path d="M7 14l5-5 5 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
    
    <div class="rp-vote-score">
        <?php echo number_format($score); ?>
    </div>
    
    <button class="rp-vote-btn rp-vote-down<?php echo $user_downvoted ? ' active' : ''; ?>" 
            type="button"
            data-value="-1"
            aria-pressed="<?php echo $user_downvoted ? 'true' : 'false'; ?>"
            aria-label="<?php _e('Downvote', 'ratepress'); ?>">
        <svg viewBox="0 0 24 24" fill="none"><path d="M7 10l5 5 5-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
</div>
