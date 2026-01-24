<?php require APP_ROOT . '/views/inc/header.php'; ?>
<!-- Hero Section -->
<div class="hero-wrapper">
    <div class="hero-bg">
        <div class="hero-overlay">
            <div class="hero-content">
                <div class="hero-design-container">
                    <!-- Big Number -->
                    <div class="hero-big-number">
                        01
                        <span>TOP RATED</span>
                    </div>

                    <!-- Text Block -->
                    <div class="hero-text-block">
                        <div class="hero-top-tag">
                            MOVIE BOOKING SYSTEM <span class="hero-tag-badge">PREMIUM</span>
                        </div>
                        <div class="hero-main-title">
                            BLOCKBUSTER<br>PREMIERES
                        </div>
                        <div class="hero-btn-group">
                            <a href="#now-showing"
                                class="btn btn-outline-dark rounded-0 px-4 py-2 border-2 fw-bold text-uppercase"
                                style="letter-spacing: 1px;">
                                Book Your Ticket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="mt-3 container">
    <?php flash('booking_success'); ?>

    <!-- Features Section -->
    <div class="features-section mb-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3 class="feature-title">Instant Booking</h3>
                    <p class="feature-text">Book your favorite movies in seconds with our seamless and secure checkout
                        process.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-volume-up"></i>
                    </div>
                    <h3 class="feature-title">Immersive Sound</h3>
                    <p class="feature-text">Experience every whisper and explosion with our state-of-the-art Dolby Atmos
                        systems.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="feature-title">Exclusive Perks</h3>
                    <p class="feature-text">Join our membership for early access, special screenings, and free popcorn
                        upgrades.</p>
                </div>
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
                id="pills-coming-tab" data-bs-toggle="pill" data-bs-target="#pills-coming" type="button"
                role="tab">Coming
                Soon</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?php echo $data['section'] == 'ended' ? 'active' : ''; ?> rounded-pill px-4"
                id="pills-ended-tab" data-bs-toggle="pill" data-bs-target="#pills-ended" type="button"
                role="tab">Stopped
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
                                    <span
                                        class="page-link border-0 shadow-none mx-1 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">...</span>
                                </li>
                                    <?php endif; endfor; ?>

                        <!-- Next -->
                        <li
                            class="page-item <?php echo $data['current_page'] >= $data['total_pages'] ? 'disabled' : ''; ?>">
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
        <div class="tab-pane fade <?php echo $data['section'] == 'coming-soon' ? 'show active' : ''; ?>"
            id="pills-coming" role="tabpanel">
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
                                <span
                                    class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm">Coming
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
                                    <span
                                        class="page-link border-0 shadow-none mx-1 d-flex align-items-center justify-content-center"
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
                                    <div class="bg-light h-100 w-100 rounded d-flex align-items-center justify-content-center">
                                        No
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
                                    <span
                                        class="page-link border-0 shadow-none mx-1 d-flex align-items-center justify-content-center"
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

    <!-- Promo Banner -->
    <div class="promo-banner">
        <div class="promo-content">
            <h2 class="promo-title">Unlimited Movies, One Price</h2>
            <p class="promo-text">Get the Ultimate Pass today and watch as many movies as you want for just $19.99/month. Experience cinema without limits.</p>
            <a href="#" class="btn btn-landing-primary btn-landing shadow-lg">Get Started Now</a>
        </div>
    </div>

    <?php require APP_ROOT . '/views/inc/footer.php'; ?>