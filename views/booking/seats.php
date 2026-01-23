<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <?php flash('booking_msg'); ?>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="bg-white p-4 rounded-3 border shadow-sm d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1 text-dark"><?php echo $data['movie']->title; ?></h4>
                <p class="mb-0 text-muted small">
                    <span class="me-3"><i class="fas fa-map-marker-alt text-primary"></i>
                        <?php echo $data['room']->name; ?></span>
                    <span><i class="far fa-clock text-primary"></i> <?php echo $data['showtime']->start_time; ?></span>
                </p>
            </div>
            <div class="text-end">
                <h4 class="text-primary fw-bold mb-0"><?php echo number_format($data['showtime']->price); ?> đ</h4>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <!-- Screen -->
        <div class="screen-box">SCREEN</div>

        <form action="<?php echo URLROOT; ?>/booking/checkout" method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="showtime_id" value="<?php echo $data['showtime']->id; ?>">

            <div class="d-flex justify-content-center align-items-start gap-4 mb-5">
                <!-- Left Aisle -->
                <div class="aisle d-none d-md-flex flex-column justify-content-between h-100 py-2">
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                </div>

                <div class="seat-grid"
                    style="display: grid; grid-template-columns: repeat(<?php echo $data['room']->total_cols; ?>, 1fr); gap: 10px;">
                    <?php foreach ($data['seats'] as $seat): ?>
                        <?php
                        $isBooked = in_array($seat->id, $data['bookedSeats']);
                        // Use neutral 'secondary' for available, handled by CSS overrides
                        $class = $isBooked ? 'btn-secondary disabled' : 'btn-outline-secondary shadow-none';
                        ?>
                        <div class="text-center">
                            <?php if ($isBooked): ?>
                                <button type="button" class="btn <?php echo $class; ?> btn-sm"
                                    style="width: 40px; height: 35px;" disabled></button>
                            <?php else: ?>
                                <input type="checkbox" name="seats[]" value="<?php echo $seat->id; ?>"
                                    id="seat-<?php echo $seat->id; ?>" class="btn-check">
                                <label class="btn <?php echo $class; ?> btn-sm" for="seat-<?php echo $seat->id; ?>"
                                    style="width: 40px; height: 35px;">
                                    <span style="font-size: 0.7rem;"><?php echo $seat->seat_code; ?></span>
                                </label>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Right Aisle -->
                <div class="aisle d-none d-md-flex flex-column justify-content-between h-100 py-2">
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                    <div class="step"></div>
                </div>
            </div>

            <!-- Legend -->
            <div class="d-flex justify-content-center gap-4 mb-4 small text-muted">
                <div class="d-flex align-items-center"><span class="d-inline-block border rounded me-2"
                        style="width:20px; height:20px;"></span> Available</div>
                <div class="d-flex align-items-center"><span class="d-inline-block bg-primary rounded me-2"
                        style="width:20px; height:20px;"></span> Selected</div>
                <div class="d-flex align-items-center"><span class="d-inline-block bg-light border rounded me-2"
                        style="width:20px; height:20px; background-color: #E6E9ED !important;"></span> Taken</div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm">
                    Confirm Booking
                </button>
            </div>
        </form>
    </div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>