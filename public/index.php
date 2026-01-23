<?php
require_once '../config/config.php';
require_once '../core/session_helper.php';
require_once '../core/middleware.php';

// Load Helpers (optional for Step 2 but good practice)
// require_once '../core/helpers.php'; 

// Autoload Core Libraries
spl_autoload_register(function ($className) {
    // Try to load from core folder first
    if (file_exists('../core/' . $className . '.php')) {
        require_once '../core/' . $className . '.php';
    }
    // Or from models
    elseif (file_exists('../models/' . $className . '.php')) {
        require_once '../models/' . $className . '.php';
    }
});

// Init Core App
$init = new App();
