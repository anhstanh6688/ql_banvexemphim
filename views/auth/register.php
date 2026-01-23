<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow border-0 rounded-3">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">Create Account</h2>
                    <p class="text-muted">Join us to book your tickets</p>
                </div>
                
                <form action="<?php echo URLROOT; ?>/auth/register" method="post">
                    <?php echo csrf_field(); ?>

                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" id="nameInput" placeholder="Full Name" value="<?php echo $data['name']; ?>">
                        <label for="nameInput">Full Name</label>
                        <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" id="emailInput" placeholder="name@example.com" value="<?php echo $data['email']; ?>">
                        <label for="emailInput">Email address</label>
                        <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                    </div>

                    <div class="form-floating mb-3">
                         <input type="text" name="phone" class="form-control <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" id="phoneInput" placeholder="Phone Number" value="<?php echo $data['phone']; ?>">
                         <label for="phoneInput">Phone Number</label>
                         <span class="invalid-feedback"><?php echo $data['phone_err']; ?></span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" id="passwordInput" placeholder="Password" value="<?php echo $data['password']; ?>">
                                <label for="passwordInput">Password</label>
                                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" id="confirmPasswordInput" placeholder="Confirm Password" value="<?php echo $data['confirm_password']; ?>">
                                <label for="confirmPasswordInput">Confirm Password</label>
                                <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">Register</button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <span class="text-muted">Already have an account?</span> 
                    <a href="<?php echo URLROOT; ?>/auth/login" class="fw-bold text-decoration-none">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>