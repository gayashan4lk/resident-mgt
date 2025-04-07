<?php
// Front Controller
require_once 'config/config.php';
require_once 'controllers/ResidentController.php';

// Create controller instance
$controller = new ResidentController();

// Route requests to appropriate controller method
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'save':
        $controller->save();
        break;
    case 'delete':
        $controller->delete();
        break;
    case 'search':
        $controller->search();
        break;
    case 'index':
    default:
        $controller->index();
        break;
}
