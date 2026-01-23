<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h4 class="mb-0 text-primary fw-bold"><i class="fas fa-credit-card"></i> Payment Confirmation</h4>
            </div>
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-4">Booking Summary</h5>

                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Movie</div>
                    <div class="col-md-8 fw-bold">
                        <?php echo $data['movie']->title; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Cinema / Room</div>
                    <div class="col-md-8">
                        <?php echo $data['room']->name; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Showtime</div>
                    <div class="col-md-8">
                        <?php echo date('H:i, d M Y', strtotime($data['showtime']->start_time)); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Seats</div>
                    <div class="col-md-8">
                        <?php foreach ($data['seat_codes'] as $code): ?>
                            <span class="badge bg-primary me-1">
                                <?php echo $code; ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="h5 mb-0">Total Amount</span>
                    <span class="h3 text-danger fw-bold mb-0">
                        <?php echo number_format($data['total_amount']); ?> VND
                    </span>
                </div>

                <!-- Payment Method Mockup -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Select Payment Method</label>
                    <div class="d-flex gap-3">
                        <div class="border rounded p-3 w-100 text-center"
                            style="cursor: pointer; border-color: var(--primary-color) !important; background: #eef7ff;">
                            <i class="fas fa-wallet fa-2x text-primary mb-2"></i><br>
                            <span class="fw-bold">E-Wallet (Momo/ZaloPay)</span>
                        </div>
                        <div class="border rounded p-3 w-100 text-center text-muted">
                            <i class="fas fa-credit-card fa-2x mb-2"></i><br>
                            Credit Card
                        </div>
                    </div>
                </div>

                <form action="<?php echo URLROOT; ?>/booking/process_payment" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="showtime_id" value="<?php echo $data['showtime']->id; ?>">
                    <?php foreach ($data['selected_seats'] as $seatId): ?>
                        <input type="hidden" name="seats[]" value="<?php echo $seatId; ?>">
                    <?php endforeach; ?>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg py-3 fw-bold shadow-sm">
                            PAY NOW
                            <?php echo number_format($data['total_amount']); ?> VND
                        </button>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?php echo URLROOT; ?>/booking/seats/<?php echo $data['showtime']->id; ?>"
                            class="text-muted text-decoration-none">Cancel & Go Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>