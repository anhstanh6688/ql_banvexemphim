<?php
// Mock session if needed
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/config.php';
// require_once 'core/session_helper.php'; // might cause issues if not needed

// Autoload Core Libraries
spl_autoload_register(function ($className) {
    // Try to load from core folder first
    if (file_exists('core/' . $className . '.php')) {
        require_once 'core/' . $className . '.php';
    }
    // Or from models
    elseif (file_exists('models/' . $className . '.php')) {
        require_once 'models/' . $className . '.php';
    }
});

class TestController extends Controller
{
    // Override model loading to work from root
    public function model($model)
    {
        // Require model file
        if (file_exists('models/' . $model . '.php')) {
            require_once 'models/' . $model . '.php';
            // Instantiate model
            return new $model();
        } else {
            die('Model does not exist: ' . $model . ' (checked models/' . $model . '.php)');
        }
    }

    public function test()
    {
        echo "Loading Coupon model...\n";
        try {
            $couponModel = $this->model('Coupon');

            if ($couponModel) {
                echo "Model loaded. Fetching coupons...\n";
                $coupons = $couponModel->getAvailableCoupons();
                echo "Found " . count($coupons) . " coupons.\n";
                print_r($coupons);
            } else {
                echo "Failed to load model.\n";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}

$test = new TestController();
$test->test();
