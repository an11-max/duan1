<?php 
// Bắt đầu session
session_start();

// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/QuanTriController.php';
require_once './controllers/XacThucController.php';
require_once './controllers/HuongDanVienController.php';

// Require toàn bộ file Models
require_once './models/TourModel.php';
require_once './models/DatTourModel.php';
require_once './models/PhanCongDatTourModel.php';
require_once './models/KhachHangModel.php';
require_once './models/KhoiHanhModel.php';
require_once './models/HuongDanVienModel.php';
require_once './models/NguoiDungModel.php';
require_once './models/QuyTrinhModel.php';

// Route
$act = $_GET['act'] ?? '/';

// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match

$result = match ($act) {
    // Xác thực Routes
    'login' => (new XacThucController())->login(),
    'logout' => (new XacThucController())->logout(),
    'register' => (new XacThucController())->register(),
    'user-list' => (new XacThucController())->userList(),
    'delete-user' => (new XacThucController())->deleteUser(),
    'toggle-user-status' => (new XacThucController())->toggleUserStatus(),

    // Trang chủ - chuyển thẳng vào admin dashboard
    '/' => (new QuanTriController())->dashboard(),

    // Quản trị Routes
    'admin-dashboard' => (new QuanTriController())->dashboard(),

    // Tours
    'admin-tours' => (new QuanTriController())->listTours(),
    'admin-tour-add' => (new QuanTriController())->addTour(),
    'admin-tour-edit' => (new QuanTriController())->editTour(),
    'admin-tour-delete' => (new QuanTriController())->deleteTour(),

    // Đặt tour
    'admin-bookings' => (new QuanTriController())->listBookings(),
    'admin-booking-add' => (new QuanTriController())->addBooking(),
    'admin-booking-edit' => (new QuanTriController())->editBooking(),
    'admin-booking-delete' => (new QuanTriController())->deleteBooking(),

    // Khách hàng
    'admin-customers' => (new QuanTriController())->listCustomers(),
    'admin-customer-add' => (new QuanTriController())->addCustomer(),
    'admin-customer-edit' => (new QuanTriController())->editCustomer(),
    'admin-customer-delete' => (new QuanTriController())->deleteCustomer(),

    // Khởi hành
    'admin-departures' => (new QuanTriController())->listDepartures(),

    // Hướng dẫn viên
    'admin-tour-guides' => (new QuanTriController())->listTourGuides(),

    // Hướng dẫn viên Routes (HDV)
    'guide-tours' => (new HuongDanVienController())->viewToursAndSchedule(),
    'guide-tour-detail' => (new HuongDanVienController())->viewTourDetail(),
    'guide-request-form' => (new HuongDanVienController())->showRequestForm(),
    'guide-request-tour' => (new HuongDanVienController())->requestTour(),
    'guide-notifications' => (new HuongDanVienController())->viewNotifications(),
    'guide-requests' => (new HuongDanVienController())->viewRequests(),
    'guide-respond-assignment' => (new HuongDanVienController())->respondToAssignment(),
    'guide-schedule' => (new HuongDanVienController())->viewSchedule(),
    'guide_schedule' => (new HuongDanVienController())->viewSchedule(),
    'guide_assignments' => (new HuongDanVienController())->viewAssignments(),
    'guide-reviews' => (new HuongDanVienController())->viewReviews(),
    'guide-profile' => (new HuongDanVienController())->viewProfile(),
    
    // Booking Assignment Routes for Tour Guides
    'guide-booking-assignments' => (new HuongDanVienController())->bookingAssignments(),
    'guide-booking-assignment-detail' => (new HuongDanVienController())->viewBookingAssignment(),
    'guide-respond-booking-assignment' => (new HuongDanVienController())->respondToBookingAssignment(),
    'guide-mark-booking-assignment-read' => (new HuongDanVienController())->markBookingAssignmentAsRead(),
    
    // Tour Guide API Routes
    'guide-notification-count' => (new HuongDanVienController())->getNotificationCount(),
    'guide-mark-notification-read' => (new HuongDanVienController())->markNotificationRead(),
    'guide-mark-notifications-read' => (new HuongDanVienController())->markNotificationsRead(),
    'guide-delete-notification' => (new HuongDanVienController())->deleteNotification(),
    'guide-delete-notifications' => (new HuongDanVienController())->deleteNotifications(),
    'guide-cancel-request' => (new HuongDanVienController())->cancelRequest(),
    'guide-get-booking-assignments' => (new HuongDanVienController())->getBookingAssignments(),
    'guide-get-booking-assignment-stats' => (new HuongDanVienController())->getBookingAssignmentStats(),

    // Admin Workflow Routes
    'admin-workflow' => (new QuanTriController())->workflowManagement(),
    'admin-approve-request' => (new QuanTriController())->approveRequest(),
    'admin-reject-request' => (new QuanTriController())->rejectRequest(),
    'admin-assign-tour' => (new QuanTriController())->assignTour(),
    
    // Booking Assignment Routes for Admin
    'admin-booking-assignments' => (new QuanTriController())->bookingAssignments(),
    'assign-booking-form' => (new QuanTriController())->assignBookingForm(),
    'process-booking-assignment' => (new QuanTriController())->processBookingAssignment(),
    'admin-booking-assignment-detail' => (new QuanTriController())->viewBookingAssignment(),
    'cancel-booking-assignment' => (new QuanTriController())->cancelBookingAssignment(),
    'admin-get-booking-assignment-stats' => (new QuanTriController())->getBookingAssignmentStats(),

    default => (new XacThucController())->showLogin(),
};

// Xử lý kết quả (nếu cần)
// Do các controller methods đã handle output và redirect