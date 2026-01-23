<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-lg border-0 rounded-3 overflow-hidden mt-4 mb-5">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold text-primary mb-1"><i class="fas fa-film me-2"></i>Add New Movie</h2>
                            <p class="text-muted small mb-0">Enter movie details to create a new entry</p>
                        </div>
                        <a href="<?php echo URLROOT; ?>/movies" class="btn btn-outline-secondary rounded-pill btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body p-5">
                    <form action="<?php echo URLROOT; ?>/movies/add" method="post">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-floating mb-3">
                                    <input type="text" name="title" id="title"
                                        class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo $data['title']; ?>" placeholder="Movie Title">
                                    <label for="title">Movie Title *</label>
                                    <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="text" name="genre" id="genre" class="form-control"
                                        value="<?php echo $data['genre']; ?>" placeholder="Genre">
                                    <label for="genre">Genre</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" name="duration" id="duration"
                                        class="form-control <?php echo (!empty($data['duration_err'])) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo $data['duration']; ?>" placeholder="Duration">
                                    <label for="duration">Duration (mins) *</label>
                                    <span class="invalid-feedback"><?php echo $data['duration_err']; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="date" name="release_date" id="release_date" class="form-control"
                                        value="<?php echo $data['release_date']; ?>" placeholder="Release Date">
                                    <label for="release_date">Release Date</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea name="description" id="description" class="form-control" style="height: 120px"
                                placeholder="Description"><?php echo $data['description']; ?></textarea>
                            <label for="description">Description</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="text" name="poster" id="poster" class="form-control"
                                value="<?php echo $data['poster']; ?>" placeholder="Poster URL">
                            <label for="poster">Poster Image URL (Optional)</label>
                            <div class="form-text text-muted ms-1">Link to an external image resource</div>
                        </div>

                        <div class="d-grid gap-2">
                            <input type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm"
                                value="Create Movie">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>