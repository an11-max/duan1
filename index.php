<?php 
// Bắt đầu session
session_start();

// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/AdminController.php';
require_once './controllers/AuthController.php';
require_once './controllers/TourGuideController.php';

// Require toàn bộ file Models
require_once './models/TourModel.php';
require_once './models/BookingModel.php';
require_once './models/BookingAssignmentModel.php';
require_once './models/CustomerModel.php';
require_once './models/DepartureModel.php';
require_once './models/TourGuideModel.php';
require_once './models/UserModel.php';
require_once './models/WorkflowModel.php';

// Route
$act = $_GET['act'] ?? '/';

// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match

$result = match ($act) {
    // Authentication Routes
    'login' => (new AuthController())->login(),
    'logout' => (new AuthController())->logout(),
    'register' => (new AuthController())->register(),
    'user-list' => (new AuthController())->userList(),
    'delete-user' => (new AuthController())->deleteUser(),
    'toggle-user-status' => (new AuthController())->toggleUserStatus(),

    // Trang chủ - chuyển thẳng vào admin dashboard
    '/' => (new AdminController())->dashboard(),

    // Admin Routes
    'admin-dashboard' => (new AdminController())->dashboard(),

    // Tours
    'admin-tours' => (new AdminController())->listTours(),
    'admin-tour-add' => (new AdminController())->addTour(),
    'admin-tour-edit' => (new AdminController())->editTour(),
    'admin-tour-delete' => (new AdminController())->deleteTour(),

    // Bookings
    'admin-bookings' => (new AdminController())->listBookings(),
    'admin-booking-add' => (new AdminController())->addBooking(),
    'admin-booking-edit' => (new AdminController())->editBooking(),
    'admin-booking-delete' => (new AdminController())->deleteBooking(),

    // Customers
    'admin-customers' => (new AdminController())->listCustomers(),
    'admin-customer-add' => (new AdminController())->addCustomer(),
    'admin-customer-edit' => (new AdminController())->editCustomer(),
    'admin-customer-delete' => (new AdminController())->deleteCustomer(),

    // Departures
    'admin-departures' => (new AdminController())->listDepartures(),

    // Tour Guides
    'admin-tour-guides' => (new AdminController())->listTourGuides(),

    // Tour Guide Routes (HDV)
    'guide-tours' => (new TourGuideController())->viewToursAndSchedule(),
    'guide-tour-detail' => (new TourGuideController())->viewTourDetail(),
    'guide-request-form' => (new TourGuideController())->showRequestForm(),
    'guide-request-tour' => (new TourGuideController())->requestTour(),
    'guide-notifications' => (new TourGuideController())->viewNotifications(),
    'guide-requests' => (new TourGuideController())->viewRequests(),
    'guide-respond-assignment' => (new TourGuideController())->respondToAssignment(),
    'guide-schedule' => (new TourGuideController())->viewSchedule(),
    'guide_schedule' => (new TourGuideController())->viewSchedule(),
    'guide_assignments' => (new TourGuideController())->viewAssignments(),
    'guide-reviews' => (new TourGuideController())->viewReviews(),
    'guide-profile' => (new TourGuideController())->viewProfile(),
    
    // Booking Assignment Routes for Tour Guides
    'guide-booking-assignments' => (new TourGuideController())->bookingAssignments(),
    'guide-booking-assignment-detail' => (new TourGuideController())->viewBookingAssignment(),
    'guide-respond-booking-assignment' => (new TourGuideController())->respondToBookingAssignment(),
    'guide-mark-booking-assignment-read' => (new TourGuideController())->markBookingAssignmentAsRead(),
    
    // Tour Guide API Routes
    'guide-notification-count' => (new TourGuideController())->getNotificationCount(),
    'guide-mark-notification-read' => (new TourGuideController())->markNotificationRead(),
    'guide-mark-notifications-read' => (new TourGuideController())->markNotificationsRead(),
    'guide-delete-notification' => (new TourGuideController())->deleteNotification(),
    'guide-delete-notifications' => (new TourGuideController())->deleteNotifications(),
    'guide-cancel-request' => (new TourGuideController())->cancelRequest(),
    'guide-get-booking-assignments' => (new TourGuideController())->getBookingAssignments(),
    'guide-get-booking-assignment-stats' => (new TourGuideController())->getBookingAssignmentStats(),

    // Admin Workflow Routes
    'admin-workflow' => (new AdminController())->workflowManagement(),
    'admin-approve-request' => (new AdminController())->approveRequest(),
    'admin-reject-request' => (new AdminController())->rejectRequest(),
    'admin-assign-tour' => (new AdminController())->assignTour(),
    
    // Booking Assignment Routes for Admin
    'admin-booking-assignments' => (new AdminController())->bookingAssignments(),
    'assign-booking-form' => (new AdminController())->assignBookingForm(),
    'process-booking-assignment' => (new AdminController())->processBookingAssignment(),
    'admin-booking-assignment-detail' => (new AdminController())->viewBookingAssignment(),
    'cancel-booking-assignment' => (new AdminController())->cancelBookingAssignment(),
    'admin-get-booking-assignment-stats' => (new AdminController())->getBookingAssignmentStats(),

    default => (new AuthController())->showLogin(),
};

// Xử lý kết quả (nếu cần)
// Do các controller methods đã handle output và redirect