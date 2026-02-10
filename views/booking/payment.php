<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h4 class="mb-0 text-primary fw-bold"><i class="fas fa-credit-card"></i> Payment Confirmation</h4>
            </div>
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- LEFT COLUMN: Payment Info & Options -->
                    <div class="col-md-7 p-4 border-end">
                        <h5 class="fw-bold mb-4 text-primary">Payment Information</h5>

                        <!-- Booking Summary -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Movie</span>
                                <span class="fw-bold text-end"><?php echo $data['movie']->title; ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Cinema / Room</span>
                                <span class="fw-bold text-end"><?php echo $data['room']->name; ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Showtime</span>
                                <span
                                    class="fw-bold text-end"><?php echo date('H:i, d/m/Y', strtotime($data['showtime']->start_time)); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Seats</span>
                                <div class="text-end">
                                    <?php foreach ($data['seat_codes'] as $code): ?>
                                        <span class="badge bg-primary me-1"><?php echo $code; ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Coupon Input with Select Button -->
                        <!-- Coupon Input with Dropdown -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Coupon Code</label>
                            <div class="input-group shadow-sm rounded-3 coupon-dropdown-group">
                                <input type="text" id="couponInput" class="form-control border-end-0"
                                    placeholder="Enter code (e.g. SALE100)">

                                <!-- Dropdown Toggle -->
                                <button class="btn btn-coupon-select border border-start-0 dropdown-toggle"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ticket-alt me-1"></i> Select
                                </button>

                                <!-- Dropdown Menu -->
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg p-0 border-0"
                                    style="min-width: 300px; max-height: 400px; overflow-y: auto;">
                                    <li class="p-2 bg-light border-bottom fw-bold text-muted small text-uppercase">
                                        <i class="fas fa-gift me-1"></i> Available Coupons
                                    </li>
                                    <?php
                                    // Fallback for coupons data
                                    $coupons = isset($data['available_coupons']) ? $data['available_coupons'] : [];
                                    ?>
                                    <?php if (!empty($coupons)): ?>
                                        <?php foreach ($coupons as $coupon): ?>
                                            <?php
                                            // Robust data access (Object vs Array)
                                            $code = is_object($coupon) ? $coupon->code : $coupon['code'];
                                            $dtype = is_object($coupon) ? $coupon->discount_type : $coupon['discount_type'];
                                            $dval = is_object($coupon) ? $coupon->discount_value : $coupon['discount_value'];

                                            // Handle missing description
                                            if (is_object($coupon) && isset($coupon->description)) {
                                                $desc = $coupon->description;
                                            } elseif (is_array($coupon) && isset($coupon['description'])) {
                                                $desc = $coupon['description'];
                                            } else {
                                                // Generate description if missing
                                                if ($dtype == 'percent') {
                                                    $desc = (float) $dval . "% off ticket price";
                                                } else {
                                                    $desc = number_format($dval, 0, ',', '.') . " VND off ticket price";
                                                }
                                            }
                                            ?>
                                            <li>
                                                <a class="dropdown-item p-3 border-bottom d-flex justify-content-between align-items-center coupon-select-item"
                                                    href="#" data-code="<?php echo $code; ?>">
                                                    <div>
                                                        <div class="fw-bold text-primary mb-1"><?php echo $code; ?></div>
                                                        <div class="small text-muted text-wrap"
                                                            style="max-width: 180px; line-height: 1.2;"><?php echo $desc; ?>
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                                        <?php echo ($dtype == 'percent') ? '-' . $dval . '%' : '-' . number_format($dval) . ' đ'; ?>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="p-4 text-center text-muted">
                                            <i class="fas fa-ticket-alt mb-2 opacity-25" style="font-size: 2rem;"></i>
                                            <p class="small mb-0">No coupons available</p>
                                        </li>
                                    <?php endif; ?>
                                </ul>

                                <button class="btn btn-primary" type="button" id="applyCouponBtn">Apply</button>
                            </div>
                            <div id="couponMessage" class="form-text mt-1"></div>
                        </div>

                        <!-- Total Display -->
                        <div
                            class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded shadow-sm">
                            <span class="h5 mb-0">Total Amount</span>
                            <div class="text-end">
                                <div class="h6 text-muted text-decoration-line-through mb-0 d-none"
                                    id="originalPriceDisplay">
                                    <?php echo number_format($data['total_amount']); ?> VND
                                </div>
                                <span class="h3 text-danger fw-bold mb-0" id="finalPriceDisplay">
                                    <?php echo number_format($data['total_amount']); ?> VND
                                </span>
                            </div>
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="mb-4" id="paymentMethodSection">
                            <label class="form-label fw-bold mb-2">Payment Method</label>
                            <div class="d-flex gap-2">
                                <div class="border rounded p-2 text-center payment-option selected-method flex-grow-1"
                                    data-method="vietqr"
                                    style="cursor: pointer; background: #eef7ff; border-color: var(--primary-color) !important;">
                                    <img src="https://img.vietqr.io/image/BIDV-8871414053-compact.jpg?amount=0&addInfo=S"
                                        alt="VietQR" height="25" class="mb-1"
                                        style="object-fit: contain; display: block; margin: 0 auto; min-height: 25px;">
                                    <div class="small fw-bold">VietQR</div>
                                </div>
                                <div class="border rounded p-2 text-center text-muted flex-grow-1"
                                    style="opacity: 0.6; cursor: not-allowed;">
                                    <i class="fas fa-wallet fa-lg mb-1"></i><br>
                                    <span class="small">E-Wallet</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Form -->
                        <form action="<?php echo URLROOT; ?>/booking/process_payment" method="post" id="checkoutForm">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="showtime_id" value="<?php echo $data['showtime']->id; ?>">
                            <?php foreach ($data['selected_seats'] as $seatId): ?>
                                <input type="hidden" name="seats[]" value="<?php echo $seatId; ?>">
                            <?php endforeach; ?>

                            <input type="hidden" name="coupon_code" id="hiddenCouponCode">
                            <input type="hidden" name="payment_method" id="hiddenPaymentMethod" value="vietqr">

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm" id="payNowBtn">
                                    CONFIRM PAYMENT
                                </button>
                                <a href="<?php echo URLROOT; ?>/booking/seats/<?php echo $data['showtime']->id; ?>"
                                    class="btn btn-light text-muted">Cancel & Go Back</a>
                            </div>
                        </form>
                    </div>

                    <!-- RIGHT COLUMN: QR Display -->
                    <div class="col-md-5 bg-light p-4 d-flex flex-column justify-content-center align-items-center text-center"
                        id="qrColumn">
                        <h5 class="fw-bold mb-4 text-primary">Scan to Pay</h5>

                        <div id="qrCodeContainer" class="bg-white p-3 rounded shadow-sm border"
                            style="width: 100%; max-width: 300px;">
                            <div id="qrImageWrapper" class="mb-3 position-relative"
                                style="min-height: 250px; display: flex; align-items: center; justify-content: center;">
                                <!-- Loader / Placeholder -->
                                <div id="qrLoader"
                                    class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center bg-white"
                                    style="z-index: 1;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <!-- Actual Image -->
                                <img src="" id="vietqrImage" class="img-fluid"
                                    style="width: 100%; display: block; z-index: 2;"
                                    onload="document.getElementById('qrLoader').classList.add('d-none')"
                                    onerror="this.style.display='none'; document.getElementById('qrLoader').innerHTML='Error loading QR'">
                            </div>

                            <div class="alert alert-warning py-2 small mb-0">
                                <i class="fas fa-stopwatch me-1"></i> Please pay within 10 minutes
                            </div>

                            <div class="mt-3 small text-start border-top pt-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Account Holder:</span>
                                    <span class="fw-bold">NGUYEN VIET ANH</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Content:</span>
                                    <span class="fw-bold text-primary" id="qrContentDisplay">THANHTOAN VE
                                        <?php echo $data['showtime']->id; ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Success / Free Message -->
                        <div id="freeBookingMessage" class="d-none text-center">
                            <div class="text-success mb-3">
                                <i class="fas fa-check-circle fa-4x"></i>
                            </div>
                            <h5 class="fw-bold text-success">Payment Free!</h5>
                            <p class="text-muted">Please confirm on the left.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Coupon Modal -->
<!-- Modal Removed -->

<script>
    // Variables
    let currentAmount = <?php echo $data['total_amount']; ?>;
    const initialAmount = <?php echo $data['total_amount']; ?>;
    const showtimeId = <?php echo $data['showtime']->id; ?>;

    // Elements
    const elements = {
        applyBtn: document.getElementById('applyCouponBtn'),
        couponInput: document.getElementById('couponInput'),
        couponMsg: document.getElementById('couponMessage'),
        originalPrice: document.getElementById('originalPriceDisplay'),
        finalPrice: document.getElementById('finalPriceDisplay'),
        paymentSection: document.getElementById('paymentMethodSection'),
        hiddenCoupon: document.getElementById('hiddenCouponCode'),
        hiddenMethod: document.getElementById('hiddenPaymentMethod'),
        payNowBtn: document.getElementById('payNowBtn'),
        vietqrImg: document.getElementById('vietqrImage'),
        qrContainer: document.getElementById('qrCodeContainer'),
        qrColumn: document.getElementById('qrColumn'),
        freeMsg: document.getElementById('freeBookingMessage'),
        qrContentDisplay: document.getElementById('qrContentDisplay'),
        paymentOptions: document.querySelectorAll('.payment-option')
    };

    // Formatter
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('en-US').format(amount);
    };

    // --- 1. Coupon Logic ---
    if (elements.applyBtn) {
        elements.applyBtn.addEventListener('click', function () {
            checkCoupon();
        });
    }

    // Modal Coupon Selection
    // Dropdown Item Selection
    document.querySelectorAll('.coupon-select-item').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const code = this.getAttribute('data-code');
            elements.couponInput.value = code;

            // Force close the dropdown (disable hover effect temporarily)
            const group = this.closest('.coupon-dropdown-group');
            if (group) {
                group.classList.add('force-close');

                // Re-enable hover when mouse leaves the area
                const restoreHover = function () {
                    group.classList.remove('force-close');
                    group.removeEventListener('mouseleave', restoreHover);
                };
                group.addEventListener('mouseleave', restoreHover);
            }
        });
    });

    function checkCoupon() {
        const code = elements.couponInput.value.trim();
        if (!code) return;

        elements.applyBtn.disabled = true;
        elements.couponMsg.innerHTML = '<span class="text-info">Checking...</span>';

        const formData = new FormData();
        formData.append('code', code);
        formData.append('total_amount', initialAmount);

        fetch('<?php echo URLROOT; ?>/booking/check_coupon', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                elements.applyBtn.disabled = false;
                if (data.success) {
                    try {
                        // Update Amount
                        currentAmount = parseFloat(data.new_total);

                        // Update UI
                        elements.couponMsg.innerHTML = `<span class="text-success fw-bold"><i class="fas fa-check"></i> ${data.message} (-${formatCurrency(data.discount_amount)} VND)</span>`;

                        if (elements.originalPrice) elements.originalPrice.classList.remove('d-none');
                        if (elements.finalPrice) elements.finalPrice.innerText = formatCurrency(currentAmount) + ' VND';

                        if (elements.hiddenCoupon) elements.hiddenCoupon.value = code;

                        // Update State
                        checkFreePayment();
                        updateQR();

                    } catch (e) {
                        console.error('UI Update Error:', e);
                        elements.couponMsg.innerText = 'Error updating UI.';
                    }
                } else {
                    elements.couponMsg.innerHTML = `<span class="text-danger"><i class="fas fa-times"></i> ${data.message}</span>`;
                }
            })
            .catch(err => {
                elements.applyBtn.disabled = false;
                elements.couponMsg.innerText = 'Error checking coupon.';
                console.error('Coupon Error:', err);
            });
    }

    // --- 2. Free Payment Check ---
    function checkFreePayment() {
        if (!elements.payNowBtn) return;

        if (currentAmount <= 0) {
            // Free mode
            if (elements.paymentSection) elements.paymentSection.classList.add('d-none');
            if (elements.qrContainer) elements.qrContainer.classList.add('d-none');
            if (elements.freeMsg) elements.freeMsg.classList.remove('d-none');

            elements.payNowBtn.innerHTML = 'CONFIRM (FREE)';
            elements.payNowBtn.className = 'btn btn-primary btn-lg fw-bold shadow-sm';

            if (elements.hiddenMethod) elements.hiddenMethod.value = 'free';
        } else {
            // Paid mode
            if (elements.paymentSection) elements.paymentSection.classList.remove('d-none');

            // Show QR if method is VietQR
            if (elements.hiddenMethod && elements.hiddenMethod.value === 'vietqr') {
                if (elements.qrContainer) elements.qrContainer.classList.remove('d-none');
            }
            if (elements.freeMsg) elements.freeMsg.classList.add('d-none');

            elements.payNowBtn.innerHTML = 'CONFIRM PAYMENT';
            elements.payNowBtn.className = 'btn btn-success btn-lg fw-bold shadow-sm';

            // Default back to VietQR if checking validity
            if (elements.hiddenMethod && elements.hiddenMethod.value === 'free') {
                elements.hiddenMethod.value = 'vietqr';
                if (elements.qrContainer) elements.qrContainer.classList.remove('d-none');
            }
        }
    }

    // --- 3. QR Update ---
    function updateQR() {
        if (currentAmount <= 0) return;

        const amount = Math.round(currentAmount);
        const content = "THANHTOAN VE " + showtimeId;
        const qrUrl = `https://img.vietqr.io/image/BIDV-8871414053-compact2.jpg?amount=${amount}&addInfo=${encodeURIComponent(content)}`;

        console.log("Updating QR: ", qrUrl);

        if (elements.vietqrImg) {
            elements.vietqrImg.src = qrUrl;
        }
        if (elements.qrContentDisplay) {
            elements.qrContentDisplay.innerText = content;
        }
    }

    // --- 4. Payment Method Selection ---
    elements.paymentOptions.forEach(option => {
        option.addEventListener('click', function () {
            // Reset styles
            elements.paymentOptions.forEach(opt => {
                opt.style.backgroundColor = 'transparent';
                opt.style.borderColor = '#dee2e6';
                opt.classList.remove('selected-method');
            });
            // Set Active
            this.style.backgroundColor = '#eef7ff';
            this.style.borderColor = 'var(--primary-color)';
            this.classList.add('selected-method');

            const method = this.getAttribute('data-method');
            if (elements.hiddenMethod) elements.hiddenMethod.value = method;

            if (method === 'vietqr') {
                if (elements.qrContainer) elements.qrContainer.classList.remove('d-none');
                updateQR();
            } else {
                if (elements.qrContainer) elements.qrContainer.classList.add('d-none');
            }
        });
    });

    // --- Init ---
    window.addEventListener('DOMContentLoaded', () => {
        checkFreePayment();
        if (currentAmount > 0) updateQR();
    });
    // Run immediately just in case
    checkFreePayment();
    if (currentAmount > 0) updateQR();

</script>