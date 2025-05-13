/**
 * Login page interactions
 * Handles input animations and alert notifications
 */

// Add animation and interaction effects
document.addEventListener('DOMContentLoaded', function() {
    // Animate input fields when they receive focus
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
            this.parentElement.style.transition = 'transform 0.3s ease';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
    
    // Close error/success messages after 5 seconds
    const alerts = document.querySelectorAll('.error, .success');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
});