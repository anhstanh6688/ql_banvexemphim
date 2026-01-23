<?php
// Function to redirect to login if not logged in
function requireLogin()
{
    if (!isLoggedIn()) {
        redirect('auth/login');
    }
}

// Function to redirect non-admins
function requireAdmin()
{
    requireLogin(); // Must be logged in first
    if (!isAdmin()) {
        redirect('pages/index'); // Or show 403
    }
}
