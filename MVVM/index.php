<?php
// index.php

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Include database connection
require_once 'config/database.php';

// Include helpers
require_once 'includes/helpers.php';

// Include models
require_once 'models/MenuModel.php';
require_once 'models/OrderModel.php';
require_once 'models/OrderItemModel.php';

// Include ViewModels
require_once 'viewmodels/MenuViewModel.php';
require_once 'viewmodels/OrderViewModel.php';
require_once 'viewmodels/OrderItemViewModel.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Get page and action parameters
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Include header/layout
include 'views/layouts/header.php';

// Route to appropriate page
switch($page) {
    case 'dashboard':
        include 'views/dashboard.php';
        break;
    
    case 'menu':
        switch($action) {
            case 'create':
                include 'views/menu/create.php';
                break;
            case 'edit':
                include 'views/menu/edit.php';
                break;
            case 'index':
            default:
                include 'views/menu/index.php';
                break;
        }
        break;
    
    case 'order':
        switch($action) {
            case 'create':
                include 'views/order/create.php';
                break;
            case 'edit':
                include 'views/order/edit.php';
                break;
            case 'index':
            default:
                include 'views/order/index.php';
                break;
        }
        break;
    
    default:
        include 'views/dashboard.php';
        break;
}

// Include footer/layout
include 'views/layouts/footer.php';
?>