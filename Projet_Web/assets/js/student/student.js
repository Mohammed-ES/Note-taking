/**
 * Student Management JavaScript Functions
 * Handles user interactions and confirmations
 */

/**
 * Confirms student deletion with a dialog box
 * @param {number} id - The ID of the student to delete
 */
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this student?")) {
        window.location.href = 'delete_student.php?id=' + id;
    }
}

// Add any additional student management functions here

// Initialize any functionality when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Future functionality can be added here
});