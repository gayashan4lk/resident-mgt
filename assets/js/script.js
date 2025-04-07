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
    
    // Initialize toasts
    initToasts();
    
    // Setup delete confirmation modal
    setupDeleteConfirmation();
});

/**
 * Initialize Bootstrap toasts
 */
function initToasts() {
    // Get all toast elements
    const toastElList = document.querySelectorAll('.toast');
    
    // Create toast instances with auto-hide enabled
    const toastList = Array.from(toastElList).map(toastEl => {
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 5000
        });
        toast.show();
        return toast;
    });
}

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
    document.querySelectorAll('.delete-btn').forEach(btn => {
        // Override the default click behavior
        btn.addEventListener('click', function(e) {
            // Extract details from the button
            const id = this.getAttribute('data-id');
            const fullName = this.getAttribute('data-name') || 'this resident';
            
            // Update modal with resident info
            residentNameElement.textContent = fullName;
            confirmDeleteBtn.setAttribute('href', `index.php?action=delete&id=${id}`);
            
            // Show the modal
            modal.show();
        });
    });
}
