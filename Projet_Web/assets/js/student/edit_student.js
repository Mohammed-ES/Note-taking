/**
 * Student Edit Form JavaScript
 * Handles password visibility toggle and form interactions
 */

/**
 * Toggles password visibility between hidden and visible
 * Changes the eye icon accordingly
 */
function togglePassword() {
    const passwordField = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');
    
    if (passwordField.type === 'password') {
        // Show password
        passwordField.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        // Hide password
        passwordField.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
}

// Execute when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add any additional initialization code here
    
    // Example: Form validation enhancement
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(event) {
            const passwordField = document.getElementById('password');
            const usernameField = document.getElementById('username');
            
            // Example validation - can be customized as needed
            if (passwordField.value.length < 6) {
                alert('Password should be at least 6 characters long');
                event.preventDefault();
            }
        });
    }
});