<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public Web Routes
$routes->get('/', 'Front\HomeController::index');
$routes->get('search', 'Front\BookingController::search');
$routes->get('booking/seat-map/(:num)', 'Front\BookingController::seatMap/$1');
$routes->get('api/routes/destinations/(:num)', 'Front\BookingController::getDestinationsByOrigin/$1');
$routes->post('booking/lock-seat', 'Front\BookingController::lockSeat');
$routes->post('booking/store', 'Front\BookingController::store');
$routes->get('booking/manage', 'Front\BookingController::manageView');
$routes->post('booking/manage/search', 'Front\BookingController::manageSearch');
$routes->post('booking/assign-seats', 'Front\BookingController::assignSeats');

// Payment Routes
$routes->get('payment/checkout/(:segment)', 'Front\PaymentController::checkout/$1');
$routes->get('checkout/mock-pay/(:segment)', 'Front\PaymentController::mockPay/$1');
$routes->post('api/midtrans/notification', 'Front\PaymentController::notificationCallback');
$routes->get('booking/success/(:segment)', 'Front\PaymentController::success/$1');

// Ticket & Customer Services
$routes->get('ticket/pdf/(:segment)', 'Front\TicketController::downloadPdf/$1');
$routes->post('ticket/refund/submit', 'Front\TicketController::submitRefund');
$routes->post('ticket/reschedule/submit', 'Front\TicketController::submitReschedule');

// Auth Routes
$routes->get('login', 'Front\AuthController::loginView');
$routes->post('login', 'Front\AuthController::loginProcess');
$routes->get('register', 'Front\AuthController::registerView');
$routes->post('register', 'Front\AuthController::registerProcess');
$routes->get('logout', 'Front\AuthController::logout');

// REST API v1 Routes (JSON Endpoints)
$routes->group('api/v1', function ($routes) {
    $routes->post('auth/login', 'Api\ApiController::login');
    $routes->post('auth/register', 'Api\ApiController::register');
    $routes->get('schedules', 'Api\ApiController::schedules');
    $routes->get('seats/(:num)', 'Api\ApiController::seats/$1');
    $routes->post('booking/create', 'Api\ApiController::createBooking');
    $routes->get('tickets/(:segment)', 'Api\ApiController::getTicket/$1');
    $routes->post('checkin/scan', 'Api\ApiController::scanCheckin');
    $routes->get('manifest/(:num)', 'Api\ApiController::getManifest/$1');
});

// Admin Routes
$routes->group('admin', function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
    
    // Master Data - Locations CRUD
    $routes->get('master/locations', 'Admin\MasterController::locations');
    $routes->post('master/locations/store', 'Admin\MasterController::storeLocation');
    $routes->post('master/locations/update/(:num)', 'Admin\MasterController::updateLocation/$1');
    $routes->get('master/locations/delete/(:num)', 'Admin\MasterController::deleteLocation/$1');

    // Master Data - Boats CRUD
    $routes->get('master/boats', 'Admin\MasterController::boats');
    $routes->post('master/boats/store', 'Admin\MasterController::storeBoat');
    $routes->post('master/boats/update/(:num)', 'Admin\MasterController::updateBoat/$1');

    // Master Data - Routes CRUD
    $routes->get('master/routes', 'Admin\MasterController::routes');
    $routes->post('master/routes/store', 'Admin\MasterController::storeRoute');
    $routes->post('master/routes/update/(:num)', 'Admin\MasterController::updateRoute/$1');
    $routes->get('master/routes/delete/(:num)', 'Admin\MasterController::deleteRoute/$1');

    // Master Data - Schedules
    $routes->get('master/schedules', 'Admin\MasterController::schedules');
    $routes->post('master/schedules/store', 'Admin\MasterController::storeSchedule');
    $routes->post('master/schedules/update/(:num)', 'Admin\MasterController::updateSchedule/$1');
    $routes->get('master/schedules/delete/(:num)', 'Admin\MasterController::deleteSchedule/$1');

    // Check-In Scanner & Boarding Manifest
    $routes->get('checkin/scanner', 'Admin\CheckInController::scanner');
    $routes->post('checkin/scan', 'Admin\CheckInController::scanTicket');
    $routes->get('checkin/manifest/(:num)', 'Admin\CheckInController::manifest/$1');

    // Refunds
    $routes->get('refunds', 'Admin\RefundAdminController::index');
    $routes->post('refunds/update-status/(:num)', 'Admin\RefundAdminController::updateStatus/$1');

    // Reports
    $routes->get('reports/sales', 'Admin\ReportAdminController::sales');
    $routes->get('reports/sales/excel', 'Admin\ReportAdminController::exportExcel');
    $routes->get('reports/sales/pdf', 'Admin\ReportAdminController::exportPdf');
});
