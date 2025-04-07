/**
 * Resident Management Application JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Search functionality with AJAX
    const searchInput = document.getElementById('searchInput');
    const searchResultsTable = document.querySelector('tbody');
    
    if (searchInput) {
        // Add debounce to avoid too many requests
        let timer;
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                const searchTerm = this.value.trim();
                performSearch(searchTerm, searchResultsTable);
            }, 300); // 300ms delay
        });
    }
    
    // Initialize toasts
    initToasts();
    
    // Setup delete confirmation modal
    setupDeleteConfirmation();
});

/**
 * Perform AJAX search and update results
 */
function performSearch(searchTerm, resultsElement) {
    // Show loading indicator
    resultsElement.innerHTML = '<tr><td colspan="7" class="text-center py-3"><i class="bi bi-hourglass-split me-2"></i>Searching...</td></tr>';
    
    // Create AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `index.php?action=search&search=${encodeURIComponent(searchTerm)}`, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    xhr.onload = function() {
        if (this.status === 200) {
            try {
                const response = JSON.parse(this.responseText);
                displaySearchResults(response.residents, response.modals, resultsElement);
            } catch (e) {
                resultsElement.innerHTML = '<tr><td colspan="7" class="text-center py-3"><i class="bi bi-exclamation-circle me-2"></i>Error processing results</td></tr>';
                console.error('Error parsing JSON response', e);
            }
        } else {
            resultsElement.innerHTML = '<tr><td colspan="7" class="text-center py-3"><i class="bi bi-exclamation-circle me-2"></i>Error fetching results</td></tr>';
        }
    };
    
    xhr.onerror = function() {
        resultsElement.innerHTML = '<tr><td colspan="7" class="text-center py-3"><i class="bi bi-wifi-off me-2"></i>Network error</td></tr>';
    };
    
    xhr.send();
}

/**
 * Display search results in the table
 */
function displaySearchResults(residents, modals, resultsElement) {
    if (!residents || residents.length === 0) {
        resultsElement.innerHTML = '<tr><td colspan="7" class="text-center py-3"><i class="bi bi-search me-2"></i>No residents found</td></tr>';
        return;
    }
    
    let html = '';
    
    residents.forEach(resident => {
        html += `
        <tr>
            <td>${resident.row_number}</td>
            <td>${resident.full_name}</td>
            <td>${resident.nic}</td>
            <td>${resident.phone}</td>
            <td>${resident.email}</td>
            <td>${resident.address}</td>
            <td class="text-center">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewModal${resident.id}"><i class="bi bi-eye"></i></button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal${resident.id}"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-danger delete-btn" data-id="${resident.id}" data-name="${resident.full_name}"><i class="bi bi-trash"></i></button>
                </div>
            </td>
        </tr>
        `;
    });
    
    // Update table content
    resultsElement.innerHTML = html;
    
    // Add modals to the page
    if (modals) {
        // First remove any existing modals from previous searches
        document.querySelectorAll('.search-result-modal').forEach(modal => {
            modal.remove();
        });
        
        // Create a div to hold the modals
        const modalsContainer = document.createElement('div');
        modalsContainer.className = 'search-result-modals';
        modalsContainer.innerHTML = modals;
        
        // Add class to each modal for future cleanup
        modalsContainer.querySelectorAll('.modal').forEach(modal => {
            modal.classList.add('search-result-modal');
        });
        
        // Append to the document body
        document.body.appendChild(modalsContainer);
    }
    
    // Reinitialize delete confirmation for new rows
    setupDeleteConfirmation();
}

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
