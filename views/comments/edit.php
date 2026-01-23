<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4">Edit Your Review</h4>

                    <form action="<?php echo URLROOT; ?>/comments/update/<?php echo $data['comment']->id; ?>"
                        method="POST">
                        <!-- Movie Title -->
                        <div class="mb-4">
                            <label class="form-label text-muted small text-uppercase fw-bold">Movie</label>
                            <div class="fw-bold fs-5 text-primary">
                                <?php echo $data['movie']->title; ?>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label text-muted small text-uppercase fw-bold">Rating</label>
                            <select name="rating" class="form-select rounded-pill" style="padding-right: 2.5rem;">
                                <?php for ($i = 10; $i >= 1; $i--): ?>
                                    <option value="<?php echo $i; ?>" <?php echo $data['comment']->rating == $i ? 'selected' : ''; ?>>
                                        <?php echo $i; ?> -
                                        <?php echo ($i == 10 ? 'Masterpiece' : ($i >= 8 ? 'Great' : ($i >= 5 ? 'Average' : 'Bad'))); ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label class="form-label text-muted small text-uppercase fw-bold">Review</label>
                            <textarea name="content" class="form-control rounded-3" rows="5"
                                required><?php echo $data['comment']->content; ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Update
                                Review</button>
                            <a href="<?php echo URLROOT; ?>/booking/movie/<?php echo $data['comment']->movie_id; ?>"
                                class="btn btn-light rounded-pill px-4">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>