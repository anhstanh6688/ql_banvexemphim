<?php
/*
 * Base Controller
 * Loads the models and views
 */
class Controller
{
    // Load model
    public function model($model)
    {
        // Require model file
        if (file_exists('../models/' . $model . '.php')) {
            require_once '../models/' . $model . '.php';
            // Instantiate model
            return new $model();
        } else {
            die('Model does not exist: ' . $model);
        }
    }

    // Load view
    public function view($view, $data = [])
    {
        // Check for view file
        if (file_exists('../views/' . $view . '.php')) {
            require_once '../views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist: ' . $view);
        }
    }
}
