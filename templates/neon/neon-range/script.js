// Neon Range Script
document.addEventListener('DOMContentLoaded', function() {
    // Update value display while dragging
    document.querySelectorAll('.ratepress-neon-range .range-input').forEach(function(input) {
        input.addEventListener('input', function() {
            const value = parseFloat(this.value);
            const display = this.closest('.range-container').querySelector('[data-display="value"]');
            if (display) {
                display.textContent = value.toFixed(1);
            }
        });
    });
});
