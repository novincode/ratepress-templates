/**
 * RatePress Core JavaScript v2 - Headless & Scalable
 * 
 * Template-agnostic rating system using ONLY data attributes
 * Works with any design - binary, bipolar, scale
 */

(function(window) {
    'use strict';

    const RatePress = {
        /**
         * Submit rating with category and value for any object
         * @param {number} objectId - Object ID to rate
         * @param {string} objectType - Object type ('post' or 'comment')
         * @param {string} category - Category (binary, bipolar, scale)
         * @param {number} value - Rating value
         * @returns {Promise} AJAX response
         */
        rate: async function(objectId, objectType, category, value) {
            // Validate inputs
            if (!objectId || !objectType || !category || value === null || value === undefined) {
                console.error('RatePress: Invalid rating parameters', { objectId, objectType, category, value });
                throw new Error('Invalid rating parameters');
            }

            const response = await fetch(`${ratepressAjax.api_url}/rate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': ratepressAjax.rest_nonce
                },
                body: JSON.stringify({
                    object_id: objectId,
                    object_type: objectType,
                    category: category,
                    value: value
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        },

        /**
         * Get object statistics
         * @param {number} objectId - Object ID
         * @param {string} objectType - Object type ('post' or 'comment')
         * @returns {Promise} Object stats
         */
        getStats: async function(objectId, objectType = 'post') {
            const url = new URL(ratepressAjax.api_url + '/stats/' + objectId);
            url.searchParams.set('object_type', objectType);
            
            const response = await fetch(url.toString(), {
                headers: {
                    'X-WP-Nonce': ratepressAjax.rest_nonce
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            
            // Normalize response format
            if (result.success && result.data) {
                return { success: true, stats: result.data, user_ratings: {} };
            } else if (result.success && result.stats) {
                return result;
            } else {
                throw new Error('Invalid response format');
            }
        },

        /**
         * Update widget using ONLY data attributes - completely headless
         * @param {Element} widget - Widget element
         * @param {Object} data - Rating data from server
         */
        updateWidget: function(widget, data) {
            const category = widget.dataset.category;
            if (!category || !data.stats || !data.stats[category]) {
                return;
            }

            const categoryStats = data.stats[category];
            const userRating = data.user_ratings?.[category] || null;

            // Update data-user-rating attribute on widget
            if (userRating) {
                widget.dataset.userRating = userRating.value;
            } else {
                delete widget.dataset.userRating;
            }

            // Update elements with data-count attributes (for displaying stats)
            if (category === 'binary') {
                this.updateDataCount(widget, 'positive', categoryStats.positive || 0);
            } else if (category === 'bipolar') {
                this.updateDataCount(widget, 'positive', categoryStats.positive || 0);
                this.updateDataCount(widget, 'negative', categoryStats.negative || 0);
                this.updateDataCount(widget, 'score', (categoryStats.positive || 0) - (categoryStats.negative || 0));
            } else if (category === 'scale') {
                // Update average and total displays
                const avgElements = widget.querySelectorAll('[data-stat="average"]');
                avgElements.forEach(el => {
                    const scale = parseFloat(el.dataset.scale || 5);
                    const displayValue = (categoryStats.average || 0) * scale;
                    el.textContent = displayValue.toFixed(1);
                });

                const totalElements = widget.querySelectorAll('[data-stat="total"]');
                totalElements.forEach(el => {
                    el.textContent = categoryStats.total || 0;
                });
            }

            // Update active state on rating buttons based on data-value
            this.updateActiveButtons(widget, category, userRating);

            // Trigger custom event
            widget.dispatchEvent(new CustomEvent('ratepress:updated', {
                detail: { category, stats: categoryStats, userRating }
            }));
        },

        /**
         * Update elements with data-count attribute
         * @param {Element} widget - Widget element
         * @param {string} countType - Type of count (positive, negative, score)
         * @param {number} value - Count value
         */
        updateDataCount: function(widget, countType, value) {
            const elements = widget.querySelectorAll(`[data-count="${countType}"]`);
            elements.forEach(el => {
                el.textContent = value;
            });
        },

        /**
         * Update active state on buttons based on user rating
         * @param {Element} widget - Widget element
         * @param {string} category - Rating category
         * @param {Object|null} userRating - User rating data
         */
        updateActiveButtons: function(widget, category, userRating) {
            const buttons = widget.querySelectorAll('[data-value]');
            
            if (category === 'binary') {
                // Binary: activate button with value="1" if user rated
                buttons.forEach(btn => {
                    const btnValue = parseFloat(btn.dataset.value);
                    const isActive = userRating && userRating.value === 1 && btnValue === 1;
                    btn.classList.toggle('active', isActive);
                    if (btn.hasAttribute('aria-pressed')) {
                        btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    }
                });
            } else if (category === 'bipolar') {
                // Bipolar: activate button matching user's value (-1, 0, 1)
                buttons.forEach(btn => {
                    const btnValue = parseFloat(btn.dataset.value);
                    const isActive = userRating && userRating.value === btnValue;
                    btn.classList.toggle('active', isActive);
                    if (btn.hasAttribute('aria-pressed')) {
                        btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    }
                });
            } else if (category === 'scale') {
                // Scale: activate all buttons up to user's rating
                // Get scale from first button or default to 5
                const firstBtn = buttons[0];
                const scale = firstBtn ? this.getScaleFromButtons(buttons) : 5;
                
                buttons.forEach(btn => {
                    const btnValue = parseFloat(btn.dataset.value);
                    // User's rating in scale units (e.g., 0.8 * 5 = 4 stars)
                    const userScaleValue = userRating ? userRating.value * scale : 0;
                    // Activate if button value <= user's scaled value
                    const isActive = userRating && btnValue <= userRating.value;
                    btn.classList.toggle('active', isActive);
                    if (btn.hasAttribute('aria-checked')) {
                        btn.setAttribute('aria-checked', isActive ? 'true' : 'false');
                    }
                });
            }
        },

        /**
         * Detect scale from buttons (5-star, 10-dot, etc.)
         * @param {NodeList} buttons - Button elements
         * @returns {number} Scale size
         */
        getScaleFromButtons: function(buttons) {
            if (buttons.length === 0) return 5;
            
            // Get all unique values
            const values = Array.from(buttons).map(btn => parseFloat(btn.dataset.value)).sort((a, b) => b - a);
            
            // Highest value tells us the scale
            // If highest is 1.0 and there are 5 buttons, it's 5-point scale
            // If highest is 1.0 and there are 10 buttons, it's 10-point scale
            return buttons.length;
        },

        /**
         * Initialize widgets on page load
         */
        init: function() {
            if (window._ratepressInitialized) return;
            window._ratepressInitialized = true;
            
            // Event delegation for all rating clicks
            document.addEventListener('click', this.handleClickEvent.bind(this));
            
            // Handle range inputs separately
            document.addEventListener('input', this.handleInputEvent.bind(this));
            document.addEventListener('change', this.handleChangeEvent.bind(this));

            // Load initial data
            this.loadAllWidgets();
        },

        /**
         * Handle click events with event delegation
         * @param {Event} event - Click event
         */
        handleClickEvent: function(event) {
            const element = event.target.closest('[data-value]');
            if (!element) return;
            
            // Skip if it's an input element (handled by change event)
            if (element.tagName === 'INPUT') return;
            
            const widget = element.closest('.ratepress-widget');
            if (!widget) return;
            
            event.preventDefault();
            this.submitRating(element, widget);
        },

        /**
         * Handle input events for range sliders
         * @param {Event} event - Input event
         */
        handleInputEvent: function(event) {
            const element = event.target;
            if (!element.dataset.value && element.type !== 'range') return;
            
            const widget = element.closest('.ratepress-widget');
            if (!widget) return;

            // Update display value if there's a data-display element
            const displayEl = widget.querySelector('[data-display="value"]');
            if (displayEl) {
                const scale = parseFloat(element.max || 10);
                const displayValue = parseFloat(element.value);
                displayEl.textContent = displayValue.toFixed(1);
            }
        },

        /**
         * Handle change events for range sliders
         * @param {Event} event - Change event
         */
        handleChangeEvent: function(event) {
            const element = event.target;
            if (element.type !== 'range') return;
            
            const widget = element.closest('.ratepress-widget');
            if (!widget) return;

            // For range inputs, normalize value to 0-1 scale
            const scale = parseFloat(element.max || 10);
            const min = parseFloat(element.min || 0);
            const rawValue = parseFloat(element.value);
            const normalizedValue = (rawValue - min) / (scale - min);
            
            // Temporarily set the normalized value
            element.dataset.value = normalizedValue.toFixed(3);
            
            this.submitRating(element, widget);
        },

        /**
         * Submit rating from element
         * @param {Element} element - Element with data-value
         * @param {Element} widget - Widget container
         */
        submitRating: function(element, widget) {
            const objectId = parseInt(widget.dataset.objectId);
            const objectType = widget.dataset.objectType || 'post';
            const category = widget.dataset.category;
            const value = parseFloat(element.dataset.value);

            if (!objectId || !category || isNaN(value)) {
                console.error('RatePress: Invalid rating data', { objectId, objectType, category, value });
                return;
            }

            widget.classList.add('loading');

            this.rate(objectId, objectType, category, value)
                .then(response => {
                    // Update ALL widgets for this object
                    const allWidgets = document.querySelectorAll(
                        `.ratepress-widget[data-object-id="${objectId}"][data-object-type="${objectType}"]`
                    );
                    
                    allWidgets.forEach(w => this.updateWidget(w, response));
                })
                .catch(error => {
                    console.error('Rating submission failed:', error);
                    widget.classList.add('error');
                    setTimeout(() => widget.classList.remove('error'), 3000);
                })
                .finally(() => {
                    widget.classList.remove('loading');
                });
        },

        /**
         * Load data for all widgets on page
         */
        loadAllWidgets: function() {
            const widgets = document.querySelectorAll('.ratepress-widget[data-object-id]');
            
            // Group by object to avoid duplicate requests
            const objectGroups = {};
            
            widgets.forEach(widget => {
                const objectId = widget.dataset.objectId;
                const objectType = widget.dataset.objectType || 'post';
                const key = `${objectType}-${objectId}`;
                
                if (!objectGroups[key]) {
                    objectGroups[key] = { objectId, objectType, widgets: [] };
                }
                objectGroups[key].widgets.push(widget);
            });
            
            // Load each unique object
            Object.values(objectGroups).forEach(group => {
                group.widgets.forEach(widget => widget.classList.add('loading'));
                
                this.getStats(parseInt(group.objectId), group.objectType)
                    .then(response => {
                        group.widgets.forEach(widget => this.updateWidget(widget, response));
                    })
                    .catch(error => {
                        console.error('Failed to load widget data:', error);
                    })
                    .finally(() => {
                        group.widgets.forEach(widget => widget.classList.remove('loading'));
                    });
            });
        },

        // Legacy methods for backward compatibility
        ratePost: async function(postId, category, value) {
            return this.rate(postId, 'post', category, value);
        },

        rateComment: async function(commentId, category, value) {
            return this.rate(commentId, 'comment', category, value);
        },

        getPostStats: async function(postId) {
            return this.getStats(postId, 'post');
        },

        updateUI: function(elementId, data) {
            const element = document.getElementById(elementId);
            if (!element) return;

            const objectId = element.dataset.objectId;
            const objectType = element.dataset.objectType || 'post';
            if (!objectId) return;

            const widgets = document.querySelectorAll(
                `.ratepress-widget[data-object-id="${objectId}"][data-object-type="${objectType}"]`
            );
            widgets.forEach(widget => this.updateWidget(widget, data));
        }
    };

    // Make globally available
    window.RatePress = RatePress;

    // Auto-initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => RatePress.init());
    } else {
        RatePress.init();
    }

})(window);
