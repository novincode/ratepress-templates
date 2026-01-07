<?php
/**
 * Five-Stars Template Renderer - Scale Category
 */

namespace RateKit\Templates;

// Get template data
$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? ($data->post_id ?? 0);
$object_type = $data->object_type ?? 'post';
$size = $data->size ?? 'medium';

// Calculate star values
$average = $stats['average'] ?? 0; // 0.0 to 1.0
$total_ratings = $stats['total'] ?? 0;
$display_average = $average * 5; // Convert to 5-star scale

// Template settings
$settings = $config['settings'] ?? [];
$show_counts = $data->show_counts ?? $settings['show_counts']['default'] ?? true;
$is_js_mode = $data->is_js_mode ?? false;

// In JS mode, show placeholders for better caching
if ($is_js_mode) {
    $average = 0; // Placeholder
    $total_ratings = 0; // Placeholder
    $display_average = 0; // Placeholder
    $user_value = 0; // Placeholder
    $user_has_rated = false; // Placeholder
}
?>

<div class="ratekit-widget ratekit-stars-widget<?php echo $is_js_mode ? ' ratekit-js-mode' : ''; ?>" 
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="scale" 
     data-template="five-stars"
     data-size="<?php echo esc_attr($size); ?>"
     data-user-rating="<?php echo esc_attr($user_value); ?>"
     role="group"
     aria-label="<?php esc_attr_e('Star rating widget', 'ratekit'); ?>">
     
    <div class="ratekit-stars-container">
        <!-- Interactive Rating Input -->
        <div class="ratekit-stars-input" 
             role="radiogroup" 
             aria-label="<?php esc_attr_e('Rate from 1 to 5 stars', 'ratekit'); ?>"
             aria-describedby="stars-info-<?php echo esc_attr($object_id); ?>"><?php for ($i = 1; $i <= 5; $i++): 
                $value = $i / 5;
                $isSelected = $user_has_rated && ($user_value * 5) >= $i;
            ?>
                <button class="ratekit-star-btn <?php echo $isSelected ? 'active' : ''; ?>" 
                        type="button"
                        data-value="<?php echo esc_attr($value); ?>"
                        data-star="<?php echo esc_attr($i); ?>"
                        role="radio"
                        aria-checked="<?php echo $isSelected ? 'true' : 'false'; ?>"
                        aria-label="<?php 
                            /* translators: %d: star rating number from 1 to 5 */
                            echo esc_attr(sprintf(__('Rate %d out of 5 stars', 'ratekit'), $i)); 
                        ?>"
                        title="<?php 
                            /* translators: %d: star rating number */
                            echo esc_attr(sprintf(_n('%d star', '%d stars', $i, 'ratekit'), $i)); 
                        ?>">
                        
                    <svg class="ratekit-star-icon" 
                         viewBox="0 0 24 24" 
                         fill="none" 
                         xmlns="http://www.w3.org/2000/svg"
                         aria-hidden="true">
                        <path class="star-outline" 
                              d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" 
                              stroke="currentColor" 
                              stroke-width="1.5" 
                              stroke-linejoin="round"/>
                        <path class="star-fill" 
                              d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" 
                              fill="currentColor"/>
                    </svg>
                </button>
            <?php endfor; ?>
        </div>
        
        <!-- Rating Information -->
        <?php if ($show_counts && ($total_ratings > 0 || $is_js_mode)): ?>
            <div class="ratekit-stars-info" id="stars-info-<?php echo esc_attr($object_id); ?>">
                <span class="ratekit-average" 
                      data-stat="average"
                      data-scale="5"
                      aria-label="<?php 
                          /* translators: %s: average rating value */
                          echo esc_attr(sprintf(__('Average rating: %s out of 5 stars', 'ratekit'), number_format($display_average, 1))); 
                      ?>">
                    <?php echo esc_html(number_format($display_average, 1)); ?>
                </span>
                
                <span class="ratekit-count" 
                      data-stat="total"
                      aria-label="<?php 
                          /* translators: %d: number of ratings */
                          echo esc_attr(sprintf(_n('%d rating', '%d ratings', $total_ratings, 'ratekit'), $total_ratings)); 
                      ?>">
                    <?php echo esc_html(number_format($total_ratings)); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
</div>