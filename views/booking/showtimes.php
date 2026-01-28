<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="container mt-5">
    <div class="row">

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden sticky-top floating-card"
                style="top: 110px; z-index: 900;">
                <div class="position-relative">
                    <?php if (!empty($data['movie']->poster)): ?>
                        <img src="<?php echo $data['movie']->poster; ?>" class="img-fluid w-100"
                            style="height: 450px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-gray-200 d-flex align-items-center justify-content-center" style="height: 450px;">
                            <i class="fas fa-film fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <div class="position-absolute bottom-0 start-0 w-100 p-3"
                        style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                        <?php if (isset($data['movie_info']['rating'])): ?>
                            <span class="badge bg-warning text-dark mb-2"><i class="fas fa-star me-1"></i>
                                <?php echo $data['movie_info']['rating']; ?></span>
                        <?php endif; ?>
                        <h2 class="text-white fw-bold mb-0 text-shadow"><?php echo $data['movie']->title; ?></h2>
                    </div>
                </div>
                <div class="card-body bg-white p-4">
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted fw-bold small mb-3">Movie Info</h6>
                        <div class="d-flex mb-2">
                            <span class="text-muted flex-shrink-0" style="width: 100px;">Release:</span>
                            <span class="fw-medium text-dark">
                                <?php echo date('d M Y', strtotime($data['movie']->release_date)); ?></span>
                        </div>
                        <div class="d-flex mb-2">
                            <span class="text-muted flex-shrink-0" style="width: 100px;">Genre:</span>
                            <span class="fw-medium text-dark"><?php echo $data['movie']->genre; ?></span>
                        </div>
                        <div class="d-flex mb-2">
                            <span class="text-muted flex-shrink-0" style="width: 100px;">Duration:</span>
                            <span class="fw-medium text-dark"><?php echo $data['movie']->duration; ?> mins</span>
                        </div>
                        <?php if (isset($data['movie_info']['language'])): ?>
                            <div class="d-flex mb-2">
                                <span class="text-muted flex-shrink-0" style="width: 100px;">Language:</span>
                                <span class="fw-medium text-dark"><?php echo $data['movie_info']['language']; ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($data['movie_info']['director'])): ?>
                            <div class="d-flex mb-2">
                                <span class="text-muted flex-shrink-0" style="width: 100px;">Director:</span>
                                <span class="fw-medium text-dark"><?php echo $data['movie_info']['director']; ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($data['movie_info']['cast'])): ?>
                            <div class="d-flex">
                                <span class="text-muted flex-shrink-0" style="width: 100px;">Cast:</span>
                                <span class="fw-medium text-dark"><?php echo $data['movie_info']['cast']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <hr class="text-muted opacity-25">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small mb-2">Description</h6>
                        <p class="text-secondary small" style="line-height: 1.6;">
                            <?php echo $data['movie']->description; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Showtimes & Reviews -->
        <div class="col-md-8">
            <!-- Tabs -->
            <ul class="nav nav-pills mb-4 gap-2" id="mainTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active px-4 rounded-pill fw-bold" id="showtimes-tab" data-bs-toggle="tab"
                        data-bs-target="#showtimes-pane" type="button">Showtimes</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link px-4 rounded-pill fw-bold" id="details-tab" data-bs-toggle="tab"
                        data-bs-target="#details-pane" type="button">Synopsis & Trailer</button>
                </li>
            </ul>

            <div class="tab-content" id="mainTabContent">
                <!-- SHOWTIMES TAB -->
                <div class="tab-pane fade show active" id="showtimes-pane" role="tabpanel">

                    <!-- Filters (Restored "Beautiful" Layout) -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-3 d-flex flex-wrap align-items-center gap-3">
                            <span class="fw-bold text-dark"><i
                                    class="fas fa-filter me-2 text-primary"></i>Filter:</span>

                            <!-- Room Filter -->
                            <select class="form-select form-select-sm rounded-pill shadow-none border-secondary-subtle"
                                id="roomFilter" style="width: auto; padding-right: 2.5rem; cursor: pointer;">
                                <option value="all">All Rooms</option>
                                <option value="Room A">Room A</option>
                                <option value="Room B">Room B</option>
                                <option value="Room C">Room C</option>
                            </select>

                            <!-- Time Filter -->
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="timeFilter" id="timeAll" value="all"
                                    checked>
                                <label class="btn btn-sm btn-outline-light text-dark rounded-start-pill border-end-0"
                                    for="timeAll">All</label>

                                <input type="radio" class="btn-check" name="timeFilter" id="timeMorning"
                                    value="morning">
                                <label class="btn btn-sm btn-outline-light text-dark border-end-0"
                                    for="timeMorning">Morning</label>

                                <input type="radio" class="btn-check" name="timeFilter" id="timeAfternoon"
                                    value="afternoon">
                                <label class="btn btn-sm btn-outline-light text-dark border-end-0"
                                    for="timeAfternoon">Afternoon</label>

                                <input type="radio" class="btn-check" name="timeFilter" id="timeEvening"
                                    value="evening">
                                <label class="btn btn-sm btn-outline-light text-dark rounded-end-pill"
                                    for="timeEvening">Evening</label>
                            </div>

                            <div class="form-check form-switch ms-auto">
                                <input class="form-check-input" type="checkbox" id="availSwitch" checked>
                                <label class="form-check-label small" for="availSwitch">Show Available Only</label>
                            </div>
                        </div>
                    </div>

                    <!-- Date Tabs -->
                    <?php if (empty($data['grouped_showtimes'])): ?>
                        <div class="alert alert-info rounded-3 text-center py-5">
                            <i class="fas fa-calendar-times fa-2x mb-3 text-info"></i><br>
                            No showtimes available at the moment.
                        </div>
                    <?php else: ?>
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-5">
                            <ul class="nav nav-underline mb-4 border-bottom" id="dateTab" role="tablist">
                                <?php $index = 0;
                                foreach ($data['grouped_showtimes'] as $date => $shows): ?>
                                    <li class="nav-item me-3">
                                        <button
                                            class="nav-link <?php echo $index === 0 ? 'active fw-bold text-primary' : 'text-muted'; ?> px-3 pb-3"
                                            id="date-<?php echo $date; ?>-tab" data-bs-toggle="tab"
                                            data-bs-target="#date-<?php echo $date; ?>" type="button">
                                            <?php echo date('D, d M', strtotime($date)); ?>
                                        </button>
                                    </li>
                                    <?php $index++; endforeach; ?>
                            </ul>

                            <div class="tab-content">
                                <?php $index = 0;
                                foreach ($data['grouped_showtimes'] as $date => $shows): ?>
                                    <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>"
                                        id="date-<?php echo $date; ?>" role="tabpanel">

                                        <div class="showtime-carousel-wrapper position-relative group-carousel">
                                            <!-- Controls -->
                                            <button
                                                class="btn btn-light rounded-circle shadow-sm position-absolute start-0 top-50 translate-middle-y z-3 carousel-btn prev-btn opacity-0 transition-opacity"
                                                style="margin-left: -15px; width: 40px; height: 40px; pointer-events: none;">
                                                <i class="fas fa-chevron-left text-primary"></i>
                                            </button>

                                            <button
                                                class="btn btn-light rounded-circle shadow-sm position-absolute end-0 top-50 translate-middle-y z-3 carousel-btn next-btn"
                                                style="margin-right: -15px; width: 40px; height: 40px;">
                                                <i class="fas fa-chevron-right text-primary"></i>
                                            </button>

                                            <!-- Track -->
                                            <div class="d-flex flex-nowrap gap-3 overflow-hidden showtime-track py-2"
                                                id="showtime-track-<?php echo $date; ?>"
                                                style="scroll-behavior: smooth; -webkit-overflow-scrolling: touch;">

                                                <!-- Dynamic No Shows Message -->
                                                <div class="no-shows-msg w-100 text-center py-5 d-none">
                                                    <i class="fas fa-search-minus fa-2x text-muted mb-3 opacity-50"></i>
                                                    <p class="text-muted fw-bold">No showtimes match your filter.</p>
                                                </div>

                                                <?php foreach ($shows as $show):
                                                    // Calculate Stats
                                                    $booked = isset($data['ticket_counts'][$show->id]) ? $data['ticket_counts'][$show->id] : 0;
                                                    // Dynamic Total Seats from Room Size
                                                    $total = $show->total_rows * $show->total_cols;
                                                    // Locked Seats
                                                    $locked = isset($data['locked_seats'][$show->room_id]) ? $data['locked_seats'][$show->room_id] : 0;
                                                    $available = max(0, $total - $booked - $locked);
                                                    $percent = ($booked / $total) * 100;

                                                    // Time Period
                                                    $hour = (int) date('H', strtotime($show->start_time));
                                                    $period = 'evening';
                                                    if ($hour < 12)
                                                        $period = 'morning';
                                                    elseif ($hour < 17)
                                                        $period = 'afternoon';
                                                    ?>
                                                    <a href="<?php echo URLROOT; ?>/booking/seats/<?php echo $show->id; ?>"
                                                        class="btn btn-outline-light text-dark border shadow-sm p-0 overflow-hidden text-start showtime-item position-relative group-hover flex-shrink-0"
                                                        style="width: 200px; transition: all 0.2s;"
                                                        data-room="<?php echo $show->room_name; ?>"
                                                        data-period="<?php echo $period; ?>"
                                                        data-available="<?php echo $available; ?>">

                                                        <div class="p-3">
                                                            <div class="fs-4 fw-bold text-primary mb-1">
                                                                <?php echo date('H:i', strtotime($show->start_time)); ?>
                                                            </div>
                                                            <div class="small text-muted mb-3 d-flex align-items-center">
                                                                <i class="fas fa-tv me-1"></i> <?php echo $show->room_name; ?>
                                                            </div>
                                                            <div
                                                                class="d-flex justify-content-between align-items-center pt-2 border-top border-light-subtle">
                                                                <span
                                                                    class="fw-bold text-success small"><?php echo number_format($show->price / 1000); ?>k</span>
                                                                <span
                                                                    class="badge bg-light text-secondary border border-light-subtle fw-normal"
                                                                    style="font-size: 0.7rem;"><?php echo $available; ?>/<?php echo $total; ?></span>
                                                            </div>
                                                        </div>
                                                        <!-- Progress Bar at bottom -->
                                                        <div class="progress" style="height: 4px; border-radius: 0;">
                                                            <div class="progress-bar bg-<?php echo $available < 10 ? 'danger' : ($available < 30 ? 'warning' : 'success'); ?>"
                                                                role="progressbar" style="width: <?php echo 100 - $percent; ?>%">
                                                            </div>
                                                        </div>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div> <!-- End Track -->
                                        </div> <!-- End Carousel Wrapper (Added Missing Div) -->
                                    </div> <!-- End Tab Pane -->
                                    <?php $index++; endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- REVIEWS SECTION (Moved Here) -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mt-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">User Reviews</h5>
                            <?php if (!isLoggedIn()): ?>
                                <a href="<?php echo URLROOT; ?>/auth/login"
                                    class="btn btn-sm btn-outline-primary rounded-pill">Login to Review</a>
                            <?php endif; ?>
                        </div>

                        <!-- Review Form -->
                        <?php if (isLoggedIn() && isset($data['user_has_commented']) && !$data['user_has_commented']): ?>
                            <div class="card bg-light border-0 rounded-3 p-3 mb-4">
                                <h6 class="fw-bold mb-3">Write a Review</h6>
                                <form action="<?php echo URLROOT; ?>/comments/create" method="POST">
                                    <input type="hidden" name="movie_id" value="<?php echo $data['movie']->id; ?>">
                                    <div class="mb-3">
                                        <div class="rating-css">
                                            <select name="rating" class="form-select form-select-sm w-auto d-inline-block"
                                                style="padding-right: 2.5rem;">
                                                <option value="10">10 - Masterpiece</option>
                                                <option value="9">9 - Excellent</option>
                                                <option value="8">8 - Great</option>
                                                <option value="7">7 - Good</option>
                                                <option value="6">6 - Fine</option>
                                                <option value="5">5 - Average</option>
                                                <option value="4">4 - Bad</option>
                                                <option value="1">1 - Terrible</option>
                                            </select>
                                            <span class="text-muted small ms-2">Star Rating</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <textarea name="content" class="form-control" rows="3"
                                            placeholder="Share your thoughts..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">Post
                                        Review</button>
                                </form>
                            </div>
                        <?php elseif (isLoggedIn() && isset($data['user_has_commented']) && $data['user_has_commented']): ?>
                            <div class="alert alert-success py-2 small mb-4">
                                <i class="fas fa-check-circle me-1"></i> You have reviewed this movie.
                            </div>
                        <?php endif; ?>

                        <!-- Reviews List -->
                        <div class="reviews-list">
                            <?php if (empty($data['comments'])): ?>
                                <p class="text-muted text-center py-4">No reviews yet. Be the first to review!</p>
                            <?php else: ?>
                                <?php foreach ($data['comments'] as $comment): ?>
                                    <div class="border-bottom pb-3 mb-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                    style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    <?php echo strtoupper(substr($comment->user_name ?? 'U', 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <span class="fw-bold d-block lh-1 text-dark"
                                                        style="font-size: 0.9rem;"><?php echo $comment->user_name ?? 'User'; ?></span>
                                                    <span class="text-muted small"
                                                        style="font-size: 0.75rem;"><?php echo date('d M Y', strtotime($comment->created_at)); ?></span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-warning text-dark me-2"><i
                                                        class="fas fa-star me-1 small"></i><?php echo $comment->rating; ?></span>
                                                <?php if (isLoggedIn() && $_SESSION['user_id'] == $comment->user_id): ?>
                                                    <!-- Edit Button -->
                                                    <a href="<?php echo URLROOT; ?>/comments/edit/<?php echo $comment->id; ?>"
                                                        class="btn btn-sm btn-outline-secondary border-0 rounded-circle p-1 me-1"
                                                        style="width: 24px; height: 24px; line-height: 1;" title="Edit">
                                                        <i class="fas fa-pencil-alt" style="font-size: 0.8rem;"></i>
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <!-- Delete Button -->
                                                    <form id="delete-form-<?php echo $comment->id; ?>"
                                                        action="<?php echo URLROOT; ?>/comments/delete/<?php echo $comment->id; ?>"
                                                        method="POST" class="d-inline">
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger border-0 rounded-circle p-1"
                                                            style="width: 24px; height: 24px; line-height: 1;" title="Delete"
                                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                            onclick="setDeleteForm('delete-form-<?php echo $comment->id; ?>')">
                                                            <i class="fas fa-trash-alt" style="font-size: 0.8rem;"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <p class="text-secondary small mb-0" style="font-size: 0.9rem;">
                                            <?php echo nl2br(htmlspecialchars($comment->content)); ?>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

                <!-- DETAILS TAB -->
                <div class="tab-pane fade" id="details-pane" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-3">Synopsis</h5>
                        <p class="text-secondary mb-5" style="line-height: 1.8;">
                            Review: The movie "<?php echo $data['movie']->title; ?>" is a masterpiece of
                            storytelling.
                            Set in a visually stunning world, it explores themes of love, loss, and redemption.
                            <?php echo $data['movie']->description; ?>
                        </p>
                        <h5 class="fw-bold mb-3">Official Trailer</h5>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm bg-dark">
                            <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ?controls=0"
                                title="YouTube video player" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>

<script>
    // Delete Modal Logic
    document.addEventListener('DOMContentLoaded', function () {
        let targetFormId = null;

        // Make globally available for onclick
        window.setDeleteForm = function (formId) {
            targetFormId = formId;
        };

        const confirmBtn = document.getElementById('confirmDeleteBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                if (targetFormId) {
                    document.getElementById(targetFormId).submit();
                }
            });
        }
    });

    // Filter Logic
    document.addEventListener('DOMContentLoaded', function () {
        const roomSelect = document.getElementById('roomFilter');
        const timeRadios = document.querySelectorAll('input[name="timeFilter"]');
        const availSwitch = document.getElementById('availSwitch');
        const items = document.querySelectorAll('.showtime-item');

        function filterItems() {
            const room = roomSelect.value;
            const period = document.querySelector('input[name="timeFilter"]:checked').value;
            const onlyAvail = availSwitch.checked;

            // Handle each day tab separately
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabPanes.forEach(pane => {
                const items = pane.querySelectorAll('.showtime-item');
                const noShowsMsg = pane.querySelector('.no-shows-msg');

                if (!items.length) return;

                let visibleCount = 0;

                items.forEach(item => {
                    const itemRoom = item.dataset.room;
                    const itemPeriod = item.dataset.period;
                    const itemAvail = parseInt(item.dataset.available);

                    let show = true;
                    if (room !== 'all' && itemRoom !== room) show = false;
                    if (period !== 'all' && itemPeriod !== period) show = false;
                    if (onlyAvail && itemAvail <= 0) show = false;

                    item.style.display = show ? 'inline-block' : 'none';
                    if (show) visibleCount++;
                });

                // Toggle No Shows Message
                if (noShowsMsg) {
                    if (visibleCount === 0) {
                        noShowsMsg.classList.remove('d-none');
                    } else {
                        noShowsMsg.classList.add('d-none');
                    }
                }
            });
        }

        if (roomSelect) {
            roomSelect.addEventListener('change', filterItems);
            timeRadios.forEach(r => r.addEventListener('change', filterItems));
            availSwitch.addEventListener('change', filterItems);
        }
    });
</script>

<!-- Delete Confirmation Modal -->
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

<script>
    // Carousel Logic
    document.addEventListener('DOMContentLoaded', function () {
        const carousels = document.querySelectorAll('.showtime-carousel-wrapper');

        carousels.forEach(wrapper => {
            const track = wrapper.querySelector('.showtime-track');
            const nextBtn = wrapper.querySelector('.next-btn');
            const prevBtn = wrapper.querySelector('.prev-btn');
            const items = track.querySelectorAll('.showtime-item');

            // If no scroll needed, hide buttons
            if (track.scrollWidth <= track.clientWidth) {
                nextBtn.style.display = 'none';
                prevBtn.style.display = 'none';
                return;
            }

            const itemWidth = items[0].offsetWidth + 16; // width + gap

            // Interaction State
            let isPaused = false;
            let pauseTimeout;

            function pauseInteraction() {
                isPaused = true;
                clearTimeout(pauseTimeout);
                pauseTimeout = setTimeout(() => {
                    isPaused = false;
                }, 3000); // Resume after 3s of inactivity
            }

            // Manual Scroll
            nextBtn.addEventListener('click', () => {
                pauseInteraction();
                track.scrollBy({ left: itemWidth, behavior: 'smooth' });
            });

            prevBtn.addEventListener('click', () => {
                pauseInteraction();
                track.scrollBy({ left: -itemWidth, behavior: 'smooth' });
            });

            // Update Arrows Visibility
            track.addEventListener('scroll', () => {
                const maxScroll = track.scrollWidth - track.clientWidth;

                // Prev Btn
                if (track.scrollLeft > 10) {
                    prevBtn.classList.remove('opacity-0');
                    prevBtn.style.pointerEvents = 'auto';
                } else {
                    prevBtn.classList.add('opacity-0');
                    prevBtn.style.pointerEvents = 'none';
                }

                // Next Btn
                if (track.scrollLeft >= maxScroll - 10) {
                    nextBtn.classList.add('opacity-0');
                    nextBtn.style.pointerEvents = 'none';
                } else {
                    nextBtn.classList.remove('opacity-0');
                    nextBtn.style.pointerEvents = 'auto';
                }
            });

            // Auto-Scroll Logic
            let autoScrollInterval;

            function startAutoScroll() {
                clearInterval(autoScrollInterval);
                autoScrollInterval = setInterval(() => {
                    if (isPaused) return; // Don't scroll if user is interacting

                    // Check if at end
                    if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 1) {
                        // Reached end. Pause, then scroll back to start smoothly.
                        isPaused = true;
                        setTimeout(() => {
                            track.scrollTo({ left: 0, behavior: 'smooth' });
                            // Wait for scroll to finish before resuming
                            setTimeout(() => { isPaused = false; }, 1000);
                        }, 2000);
                    } else {
                        // Constant slow scroll
                        track.scrollLeft += 1;
                    }
                }, 40); // 40ms speed (slightly slower for smoothness)
            }

            function stopAutoScrollLoop() {
                clearInterval(autoScrollInterval);
            }

            // Init
            startAutoScroll();

            // Pause on Hover
            track.addEventListener('mouseenter', () => { isPaused = true; });
            track.addEventListener('mouseleave', () => { isPaused = false; });

            nextBtn.addEventListener('mouseenter', () => { isPaused = true; });
            prevBtn.addEventListener('mouseenter', () => { isPaused = true; });

            nextBtn.addEventListener('mouseleave', () => { isPaused = false; });
            prevBtn.addEventListener('mouseleave', () => { isPaused = false; });
        });
    });
</script>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>