<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="page-header text-center">
    <div class="container">
        <h1>Manage Rooms</h1>
        <p class="lead mb-0">List of all cinema rooms</p>
    </div>
</div>

<div class="container">
    <?php flash('room_message'); ?>

    <div class="row mb-4">
        <div class="col-md-6">
            <!-- Optional Search could go here -->
        </div>
        <div class="col-md-6 text-end">
            <a href="<?php echo URLROOT; ?>/rooms/add" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Add New Room
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Name</th>
                        <th>Matrix (Rows x Cols)</th>
                        <th>Capacity</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['rooms'])): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No rooms found. Add one to get started.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data['rooms'] as $room): ?>
                            <tr>
                                <td class="ps-4 fw-bold">#
                                    <?php echo $room->id; ?>
                                </td>
                                <td class="fw-bold text-primary">
                                    <?php echo $room->name; ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo $room->total_rows; ?> R x
                                        <?php echo $room->total_cols; ?> C
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <?php echo $room->total_rows * $room->total_cols; ?> Seats
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?php echo URLROOT; ?>/rooms/seats/<?php echo $room->id; ?>"
                                        class="btn btn-sm btn-outline-primary me-1" title="Manage Seats">
                                        <i class="fas fa-th"></i>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/rooms/edit/<?php echo $room->id; ?>"
                                        class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="setDeleteForm('delete-form-<?php echo $room->id; ?>')" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-<?php echo $room->id; ?>"
                                        action="<?php echo URLROOT; ?>/rooms/delete/<?php echo $room->id; ?>" method="post"
                                        class="d-none">
                                        <!-- CSRF Token handled in Controller via standard check -->
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i> Confirm
                    Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-1 fs-5">Are you sure you want to delete this room?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm" id="confirmDeleteBtn">Yes,
                    Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    let targetFormId = null;
    function setDeleteForm(formId) {
        targetFormId = formId;
    }
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (targetFormId) {
            document.getElementById(targetFormId).submit();
        }
    });
</script>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>