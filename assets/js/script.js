/**
 * Resident Management Application JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                const textContent = row.textContent.toLowerCase();
                const found = textContent.includes(searchTerm);
                row.style.display = found ? '' : 'none';
            });
        });
    }
    
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeButton = alert.querySelector('.btn-close');
            if (closeButton) {
                closeButton.click();
            }
        }, 5000);
    });

    // Setup delete confirmation modal
    setupDeleteConfirmation();
});

/**
 * Initialize delete confirmation modal handlers
 */
function setupDeleteConfirmation() {
    // Get modal elements
    const deleteModal = document.getElementById('deleteConfirmModal');
    const confirmDeleteBtn = document.querySelector('.confirm-delete');
    const residentNameElement = document.querySelector('.resident-name');
    
    if (!deleteModal || !confirmDeleteBtn) return;
    
    // Create Bootstrap modal instance
    const modal = new bootstrap.Modal(deleteModal);
    
    // Setup event handlers for all delete buttons
    document.querySelectorAll('a.btn-danger').forEach(btn => {
        // Skip if it's the confirmation button in the modal
        if (btn.classList.contains('confirm-delete')) return;
        
        // Override the default click behavior
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Extract details from the button
            const href = this.getAttribute('href');
            const id = href.split('=')[1] || '';
            const fullName = this.getAttribute('data-name') || 'this resident';
            
            // Update modal with resident info
            residentNameElement.textContent = fullName;
            confirmDeleteBtn.setAttribute('href', href);
            
            // Show the modal
            modal.show();
        });
    });
}
