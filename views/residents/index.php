<?php
// View helper functions for resident modals
require_once 'views/residents/helpers.php';
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="bi bi-people"></i> Residents</h2>
    </div>
    <div class="col-md-6 text-md-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResidentModal">
            <i class="bi bi-person-plus"></i> Add Resident
        </button>
    </div>
</div>

<!-- Search Box -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Search for residents...">
        </div>
    </div>
</div>

<!-- Residents Table -->
<div class="card shadow-sm border">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="border: 1px solid #dee2e6;">
                <thead  class="text-white">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">NIC</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Address</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo generateResidentRow($row, $i++);
                            echo generateViewModal($row);
                            echo generateEditModal($row);
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-3'><i class='bi bi-exclamation-circle me-2'></i>No residents found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Resident Modal -->
<div class="modal fade" id="addResidentModal" tabindex="-1" aria-labelledby="addResidentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addResidentModalLabel"><i class="bi bi-person-plus"></i> Add New Resident</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="index.php?action=save" method="POST">
                    <input type="hidden" name="form_token" value="<?= $_SESSION['form_token'] ?>">
                    <?php include 'views/residents/form.php'; ?>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Close</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Resident</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
