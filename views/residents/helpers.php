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
      <button type='button' class='btn btn-danger delete-btn' data-name='{$fullName}' data-id='{$id}' title='Delete Resident'>
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
    $dob = htmlspecialchars($row['dob']);
    $nic = htmlspecialchars($row['nic']);
    $gender = htmlspecialchars($row['gender']);
    $address = htmlspecialchars($row['address']);
    $phone = htmlspecialchars($row['phone']);
    $email = htmlspecialchars($row['email']);
    $occupation = isset($row['occupation']) ? htmlspecialchars($row['occupation']) : '';
    
    $modalId = "editModal{$id}";
    $modalLabelId = "editModalLabel{$id}";
    
    // Prepare selected states for gender dropdown
    $maleSelected = ($gender === 'Male') ? 'selected' : '';
    $femaleSelected = ($gender === 'Female') ? 'selected' : '';
    $otherSelected = ($gender === 'Other') ? 'selected' : '';
    
    $html = <<<HTML
<div class='modal fade' id='{$modalId}' tabindex='-1' aria-labelledby='{$modalLabelId}' aria-hidden='true'>
  <div class='modal-dialog modal-lg'>
    <div class='modal-content'>
      <div class='modal-header bg-primary text-white'>
        <h5 class='modal-title' id='{$modalLabelId}'><i class='bi bi-pencil-square'></i> Edit {$fullName}</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
      </div>
      <div class='modal-body'>
        <form action='index.php?action=save' method='POST'>
          <input type='hidden' name='id' value='{$id}'>
          <input type='hidden' name='form_token' value='{$_SESSION['form_token']}'>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="full_name_{$id}" class="form-label">Full Name*</label>
              <input type="text" class="form-control" id="full_name_{$id}" name="full_name" value="{$fullName}" required>
            </div>
            <div class="col-md-6">
              <label for="dob_{$id}" class="form-label">Date of Birth*</label>
              <input type="date" class="form-control" id="dob_{$id}" name="dob" value="{$dob}" required>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="nic_{$id}" class="form-label">NIC*</label>
              <input type="text" class="form-control" id="nic_{$id}" name="nic" value="{$nic}" required>
            </div>
            <div class="col-md-6">
              <label for="gender_{$id}" class="form-label">Gender*</label>
              <select class="form-select" id="gender_{$id}" name="gender" required>
                <option value="" disabled>Select Gender</option>
                <option value="Male" {$maleSelected}>Male</option>
                <option value="Female" {$femaleSelected}>Female</option>
                <option value="Other" {$otherSelected}>Other</option>
              </select>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-12">
              <label for="address_{$id}" class="form-label">Address*</label>
              <textarea class="form-control" id="address_{$id}" name="address" rows="3" required>{$address}</textarea>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="phone_{$id}" class="form-label">Phone*</label>
              <input type="text" class="form-control" id="phone_{$id}" name="phone" value="{$phone}" required>
            </div>
            <div class="col-md-6">
              <label for="email_{$id}" class="form-label">Email*</label>
              <input type="email" class="form-control" id="email_{$id}" name="email" value="{$email}" required>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="occupation_{$id}" class="form-label">Occupation</label>
              <input type="text" class="form-control" id="occupation_{$id}" name="occupation" value="{$occupation}">
            </div>
          </div>

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
    
    return $html;
}
