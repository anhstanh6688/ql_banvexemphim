<?php require APP_ROOT . '/views/inc/header.php'; ?>

<!-- Hero / Page Header -->
<div class="page-header text-center">
    <div class="container">
        <h1>Manage Showtimes</h1>
        <p class="lead mb-0 fw-light">Schedule and organize movie screenings</p>
    </div>
</div>



<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 text-end">
            <a href="<?php echo URLROOT; ?>/showtimes/add" class="btn btn-success shadow-sm">
                <i class="fas fa-clock me-1"></i> Add New Showtime
            </a>
        </div>
    </div>

    <?php flash('showtime_message'); ?>

    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Movie</th>
                            <th>Room</th>
                            <th>Start Time</th>
                            <th>Price</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['showtimes'] as $show): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-primary"><?php echo $show->title; ?></td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-muted me-1"></i> <?php echo $show->room_name; ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo date('d M Y', strtotime($show->start_time)); ?>
                                    </span>
                                    <span class="fw-bold ms-1 text-dark">
                                        <?php echo date('H:i', strtotime($show->start_time)); ?>
                                    </span>
                                </td>
                                <td class="fw-bold text-success">
                                    <?php echo number_format($show->price); ?> đ
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?php echo URLROOT; ?>/showtimes/edit/<?php echo $show->id; ?>"
                                        class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form id="delete-form-<?php echo $show->id; ?>"
                                        action="<?php echo URLROOT; ?>/showtimes/delete/<?php echo $show->id; ?>"
                                        method="post" class="d-inline">
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            onclick="setDeleteForm('delete-form-<?php echo $show->id; ?>')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($data['showtimes'])): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No showtimes found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if (isset($data['total_pages']) && $data['total_pages'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $data['current_page'] <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;"
                            href="<?php echo URLROOT; ?>/showtimes?page=<?php echo $data['current_page'] - 1; ?>"
                            aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <?php
                    $range = 1; // Number of pages around current page
                    for ($i = 1; $i <= $data['total_pages']; $i++):
                        if ($i == 1 || $i == $data['total_pages'] || ($i >= $data['current_page'] - $range && $i <= $data['current_page'] + $range)):
                            ?>
                            <li class="page-item <?php echo $data['current_page'] == $i ? 'active' : ''; ?>">
                                <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;"
                                    href="<?php echo URLROOT; ?>/showtimes?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php elseif ($i == $data['current_page'] - $range - 1 || $i == $data['current_page'] + $range + 1): ?>
                            <li class="page-item disabled">
                                <span class="page-link border-0 shadow-none mx-1 d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">...</span>
                            </li>
                        <?php endif; endfor; ?>

                    <li class="page-item <?php echo $data['current_page'] >= $data['total_pages'] ? 'disabled' : ''; ?>">
                        <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;"
                            href="<?php echo URLROOT; ?>/showtimes?page=<?php echo $data['current_page'] + 1; ?>"
                            aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="text-center text-muted small mt-2">
                Showing page <?php echo $data['current_page']; ?> of <?php echo $data['total_pages']; ?> (Total
                <?php echo $data['total_showtimes']; ?> showtimes)
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>
                    Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-1 fs-5">Are you sure you want to delete this showtime?</p>
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