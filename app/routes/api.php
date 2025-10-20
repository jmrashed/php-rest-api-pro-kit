<?php

use App\Core\Router;
use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Controllers\DepartmentController;
use App\Controllers\AttendanceController;
use App\Controllers\EmployeeProfileController;
use App\Controllers\FileController;
use App\Controllers\HealthController;
use App\Controllers\LeaveRequestController;
use App\Controllers\LeaveTypeController;
use App\Controllers\PayrollController;
use App\Controllers\PerformanceReviewController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CorsMiddleware;

/** @var Router $router */

$router->group('/api', [CorsMiddleware::class], function (Router $router) {
    // Public routes
    $router->addRoute('GET', '/health', [HealthController::class, 'check']);
    $router->addRoute('GET', '/health/info', [HealthController::class, 'info']);
    $router->addRoute('POST', '/auth/register', [AuthController::class, 'register']);
    $router->addRoute('POST', '/auth/login', [AuthController::class, 'login']);
    $router->addRoute('POST', '/auth/logout', [AuthController::class, 'logout']);

    // Protected routes (require authentication)
    $router->group('/users', [AuthMiddleware::class], function (Router $router) {
        $router->addRoute('GET', '', [UserController::class, 'index']);
        $router->addRoute('GET', '/{id}', [UserController::class, 'show']);
        $router->addRoute('POST', '', [UserController::class, 'store']);
        $router->addRoute('PUT', '/{id}', [UserController::class, 'update']);
        $router->addRoute('DELETE', '/{id}', [UserController::class, 'destroy']);
    });

    $router->group('/files', [AuthMiddleware::class], function (Router $router) {
        $router->addRoute('POST', '/upload', [FileController::class, 'upload']);
        $router->addRoute('GET', '/{id}', [FileController::class, 'getFile']); // Assuming {id} is not used, but keeping for consistency if needed later
        $router->addRoute('DELETE', '/{id}', [FileController::class, 'delete']); // Assuming {id} is not used, but keeping for consistency if needed later
    });

    $router->group('/departments', [AuthMiddleware::class], function (Router $router) {
        $router->addRoute('GET', '', [DepartmentController::class, 'index']);
        $router->addRoute('GET', '/{id}', [DepartmentController::class, 'show']);
        $router->addRoute('POST', '', [DepartmentController::class, 'store']);
        $router->addRoute('PUT', '/{id}', [DepartmentController::class, 'update']);
        $router->addRoute('DELETE', '/{id}', [DepartmentController::class, 'destroy']);
    });

    $router->group('/employee-profiles', [AuthMiddleware::class], function (Router $router) {
        $router->addRoute('GET', '', [EmployeeProfileController::class, 'index']);
        $router->addRoute('GET', '/{id}', [EmployeeProfileController::class, 'show']);
        $router->addRoute('POST', '', [EmployeeProfileController::class, 'store']);
        $router->addRoute('PUT', '/{id}', [EmployeeProfileController::class, 'update']);
        $router->addRoute('DELETE', '/{id}', [EmployeeProfileController::class, 'destroy']);
    });

    $router->group('/attendance', [AuthMiddleware::class], function (Router $router) {
        $router->addRoute('GET', '', [AttendanceController::class, 'index']);
        $router->addRoute('GET', '/{id}', [AttendanceController::class, 'show']);
        $router->addRoute('POST', '', [AttendanceController::class, 'store']);
        $router->addRoute('PUT', '/{id}', [AttendanceController::class, 'update']);
        $router->addRoute('DELETE', '/{id}', [AttendanceController::class, 'destroy']);
    });

    $router->group('/leave-types', [AuthMiddleware::class], function (Router $router) {
        $router->addRoute('GET', '', [LeaveTypeController::class, 'index']);
        $router->addRoute('GET', '/{id}', [LeaveTypeController::class, 'show']);
        $router->addRoute('POST', '', [LeaveTypeController::class, 'store']);
        $router->addRoute('PUT', '/{id}', [LeaveTypeController::class, 'update']);
        $router->addRoute('DELETE', '/{id}', [LeaveTypeController::class, 'destroy']);
    });

    $router->group('/leave-requests', [AuthMiddleware::class], function (Router $router) {
        $router->addRoute('GET', '', [LeaveRequestController::class, 'index']);
        $router->addRoute('GET', '/{id}', [LeaveRequestController::class, 'show']);
        $router->addRoute('POST', '', [LeaveRequestController::class, 'store']);
        $router->addRoute('PUT', '/{id}', [LeaveRequestController::class, 'update']);
        $router->addRoute('DELETE', '/{id}', [LeaveRequestController::class, 'destroy']);
    });

    $router->group('/payroll', [AuthMiddleware::class], function (Router $router) {
        $router->addRoute('GET', '', [PayrollController::class, 'index']);
        $router->addRoute('GET', '/{id}', [PayrollController::class, 'show']);
        $router->addRoute('POST', '', [PayrollController::class, 'store']);
        $router->addRoute('PUT', '/{id}', [PayrollController::class, 'update']);
        $router->addRoute('DELETE', '/{id}', [PayrollController::class, 'destroy']);
    });

    $router->group('/performance-reviews', [AuthMiddleware::class], function (Router $router) {
        $router->addRoute('GET', '', [PerformanceReviewController::class, 'index']);
        $router->addRoute('GET', '/{id}', [PerformanceReviewController::class, 'show']);
        $router->addRoute('POST', '', [PerformanceReviewController::class, 'store']);
        $router->addRoute('PUT', '/{id}', [PerformanceReviewController::class, 'update']);
        $router->addRoute('DELETE', '/{id}', [PerformanceReviewController::class, 'destroy']);
    });
});