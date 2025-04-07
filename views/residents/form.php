<?php 
$resident = $resident ?? null;
$form_data = $_SESSION['form_data'] ?? null;
unset($_SESSION['form_data']); // Clear after use
?>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="full_name" class="form-label">Full Name*</label>
        <input type="text" class="form-control" id="full_name" name="full_name" value="<?= $form_data ? htmlspecialchars($form_data['full_name']) : ($resident ? htmlspecialchars($resident['full_name']) : '') ?>" required>
    </div>
    <div class="col-md-6">
        <label for="dob" class="form-label">Date of Birth*</label>
        <input type="date" class="form-control" id="dob" name="dob" value="<?= $form_data ? htmlspecialchars($form_data['dob']) : ($resident ? htmlspecialchars($resident['dob']) : '') ?>" required>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="nic" class="form-label">NIC*</label>
        <input type="text" class="form-control" id="nic" name="nic" value="<?= $form_data ? htmlspecialchars($form_data['nic']) : ($resident ? htmlspecialchars($resident['nic']) : '') ?>" required>
    </div>
    <div class="col-md-6">
        <label for="gender" class="form-label">Gender*</label>
        <select class="form-select" id="gender" name="gender" required>
            <option value="" disabled <?= !$resident && !$form_data ? 'selected' : '' ?>>Select Gender</option>
            <option value="Male" <?= ($form_data && $form_data['gender'] === 'Male') || ($resident && $resident['gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= ($form_data && $form_data['gender'] === 'Female') || ($resident && $resident['gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= ($form_data && $form_data['gender'] === 'Other') || ($resident && $resident['gender'] === 'Other') ? 'selected' : '' ?>>Other</option>
        </select>
    </div>
</div>
<div class="row mb-3">
    <div class="col-12">
        <label for="address" class="form-label">Address*</label>
        <textarea class="form-control" id="address" name="address" rows="2" required><?= $form_data ? htmlspecialchars($form_data['address']) : ($resident ? htmlspecialchars($resident['address']) : '') ?></textarea>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="phone" class="form-label">Phone*</label>
        <input type="tel" class="form-control" id="phone" name="phone" value="<?= $form_data ? htmlspecialchars($form_data['phone']) : ($resident ? htmlspecialchars($resident['phone']) : '') ?>" required>
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Email*</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= $form_data ? htmlspecialchars($form_data['email']) : ($resident ? htmlspecialchars($resident['email']) : '') ?>" required>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="occupation" class="form-label">Occupation</label>
        <input type="text" class="form-control" id="occupation" name="occupation" value="<?= $form_data && isset($form_data['occupation']) ? htmlspecialchars($form_data['occupation']) : ($resident && isset($resident['occupation']) ? htmlspecialchars($resident['occupation']) : '') ?>">
    </div>
</div>
