/**
 * Modern Heart Template JavaScript
 * Handles rating interaction with smooth animations
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        const templates = document.querySelectorAll('.ratepress-template.modern-heart');
        templates.forEach(template => initTemplate(template));
    }

    function initTemplate(template) {
        const button = template.querySelector('.modern-heart__button');
        if (!button) return;

        // Handle click
        button.addEventListener('click', function(e) {
            e.preventDefault();
            handleRating(template, button);
        });

        // Keyboard accessibility
        button.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                handleRating(template, button);
            }
        });
    }

    function handleRating(template, button) {
        const objectId = parseInt(template.dataset.objectId);
        const objectType = template.dataset.objectType;
        const category = template.dataset.category;
        const currentRating = parseFloat(template.dataset.userRating) || 0;
        
        // Toggle value: 1 if not rated, 0 if already rated
        const newValue = currentRating === 1 ? 0 : 1;

        // Optimistic UI update
        const wasActive = button.classList.contains('active');
        button.classList.toggle('active');
        button.setAttribute('aria-pressed', !wasActive);
        button.setAttribute('aria-label', !wasActive ? 'Unlike' : 'Like');

        // Submit rating
        if (typeof RatePress !== 'undefined' && RatePress.submitRating) {
            RatePress.submitRating(objectId, objectType, category, newValue)
                .then(response => {
                    // Update with server response
                    updateUI(template, response.data);
                    template.dataset.userRating = newValue;

                    // Trigger particle animation if liked
                    if (newValue === 1) {
                        triggerParticles(button);
                    }
                })
                .catch(error => {
                    // Revert on error
                    console.error('Rating failed:', error);
                    button.classList.toggle('active');
                    button.setAttribute('aria-pressed', wasActive);
                    button.setAttribute('aria-label', wasActive ? 'Unlike' : 'Like');

                    // Show error message
                    showError(template, error.message || 'Rating failed. Please try again.');
                });
        } else {
            console.error('RatePress API not available');
            // Revert UI
            button.classList.toggle('active');
            button.setAttribute('aria-pressed', wasActive);
        }
    }

    function updateUI(template, data) {
        const stats = data.stats || {};
        const categoryStats = stats[template.dataset.category] || {};
        const positive = categoryStats.positive || 0;

        // Update count
        let countEl = template.querySelector('.modern-heart__count');
        
        if (positive > 0) {
            if (!countEl) {
                countEl = document.createElement('span');
                countEl.className = 'modern-heart__count';
                countEl.setAttribute('aria-label', `${positive} likes`);
                template.appendChild(countEl);
            }
            countEl.textContent = positive.toLocaleString();
        } else if (countEl) {
            countEl.remove();
        }
    }

    function triggerParticles(button) {
        // Add temporary class to trigger particle animation
        const particles = button.querySelector('.modern-heart__particles');
        if (particles) {
            particles.style.opacity = '1';
            setTimeout(() => {
                particles.style.opacity = '0';
            }, 600);
        }
    }

    function showError(template, message) {
        // Create error message element
        const error = document.createElement('div');
        error.className = 'modern-heart__error';
        error.textContent = message;
        error.style.cssText = 'position: absolute; top: 100%; left: 0; margin-top: 8px; padding: 8px 12px; background: #fee; color: #c00; border-radius: 4px; font-size: 12px; white-space: nowrap; z-index: 1000;';

        template.style.position = 'relative';
        template.appendChild(error);

        // Remove after 3 seconds
        setTimeout(() => {
            error.remove();
        }, 3000);
    }

    // Listen for custom events from other widgets
    document.addEventListener('ratepress:updated', function(e) {
        const detail = e.detail;
        if (detail && detail.objectId && detail.objectType) {
            // Update all matching templates on the page
            const templates = document.querySelectorAll('.ratepress-template.modern-heart');
            templates.forEach(template => {
                if (parseInt(template.dataset.objectId) === detail.objectId &&
                    template.dataset.objectType === detail.objectType) {
                    updateUI(template, detail);
                }
            });
        }
    });

})();
