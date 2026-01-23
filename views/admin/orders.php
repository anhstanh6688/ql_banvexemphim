<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="page-header text-center">
    <div class="container">
        <h1>Manage Orders</h1>
        <p class="lead mb-0">List of all booking transactions</p>
    </div>
</div>

<div class="container mt-4">

    <!-- Filter Form & Actions -->
    <div class="row mb-4">
        <div class="col-md-9">
            <form action="<?php echo URLROOT; ?>/admin/orders" method="get"
                class="d-flex align-items-center flex-wrap gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search Order ID, Name, Movie..."
                    value="<?php echo isset($data['filters']['search']) ? $data['filters']['search'] : ''; ?>"
                    style="max-width: 300px;">

                <input type="date" name="date" class="form-control"
                    value="<?php echo isset($data['filters']['date']) ? $data['filters']['date'] : ''; ?>"
                    style="max-width: 150px;" title="Filter by Booking Date">

                <button type="submit" class="btn btn-outline-primary"><i class="fas fa-filter"></i> Filter</button>

                <?php if (!empty($data['filters']['search']) || !empty($data['filters']['date'])): ?>
                    <a href="<?php echo URLROOT; ?>/admin/orders" class="btn btn-outline-secondary" title="Clear Filters"><i
                            class="fas fa-times"></i></a>
                <?php endif; ?>
            </form>
        </div>
        <div class="col-md-3 text-end">
            <a href="<?php echo URLROOT; ?>/admin" class="btn btn-outline-dark shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Results Container -->
    <div id="order-results">
        <?php require APP_ROOT . '/views/admin/orders_partial.php'; ?>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.querySelector('input[name="search"]');
        const dateInput = document.querySelector('input[name="date"]');
        const resultsContainer = document.getElementById('order-results');
        const filterForm = document.querySelector('form[action="<?php echo URLROOT; ?>/admin/orders"]');

        let debounceTimer;

        function fetchResults(url) {
            const headers = new Headers();
            headers.append('X-Requested-With', 'XMLHttpRequest');

            resultsContainer.style.opacity = '0.5';

            fetch(url, { headers: headers })
                .then(response => response.text())
                .then(html => {
                    resultsContainer.innerHTML = html;
                    resultsContainer.style.opacity = '1';
                    window.history.pushState({ path: url }, '', url);
                    attachPaginationListeners();
                })
                .catch(error => {
                    console.error('Error fetching orders:', error);
                    resultsContainer.style.opacity = '1';
                });
        }

        function buildUrl() {
            const search = searchInput.value;
            const date = dateInput.value;

            let url = '<?php echo URLROOT; ?>/admin/orders?';
            const params = [];

            if (search) params.push('search=' + encodeURIComponent(search));
            if (date) params.push('date=' + encodeURIComponent(date));

            return url + params.join('&');
        }

        function handleInput() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchResults(buildUrl());
            }, 300);
        }

        function handleDate() {
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

        if (searchInput) searchInput.addEventListener('input', handleInput);
        if (dateInput) dateInput.addEventListener('change', handleDate);

        if (filterForm) {
            filterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                fetchResults(buildUrl());
            });
        }

        attachPaginationListeners();
    });
</script>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>