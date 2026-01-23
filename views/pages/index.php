<?php require APP_ROOT . '/views/inc/header.php'; ?>
<!-- Hero Section -->
<div class="row mb-5">
    <div class="col-md-12">
        <div class="p-5 text-center rounded-3 shadow-lg position-relative overflow-hidden"
            style="background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%); color: white;">
            <!-- Background decoration -->
            <div
                style="position: absolute; top: -50%; left: -10%; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%;">
            </div>
            <div
                style="position: absolute; bottom: -50%; right: -10%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%;">
            </div>

            <h1 class="display-4 fw-bold mb-3" style="text-shadow: 0 4px 15px rgba(0,0,0,0.3); letter-spacing: 1px;">
                <?php echo $data['title']; ?>
            </h1>
            <p class="fs-5 mb-5 opacity-75 fw-light" style="max-width: 700px; margin: 0 auto;">
                <?php echo $data['description']; ?>
            </p>

            <div class="d-flex justify-content-center gap-3">
                <a href="#now-showing"
                    class="btn btn-light px-5 py-3 rounded-pill fw-bold shadow-sm text-dark position-relative overflow-hidden group-hover">
                    <span class="position-relative z-1"><i class="fas fa-ticket-alt me-2 text-primary"></i>Book
                        Tickets</span>
                </a>
                <a href="#coming-soon" class="btn btn-outline-light px-5 py-3 rounded-pill fw-bold backdrop-blur">
                    Coming Soon
                </a>
            </div>
        </div>
        <div class="mt-3">
            <?php flash('booking_success'); ?>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-pills mb-4 justify-content-center" id="movieTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link <?php echo $data['section'] == 'now-showing' ? 'active' : ''; ?> rounded-pill px-4"
            id="pills-now-tab" data-bs-toggle="pill" data-bs-target="#pills-now" type="button" role="tab">Now
            Showing</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?php echo $data['section'] == 'coming-soon' ? 'active' : ''; ?> rounded-pill px-4"
            id="pills-coming-tab" data-bs-toggle="pill" data-bs-target="#pills-coming" type="button" role="tab">Coming
            Soon</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?php echo $data['section'] == 'ended' ? 'active' : ''; ?> rounded-pill px-4"
            id="pills-ended-tab" data-bs-toggle="pill" data-bs-target="#pills-ended" type="button" role="tab">Stopped
            Showing</button>
    </li>
</ul>

<!-- Tabs Content -->
<div class="tab-content" id="pills-tabContent">

    <!-- NOW SHOWING -->
    <div class="tab-pane fade <?php echo $data['section'] == 'now-showing' ? 'show active' : ''; ?>" id="pills-now"
        role="tabpanel">
        <div class="row">
            <?php foreach ($data['showtimes'] as $movie): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm" style="background: #fff; transition: transform 0.2s;">
                        <div
                            style="height: 250px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; position: relative;">
                            <?php if (!empty($movie->poster)): ?>
                                <img src="<?php echo $movie->poster; ?>"
                                    style="width:100%; height:100%; object-fit:cover; border-radius: 12px 12px 0 0;">
                            <?php else: ?>
                                <i class="fas fa-film fa-3x text-muted"></i>
                            <?php endif; ?>
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2 shadow-sm">HOT</span>
                        </div>

                        <div class="card-body">
                            <h6 class="card-title fw-bold mb-1 text-truncate" title="<?php echo $movie->title; ?>">
                                <?php echo $movie->title; ?>
                            </h6>
                            <p class="card-text text-muted small mb-3">
                                <i class="far fa-clock text-primary"></i> <?php echo $movie->duration; ?> mins <br>
                                <i class="fas fa-tag text-primary"></i> <?php echo $movie->genre; ?>
                            </p>
                            <a href="<?php echo URLROOT; ?>/booking/movie/<?php echo $movie->id; ?>"
                                class="btn btn-outline-primary w-100 btn-sm rounded-pill stretched-link">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($data['showtimes'])): ?>
                <div class="col-12 text-center text-muted py-5">No movies currently showing</div>
            <?php endif; ?>
        </div>

        <!-- Pagination for Now Showing -->
        <?php if (isset($data['total_pages']) && $data['total_pages'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <!-- Previous -->
                    <li class="page-item <?php echo $data['current_page'] <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;"
                            href="<?php echo URLROOT; ?>?page=<?php echo $data['current_page'] - 1; ?>#now-showing"
                            aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php
                    $range = 1;
                    for ($i = 1; $i <= $data['total_pages']; $i++):
                        if ($i == 1 || $i == $data['total_pages'] || ($i >= $data['current_page'] - $range && $i <= $data['current_page'] + $range)):
                            ?>
                            <li class="page-item <?php echo $data['current_page'] == $i ? 'active' : ''; ?>">
                                <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;"
                                    href="<?php echo URLROOT; ?>?page=<?php echo $i; ?>#now-showing"><?php echo $i; ?></a>
                            </li>
                        <?php elseif ($i == $data['current_page'] - $range - 1 || $i == $data['current_page'] + $range + 1): ?>
                            <li class="page-item disabled">
                                <span class="page-link border-0 shadow-none mx-1 d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">...</span>
                            </li>
                        <?php endif; endfor; ?>

                    <!-- Next -->
                    <li class="page-item <?php echo $data['current_page'] >= $data['total_pages'] ? 'disabled' : ''; ?>">
                        <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;"
                            href="<?php echo URLROOT; ?>?page=<?php echo $data['current_page'] + 1; ?>#now-showing"
                            aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <!-- COMING SOON -->
    <div class="tab-pane fade <?php echo $data['section'] == 'coming-soon' ? 'show active' : ''; ?>" id="pills-coming"
        role="tabpanel">
        <div class="row">
            <?php foreach ($data['coming_soon'] as $movie): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm opacity-100"> <!-- Removed opacity reduction logic -->
                        <div
                            style="height: 250px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; position: relative;">
                            <?php if (!empty($movie->poster)): ?>
                                <img src="<?php echo $movie->poster; ?>"
                                    style="width:100%; height:100%; object-fit:cover; border-radius: 12px 12px 0 0; filter: grayscale(30%);">
                            <?php else: ?>
                                <i class="fas fa-hourglass-half fa-3x text-muted"></i>
                            <?php endif; ?>
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm">Coming
                                Soon</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold mb-1 text-truncate"><?php echo $movie->title; ?></h6>
                            <p class="card-text text-muted small">
                                <i class="far fa-calendar-alt"></i> Release:
                                <?php echo date('d M Y', strtotime($movie->release_date)); ?>
                            </p>
                            <button class="btn btn-secondary w-100 btn-sm rounded-pill disabled">
                                Pre-order Soon
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($data['coming_soon'])): ?>
                <div class="col-12 text-center text-muted py-5">No upcoming movies found</div>
            <?php endif; ?>
        </div>

        <!-- Pagination for Coming Soon -->
        <?php if (isset($data['total_pages_coming']) && $data['total_pages_coming'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <!-- Previous -->
                    <li class="page-item <?php echo $data['current_page_coming'] <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;"
                            href="<?php echo URLROOT; ?>?page_coming=<?php echo $data['current_page_coming'] - 1; ?>#pills-coming"
                            aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php
                    $range = 1;
                    for ($i = 1; $i <= $data['total_pages_coming']; $i++):
                        if ($i == 1 || $i == $data['total_pages_coming'] || ($i >= $data['current_page_coming'] - $range && $i <= $data['current_page_coming'] + $range)):
                            ?>
                            <li class="page-item <?php echo $data['current_page_coming'] == $i ? 'active' : ''; ?>">
                                <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;"
                                    href="<?php echo URLROOT; ?>?page_coming=<?php echo $i; ?>#pills-coming"><?php echo $i; ?></a>
                            </li>
                        <?php elseif ($i == $data['current_page_coming'] - $range - 1 || $i == $data['current_page_coming'] + $range + 1): ?>
                            <li class="page-item disabled">
                                <span class="page-link border-0 shadow-none mx-1 d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">...</span>
                            </li>
                        <?php endif; endfor; ?>

                    <!-- Next -->
                    <li
                        class="page-item <?php echo $data['current_page_coming'] >= $data['total_pages_coming'] ? 'disabled' : ''; ?>">
                        <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;"
                            href="<?php echo URLROOT; ?>?page_coming=<?php echo $data['current_page_coming'] + 1; ?>#pills-coming"
                            aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <!-- STOPPED SHOWING -->
    <div class="tab-pane fade <?php echo $data['section'] == 'ended' ? 'show active' : ''; ?>" id="pills-ended"
        role="tabpanel">
        <div class="row">
            <?php foreach ($data['ended'] as $movie): ?>
                <div class="col-md-3 mb-4"> <!-- Changed to col-md-3 for consistency -->
                    <div class="card h-100 border-0 shadow-sm opacity-75">
                        <div
                            style="height: 250px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; position: relative;">
                            <?php if (!empty($movie->poster)): ?>
                                <img src="<?php echo $movie->poster; ?>"
                                    style="width:100%; height:100%; object-fit:cover; border-radius: 12px 12px 0 0; filter: grayscale(100%);">
                            <?php else: ?>
                                <div class="bg-light h-100 w-100 rounded d-flex align-items-center justify-content-center">No
                                    Img</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold mb-1 text-truncate"><?php echo $movie->title; ?>
                            </h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Ended</small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($data['ended'])): ?>
                <div class="col-12 text-center text-muted py-5">No ended movies found</div>
            <?php endif; ?>
        </div>

        <!-- Pagination for Stopped Showing -->
        <?php if (isset($data['total_pages_ended']) && $data['total_pages_ended'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <!-- Previous -->
                    <li class="page-item <?php echo $data['current_page_ended'] <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;"
                            href="<?php echo URLROOT; ?>?page_ended=<?php echo $data['current_page_ended'] - 1; ?>#pills-ended"
                            aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php
                    $range = 1;
                    for ($i = 1; $i <= $data['total_pages_ended']; $i++):
                        if ($i == 1 || $i == $data['total_pages_ended'] || ($i >= $data['current_page_ended'] - $range && $i <= $data['current_page_ended'] + $range)):
                            ?>
                            <li class="page-item <?php echo $data['current_page_ended'] == $i ? 'active' : ''; ?>">
                                <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;"
                                    href="<?php echo URLROOT; ?>?page_ended=<?php echo $i; ?>#pills-ended"><?php echo $i; ?></a>
                            </li>
                        <?php elseif ($i == $data['current_page_ended'] - $range - 1 || $i == $data['current_page_ended'] + $range + 1): ?>
                            <li class="page-item disabled">
                                <span class="page-link border-0 shadow-none mx-1 d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">...</span>
                            </li>
                        <?php endif; endfor; ?>

                    <!-- Next -->
                    <li
                        class="page-item <?php echo $data['current_page_ended'] >= $data['total_pages_ended'] ? 'disabled' : ''; ?>">
                        <a class="page-link shadow-sm border-0 mx-1 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;"
                            href="<?php echo URLROOT; ?>?page_ended=<?php echo $data['current_page_ended'] + 1; ?>#pills-ended"
                            aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>