<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold text-primary mb-0"><i class="fas fa-edit me-2"></i>Edit Showtime</h3>
                    <a href="<?php echo URLROOT; ?>/showtimes" class="btn btn-outline-secondary rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>

                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger rounded-3 mb-4 icon-link-hover">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $data['error']; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/showtimes/edit/<?php echo $data['id']; ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <!-- Movie -->
                    <div class="mb-4">
                        <label for="movie_id" class="form-label fw-bold text-secondary">Movie</label>
                        <select name="movie_id" class="form-select form-select-lg rounded-3 shadow-sm bg-light border-0"
                            required>
                            <?php foreach ($data['movies'] as $movie): ?>
                                <option value="<?php echo $movie->id; ?>" <?php echo ($data['movie_id'] == $movie->id) ? 'selected' : ''; ?>>
                                    <?php echo $movie->title; ?> (
                                    <?php echo $movie->duration; ?> min)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row mb-4">
                        <!-- Room -->
                        <div class="col-md-6">
                            <label for="room_id" class="form-label fw-bold text-secondary">Room</label>
                            <select name="room_id"
                                class="form-select form-select-lg rounded-3 shadow-sm bg-light border-0" required>
                                <?php foreach ($data['rooms'] as $room): ?>
                                    <option value="<?php echo $room->id; ?>" <?php echo ($data['room_id'] == $room->id) ? 'selected' : ''; ?>>
                                        <?php echo $room->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Start Time -->
                        <div class="col-md-6">
                            <label for="start_time" class="form-label fw-bold text-secondary">Start Time</label>
                            <input type="datetime-local" name="start_time"
                                class="form-control form-control-lg rounded-3 shadow-sm bg-light border-0"
                                value="<?php echo $data['start_time']; ?>" required>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="mb-5">
                        <label for="price" class="form-label fw-bold text-secondary">Ticket Price (VND)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0 text-secondary fw-bold">₫</span>
                            <input type="number" name="price" class="form-control bg-light border-0 shadow-sm"
                                value="<?php echo $data['price']; ?>" required min="0" step="1000">
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit"
                            class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm custom-hover">
                            <i class="fas fa-save me-2"></i> Update Showtime
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>