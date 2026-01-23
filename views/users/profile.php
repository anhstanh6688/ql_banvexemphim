<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h4 class="mb-0 text-primary fw-bold"><i class="fas fa-user-circle"></i> My Profile</h4>
            </div>
            <div class="card-body p-4">
                <?php flash('profile_msg'); ?>

                <form action="<?php echo URLROOT; ?>/users/profile" method="post">

                    <div class="mb-3">
                        <label class="form-label text-muted">Email Address (Read-Only)</label>
                        <input type="text" class="form-control bg-light" value="<?php echo $data['email']; ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name"
                            class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $data['fullname']; ?>">
                        <span class="invalid-feedback">
                            <?php echo $data['name_err']; ?>
                        </span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone Number</label>
                        <input type="text" name="phone"
                            class="form-control <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $data['phone']; ?>">
                        <span class="invalid-feedback">
                            <?php echo $data['phone_err']; ?>
                        </span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">New Password (Optional)</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Leave blank to keep current password">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-pill shadow-sm">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>