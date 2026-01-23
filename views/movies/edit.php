<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="<?php echo URLROOT; ?>/movies" class="btn btn-light mb-3 text-muted"><i
                    class="fas fa-arrow-left"></i> Back to List</a>

            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h3 class="mb-0 fw-bold text-primary">Edit Movie</h3>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo URLROOT; ?>/movies/edit/<?php echo $data['id']; ?>" method="post">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Movie Title <span class="text-danger">*</span></label>
                                <input type="text" name="title"
                                    class="form-control form-control-lg <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>"
                                    value="<?php echo $data['title']; ?>">
                                <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Genre</label>
                                <input type="text" name="genre" class="form-control"
                                    value="<?php echo $data['genre']; ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                <input type="number" name="duration"
                                    class="form-control <?php echo (!empty($data['duration_err'])) ? 'is-invalid' : ''; ?>"
                                    value="<?php echo $data['duration']; ?>">
                                <span class="invalid-feedback"><?php echo $data['duration_err']; ?></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Release Date</label>
                                <input type="date" name="release_date" class="form-control"
                                    value="<?php echo $data['release_date']; ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Poster URL</label>
                                <input type="text" name="poster" class="form-control"
                                    value="<?php echo $data['poster']; ?>" placeholder="https://...">
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"
                                    rows="4"><?php echo $data['description']; ?></textarea>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold text-white shadow-sm">
                                <i class="fas fa-save me-2"></i> Update Movie
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>