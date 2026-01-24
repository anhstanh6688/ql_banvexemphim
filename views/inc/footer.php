</div> <!-- /container -->

<footer class="mt-auto">
    <div class="container">
        <div class="row">
            <!-- Column 1: About + Contact -->
            <div class="col-lg-5 mb-5 mb-lg-0 pe-lg-5">
                <h5 class="mb-4 d-flex align-items-center gap-2">
                    <img src="https://img.pikbest.com/origin/10/49/11/85tpIkbEsTcjq.png!sw800" alt="Logo" width="32"
                        height="32" class="d-inline-block rounded-circle">
                    <span class="fw-bold"
                        style="font-family: 'Nunito', sans-serif; font-size: 1.25rem; color: #0f172a; letter-spacing: -0.5px;">
                        <?php echo SITENAME; ?>
                    </span>
                </h5>
                <p class="mb-4">
                    Your premium destination for the latest blockbusters and an unforgettable cinematic experience.
                </p>
                <div class="d-flex flex-column gap-2">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <span class="fw-medium">0399501846</span>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <span class="fw-medium">support@moviebooking.com</span>
                    </div>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5>Quick Links</h5>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                    <li><a href="<?php echo URLROOT; ?>">Home</a></li>
                    <li><a href="<?php echo URLROOT; ?>/showtimes">Showtimes</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo URLROOT; ?>/users/history">My Tickets</a></li>
                        <li><a href="<?php echo URLROOT; ?>/users/profile">Profile Settings</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo URLROOT; ?>/auth/login">Login / Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Column 3: Connect -->
            <div class="col-lg-4 col-md-6">
                <h5>Connect With Us</h5>
                <p class="mb-4 text-muted small">Follow us on social media for updates and exclusive offers.</p>

                <div class="footer-social mb-5">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>

                <div class="contact-item align-items-start">
                    <div class="contact-icon mt-1">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <span>
                        To dan pho Thu Doi,<br>
                        Phuong Ninh Xa, Tinh Bac Ninh
                    </span>
                </div>
            </div>
        </div>


    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>