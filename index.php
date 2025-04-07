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
    
    // Insert query
    $sql = "INSERT INTO residents (full_name, dob, nic, address, phone, email, occupation, gender) 
            VALUES ('$full_name', '$dob', '$nic', '$address', '$phone', '$email', '$occupation', '$gender')";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Resident added successfully!";
    } else {
        $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
    }
    
    // Generate a new token after submission
    $_SESSION['form_token'] = md5(uniqid(mt_rand(), true));
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
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
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>

</html>