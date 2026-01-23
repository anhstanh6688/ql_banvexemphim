<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">My Review History</h3>
                <a href="<?php echo URLROOT; ?>" class="btn btn-outline-primary rounded-pill btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Home
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <?php if (empty($data['comments'])): ?>
                        <div class="text-center py-5">
                            <i class="far fa-comment-dots fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">You haven't reviewed any movies yet.</h5>
                            <a href="<?php echo URLROOT; ?>" class="btn btn-primary rounded-pill mt-3">Browse Movies</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4" style="width: 25%;">Movie</th>
                                        <th style="width: 15%;">Rating</th>
                                        <th style="width: 40%;">Review</th>
                                        <th style="width: 10%;">Date</th>
                                        <th class="text-end pe-4" style="width: 10%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['comments'] as $comment): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($comment->movie_poster)): ?>
                                                        <img src="<?php echo $comment->movie_poster; ?>"
                                                            class="rounded shadow-sm me-3"
                                                            style="width: 45px; height: 65px; object-fit: cover;">
                                                    <?php endif; ?>
                                                    <span class="fw-bold text-dark"><?php echo $comment->movie_title; ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-star me-1 small"></i><?php echo $comment->rating; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <p class="mb-0 small text-secondary text-truncate" style="max-width: 300px;">
                                                    <?php echo htmlspecialchars($comment->content); ?>
                                                </p>
                                            </td>
                                            <td class="small text-muted">
                                                <?php echo date('d M Y', strtotime($comment->created_at)); ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="<?php echo URLROOT; ?>/comments/edit/<?php echo $comment->id; ?>"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-circle p-1 me-1"
                                                    title="Edit">
                                                    <i class="fas fa-pencil-alt small"></i>
                                                </a>

                                                <form id="delete-form-history-<?php echo $comment->id; ?>"
                                                    action="<?php echo URLROOT; ?>/comments/delete/<?php echo $comment->id; ?>"
                                                    method="POST" class="d-inline">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger border-0 rounded-circle p-1"
                                                        title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                        onclick="setDeleteForm('delete-form-history-<?php echo $comment->id; ?>')">
                                                        <i class="fas fa-trash-alt small"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Stats -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                                <i class="fas fa-comments fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 opacity-75">Total Reviews</h6>
                                <h3 class="mb-0 fw-bold"><?php echo count($data['comments']); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Simple Delete Modal Script (Reusable) -->
<script>
    let targetFormId = null;
    function setDeleteForm(formId) {
        targetFormId = formId;
    }

    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (targetFormId) {
                document.getElementById(targetFormId).submit();
            }
        });
    }
</script>

<!-- Delete Confirmation Modal (Available Globally) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i> Confirm
                    Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-1 fs-5 text-dark">Are you sure you want to delete this review?</p>
                <p class="text-secondary small mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold"
                    data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm fw-bold"
                    id="confirmDeleteBtn">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>