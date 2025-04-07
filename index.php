<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Resident Management</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<!-- Add cache control headers -->
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="0">
</head>

<body>
<?php
// Start session for flash messages
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'resident_database');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize message variables from session
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null;

// Clear flash messages after retrieving them
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Generate a unique form token
if (!isset($_SESSION['form_token'])) {
    $_SESSION['form_token'] = md5(uniqid(mt_rand(), true));
}

// Form submission processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if we're updating an existing resident
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Get form data
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $nic = mysqli_real_escape_string($conn, $_POST['nic']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $occupation = mysqli_real_escape_string($conn, $_POST['occupation']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        
        // Update query with prepared statement
        $stmt = $conn->prepare("UPDATE residents SET full_name=?, dob=?, nic=?, address=?, phone=?, email=?, occupation=?, gender=? WHERE id=?");
        $stmt->bind_param("ssssssssi", $full_name, $dob, $nic, $address, $phone, $email, $occupation, $gender, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Resident updated successfully!";
        } else {
            $_SESSION['error_message'] = "Error: " . $stmt->error;
        }
        
        $stmt->close();
        
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } 
    // Handle adding a new resident
    else {
        // Verify form token to prevent duplicate submissions
        if (!isset($_POST['form_token']) || $_POST['form_token'] !== $_SESSION['form_token']) {
            $_SESSION['error_message'] = "Invalid form submission or form already submitted.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        
        // Get form data
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $nic = mysqli_real_escape_string($conn, $_POST['nic']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $occupation = mysqli_real_escape_string($conn, $_POST['occupation']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        
        // Insert with prepared statement
        $stmt = $conn->prepare("INSERT INTO residents (full_name, dob, nic, address, phone, email, occupation, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $full_name, $dob, $nic, $address, $phone, $email, $occupation, $gender);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Resident added successfully!";
        } else {
            $_SESSION['error_message'] = "Error: " . $stmt->error;
        }
        
        $stmt->close();
        
        // Generate a new token after submission
        $_SESSION['form_token'] = md5(uniqid(mt_rand(), true));
        
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

	<div class="container mt-4">
		<div class="row">
			<div class="col-md-12 mb-4">
				<h1 class="text-center">
					<i class="bi bi-building"></i> Resident Management
				</h1>
			</div>
		</div>

		<?php if (isset($success_message)): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo $success_message; ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<?php endif; ?>

		<?php if (isset($error_message)): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?php echo $error_message; ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<?php endif; ?>

		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header bg-primary text-white">
						<h5 class="mb-0"><i class="bi bi-person-plus"></i> Add New Resident</h5>
					</div>
					<div class="card-body">
						<form action="" method="POST">
							<!-- Hidden form token field -->
							<input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">
							<div class="row mb-3">
								<div class="col-md-6">
									<label for="full_name" class="form-label">Full Name*</label>
									<input type="text" class="form-control" id="full_name" name="full_name" required>
								</div>
								<div class="col-md-6">
									<label for="dob" class="form-label">Date of Birth*</label>
									<input type="date" class="form-control" id="dob" name="dob" required>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-md-6">
									<label for="nic" class="form-label">NIC*</label>
									<input type="text" class="form-control" id="nic" name="nic" required>
								</div>
								<div class="col-md-6">
									<label for="gender" class="form-label">Gender*</label>
									<select class="form-select" id="gender" name="gender" required>
										<option value="" selected disabled>Select Gender</option>
										<option value="Male">Male</option>
										<option value="Female">Female</option>
										<option value="Other">Other</option>
									</select>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-md-12">
									<label for="address" class="form-label">Address*</label>
									<textarea class="form-control" id="address" name="address" rows="2" required></textarea>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-md-6">
									<label for="phone" class="form-label">Phone*</label>
									<input type="tel" class="form-control" id="phone" name="phone" required>
								</div>
								<div class="col-md-6">
									<label for="email" class="form-label">Email*</label>
									<input type="email" class="form-control" id="email" name="email" required>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-md-6">
									<label for="occupation" class="form-label">Occupation</label>
									<input type="text" class="form-control" id="occupation" name="occupation">
								</div>
							</div>
							<div class="d-grid gap-2 d-md-flex justify-content-md-end">
								<button type="reset" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
								<button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Resident</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-4">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
						<h5 class="mb-0"><i class="bi bi-people"></i> Resident List</h5>
						<div class="input-group" style="max-width: 300px">
							<input type="text" id="searchInput" class="form-control" placeholder="Search...">
							<button class="btn btn-light" type="button"><i class="bi bi-search"></i></button>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th scope="col" width="5%">#</th>
										<th scope="col" width="20%">Full Name</th>
										<th scope="col" width="15%">NIC</th>
										<th scope="col" width="15%">Phone</th>
										<th scope="col" width="20%">Email</th>
										<th scope="col" width="25%">Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// Function to generate resident table row
									function generateResidentRow($row, $rowNum) {
										$id = (int)$row['id']; // Force integer type for security
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

									// Function to generate resident details modal
									function generateResidentModal($row) {
										$id = (int)$row['id']; // Force integer type for security
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
									
									// Function to generate edit resident modal
									function generateEditResidentModal($row) {
										$id = (int)$row['id']; // Force integer type for security
										$fullName = htmlspecialchars($row['full_name']);
										$dob = htmlspecialchars($row['dob']);
										$nic = htmlspecialchars($row['nic']);
										$gender = htmlspecialchars($row['gender']);
										$address = htmlspecialchars($row['address']);
										$phone = htmlspecialchars($row['phone']);
										$email = htmlspecialchars($row['email']);
										$occupation = empty($row['occupation']) ? '' : htmlspecialchars($row['occupation']);
										
										// Pre-calculate selected attributes
										$selectedMale = ($gender === 'Male') ? 'selected' : '';
										$selectedFemale = ($gender === 'Female') ? 'selected' : '';
										$selectedOther = ($gender === 'Other') ? 'selected' : '';
										
										$modalId = "editModal{$id}";
										$modalLabelId = "editModalLabel{$id}";
										
										return <<<HTML
<div class='modal fade' id='{$modalId}' tabindex='-1' aria-labelledby='{$modalLabelId}' aria-hidden='true'>
  <div class='modal-dialog modal-lg'>
    <div class='modal-content'>
      <div class='modal-header bg-primary text-white'>
        <h5 class='modal-title' id='{$modalLabelId}'><i class='bi bi-person-badge'></i> Edit {$fullName}'s Details</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
      </div>
      <div class='modal-body'>
        <form action='' method='POST'>
          <input type='hidden' name='id' value='{$id}'>
          <div class='row mb-3'>
            <div class='col-md-6'>
              <label for='full_name' class='form-label'>Full Name*</label>
              <input type='text' class='form-control' id='full_name' name='full_name' value='{$fullName}' required>
            </div>
            <div class='col-md-6'>
              <label for='dob' class='form-label'>Date of Birth*</label>
              <input type='date' class='form-control' id='dob' name='dob' value='{$dob}' required>
            </div>
          </div>
          <div class='row mb-3'>
            <div class='col-md-6'>
              <label for='nic' class='form-label'>NIC*</label>
              <input type='text' class='form-control' id='nic' name='nic' value='{$nic}' required>
            </div>
            <div class='col-md-6'>
              <label for='gender' class='form-label'>Gender*</label>
              <select class='form-select' id='gender' name='gender' required>
                <option value='' disabled>Select Gender</option>
                <option value='Male' {$selectedMale}>Male</option>
                <option value='Female' {$selectedFemale}>Female</option>
                <option value='Other' {$selectedOther}>Other</option>
              </select>
            </div>
          </div>
          <div class='row mb-3'>
            <div class='col-12'>
              <label for='address' class='form-label'>Address*</label>
              <textarea class='form-control' id='address' name='address' rows='2' required>{$address}</textarea>
            </div>
          </div>
          <div class='row mb-3'>
            <div class='col-md-6'>
              <label for='phone' class='form-label'>Phone*</label>
              <input type='tel' class='form-control' id='phone' name='phone' value='{$phone}' required>
            </div>
            <div class='col-md-6'>
              <label for='email' class='form-label'>Email*</label>
              <input type='email' class='form-control' id='email' name='email' value='{$email}' required>
            </div>
          </div>
          <div class='row mb-3'>
            <div class='col-md-6'>
              <label for='occupation' class='form-label'>Occupation</label>
              <input type='text' class='form-control' id='occupation' name='occupation' value='{$occupation}'>
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
									}
									
									// Retrieve resident data from database with prepared statement
									$stmt = $conn->prepare("SELECT * FROM residents ORDER BY full_name ASC");
									$stmt->execute();
									$result = $stmt->get_result();
									
									if ($result->num_rows > 0) {
										$i = 1;
										while ($row = $result->fetch_assoc()) {
											// Generate row and modals
											echo generateResidentRow($row, $i++);
											echo generateResidentModal($row);
											echo generateEditResidentModal($row);
										}
									} else {
										echo "<tr><td colspan='6' class='text-center py-3'><i class='bi bi-exclamation-circle me-2'></i>No residents found.</td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
	<script>
	// Simple search functionality
	document.getElementById('searchInput').addEventListener('keyup', function() {
		let input = this.value.toLowerCase();
		let table = document.querySelector('table');
		let rows = table.getElementsByTagName('tr');
		
		for (let i = 1; i < rows.length; i++) { // Skip header row
			let found = false;
			let cells = rows[i].getElementsByTagName('td');
			
			for (let j = 0; j < cells.length - 1; j++) { // Skip last column (actions)
				let cell = cells[j];
				if (cell) {
					let text = cell.textContent || cell.innerText;
					if (text.toLowerCase().indexOf(input) > -1) {
						found = true;
						break;
					}
				}
			}
			
			rows[i].style.display = found ? '' : 'none';
		}
	});
	
	// Delete resident functionality
	function deleteResident(id, fullName) {
		if (confirm(`Are you sure you want to delete ${fullName}'s record?`)) {
			window.location.href = `delete.php?id=${id}`;
		}
	}
	</script>
</body>

</html>