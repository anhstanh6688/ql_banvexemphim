<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold fs-2 mb-1">Manage Seats:
                <?php echo $data['room']->name; ?>
            </h1>
            <p class="text-muted mb-0">Click on a seat to toggle its availability (Locked/Available)</p>
        </div>
        <a href="<?php echo URLROOT; ?>/rooms" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Back to Rooms
        </a>
    </div>

    <!-- Screen Reference -->
    <div class="screen-container mb-5 text-center">
        <div class="screen bg-light shadow-sm text-muted text-uppercase fw-bold py-2 rounded-3 mx-auto"
            style="width: 60%; letter-spacing: 2px; font-size: 0.8rem;">
            Screen
        </div>
    </div>

    <!-- Seat Map -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="seat-map-container d-flex justify-content-center overflow-auto">
            <div class="seat-grid d-grid gap-2"
                style="grid-template-columns: repeat(<?php echo $data['room']->total_cols; ?>, auto);">
                <?php foreach ($data['seats'] as $seat): ?>
                    <button type="button"
                        class="btn seat-btn fw-bold small <?php echo $seat->status == 'available' ? 'btn-outline-success' : 'btn-danger'; ?>"
                        data-id="<?php echo $seat->id; ?>" data-status="<?php echo $seat->status; ?>"
                        data-code="<?php echo $seat->seat_code; ?>" style="width: 45px; height: 45px; font-size: 0.75rem;"
                        onclick="toggleSeat(this)">
                        <?php echo $seat->seat_code; ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="d-flex justify-content-center gap-4 mt-3">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded border border-success bg-white d-flex align-items-center justify-content-center text-success"
                style="width: 30px; height: 30px;"><i class="fas fa-check small"></i></div>
            <span class="small fw-bold">Available</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="rounded bg-danger d-flex align-items-center justify-content-center text-white"
                style="width: 30px; height: 30px;"><i class="fas fa-lock small"></i></div>
            <span class="small fw-bold">Locked / Broken</span>
        </div>
    </div>
</div>

<script>
    function toggleSeat(btn) {
        const seatId = btn.getAttribute('data-id');
        const currentStatus = btn.getAttribute('data-status');
        const newStatus = currentStatus === 'available' ? 'locked' : 'available';

        // Optimistic UI Update
        if (newStatus === 'locked') {
            btn.classList.remove('btn-outline-success');
            btn.classList.add('btn-danger');
            btn.setAttribute('data-status', 'locked');
        } else {
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-success');
            btn.setAttribute('data-status', 'available');
        }

        // Send Request
        fetch('<?php echo URLROOT; ?>/rooms/update_seat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                seat_id: seatId,
                status: newStatus
            })
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Error updating seat: ' + (data.message || 'Unknown error'));
                    // Revert UI on error
                    if (currentStatus === 'locked') {
                        btn.classList.remove('btn-outline-success');
                        btn.classList.add('btn-danger');
                        btn.setAttribute('data-status', 'locked');
                    } else {
                        btn.classList.remove('btn-danger');
                        btn.classList.add('btn-outline-success');
                        btn.setAttribute('data-status', 'available');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to connect to server');
            });
    }
</script>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>