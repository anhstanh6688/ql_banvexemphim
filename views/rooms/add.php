<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title fw-bold">Add New Room</h4>
                        <a href="<?php echo URLROOT; ?>/rooms" class="btn btn-light btn-sm rounded-pill"><i
                                class="fas fa-arrow-left me-1"></i> Back</a>
                    </div>

                    <form action="<?php echo URLROOT; ?>/rooms/add" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Room Name</label>
                            <input type="text" name="name"
                                class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $data['name']; ?>" placeholder="e.g. Cinema 1">
                            <span class="invalid-feedback">
                                <?php echo $data['name_err']; ?>
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="total_rows" class="form-label fw-bold">Total Rows</label>
                                <input type="number" name="total_rows"
                                    class="form-control <?php echo (!empty($data['rows_err'])) ? 'is-invalid' : ''; ?>"
                                    value="<?php echo $data['total_rows']; ?>" min="1">
                                <span class="invalid-feedback">
                                    <?php echo $data['rows_err']; ?>
                                </span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total_cols" class="form-label fw-bold">Total Columns</label>
                                <input type="number" name="total_cols"
                                    class="form-control <?php echo (!empty($data['cols_err'])) ? 'is-invalid' : ''; ?>"
                                    value="<?php echo $data['total_cols']; ?>" min="1">
                                <span class="invalid-feedback">
                                    <?php echo $data['cols_err']; ?>
                                </span>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill fw-bold py-2">Create Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>