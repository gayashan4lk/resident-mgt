<?php $resident = $resident ?? null; ?>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="full_name" class="form-label">Full Name*</label>
        <input type="text" class="form-control" id="full_name" name="full_name" value="<?= $resident ? htmlspecialchars($resident['full_name']) : '' ?>" required>
    </div>
    <div class="col-md-6">
        <label for="dob" class="form-label">Date of Birth*</label>
        <input type="date" class="form-control" id="dob" name="dob" value="<?= $resident ? htmlspecialchars($resident['dob']) : '' ?>" required>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="nic" class="form-label">NIC*</label>
        <input type="text" class="form-control" id="nic" name="nic" value="<?= $resident ? htmlspecialchars($resident['nic']) : '' ?>" required>
    </div>
    <div class="col-md-6">
        <label for="gender" class="form-label">Gender*</label>
        <select class="form-select" id="gender" name="gender" required>
            <option value="" disabled <?= !$resident ? 'selected' : '' ?>>Select Gender</option>
            <option value="Male" <?= $resident && $resident['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $resident && $resident['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= $resident && $resident['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
    </div>
</div>
<div class="row mb-3">
    <div class="col-12">
        <label for="address" class="form-label">Address*</label>
        <textarea class="form-control" id="address" name="address" rows="2" required><?= $resident ? htmlspecialchars($resident['address']) : '' ?></textarea>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="phone" class="form-label">Phone*</label>
        <input type="tel" class="form-control" id="phone" name="phone" value="<?= $resident ? htmlspecialchars($resident['phone']) : '' ?>" required>
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Email*</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= $resident ? htmlspecialchars($resident['email']) : '' ?>" required>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="occupation" class="form-label">Occupation</label>
        <input type="text" class="form-control" id="occupation" name="occupation" value="<?= $resident && isset($resident['occupation']) ? htmlspecialchars($resident['occupation']) : '' ?>">
    </div>
</div>
