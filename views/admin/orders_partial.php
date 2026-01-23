<?php flash('admin_msg'); ?>

<div class="card shadow border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Order ID</th>
                        <th>User</th>
                        <th>Movie</th>
                        <th>Showtime</th>
                        <th>Amount</th>
                        <th>Booked At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['orders'] as $order): ?>
                        <tr>
                            <td class="ps-4 fw-bold">#
                                <?php echo $order->id; ?>
                            </td>
                            <td>
                                <?php echo $order->fullname; ?>
                            </td>
                            <td class="text-truncate" style="max-width: 200px;">
                                <?php echo $order->movie_title; ?>
                            </td>
                            <td>
                                <?php echo date('H:i d/m', strtotime($order->start_time)); ?>
                            </td>
                            <td class="fw-bold">
                                <?php echo number_format($order->total_amount); ?> đ
                            </td>
                            <td>
                                <?php echo date('d M Y H:i', strtotime($order->created_at)); ?>
                            </td>
                            <td><span class="badge bg-success">Paid</span></td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/users/order_details/<?php echo $order->id; ?>"
                                    class="btn btn-sm btn-outline-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['orders'])): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light">
        <!-- Pagination -->
        <?php if (isset($data['total_pages']) && $data['total_pages'] > 1): ?>
            <nav aria-label="Order navigation">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item <?php echo $data['current_page'] <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link"
                            href="<?php echo URLROOT; ?>/admin/orders?page=<?php echo $data['current_page'] - 1; ?>&search=<?php echo isset($data['filters']['search']) ? $data['filters']['search'] : ''; ?>&date=<?php echo isset($data['filters']['date']) ? $data['filters']['date'] : ''; ?>">Previous</a>
                    </li>

                    <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
                        <li class="page-item <?php echo $data['current_page'] == $i ? 'active' : ''; ?>">
                            <a class="page-link"
                                href="<?php echo URLROOT; ?>/admin/orders?page=<?php echo $i; ?>&search=<?php echo isset($data['filters']['search']) ? $data['filters']['search'] : ''; ?>&date=<?php echo isset($data['filters']['date']) ? $data['filters']['date'] : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php echo $data['current_page'] >= $data['total_pages'] ? 'disabled' : ''; ?>">
                        <a class="page-link"
                            href="<?php echo URLROOT; ?>/admin/orders?page=<?php echo $data['current_page'] + 1; ?>&search=<?php echo isset($data['filters']['search']) ? $data['filters']['search'] : ''; ?>&date=<?php echo isset($data['filters']['date']) ? $data['filters']['date'] : ''; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>