<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row" style="padding-top: 30px;">
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
                <h4 class="text-primary fw-bold mb-0">Base Price: <?php echo number_format($data['showtime']->price); ?>
                    đ</h4>
            </div>
        </div>
    </div>
</div>

<form action="<?php echo URLROOT; ?>/booking/checkout" method="post" id="bookingForm">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="showtime_id" value="<?php echo $data['showtime']->id; ?>">

    <div class="row">
        <!-- LEFT COLUMN: SCREEN & SEATS -->
        <div class="col-lg-8 mb-4">
            <div class="bg-white p-4 rounded-3 border shadow-sm h-100">
                <!-- Screen -->
                <div class="screen-box mb-5">SCREEN</div>

                <div class="d-flex justify-content-center align-items-start gap-4 mb-5">
                    <!-- Left Aisle -->
                    <div class="aisle d-none d-md-flex flex-column justify-content-between h-100 py-2">
                        <?php for ($i = 0; $i < 9; $i++): ?>
                            <div class="step"></div><?php endfor; ?>
                    </div>

                    <div class="seat-grid"
                        style="display: grid; grid-template-columns: repeat(<?php echo $data['room']->total_cols; ?>, 1fr); gap: 10px;">
                        <?php foreach ($data['seats'] as $seat): ?>
                            <?php
                            $isBooked = in_array($seat->id, $data['bookedSeats']);
                            $isLocked = ($seat->status == 'locked');
                            $isUnavailable = $isBooked || $isLocked;
                            $class = $isUnavailable ? 'btn-secondary disabled' : 'btn-outline-secondary shadow-none';
                            ?>
                            <div class="text-center">
                                <?php if ($isUnavailable): ?>
                                    <button type="button" class="btn <?php echo $class; ?> btn-sm"
                                        style="width: 40px; height: 35px;" disabled></button>
                                <?php else: ?>
                                    <input type="checkbox" name="seats[]" value="<?php echo $seat->id; ?>"
                                        id="seat-<?php echo $seat->id; ?>" class="btn-check seat-checkbox"
                                        data-seat-code="<?php echo $seat->seat_code; ?>">
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
                        <?php for ($i = 0; $i < 9; $i++): ?>
                            <div class="step"></div><?php endfor; ?>
                    </div>
                </div>

                <!-- Legend -->
                <div class="d-flex justify-content-center gap-4 mt-auto small text-muted">
                    <div class="d-flex align-items-center"><span class="d-inline-block border rounded me-2"
                            style="width:20px; height:20px;"></span> Available</div>
                    <div class="d-flex align-items-center"><span class="d-inline-block bg-primary rounded me-2"
                            style="width:20px; height:20px;"></span> Selected</div>
                    <div class="d-flex align-items-center"><span class="d-inline-block bg-light border rounded me-2"
                            style="width:20px; height:20px; background-color: #E6E9ED !important;"></span> Taken</div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: SUMMARY PANEL -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm summary-sticky" style="z-index: 90;">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0">Selection Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small fw-bold text-uppercase">Selected Seats</label>
                        <div id="selected-seats-display" class="fw-bold text-dark fs-5 text-break">
                            -
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Count:</span>
                        <span class="fw-bold"><span id="ticket-count">0</span> Tickets</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-muted fw-bold">TOTAL PRICE</span>
                        <span class="text-primary fw-bold fs-4" id="total-price">0 đ</span>
                    </div>

                    <button type="submit" id="confirm-btn"
                        class="btn btn-dark w-100 py-3 fw-bold text-uppercase shadow-sm" disabled>
                        Confirm Selection
                    </button>

                    <div class="mt-3 p-2 bg-light rounded border small text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Please review your selection before confirming. Seats are held for 10 minutes after booking.
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    /* Ensure aisles match height or just decoration */
    .aisle .step {
        width: 20px;
        height: 2px;
        background-color: #e0e0e0;
        margin: 5px 0;
    }

    /* Sticky summary adjustment */
    .summary-sticky {
        position: -webkit-sticky;
        position: sticky;
        top: 100px;
        /* Below navbar */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const unitPrice = <?php echo $data['showtime']->price; ?>;
        const seatsCheckboxes = document.querySelectorAll('.seat-checkbox');
        const displaySeats = document.getElementById('selected-seats-display');
        const displayCount = document.getElementById('ticket-count');
        const displayTotal = document.getElementById('total-price');
        const confirmBtn = document.getElementById('confirm-btn');

        function updateSummary() {
            const selected = Array.from(seatsCheckboxes).filter(cb => cb.checked);
            const codes = selected.map(cb => cb.dataset.seatCode).sort();

            // Update Seat Codes
            if (codes.length > 0) {
                displaySeats.textContent = codes.join(', ');
                displaySeats.classList.remove('text-muted');
            } else {
                displaySeats.textContent = '-';
                displaySeats.classList.add('text-muted');
            }

            // Update Count
            displayCount.textContent = selected.length;

            // Update Total
            const total = selected.length * unitPrice;
            displayTotal.textContent = new Intl.NumberFormat('en-US').format(total) + ' đ';

            // Update Button State
            confirmBtn.disabled = selected.length === 0;
            if (selected.length > 0) {
                confirmBtn.classList.remove('btn-secondary');
                confirmBtn.classList.add('btn-dark'); // Or primary
            }
        }

        seatsCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateSummary);
        });
    });
</script>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>