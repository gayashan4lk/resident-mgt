<?php
/**
 * Helper functions for generating resident UI components
 */

// Generate resident row in the table
function generateResidentRow($row, $rowNum) {
    $id = (int)$row['id'];
    $fullName = htmlspecialchars($row['full_name']);
    $nic = htmlspecialchars($row['nic']);
    $phone = htmlspecialchars($row['phone']);
    $email = htmlspecialchars($row['email']);
    $viewModalId = "#viewModal{$id}";
    $editModalId = "#editModal{$id}";
    
    return <<<ROW
<tr>
  <th scope='row'>{$rowNum}</th>
  <td>{$fullName}</td>
  <td>{$nic}</td>
  <td>{$phone}</td>
  <td>{$email}</td>
  <td class='text-center'>
    <div class='btn-group btn-group-sm' role='group' aria-label='Resident actions'>
      <button type='button' class='btn btn-info' data-bs-toggle='modal' data-bs-target='{$viewModalId}' title='View Details'>
        <i class='bi bi-eye'></i>
      </button>
      <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='{$editModalId}' title='Edit Resident'>
        <i class='bi bi-pencil'></i>
      </button>
      <button type='button' class='btn btn-danger' onclick="deleteResident({$id}, '{$fullName}')" title='Delete Resident'>
        <i class='bi bi-trash'></i>
      </button>
    </div>
  </td>
</tr>
ROW;
}

// Generate view modal for a resident
function generateViewModal($row) {
    $id = (int)$row['id'];
    $fullName = htmlspecialchars($row['full_name']);
    $dob = htmlspecialchars($row['dob']);
    $nic = htmlspecialchars($row['nic']);
    $gender = htmlspecialchars($row['gender']);
    $address = htmlspecialchars($row['address']);
    $phone = htmlspecialchars($row['phone']);
    $email = htmlspecialchars($row['email']);
    $occupation = empty($row['occupation']) ? 'Not specified' : htmlspecialchars($row['occupation']);
    $formattedDate = date('F j, Y', strtotime($row['registered_date']));
    
    $modalId = "viewModal{$id}";
    $modalLabelId = "viewModalLabel{$id}";
    
    return <<<HTML
<div class='modal fade' id='{$modalId}' tabindex='-1' aria-labelledby='{$modalLabelId}' aria-hidden='true'>
  <div class='modal-dialog modal-lg'>
    <div class='modal-content'>
      <div class='modal-header bg-info text-white'>
        <h5 class='modal-title' id='{$modalLabelId}'><i class='bi bi-person-badge'></i> {$fullName}'s Details</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
      </div>
      <div class='modal-body'>
        <div class='row'>
          <div class='col-md-6 mb-3'>
            <h6><i class='bi bi-person'></i> Full Name</h6>
            <p>{$fullName}</p>
          </div>
          <div class='col-md-6 mb-3'>
            <h6><i class='bi bi-calendar-date'></i> Date of Birth</h6>
            <p>{$dob}</p>
          </div>
        </div>
        <div class='row'>
          <div class='col-md-6 mb-3'>
            <h6><i class='bi bi-card-text'></i> NIC</h6>
            <p>{$nic}</p>
          </div>
          <div class='col-md-6 mb-3'>
            <h6><i class='bi bi-gender-ambiguous'></i> Gender</h6>
            <p>{$gender}</p>
          </div>
        </div>
        <div class='row'>
          <div class='col-12 mb-3'>
            <h6><i class='bi bi-geo-alt'></i> Address</h6>
            <p>{$address}</p>
          </div>
        </div>
        <div class='row'>
          <div class='col-md-6 mb-3'>
            <h6><i class='bi bi-telephone'></i> Phone</h6>
            <p>{$phone}</p>
          </div>
          <div class='col-md-6 mb-3'>
            <h6><i class='bi bi-envelope'></i> Email</h6>
            <p>{$email}</p>
          </div>
        </div>
        <div class='row'>
          <div class='col-md-6 mb-3'>
            <h6><i class='bi bi-briefcase'></i> Occupation</h6>
            <p>{$occupation}</p>
          </div>
          <div class='col-md-6 mb-3'>
            <h6><i class='bi bi-clock-history'></i> Registered Date</h6>
            <p>{$formattedDate}</p>
          </div>
        </div>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'><i class='bi bi-x-circle'></i> Close</button>
      </div>
    </div>
  </div>
</div>
HTML;
}

// Generate edit modal for a resident
function generateEditModal($row) {
    $id = (int)$row['id'];
    $fullName = htmlspecialchars($row['full_name']);
    
    $modalId = "editModal{$id}";
    $modalLabelId = "editModalLabel{$id}";
    
    // Create the modal HTML structure
    $modalHtml = <<<HTML
<div class='modal fade' id='{$modalId}' tabindex='-1' aria-labelledby='{$modalLabelId}' aria-hidden='true'>
  <div class='modal-dialog modal-lg'>
    <div class='modal-content'>
      <div class='modal-header bg-primary text-white'>
        <h5 class='modal-title' id='{$modalLabelId}'><i class='bi bi-person-badge'></i> Edit {$fullName}'s Details</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
      </div>
      <div class='modal-body'>
        <form action='index.php?action=save' method='POST'>
          <input type='hidden' name='id' value='{$id}'>
HTML;
    
    // Store the current row data temporarily
    $GLOBALS['current_resident'] = $row;
    
    // Capture the form by starting output buffer
    ob_start();
    $resident = $row; // Make row data available to the form
    include 'views/residents/form.php';
    $form = ob_get_clean();
    
    // Add the form to the modal HTML
    $modalHtml .= $form;
    
    // Finish the modal HTML
    $modalHtml .= <<<HTML
          <div class='d-grid gap-2 d-md-flex justify-content-md-end'>
            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'><i class='bi bi-x-circle'></i> Close</button>
            <button type='submit' class='btn btn-primary'><i class='bi bi-save'></i> Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
HTML;
    
    return $modalHtml;
}
