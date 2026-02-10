<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="<?php echo URLROOT; ?>/users/history" class="btn btn-light text-muted shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Back to History
                </a>
                <span class="text-muted small">Order ID: #<?php echo $data['order']->id; ?></span>
            </div>

            <?php flash('booking_msg'); ?>

            <div class="card shadow-lg border-0 overflow-hidden mb-5">
                <div class="card-header bg-white border-bottom py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1 fw-bold text-primary"><?php echo $data['order']->movie_title; ?></h4>
                            <p class="mb-0 text-muted">
                                <i class="fas fa-map-marker-alt me-2 text-warning"></i>
                                <?php echo $data['order']->room_name; ?>
                                <span class="mx-2">|</span>
                                <i class="far fa-clock me-2 text-warning"></i>
                                <?php echo date('H:i, d M Y', strtotime($data['order']->start_time)); ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <h2 class="fw-bold text-dark mb-0">
                                <?php echo number_format($data['order']->total_amount); ?> đ
                            </h2>
                            <small class="text-success fw-bold"><i class="fas fa-check-circle"></i> Paid</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Ticket Code</th>
                                    <th>Seat Position</th>
                                    <th>Ticket Status</th>
                                    <th class="text-end pe-4">Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['tickets'] as $ticket): ?>
                                    <tr>
                                        <td class="ps-4 font-monospace fw-bold text-dark">
                                            <?php echo $ticket->ticket_code; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-3 py-2"
                                                style="font-size: 0.9rem;">
                                                <?php echo $ticket->seat_code; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($ticket->status == 'valid'): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i> Valid
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-ban me-1"></i> Cancelled
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <?php if ($ticket->status == 'valid'): ?>
                                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                                                    <!-- Admin Button: Same style as User, but opens Admin Modal -->
                                                    <button type="button" class="btn btn-cancel-custom btn-sm rounded-pill px-3"
                                                        data-bs-toggle="modal" data-bs-target="#adminCancelModal"
                                                        data-ticket-id="<?php echo $ticket->id; ?>">
                                                        Cancel Ticket
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-cancel-custom btn-sm rounded-pill px-3"
                                                        data-bs-toggle="modal" data-bs-target="#cancelModal"
                                                        data-href="<?php echo URLROOT; ?>/users/cancel_ticket/<?php echo $ticket->id; ?>">
                                                        Cancel Ticket
                                                    </button>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted small fst-italic">
                                                    <?php echo !empty($ticket->cancellation_reason) ? 'Reason: ' . htmlspecialchars($ticket->cancellation_reason) : 'No action'; ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light p-4">
                    <div class="row">
                        <div class="col-md-6 text-muted small">
                            <strong>Note:</strong> Tickets can only be cancelled up to 2 hours before showtime.
                        </div>
                        <div class="col-md-6 text-end">
                            <!-- Helper link or Print button could go here -->
                            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> Print Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Cancellation Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger" id="cancelModalLabel"><i
                        class="fas fa-exclamation-triangle me-2"></i> Confirm Cancellation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-1 fs-5">Are you sure you want to cancel this ticket?</p>
                <p class="text-muted small">This action cannot be undone. Refund policy applies.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Keep
                    Ticket</button>
                <a href="#" id="confirmCancelBtn" class="btn btn-danger rounded-pill px-4 shadow-sm">Yes, Cancel
                    Ticket</a>
            </div>
        </div>
    </div>
</div>

<!-- Admin Cancellation Modal -->
<div class="modal fade" id="adminCancelModal" tabindex="-1" aria-labelledby="adminCancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?php echo URLROOT; ?>/admin/cancel_ticket" method="POST">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger" id="adminCancelModalLabel"><i
                            class="fas fa-user-shield me-2"></i> Admin Cancellation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <p class="mb-3">Please provide a reason for cancelling this ticket. This reason will be visible to
                        the customer.</p>
                    <input type="hidden" name="ticket_id" id="adminTicketId">
                    <input type="hidden" name="order_id" value="<?php echo $data['order']->id; ?>">
                    <div class="mb-3">
                        <label for="cancellationReason" class="form-label fw-bold">Reason <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="cancellationReason" name="reason" rows="3" required
                            placeholder="e.g. Cinema maintenance, Technical error..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-end pt-0 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Confirm Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var cancelModal = document.getElementById('cancelModal');
    if (cancelModal) {
        cancelModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var href = button.getAttribute('data-href');
            var confirmBtn = cancelModal.querySelector('#confirmCancelBtn');
            confirmBtn.href = href;
        });
    }

    var adminCancelModal = document.getElementById('adminCancelModal');
    if (adminCancelModal) {
        adminCancelModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var ticketId = button.getAttribute('data-ticket-id');
            var inputTicketId = adminCancelModal.querySelector('#adminTicketId');
            inputTicketId.value = ticketId;
        });
    }
</script>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>