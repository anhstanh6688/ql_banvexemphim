<?php require APP_ROOT . '/views/inc/header.php'; ?>

<!-- Hero / Page Header -->
<div class="page-header text-center">
    <div class="container">
        <h1>Manage Movies</h1>
        <p class="lead mb-0">List of all movies in the system</p>
    </div>
</div>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <form action="<?php echo URLROOT; ?>/movies" method="get" class="d-flex align-items-center flex-wrap gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search title..."
                    value="<?php echo isset($data['filters']['search']) ? $data['filters']['search'] : ''; ?>"
                    style="max-width: 200px;">

                <select name="genre" class="form-select" style="max-width: 150px;">
                    <option value="">All Genres</option>
                    <?php if (isset($data['genres'])):
                        foreach ($data['genres'] as $g): ?>
                            <option value="<?php echo $g->genre; ?>" <?php echo isset($data['filters']['genre']) && $data['filters']['genre'] == $g->genre ? 'selected' : ''; ?>>
                                <?php echo $g->genre; ?>
                            </option>
                        <?php endforeach; endif; ?>
                </select>

                <select name="status" class="form-select" style="max-width: 150px;">
                    <option value="">All Status</option>
                    <option value="showing" <?php echo isset($data['filters']['status']) && $data['filters']['status'] == 'showing' ? 'selected' : ''; ?>>Now Showing</option>
                    <option value="coming_soon" <?php echo isset($data['filters']['status']) && $data['filters']['status'] == 'coming_soon' ? 'selected' : ''; ?>>Coming Soon</option>
                    <option value="stopped" <?php echo isset($data['filters']['status']) && $data['filters']['status'] == 'stopped' ? 'selected' : ''; ?>>Stopped</option>
                </select>

                <button type="submit" class="btn btn-outline-primary"><i class="fas fa-filter"></i></button>

                <?php if (!empty($data['filters']['search']) || !empty($data['filters']['genre']) || !empty($data['filters']['status'])): ?>
                    <a href="<?php echo URLROOT; ?>/movies" class="btn btn-outline-secondary" title="Clear Filters"><i
                            class="fas fa-times"></i></a>
                <?php endif; ?>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?php echo URLROOT; ?>/movies/add" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Add New Movie
            </a>
        </div>
    </div>

    <div id="movie-results">
        <?php require APP_ROOT . '/views/movies/list_partial.php'; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i> Confirm
                    Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-1 fs-5">Are you sure you want to delete this movie?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm" id="confirmDeleteBtn">Yes,
                    Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    let targetFormId = null;

    function setDeleteForm(formId) {
        targetFormId = formId;
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (targetFormId) {
            document.getElementById(targetFormId).submit();
        }
    });

    // Real-time Search & Filtering
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.querySelector('input[name="search"]');
        const genreSelect = document.querySelector('select[name="genre"]');
        const statusSelect = document.querySelector('select[name="status"]');
        const resultsContainer = document.getElementById('movie-results');
        const filterForm = document.querySelector('form[action="<?php echo URLROOT; ?>/movies"]');
        const clearBtn = document.querySelector('a[title="Clear Filters"]');

        let debounceTimer;

        function fetchResults(url) {
            // Add AJAX header
            const headers = new Headers();
            headers.append('X-Requested-With', 'XMLHttpRequest');

            fetch(url, { headers: headers })
                .then(response => response.text())
                .then(html => {
                    resultsContainer.innerHTML = html;
                    // Update URL in browser without reload
                    window.history.pushState({ path: url }, '', url);

                    // Re-attach pagination listeners since DOM updated
                    attachPaginationListeners();
                })
                .catch(error => console.error('Error fetching movies:', error));
        }

        function buildUrl() {
            const search = searchInput.value;
            const genre = genreSelect.value;
            const status = statusSelect.value;
            let url = '<?php echo URLROOT; ?>/movies?';
            const params = [];

            if (search) params.push('search=' + encodeURIComponent(search));
            if (genre) params.push('genre=' + encodeURIComponent(genre));
            if (status) params.push('status=' + encodeURIComponent(status));

            return url + params.join('&');
        }

        function handleInput() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchResults(buildUrl());
            }, 300); // 300ms debounce
        }

        function handleSelect() {
            fetchResults(buildUrl());
        }

        function attachPaginationListeners() {
            const paginationLinks = resultsContainer.querySelectorAll('.pagination a.page-link');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    fetchResults(this.href);
                });
            });
        }

        // Attach Event Listeners
        searchInput.addEventListener('input', handleInput);
        genreSelect.addEventListener('change', handleSelect);
        statusSelect.addEventListener('change', handleSelect);

        // Prevent default form submission and use AJAX
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            fetchResults(buildUrl());
        });

        // Initial listeners
        attachPaginationListeners();
    });
</script>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>