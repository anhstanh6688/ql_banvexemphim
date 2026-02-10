<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow border-0 rounded-3">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">Welcome Back</h2>
                    <p class="text-muted">Please login to your account</p>
                </div>

                <form action="<?php echo URLROOT; ?>/auth/login" method="post">
                    <?php echo csrf_field(); ?>

                    <div class="form-floating mb-3">
                        <input type="email" name="email"
                            class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>"
                            id="emailInput" placeholder="name@example.com" value="<?php echo $data['email']; ?>">
                        <label for="emailInput">Email address</label>
                        <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" name="password"
                            class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>"
                            id="passwordInput" placeholder="Password" value="<?php echo $data['password']; ?>">
                        <label for="passwordInput">Password</label>
                        <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                    </div>

                    <div class="row gx-2 mb-3">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm w-100"
                                style="height: 44px; font-size: 16px;">Login</button>
                        </div>
                        <div class="col-6">
                            <div id="g_id_onload"
                                data-client_id="495875283176-9i9qvvns1lhkpjqqjjt9u9n5bj90iq7t.apps.googleusercontent.com"
                                data-callback="handleCredentialResponse" data-auto_prompt="false">
                            </div>
                            <div class="g_id_signin" data-type="standard" data-size="large" data-theme="outline"
                                data-text="signin" data-shape="pill" data-logo_alignment="left" style="width: 100%;">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <span class="text-muted">Don't have an account?</span>
                    <a href="<?php echo URLROOT; ?>/auth/register" class="fw-bold text-decoration-none">Register</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
    function handleCredentialResponse(response) {
        // Send the ID token to your backend via AJAX
        fetch('<?php echo URLROOT; ?>/auth/google_login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ credential: response.credential })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert('Google Login Failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during Google Login.');
            });
    }
</script>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>