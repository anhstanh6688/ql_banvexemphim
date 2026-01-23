<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2>Find Ticket</h2>
            <form action="<?php echo URLROOT; ?>/users/search" method="post">
                <div class="input-group mb-3">
                    <input type="text" name="ticket_code" class="form-control"
                        placeholder="Enter Ticket Code (e.g. TICKET-67890...)" value="<?php echo $data['code']; ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>

            <?php if (!empty($data['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $data['error']; ?>
                </div>
            <?php endif; ?>

            <?php if ($data['ticket']): ?>
                <div class="alert alert-success">
                    <h4>Ticket Found!</h4>
                    <p><strong>Code:</strong>
                        <?php echo $data['ticket']->ticket_code; ?>
                    </p>
                    <p><strong>Movie:</strong>
                        <?php echo $data['ticket']->movie_title; ?>
                    </p>
                    <p><strong>Seat:</strong>
                        <?php echo $data['ticket']->seat_code; ?>
                    </p>
                    <p><strong>Time:</strong>
                        <?php echo $data['ticket']->start_time; ?>
                    </p>
                    <p><strong>Status:</strong>
                        <?php echo strtoupper($data['ticket']->status); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>