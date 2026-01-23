<?php require APP_ROOT . '/views/inc/header.php'; ?>

<!-- Hero / Page Header -->
<div class="page-header text-center">
    <div class="container">
        <h1>Manage Showtimes</h1>
        <p class="lead mb-0 fw-light">Schedule and organize movie screenings</p>
    </div>
</div>



<div class="container">
    <div class="row mb-4">
        <div class="col-md-9">
            <form action="<?php echo URLROOT; ?>/showtimes" method="get"
                class="d-flex align-items-center flex-wrap gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search movie..."
                    value="<?php echo isset($data['filters']['search']) ? $data['filters']['search'] : ''; ?>"
                    style="max-width: 200px;">

                <select name="room_id" class="form-select" style="max-width: 150px;">
                    <option value="">All Rooms</option>
                    <?php if (isset($data['rooms'])):
                        foreach ($data['rooms'] as $r): ?>
                            <option value="<?php echo $r->id; ?>" <?php echo isset($data['filters']['room_id']) && $data['filters']['room_id'] == $r->id ? 'selected' : ''; ?>>
                                <?php echo $r->name; ?>
                            </option>
                        <?php endforeach; endif; ?>
                </select>

                <input type="date" name="date" class="form-control"
                    value="<?php echo isset($data['filters']['date']) ? $data['filters']['date'] : ''; ?>"
                    style="max-width: 150px;">

                <button type="submit" class="btn btn-outline-primary"><i class="fas fa-filter"></i></button>

                <?php if (!empty($data['filters']['search']) || !empty($data['filters']['room_id']) || !empty($data['filters']['date'])): ?>
                    <a href="<?php echo URLROOT; ?>/showtimes" class="btn btn-outline-secondary" title="Clear Filters"><i
                            class="fas fa-times"></i></a>
                <?php endif; ?>
            </form>
        </div>
        <div class="col-md-3 text-end">
            <a href="<?php echo URLROOT; ?>/showtimes/add" class="btn btn-success shadow-sm">
                <i class="fas fa-clock me-1"></i> Add New Showtime
            </a>
        </div>
    </div>

    <?php flash('showtime_message'); ?>

    <div id="showtime-results">
        <?php require APP_ROOT . '/views/showtimes/list_partial.php'; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>
                    Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-1 fs-5">Are you sure you want to delete this showtime?</p>
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
        const roomSelect = document.querySelector('select[name="room_id"]');
        const dateInput = document.querySelector('input[name="date"]');
        const resultsContainer = document.getElementById('showtime-results');
        const filterForm = document.querySelector('form[action="<?php echo URLROOT; ?>/showtimes"]');

        let debounceTimer;

        function fetchResults(url) {
            const headers = new Headers();
            headers.append('X-Requested-With', 'XMLHttpRequest');

            fetch(url, { headers: headers })
                .then(response => response.text())
                .then(html => {
                    resultsContainer.innerHTML = html;
                    window.history.pushState({ path: url }, '', url);
                    attachPaginationListeners();
                })
                .catch(error => console.error('Error fetching showtimes:', error));
        }

        function buildUrl() {
            const search = searchInput.value;
            const room_id = roomSelect.value;
            const date = dateInput.value;

            let url = '<?php echo URLROOT; ?>/showtimes?';
            const params = [];

            if (search) params.push('search=' + encodeURIComponent(search));
            if (room_id) params.push('room_id=' + encodeURIComponent(room_id));
            if (date) params.push('date=' + encodeURIComponent(date));

            return url + params.join('&');
        }

        function handleInput() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchResults(buildUrl());
            }, 300);
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

        searchInput.addEventListener('input', handleInput);
        roomSelect.addEventListener('change', handleSelect);
        dateInput.addEventListener('change', handleSelect);

        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            fetchResults(buildUrl());
        });

        attachPaginationListeners();
    });
</script>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>