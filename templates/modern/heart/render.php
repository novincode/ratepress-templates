<?php
/**
 * Modern Heart Template Render
 */

function render_modern_heart($data) {
    $object_id = $data['object_id'];
    $object_type = $data['object_type'];
    $category = $data['category'];
    $stats = $data['stats'][$category] ?? [];
    $user_rating = $data['user_rating'];
    
    $total = $stats['total'] ?? 0;
    $positive = $stats['positive'] ?? 0;
    $is_active = $user_rating && (float)$user_rating['value'] === 1.0;
    
    ?>
    <div class="ratepress-template modern-heart"
         data-object-id="<?php echo esc_attr($object_id); ?>"
         data-object-type="<?php echo esc_attr($object_type); ?>"
         data-category="<?php echo esc_attr($category); ?>"
         data-user-rating="<?php echo $is_active ? '1' : '0'; ?>">
        
        <button class="modern-heart__button <?php echo $is_active ? 'active' : ''; ?>"
                type="button"
                aria-label="<?php echo $is_active ? esc_attr__('Unlike', 'ratepress') : esc_attr__('Like', 'ratepress'); ?>"
                aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>">
            
            <svg class="modern-heart__icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
            
            <span class="modern-heart__particles">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <span class="particle particle-<?php echo $i + 1; ?>"></span>
                <?php endfor; ?>
            </span>
        </button>
        
        <?php if ($positive > 0): ?>
            <span class="modern-heart__count" aria-label="<?php echo esc_attr(sprintf(__('%d likes', 'ratepress'), $positive)); ?>">
                <?php echo number_format_i18n($positive); ?>
            </span>
        <?php endif; ?>
        
    </div>
    <?php
}
