<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold text-primary"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
        <p class="text-muted">Overview of cinema performance.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3 shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <h2 class="card-text fw-bold"><?php echo number_format($data['total_revenue']); ?> VND</h2>
                <small><i class="fas fa-arrow-up"></i> Lifetime Earnings</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3 shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title">Tickets Sold</h5>
                <h2 class="card-text fw-bold"><?php echo number_format($data['total_tickets']); ?></h2>
                <small><i class="fas fa-ticket-alt"></i> Total Valid Tickets</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3 shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title">This Month</h5>
                <h2 class="card-text fw-bold"><?php echo number_format($data['monthly_revenue']); ?> VND</h2>
                <small><i class="far fa-calendar-alt"></i> Current Month Revenue</small>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Charts Section -->
<div class="row mb-4">
    <!-- Revenue Trend Chart -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i> Revenue Trend (Last 7 Days)</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Ticket Status Chart -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2 text-success"></i> Ticket Status</h5>
            </div>
            <div class="card-body">
                <canvas id="ticketChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Orders</h5>
                    <a href="<?php echo URLROOT; ?>/admin/orders" class="btn btn-sm btn-light">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Movie</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['recent_orders'] as $order): ?>
                            <tr>
                                <td>#<?php echo $order->id; ?></td>
                                <td><?php echo $order->fullname; ?></td>
                                <td class="text-truncate" style="max-width: 150px;"><?php echo $order->title; ?></td>
                                <td><?php echo number_format($order->total_amount); ?></td>
                                <td><?php echo date('d/m H:i', strtotime($order->created_at)); ?></td>
                                <td><span class="badge bg-success">Paid</span></td>
                            </tr>
                        <?php endforeach; ?>
                         <?php if(empty($data['recent_orders'])): ?>
                            <tr><td colspan="6" class="text-center p-3">No orders yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-crown me-2 text-warning"></i> Top VIP Customers</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Customer</th>
                            <th>Email</th>
                            <th>Orders</th>
                            <th class="text-end pe-4">Total Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['top_customers'] as $customer): ?>
                            <tr>
                                <td class="ps-4 fw-bold">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-secondary"></i>
                                        </div>
                                        <?php echo $customer->fullname; ?>
                                    </div>
                                </td>
                                <td class="text-muted small"><?php echo $customer->email; ?></td>
                                <td><span class="badge bg-primary rounded-pill"><?php echo $customer->total_orders; ?></span></td>
                                <td class="text-end pe-4 fw-bold text-success"><?php echo number_format($customer->total_spent); ?>đ</td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(empty($data['top_customers'])): ?>
                            <tr><td colspan="4" class="text-center p-3">No customers data.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-md-4">
        <!-- Top Movies -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">Top Movies</h5>
            </div>
            <div class="card-body">
                <?php foreach($data['top_movies'] as $movie): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0 fw-bold"><?php echo $movie->title; ?></h6>
                            <small class="text-muted"><?php echo $movie->tickets_sold; ?> tickets sold</small>
                        </div>
                        <span class="text-success fw-bold"><?php echo number_format($movie->revenue); ?>đ</span>
                    </div>
                    <hr>
                <?php endforeach; ?>
                 <?php if(empty($data['top_movies'])): ?>
                    <p class="text-center text-muted">No data available.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Actions -->
         <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">Quick Actions</h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="<?php echo URLROOT; ?>/movies/add" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Add New Movie</a>
                <a href="<?php echo URLROOT; ?>/showtimes/add" class="btn btn-outline-success"><i class="far fa-clock"></i> Add Showtime</a>
                <a href="<?php echo URLROOT; ?>/movies" class="btn btn-outline-secondary">Manage All Movies</a>
                <a href="<?php echo URLROOT; ?>/showtimes" class="btn btn-outline-secondary">Manage All Showtimes</a>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data from PHP
    const revenueData = <?php echo json_encode($data['revenue_trend']); ?>;
    const ticketData = <?php echo json_encode($data['ticket_stats']); ?>;

    // Revenue Chart
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: revenueData.map(d => d.date),
            datasets: [{
                label: 'Revenue (VND)',
                data: revenueData.map(d => d.revenue),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                pointRadius: 4,
                pointBackgroundColor: '#4e73df',
                pointBorderColor: '#fff',
                pointHoverRadius: 6,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [2] }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Ticket Status Chart
    const ctxTicket = document.getElementById('ticketChart').getContext('2d');
    const statusLabels = ticketData.map(d => d.status.charAt(0).toUpperCase() + d.status.slice(1));
    const statusCounts = ticketData.map(d => d.count);
    
    new Chart(ctxTicket, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                backgroundColor: ['#1cc88a', '#e74a3b', '#f6c23e', '#858796'], // Green, Red, Yellow, Gray
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            cutout: '70%'
        }
    });
</script>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>
