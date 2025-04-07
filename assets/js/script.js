/**
 * Resident Management Application JavaScript
 */

// Search functionality
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
});

// Delete resident confirmation
function deleteResident(id, fullName) {
    if (confirm(`Are you sure you want to delete ${fullName}'s record?`)) {
        window.location.href = `index.php?action=delete&id=${id}`;
    }
}
