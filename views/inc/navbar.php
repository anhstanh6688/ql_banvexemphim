<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo URLROOT; ?>">
            <img src="https://img.pikbest.com/origin/10/49/11/85tpIkbEsTcjq.png!sw800" alt="Logo" width="40" height="40"
                class="d-inline-block align-text-top rounded-circle">
            <span class="fw-bold"
                style="font-family: 'Nunito', sans-serif; font-size: 1.5rem; color: #0f172a; letter-spacing: -0.5px;">
                <?php echo SITENAME; ?>
            </span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <!-- Left Side -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link fs-6 fw-medium px-3" href="<?php echo URLROOT; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-6 fw-medium px-3" href="<?php echo URLROOT; ?>/users/search">Find Ticket</a>
                </li>
            </ul>

            <!-- Right Side -->
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="me-2 text-end d-none d-lg-block">
                                <span class="d-block fw-bold text-dark"
                                    style="font-size: 0.9rem;"><?php echo $_SESSION['user_name']; ?></span>
                                <span class="d-block text-muted"
                                    style="font-size: 0.75rem;"><?php echo isset($_SESSION['user_role']) ? ucfirst($_SESSION['user_role']) : 'Member'; ?></span>
                            </div>
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name']); ?>&background=random&color=fff&size=128"
                                alt="Avatar" class="rounded-circle shadow-sm" width="45" height="45">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2 animate__animated animate__fadeIn"
                            aria-labelledby="userDropdown">
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                                <li>
                                    <h6 class="dropdown-header text-uppercase small fw-bold text-danger">Administration</h6>
                                </li>
                                <li><a class="dropdown-item py-2" href="<?php echo URLROOT; ?>/admin"><i
                                            class="fas fa-tachometer-alt me-2 text-primary"></i> Dashboard</a></li>
                                <li><a class="dropdown-item py-2" href="<?php echo URLROOT; ?>/movies"><i
                                            class="fas fa-video me-2 text-primary"></i> Manage Movies</a></li>
                                <li><a class="dropdown-item py-2" href="<?php echo URLROOT; ?>/showtimes"><i
                                            class="far fa-clock me-2 text-primary"></i> Manage Showtimes</a></li>
                                <li><a class="dropdown-item py-2" href="<?php echo URLROOT; ?>/admin/orders"><i
                                            class="fas fa-shopping-cart me-2 text-primary"></i> Manage Orders</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            <?php endif; ?>

                            <li>
                                <h6 class="dropdown-header text-uppercase small fw-bold text-muted">Personal</h6>
                            </li>
                            <li><a class="dropdown-item py-2" href="<?php echo URLROOT; ?>/users/profile"><i
                                        class="far fa-id-card me-2"></i> My Profile</a></li>
                            <li><a class="dropdown-item py-2" href="<?php echo URLROOT; ?>/users/history"><i
                                        class="fas fa-history me-2"></i> Booking History</a></li>
                            <li><a class="dropdown-item py-2" href="<?php echo URLROOT; ?>/users/comments"><i
                                        class="fas fa-comment-dots me-2"></i> My Reviews</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item py-2 text-danger fw-bold"
                                    href="<?php echo URLROOT; ?>/auth/logout"><i class="fas fa-sign-out-alt me-2"></i>
                                    Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link fw-medium px-3" href="<?php echo URLROOT; ?>/auth/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm ms-2"
                            href="<?php echo URLROOT; ?>/auth/register">Sign Up</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* CSS for Hover Dropdown (Desktop only) */
    @media (min-width: 992px) {
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
        }

        /* Bridge gap using pseudo-element on the MENU, not the toggle */
        .nav-item.dropdown .dropdown-menu::before {
            content: '';
            position: absolute;
            top: -10px;
            /* Reach up to the navbar */
            left: 0;
            width: 100%;
            height: 20px;
        }
    }

    .dropdown-item:active {
        background-color: var(--primary-color);
    }

    .dropdown-menu {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>