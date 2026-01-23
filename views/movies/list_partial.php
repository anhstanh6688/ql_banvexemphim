<?php flash('movie_message'); ?>

<div class="card border-0 shadow-lg overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Title</th>
                        <th>Genre</th>
                        <th>Duration</th>
                        <th>Release Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['movies'] as $movie): ?>
                        <tr>
                            <td class="ps-4 fw-bold">
                                <?php echo $movie->title; ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?php echo $movie->genre; ?>
                                </span>
                            </td>
                            <td>
                                <i class="far fa-clock text-muted me-1"></i>
                                <?php echo $movie->duration; ?>m
                            </td>
                            <td>
                                <?php echo date('d M Y', strtotime($movie->release_date)); ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="<?php echo URLROOT; ?>/showtimes/add/<?php echo $movie->id; ?>"
                                    class="btn btn-sm btn-outline-success me-1" title="Add Showtime">
                                    <i class="fas fa-calendar-plus"></i>
                                </a>
                                <a href="<?php echo URLROOT; ?>/movies/edit/<?php echo $movie->id; ?>"
                                    class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form id="delete-form-<?php echo $movie->id; ?>"
                                    action="<?php echo URLROOT; ?>/movies/delete/<?php echo $movie->id; ?>" method="post"
                                    class="d-inline">
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Delete"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        onclick="setDeleteForm('delete-form-<?php echo $movie->id; ?>')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['movies'])): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No movies found.</td>
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
                    href="<?php echo URLROOT; ?>/movies?page=<?php echo $data['current_page'] - 1; ?>&search=<?php echo isset($data['filters']['search']) ? $data['filters']['search'] : ''; ?>&genre=<?php echo isset($data['filters']['genre']) ? $data['filters']['genre'] : ''; ?>&status=<?php echo isset($data['filters']['status']) ? $data['filters']['status'] : ''; ?>"
                    aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>

            <?php
            $range = 1; // Number of pages around current page
            $searchParams = '&search=' . (isset($data['filters']['search']) ? $data['filters']['search'] : '') .
                '&genre=' . (isset($data['filters']['genre']) ? $data['filters']['genre'] : '') .
                '&status=' . (isset($data['filters']['status']) ? $data['filters']['status'] : '');

            for ($i = 1; $i <= $data['total_pages']; $i++):
                if ($i == 1 || $i == $data['total_pages'] || ($i >= $data['current_page'] - $range && $i <= $data['current_page'] + $range)):
                    ?>
                    <li class="page-item <?php echo $data['current_page'] == $i ? 'active' : ''; ?>">
                        <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;"
                            href="<?php echo URLROOT; ?>/movies?page=<?php echo $i; ?><?php echo $searchParams; ?>">
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
                    href="<?php echo URLROOT; ?>/movies?page=<?php echo $data['current_page'] + 1; ?><?php echo $searchParams; ?>"
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
        <?php echo $data['total_movies']; ?> movies)
    </div>
<?php endif; ?>