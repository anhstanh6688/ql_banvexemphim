<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <h1>Booking History</h1>
        <?php if (empty($data['orders'])): ?>
            <p>You have no bookings yet.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Movie</th>
                        <th>Showtime</th>
                        <th>Room</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['orders'] as $order): ?>
                        <tr>
                            <td>#
                                <?php echo $order->id; ?>
                            </td>
                            <td>
                                <?php echo $order->movie_title; ?>
                            </td>
                            <td>
                                <?php echo $order->start_time; ?>
                            </td>
                            <td>
                                <?php echo $order->room_name; ?>
                            </td>
                            <td>
                                <?php echo number_format($order->total_amount); ?> VND
                            </td>
                            <td>
                                <?php echo $order->created_at; ?>
                            </td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/users/order_details/<?php echo $order->id; ?>"
                                    class="btn btn-sm btn-info">View Tickets</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>