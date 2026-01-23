<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-lg border-0 rounded-3 overflow-hidden mt-4 mb-5">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold text-success mb-1"><i class="fas fa-clock me-2"></i>Add Showtime</h2>
                            <p class="text-muted small mb-0">Schedule a movie screening</p>
                        </div>
                        <a href="<?php echo URLROOT; ?>/showtimes"
                            class="btn btn-outline-secondary rounded-pill btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body p-5">
                    <?php if (!empty($data['error'])): ?>
                        <div class="alert alert-danger rounded-pill px-4 text-center">
                            <i class="fas fa-exclamation-circle me-1"></i> <?php echo $data['error']; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo URLROOT; ?>/showtimes/add" method="post">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select name="movie_id" id="movie_id" class="form-select">
                                        <?php foreach ($data['movies'] as $movie): ?>
                                            <option value="<?php echo $movie->id; ?>" <?php echo ($data['movie_id'] == $movie->id) ? 'selected' : ''; ?>>
                                                <?php echo $movie->title . ' (' . $movie->duration . 'm)'; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="movie_id">Select Movie</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select name="room_id" id="room_id" class="form-select">
                                        <?php foreach ($data['rooms'] as $room): ?>
                                            <option value="<?php echo $room->id; ?>">
                                                <?php echo $room->name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="room_id">Select Room</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="datetime-local" name="start_time" id="start_time" class="form-control"
                                        value="<?php echo $data['start_time']; ?>" required>
                                    <label for="start_time">Start Time</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-4">
                                    <input type="number" name="price" id="price" class="form-control" value="75000"
                                        required>
                                    <label for="price">Ticket Price (VND)</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <input type="submit" class="btn btn-success btn-lg rounded-pill shadow-sm"
                                value="Schedule Showtime">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>