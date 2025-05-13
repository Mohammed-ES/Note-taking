/**
 * Planning Student JavaScript
 * Handles alerts, modals and event confirmations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Close alerts
    const closeButtons = document.querySelectorAll('.close-alert');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.display = 'none';
        });
    }, 5000);
    
    // Modal functionality
    const modal = document.getElementById('deleteModal');
    const closeModal = document.querySelector('.close');
    const cancelDelete = document.getElementById('cancelDelete');
    
    closeModal.onclick = function() {
        modal.style.display = 'none';
    }
    
    cancelDelete.onclick = function() {
        modal.style.display = 'none';
    }
    
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});

// Confirm delete function
function confirmDelete(id, title) {
    const modal = document.getElementById('deleteModal');
    const eventTitle = document.getElementById('eventTitle');
    const confirmDelete = document.getElementById('confirmDelete');
    
    eventTitle.textContent = title;
    confirmDelete.href = '?delete=' + id;
    modal.style.display = 'block';
}