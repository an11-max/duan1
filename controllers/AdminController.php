<?php

class AdminController
{
    public $tourModel;
    public $bookingModel;
    public $customerModel;
    public $departureModel;
    public $tourGuideModel;
    public $bookingAssignmentModel;

    public function __construct()
    {
        // Kiểm tra đăng nhập cho tất cả các action
        AuthController::checkLogin();
        
        $this->tourModel = new TourModel();
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->departureModel = new DepartureModel();
        $this->tourGuideModel = new TourGuideModel();
        $this->bookingAssignmentModel = new BookingAssignmentModel();
    }

    // Dashboard - tất cả quyền đều truy cập được
    public function dashboard()
    {
        // Kiểm tra quyền xem dashboard dựa trên role
        $userRole = $_SESSION['user']['role'];
        
        if ($userRole === 'tour_guide') {
            // HDV chỉ xem được thông tin tour
            $this->tourGuideView();
            return;
        }
        
        // Admin và Super Admin xem dashboard đầy đủ
        $tourStats = $this->tourModel->getTourStats();
        $bookingStats = $this->bookingModel->getBookingStats();
        $departureStats = $this->departureModel->getDepartureStats();
        
        $recentBookings = $this->bookingModel->getAllBookings();
        $upcomingDepartures = $this->departureModel->getDeparturesByStatus('Scheduled');

        require_once './views/admin/dashboard.php';
    }

    // View riêng cho HDV - hiển thị dashboard HDV
    private function tourGuideView()
    {
        // Lấy thông tin HDV từ session
        $guide_id = $_SESSION['user']['id'];
        
        // Lấy các tour được phân công cho HDV này
        $assigned_tours = $this->tourModel->getToursByGuideId($guide_id);
        
        // Hiển thị dashboard HDV
        require_once './views/admin/guide_dashboard.php';
    }

    // Quản lý Tours - chỉ admin và super admin
    public function listTours()
    {
        // HDV chỉ được xem tours (read-only)
        $tours = $this->tourModel->getAllTours();
        require_once './views/admin/tours/list.php';
    }

    public function addTour()
    {
        // Chỉ admin và super admin được thêm tour
        AuthController::checkAdminPermission();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour_code = $_POST['tour_code'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $duration = $_POST['duration'];
            $is_international = isset($_POST['is_international']) ? 1 : 0;
            
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $image = uploadFile($_FILES['image'], 'uploads/tours/');
            }

            $result = $this->tourModel->insertTour($tour_code, $name, $image, $description, $duration, $is_international);
            
            if ($result) {
                header('Location: ' . BASE_URL . '?act=admin-tours');
                exit();
            }
        }
        require_once './views/admin/tours/add.php';
    }

    public function editTour()
    {
        // Chỉ admin và super admin được sửa tour
        AuthController::checkAdminPermission();
        
        $id = $_GET['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour_code = $_POST['tour_code'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $duration = $_POST['duration'];
            $is_international = isset($_POST['is_international']) ? 1 : 0;
            
            $tour = $this->tourModel->getTourById($id);
            $image = $tour['image'];
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                if ($image) {
                    deleteFile($image);
                }
                $image = uploadFile($_FILES['image'], 'uploads/tours/');
            }

            $result = $this->tourModel->updateTour($id, $tour_code, $name, $image, $description, $duration, $is_international);
            
            if ($result) {
                header('Location: ' . BASE_URL . '?act=admin-tours');
                exit();
            }
        }
        
        $tour = $this->tourModel->getTourById($id);
        require_once './views/admin/tours/edit.php';
    }

    public function deleteTour()
    {
        // Chỉ admin và super admin được xóa tour
        AuthController::checkAdminPermission();
        
        $id = $_GET['id'];
        $tour = $this->tourModel->getTourById($id);
        
        if ($tour['image']) {
            deleteFile($tour['image']);
        }
        
        $this->tourModel->deleteTour($id);
        header('Location: ' . BASE_URL . '?act=admin-tours');
        exit();
    }

    // Quản lý Bookings - chỉ admin và super admin
    public function listBookings()
    {
        AuthController::checkAdminPermission();
        $bookings = $this->bookingModel->getAllBookings();
        require_once './views/admin/bookings/list.php';
    }

    public function addBooking()
    {
        AuthController::checkAdminPermission();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customer_id = $_POST['customer_id'];
            $booking_code = $_POST['booking_code'];
            $booking_date = $_POST['booking_date'];
            $total_amount = $_POST['total_amount'];
            $deposit_amount = $_POST['deposit_amount'];
            $status = $_POST['status'];

            $result = $this->bookingModel->insertBooking($customer_id, $booking_code, $booking_date, $total_amount, $deposit_amount, $status);
            
            if ($result) {
                header('Location: ' . BASE_URL . '?act=admin-bookings');
                exit();
            }
        }
        
        $customers = $this->customerModel->getAllCustomers();
        require_once './views/admin/bookings/add.php';
    }

    public function editBooking()
    {
        AuthController::checkAdminPermission();
        $id = $_GET['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customer_id = $_POST['customer_id'];
            $booking_code = $_POST['booking_code'];
            $booking_date = $_POST['booking_date'];
            $total_amount = $_POST['total_amount'];
            $deposit_amount = $_POST['deposit_amount'];
            $status = $_POST['status'];

            $result = $this->bookingModel->updateBooking($id, $customer_id, $booking_code, $booking_date, $total_amount, $deposit_amount, $status);
            
            if ($result) {
                header('Location: ' . BASE_URL . '?act=admin-bookings');
                exit();
            }
        }
        
        $booking = $this->bookingModel->getBookingById($id);
        $customers = $this->customerModel->getAllCustomers();
        require_once './views/admin/bookings/edit.php';
    }

    public function deleteBooking()
    {
        AuthController::checkAdminPermission();
        $id = $_GET['id'];
        $this->bookingModel->deleteBooking($id);
        header('Location: ' . BASE_URL . '?act=admin-bookings');
        exit();
    }

    // Quản lý Customers - chỉ admin và super admin
    public function listCustomers()
    {
        AuthController::checkAdminPermission();
        $customers = $this->customerModel->getAllCustomers();
        require_once './views/admin/customers/list.php';
    }

    public function addCustomer()
    {
        AuthController::checkAdminPermission();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $address = $_POST['address'];
            $history_notes = $_POST['history_notes'] ?? '';

            $result = $this->customerModel->insertCustomer($name, $phone, $email, $address, $history_notes);
            
            if ($result) {
                header('Location: ' . BASE_URL . '?act=admin-customers');
                exit();
            }
        }
        require_once './views/admin/customers/add.php';
    }

    public function editCustomer()
    {
        AuthController::checkAdminPermission();
        $id = $_GET['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $address = $_POST['address'];
            $history_notes = $_POST['history_notes'] ?? '';

            $result = $this->customerModel->updateCustomer($id, $name, $phone, $email, $address, $history_notes);
            
            if ($result) {
                header('Location: ' . BASE_URL . '?act=admin-customers');
                exit();
            }
        }
        
        $customer = $this->customerModel->getCustomerById($id);
        require_once './views/admin/customers/edit.php';
    }

    public function deleteCustomer()
    {
        AuthController::checkAdminPermission();
        $id = $_GET['id'];
        $this->customerModel->deleteCustomer($id);
        header('Location: ' . BASE_URL . '?act=admin-customers');
        exit();
    }

    // Quản lý Departures - chỉ admin và super admin
    public function listDepartures()
    {
        AuthController::checkAdminPermission();
        $departures = $this->departureModel->getAllDepartures();
        require_once './views/admin/departures/list.php';
    }

    // Quản lý Tour Guides - chỉ admin và super admin
    public function listTourGuides()
    {
        AuthController::checkAdminPermission();
        $tourGuides = $this->tourGuideModel->getAllTourGuides();
        require_once './views/admin/tour_guides/list.php';
    }

    // Workflow Management
    public function workflowManagement()
    {
        AuthController::checkAdminPermission();
        
        // Include WorkflowModel
        if (!class_exists('WorkflowModel')) {
            require_once './models/WorkflowModel.php';
        }
        
        $workflowModel = new WorkflowModel();
        
        // Get filter type
        $filterType = $_GET['type'] ?? '';
        
        // Get requests based on filter
        $requests = $workflowModel->getRequests($filterType);
        
        // Get statistics
        $counts = [
            'total' => count($workflowModel->getRequests('')),
            'pending' => count($workflowModel->getRequests('pending')),
            'approved' => count($workflowModel->getRequests('approved')),
            'rejected' => count($workflowModel->getRequests('rejected')),
            'assignments_today' => $workflowModel->getTodayAssignmentsCount(),
            'active_guides' => $workflowModel->getActiveGuidesCount()
        ];
        
        // Get available guides for assignment
        $available_guides = $workflowModel->getAvailableGuides();
        
        require_once './views/admin/workflow_management.php';
    }
    
    public function approveRequest()
    {
        AuthController::checkAdminPermission();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $requestId = $data['request_id'] ?? 0;
        
        if (!class_exists('WorkflowModel')) {
            require_once './models/WorkflowModel.php';
        }
        
        $workflowModel = new WorkflowModel();
        $success = $workflowModel->approveRequest($requestId, $_SESSION['user']['id']);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Yêu cầu đã được duyệt thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi duyệt yêu cầu']);
        }
        exit;
    }
    
    public function rejectRequest()
    {
        AuthController::checkAdminPermission();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $requestId = $data['request_id'] ?? 0;
        $reason = $data['reason'] ?? '';
        
        if (!class_exists('WorkflowModel')) {
            require_once './models/WorkflowModel.php';
        }
        
        $workflowModel = new WorkflowModel();
        $success = $workflowModel->rejectRequest($requestId, $_SESSION['user']['id'], $reason);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Yêu cầu đã bị từ chối']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi từ chối yêu cầu']);
        }
        exit;
    }
    
    public function assignTour()
    {
        AuthController::checkAdminPermission();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $requestId = $data['request_id'] ?? 0;
        $tourId = $data['tour_id'] ?? 0;
        $guideId = $data['guide_id'] ?? 0;
        
        if (!class_exists('WorkflowModel')) {
            require_once './models/WorkflowModel.php';
        }
        
        $workflowModel = new WorkflowModel();
        $success = $workflowModel->assignTourToGuide($tourId, $guideId, $_SESSION['user']['id']);
        
        if ($success) {
            // Update request status to assigned
            $workflowModel->updateRequestStatus($requestId, 'assigned');
            echo json_encode(['success' => true, 'message' => 'Tour đã được phân công thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi phân công tour']);
        }
        exit;
    }

    // ==================== BOOKING ASSIGNMENT MANAGEMENT ====================

    // Hiển thị danh sách booking assignments
    public function bookingAssignments()
    {
        AuthController::checkAdminPermission();
        
        $user_id = $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'];
        
        // Admin chỉ xem assignments do mình tạo, Super Admin xem tất cả
        $assigned_by = ($role === 'super_admin') ? null : $user_id;
        $assignments = $this->bookingAssignmentModel->getBookingAssignmentsByAdmin($assigned_by);
        $stats = $this->bookingAssignmentModel->getBookingAssignmentStats($user_id, $role);
        
        require_once './views/admin/booking_assignments/list.php';
    }

    // Form gửi booking cho HDV
    public function assignBookingForm()
    {
        AuthController::checkAdminPermission();
        
        $booking_id = $_GET['booking_id'] ?? null;
        if (!$booking_id) {
            header('Location: ?act=admin-bookings');
            exit;
        }
        
        // Lấy thông tin booking
        $booking = $this->bookingModel->getBookingById($booking_id);
        if (!$booking) {
            header('Location: ?act=admin-bookings');
            exit;
        }
        
        // Lấy danh sách HDV có thể phân công
        $availableGuides = $this->tourGuideModel->getAvailableGuides();
        
        require_once './views/admin/booking_assignments/assign_form.php';
    }

    // Xử lý gửi booking cho HDV
    public function processBookingAssignment()
    {
        AuthController::checkAdminPermission();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking_id = $_POST['booking_id'];
            $guide_id = $_POST['guide_id'];
            $notes = $_POST['notes'] ?? '';
            $deadline = $_POST['deadline'] ?? null;
            $priority = $_POST['priority'] ?? 'medium';
            $assigned_by = $_SESSION['user']['id'];
            
            // Kiểm tra xem booking đã được giao cho HDV này chưa
            $existingAssignments = $this->bookingAssignmentModel->getBookingAssignmentsForGuide($guide_id);
            foreach ($existingAssignments as $assignment) {
                if ($assignment['booking_id'] == $booking_id && in_array($assignment['status'], ['pending', 'accepted'])) {
                    $_SESSION['error'] = 'Booking này đã được giao cho HDV này rồi!';
                    header('Location: ?act=assign-booking-form&booking_id=' . $booking_id);
                    exit;
                }
            }
            
            $result = $this->bookingAssignmentModel->assignBookingToGuide(
                $booking_id, $guide_id, $assigned_by, $notes, $deadline, $priority
            );
            
            if ($result) {
                $_SESSION['success'] = 'Đã gửi booking cho HDV thành công!';
                header('Location: ?act=admin-booking-assignments');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi gửi booking cho HDV!';
                header('Location: ?act=assign-booking-form&booking_id=' . $booking_id);
            }
        } else {
            header('Location: ?act=admin-bookings');
        }
        exit;
    }

    // Xem chi tiết booking assignment
    public function viewBookingAssignment()
    {
        AuthController::checkAdminPermission();
        
        $assignment_id = $_GET['id'] ?? null;
        if (!$assignment_id) {
            header('Location: ?act=admin-booking-assignments');
            exit;
        }
        
        $assignment = $this->bookingAssignmentModel->getBookingAssignmentById($assignment_id);
        if (!$assignment) {
            header('Location: ?act=admin-booking-assignments');
            exit;
        }
        
        // Kiểm tra quyền xem (chỉ người tạo hoặc super admin)
        if ($_SESSION['user']['role'] !== 'super_admin' && $assignment['assigned_by'] != $_SESSION['user']['id']) {
            header('Location: ?act=admin-booking-assignments');
            exit;
        }
        
        require_once './views/admin/booking_assignments/view.php';
    }

    // Hủy booking assignment
    public function cancelBookingAssignment()
    {
        AuthController::checkAdminPermission();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assignment_id = $_POST['assignment_id'];
            $cancelled_by = $_SESSION['user']['id'];
            
            // Kiểm tra quyền hủy
            $assignment = $this->bookingAssignmentModel->getBookingAssignmentById($assignment_id);
            if (!$assignment || ($_SESSION['user']['role'] !== 'super_admin' && $assignment['assigned_by'] != $_SESSION['user']['id'])) {
                echo json_encode(['success' => false, 'message' => 'Không có quyền hủy assignment này']);
                exit;
            }
            
            $result = $this->bookingAssignmentModel->cancelBookingAssignment($assignment_id, $cancelled_by);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Đã hủy booking assignment thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi hủy booking assignment']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
        exit;
    }

    // Lấy thống kê booking assignments cho API
    public function getBookingAssignmentStats()
    {
        AuthController::checkAdminPermission();
        
        $user_id = $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'];
        
        $stats = $this->bookingAssignmentModel->getBookingAssignmentStats($user_id, $role);
        
        header('Content-Type: application/json');
        echo json_encode($stats);
        exit;
    }
}

