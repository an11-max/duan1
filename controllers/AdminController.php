<?php

class AdminController
{
    public $tourModel;
    public $bookingModel;
    public $customerModel;
    public $departureModel;
    public $tourGuideModel;
    public $bookingAssignmentModel;
    public $userModel;

    public function __construct()
    {
        // Kiểm tra đăng nhập cho tất cả các action
        XacThucController::checkLogin();
        
        $this->tourModel = new TourModel();
        $this->bookingModel = new DatTourModel(); // Thay BookingModel bằng DatTourModel
        $this->customerModel = new KhachHangModel(); // Thay CustomerModel bằng KhachHangModel
        $this->departureModel = new KhoiHanhModel(); // Thay DepartureModel bằng KhoiHanhModel
        $this->tourGuideModel = new HuongDanVienModel(); // Thay TourGuideModel bằng HuongDanVienModel
        $this->bookingAssignmentModel = new PhanCongDatTourModel(); // Thay BookingAssignmentModel bằng PhanCongDatTourModel
        $this->userModel = new UserModel();
    }

    // Dashboard - admin, super admin và HDV truy cập được
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
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        $bookings = $this->bookingModel->getAllBookings();
        require_once './views/admin/bookings/list.php';
    }

    public function addBooking()
    {
        XacThucController::checkAdminPermission();
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
        XacThucController::checkAdminPermission();
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
        XacThucController::checkAdminPermission();
        $id = $_GET['id'];
        $this->bookingModel->deleteBooking($id);
        header('Location: ' . BASE_URL . '?act=admin-bookings');
        exit();
    }

    // Quản lý Customers - chỉ admin và super admin
    public function listCustomers()
    {
        XacThucController::checkAdminPermission();
        $customers = $this->customerModel->getAllCustomers();
        require_once './views/admin/customers/list.php';
    }

    public function addCustomer()
    {
        XacThucController::checkAdminPermission();
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
        XacThucController::checkAdminPermission();
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
        XacThucController::checkAdminPermission();
        $id = $_GET['id'];
        $this->customerModel->deleteCustomer($id);
        header('Location: ' . BASE_URL . '?act=admin-customers');
        exit();
    }

    // Quản lý Departures - chỉ admin và super admin
    public function listDepartures()
    {
        XacThucController::checkAdminPermission();
        $departures = $this->departureModel->getAllDepartures();
        require_once './views/admin/departures/list.php';
    }

    // Quản lý Tour Guides - chỉ admin và super admin
    public function listTourGuides()
    {
        XacThucController::checkAdminPermission();
        $tourGuides = $this->tourGuideModel->getAllTourGuides();
        require_once './views/admin/tour_guides/list.php';
    }

    // Workflow Management
    public function workflowManagement()
    {
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        
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
        XacThucController::checkAdminPermission();
        
        $user_id = $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'];
        
        $stats = $this->bookingAssignmentModel->getBookingAssignmentStats($user_id, $role);
        
        header('Content-Type: application/json');
        echo json_encode($stats);
        exit;
    }

    // ==================== USER MANAGEMENT ====================
    
    // Hiển thị danh sách tài khoản (chỉ super admin)
    public function listUsers()
    {
        // Kiểm tra quyền super admin
        if ($_SESSION['user']['role'] !== 'super_admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này!';
            header('Location: ?act=admin-dashboard');
            exit;
        }

        // Lấy tham số search và filter
        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = $_GET['page'] ?? 1;
        
        $users = $this->userModel->getAllUsers($page, 10, $search, $role, $status);
        require_once './views/quantri/users/list.php';
    }

    // Hiển thị form thêm tài khoản mới
    public function addUser()
    {
        // Kiểm tra quyền super admin
        if ($_SESSION['user']['role'] !== 'super_admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này!';
            header('Location: ?act=admin-dashboard');
            exit;
        }

        require_once './views/quantri/users/add.php';
    }

    // Xử lý thêm tài khoản mới
    public function createUser()
    {
        // Kiểm tra quyền super admin
        if ($_SESSION['user']['role'] !== 'super_admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này!';
            header('Location: ?act=admin-dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $email = trim($_POST['email']);
            $fullName = trim($_POST['full_name']);
            $role = $_POST['role'];

            // Validation
            if (empty($username) || empty($password) || empty($email) || empty($fullName)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
                header('Location: ?act=admin-user-add');
                exit;
            }

            // Kiểm tra username đã tồn tại
            if ($this->userModel->usernameExists($username)) {
                $_SESSION['error'] = 'Tên đăng nhập đã tồn tại!';
                header('Location: ?act=admin-user-add');
                exit;
            }

            // Kiểm tra email đã tồn tại
            if ($this->userModel->emailExists($email)) {
                $_SESSION['error'] = 'Email đã tồn tại!';
                header('Location: ?act=admin-user-add');
                exit;
            }

            // Avatar đã được thay thế bằng icon dựa trên role - không cần upload
            $avatar = '';

            $userData = [
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'full_name' => $fullName,
                'role' => $role,
                'avatar' => $avatar
            ];

            if ($this->userModel->createUser($userData)) {
                $_SESSION['success'] = 'Tạo tài khoản thành công!';
                header('Location: ?act=admin-users');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi tạo tài khoản!';
                header('Location: ?act=admin-user-add');
            }
            exit;
        }
    }

    // Hiển thị form chỉnh sửa tài khoản
    public function editUser()
    {
        // Kiểm tra quyền super admin
        if ($_SESSION['user']['role'] !== 'super_admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này!';
            header('Location: ?act=admin-dashboard');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        $user = $this->userModel->getUserById($id);
        
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy tài khoản!';
            header('Location: ?act=admin-users');
            exit;
        }

        require_once './views/quantri/users/edit.php';
    }

    // Xử lý cập nhật tài khoản
    public function updateUser()
    {
        // Kiểm tra quyền super admin
        if ($_SESSION['user']['role'] !== 'super_admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này!';
            header('Location: ?act=admin-dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $email = trim($_POST['email']);
            $fullName = trim($_POST['full_name']);
            $password = trim($_POST['password']);

            // Validation
            if (empty($email) || empty($fullName)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
                header('Location: ?act=admin-user-edit&id=' . $id);
                exit;
            }

            // Kiểm tra email đã tồn tại (trừ user hiện tại)
            if ($this->userModel->emailExists($email, $id)) {
                $_SESSION['error'] = 'Email đã tồn tại!';
                header('Location: ?act=admin-user-edit&id=' . $id);
                exit;
            }

            $userData = [
                'email' => $email,
                'full_name' => $fullName,
                'role' => $role
            ];

            // Thêm password nếu có
            if (!empty($password)) {
                $userData['password'] = $password;
            }

            // Avatar đã được thay thế bằng icon - không cần xử lý upload

            if ($this->userModel->updateUser($id, $userData)) {
                $_SESSION['success'] = 'Cập nhật tài khoản thành công!';
                header('Location: ?act=admin-users');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật tài khoản!';
                header('Location: ?act=admin-user-edit&id=' . $id);
            }
            exit;
        }
    }

    // Xóa tài khoản
    public function deleteUser()
    {
        // Kiểm tra quyền super admin
        if ($_SESSION['user']['role'] !== 'super_admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này!';
            header('Location: ?act=admin-dashboard');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        
        if ($this->userModel->deleteUser($id)) {
            $_SESSION['success'] = 'Xóa tài khoản thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa tài khoản!';
        }

        header('Location: ?act=admin-users');
        exit;
    }

    // Thay đổi trạng thái tài khoản
    public function toggleUserStatus()
    {
        // Kiểm tra quyền super admin
        if ($_SESSION['user']['role'] !== 'super_admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này!';
            header('Location: ?act=admin-dashboard');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        
        if ($this->userModel->toggleUserStatus($id)) {
            $_SESSION['success'] = 'Thay đổi trạng thái tài khoản thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi thay đổi trạng thái tài khoản!';
        }

        header('Location: ?act=admin-users');
        exit;
    }
}

