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
                            <td class="ps-4 fw-bold text-primary">
                                <?php echo $show->title; ?>
                            </td>
                            <td>
                                <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                <?php echo $show->room_name; ?>
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
                                    action="<?php echo URLROOT; ?>/showtimes/delete/<?php echo $show->id; ?>" method="post"
                                    class="d-inline">
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
</div>

<!-- Pagination -->
<?php if (isset($data['total_pages']) && $data['total_pages'] > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo $data['current_page'] <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px;"
                    href="<?php echo URLROOT; ?>/showtimes?page=<?php echo $data['current_page'] - 1; ?>&search=<?php echo isset($data['filters']['search']) ? $data['filters']['search'] : ''; ?>&room_id=<?php echo isset($data['filters']['room_id']) ? $data['filters']['room_id'] : ''; ?>&date=<?php echo isset($data['filters']['date']) ? $data['filters']['date'] : ''; ?>"
                    aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>

            <?php
            $range = 1; // Number of pages around current page
            $searchParams = '&search=' . (isset($data['filters']['search']) ? $data['filters']['search'] : '') .
                '&room_id=' . (isset($data['filters']['room_id']) ? $data['filters']['room_id'] : '') .
                '&date=' . (isset($data['filters']['date']) ? $data['filters']['date'] : '');

            for ($i = 1; $i <= $data['total_pages']; $i++):
                if ($i == 1 || $i == $data['total_pages'] || ($i >= $data['current_page'] - $range && $i <= $data['current_page'] + $range)):
                    ?>
                    <li class="page-item <?php echo $data['current_page'] == $i ? 'active' : ''; ?>">
                        <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;"
                            href="<?php echo URLROOT; ?>/showtimes?page=<?php echo $i; ?><?php echo $searchParams; ?>">
                            <?php echo $i; ?>
                        </a>
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
                    href="<?php echo URLROOT; ?>/showtimes?page=<?php echo $data['current_page'] + 1; ?><?php echo $searchParams; ?>"
                    aria-label="Next">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>
    <div class="text-center text-muted small mt-2">
        Showing page
        <?php echo $data['current_page']; ?> of
        <?php echo $data['total_pages']; ?> (Total
        <?php echo $data['total_showtimes']; ?> showtimes)
    </div>
<?php endif; ?>