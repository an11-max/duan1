<?php

class TourGuideController
{
    public $tourModel;
    public $customerModel;
    public $bookingModel;
    public $bookingAssignmentModel;

    public function __construct()
    {
        // Kiểm tra đăng nhập và quyền HDV
        AuthController::checkLogin();
        if ($_SESSION['user']['role'] !== 'tour_guide') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . BASE_URL . '?act=admin-dashboard');
            exit;
        }

        $this->tourModel = new TourModel();
        $this->customerModel = new CustomerModel();
        $this->bookingModel = new BookingModel();
        $this->bookingAssignmentModel = new BookingAssignmentModel();
    }

    // Dashboard cho HDV
    public function dashboard()
    {
        $guideId = $_SESSION['user']['id'];
        
        // Lấy tours được phân công (sử dụng TourModel)
        $assigned_tours = $this->tourModel->getToursByGuideId($guideId);
        
        // Lấy thông báo tours mới được phân công (chưa xác nhận)
        $newAssignments = $this->getNewAssignments($guideId);
        
        // Lấy tours sắp diễn ra (trong vòng 2 ngày)
        $upcomingTours = $this->getUpcomingTours($guideId);
        
        // Tổng hợp notifications
        $notifications = array_merge($newAssignments, $upcomingTours);
        
        // Đếm tổng số thông báo
        $notificationCount = count($notifications);

        require_once './views/admin/guide_dashboard.php';
    }

    // Xem danh sách tours được phân công và lịch trình
    public function viewToursAndSchedule()
    {
        $guideId = $_SESSION['user']['id'];
        
        // Lấy tất cả tours được phân công với thông tin chi tiết
        $assignedTours = $this->getDetailedAssignments($guideId);
        
        require_once './views/admin/guide_tours_schedule.php';
    }

    // Trang lịch trình - chỉ tour đã chấp nhận
    public function viewSchedule()
    {
        $guideId = $_SESSION['user']['id'];
        $assignedTours = $this->getDetailedAssignments($guideId);
        require_once './views/admin/guide_schedule.php';
    }

    // Trang tour được phân công - chỉ tour chờ xác nhận
    public function viewAssignments()
    {
        $guideId = $_SESSION['user']['id'];
        $assignedTours = $this->getDetailedAssignments($guideId);
        require_once './views/admin/guide_assignments.php';
    }

    // Xem chi tiết tour và khách hàng
    public function viewTourDetail()
    {
        $tourId = $_GET['id'] ?? 0;
        $guideId = $_SESSION['user']['id'];
        
        // Kiểm tra quyền xem tour này
        if (!$this->canAccessTour($guideId, $tourId)) {
            $_SESSION['error'] = 'Bạn không có quyền xem tour này';
            header('Location: ' . BASE_URL . '?act=guide-tours');
            exit;
        }

        $tour = $this->tourModel->getTourById($tourId);
        $customers = $this->getTourCustomers($tourId);
        $bookings = $this->getTourBookings($tourId);
        
        require_once './views/admin/guide_tour_detail.php';
    }

    // Hiển thị form yêu cầu tour
    public function showRequestForm()
    {
        $guideId = $_SESSION['user']['id'];
        
        // Lấy tất cả tours
        $allTours = $this->tourModel->getAllTours();
        
        // Lấy danh sách tours đã yêu cầu
        $requestedTours = $this->getRequestedTours($guideId);
        $requestedTourIds = array_column($requestedTours, 'tour_id');
        
        // Lấy tours đã được phân công
        $assignedTours = $this->tourModel->getToursByGuideId($guideId);
        $assignedTourIds = array_column($assignedTours, 'id');
        
        // Đánh dấu status cho từng tour
        foreach ($allTours as &$tour) {
            if (in_array($tour['id'], $assignedTourIds)) {
                $tour['status'] = 'assigned';
            } elseif (in_array($tour['id'], $requestedTourIds)) {
                $tour['status'] = 'requested';
            } else {
                $tour['status'] = 'available';
            }
        }
        
        require_once './views/admin/guide_request_form.php';
    }

    // Gửi yêu cầu nhận tour
    public function requestTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tourId = $_POST['tour_id'] ?? 0;
            $message = trim($_POST['message'] ?? '');
            $guideId = $_SESSION['user']['id'];

            // Kiểm tra đã gửi yêu cầu chưa
            if ($this->hasRequestedTour($guideId, $tourId)) {
                echo json_encode(['success' => false, 'message' => 'Bạn đã gửi yêu cầu cho tour này rồi']);
            } else {
                if ($this->createTourRequest($guideId, $tourId, $message)) {
                    // Tạo thông báo cho admin
                    $this->notifyAdminsNewRequest($guideId, $tourId);
                    echo json_encode(['success' => true, 'message' => 'Yêu cầu đã được gửi. Vui lòng chờ admin phê duyệt']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi gửi yêu cầu']);
                }
            }
        } else {
            // Redirect to show form if not POST
            header('Location: ' . BASE_URL . '?act=guide-request-form');
        }
        exit;
    }

    // Xem thông báo
    public function viewNotifications()
    {
        $guideId = $_SESSION['user']['id'];
        $notifications = $this->getAllNotifications($guideId);
        
        // Đánh dấu đã đọc
        $this->markNotificationsAsRead($guideId);
        
        require_once './views/admin/guide_notifications.php';
    }

    // Xem yêu cầu đã gửi
    public function viewRequests()
    {
        $guideId = $_SESSION['user']['id'];
        $requests = $this->getGuideRequests($guideId);
        require_once './views/admin/guide_requests.php';
    }

    // Chấp nhận/từ chối tour được phân công
    public function respondToAssignment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assignmentId = $_POST['assignment_id'] ?? 0;
            $action = $_POST['action'] ?? '';
            $guideId = $_SESSION['user']['id'];

            if (in_array($action, ['accepted', 'declined'])) {
                if ($this->updateAssignmentStatus($assignmentId, $guideId, $action)) {
                    $message = $action === 'accepted' ? 'Đã chấp nhận tour thành công' : 'Đã từ chối tour';
                    
                    // Thông báo cho admin
                    $this->notifyAdminAssignmentResponse($assignmentId, $action);
                    
                    echo json_encode(['success' => true, 'message' => $message]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Method không được hỗ trợ']);
        }
        exit;
    }

    // ======================
    // PRIVATE HELPER METHODS
    // ======================

    private function getAccessibleTours($guideId)
    {
        try {
            // Lấy tours được phân công
            $sql = "SELECT t.*, ta.assigned_date, ta.status as assignment_status
                    FROM tours t 
                    INNER JOIN tour_assignments ta ON t.id = ta.tour_id 
                    WHERE ta.guide_id = :guide_id 
                    AND ta.status IN ('assigned', 'accepted')
                    ORDER BY ta.assigned_date DESC";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->execute();
            $assignedTours = $stmt->fetchAll();
            
            // Lấy tất cả tours (cho trường hợp HDV muốn yêu cầu tour mới)
            $sql2 = "SELECT t.* FROM tours t ORDER BY t.id DESC";
            $stmt2 = connectDB()->prepare($sql2);
            $stmt2->execute();
            $allTours = $stmt2->fetchAll();
            
            // Đánh dấu tours đã được phân công
            foreach ($allTours as &$tour) {
                $tour['is_assigned'] = false;
                $tour['assignment_status'] = null;
                foreach ($assignedTours as $assigned) {
                    if ($assigned['id'] == $tour['id']) {
                        $tour['is_assigned'] = true;
                        $tour['assignment_status'] = $assigned['assignment_status'];
                        $tour['assigned_date'] = $assigned['assigned_date'];
                        break;
                    }
                }
            }
            
            return $allTours;
        } catch (Exception $e) {
            return [];
        }
    }

    private function getAssignedTours($guideId)
    {
        try {
            $sql = "SELECT ta.*, t.name as tour_name, t.tour_code, u.full_name as assigned_by_name
                    FROM tour_assignments ta
                    INNER JOIN tours t ON ta.tour_id = t.id
                    INNER JOIN users u ON ta.assigned_by = u.id
                    WHERE ta.guide_id = :guide_id 
                    AND ta.status IN ('assigned', 'accepted')
                    ORDER BY ta.assigned_date DESC";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function getUnreadNotifications($guideId)
    {
        try {
            $sql = "SELECT * FROM notifications 
                    WHERE user_id = :user_id AND is_read = FALSE 
                    ORDER BY created_at DESC LIMIT 5";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':user_id', $guideId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function getPendingRequests($guideId)
    {
        try {
            $sql = "SELECT tr.*, t.name as tour_name, t.tour_code
                    FROM tour_requests tr
                    INNER JOIN tours t ON tr.tour_id = t.id
                    WHERE tr.guide_id = :guide_id AND tr.status = 'pending'
                    ORDER BY tr.request_date DESC";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function getRequestedTours($guideId)
    {
        try {
            $sql = "SELECT tour_id FROM tour_requests 
                    WHERE guide_id = :guide_id AND status IN ('pending', 'approved')";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    // Lấy tours mới được phân công (chưa xác nhận)
    private function getNewAssignments($guideId)
    {
        try {
            $sql = "SELECT ta.*, t.name as tour_name, t.tour_code, u.full_name as assigned_by_name
                    FROM tour_assignments ta
                    INNER JOIN tours t ON ta.tour_id = t.id
                    INNER JOIN users u ON ta.assigned_by = u.id
                    WHERE ta.guide_id = :guide_id 
                    AND ta.status = 'assigned'
                    AND ta.assigned_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                    ORDER BY ta.assigned_date DESC";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->execute();
            $assignments = $stmt->fetchAll();
            
            // Format thành notification
            $notifications = [];
            foreach ($assignments as $assignment) {
                $notifications[] = [
                    'type' => 'new_assignment',
                    'title' => 'Tour mới được phân công',
                    'message' => "Bạn được phân công tour '{$assignment['tour_name']}' bởi {$assignment['assigned_by_name']}",
                    'tour_name' => $assignment['tour_name'],
                    'tour_code' => $assignment['tour_code'],
                    'assigned_date' => $assignment['assigned_date'],
                    'assignment_id' => $assignment['id'],
                    'icon' => 'fas fa-user-plus',
                    'color' => 'info',
                    'action_text' => 'Xác nhận ngay'
                ];
            }
            
            return $notifications;
        } catch (Exception $e) {
            return [];
        }
    }

    // Lấy tours sắp diễn ra (2 ngày trước)
    private function getUpcomingTours($guideId)
    {
        try {
            // Lấy từ bảng departures nếu có, hoặc tạm thời dùng assigned_date + duration
            $sql = "SELECT ta.*, t.name as tour_name, t.tour_code, t.duration, u.full_name as assigned_by_name
                    FROM tour_assignments ta
                    INNER JOIN tours t ON ta.tour_id = t.id
                    INNER JOIN users u ON ta.assigned_by = u.id
                    WHERE ta.guide_id = :guide_id 
                    AND ta.status = 'accepted'
                    AND DATE_ADD(ta.assigned_date, INTERVAL 5 DAY) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 DAY)
                    ORDER BY ta.assigned_date ASC";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->execute();
            $assignments = $stmt->fetchAll();
            
            // Format thành notification
            $notifications = [];
            foreach ($assignments as $assignment) {
                $startDate = date('Y-m-d', strtotime($assignment['assigned_date'] . ' + 5 days'));
                $daysLeft = floor((strtotime($startDate) - time()) / (60 * 60 * 24));
                $timeText = $daysLeft == 0 ? 'hôm nay' : ($daysLeft == 1 ? 'ngày mai' : "sau {$daysLeft} ngày");
                
                $notifications[] = [
                    'type' => 'upcoming_tour',
                    'title' => 'Tour sắp khởi hành',
                    'message' => "Tour '{$assignment['tour_name']}' sẽ khởi hành {$timeText}",
                    'tour_name' => $assignment['tour_name'],
                    'tour_code' => $assignment['tour_code'],
                    'start_date' => $startDate,
                    'days_left' => $daysLeft,
                    'duration' => $assignment['duration'],
                    'icon' => 'fas fa-calendar-exclamation',
                    'color' => $daysLeft == 0 ? 'danger' : 'warning',
                    'action_text' => 'Xem chi tiết'
                ];
            }
            
            return $notifications;
        } catch (Exception $e) {
            return [];
        }
    }

    // Lấy thông tin chi tiết về các tour được phân công
    private function getDetailedAssignments($guideId)
    {
        try {
            $sql = "SELECT ta.*, 
                           t.name as tour_name, t.tour_code, t.description, 
                           t.duration, t.is_international, t.image, t.price,
                           t.max_participants, t.start_date, t.end_date,
                           u.full_name as assigned_by_name, u.role as assigned_by_role
                    FROM tour_assignments ta
                    INNER JOIN tours t ON ta.tour_id = t.id
                    INNER JOIN users u ON ta.assigned_by = u.id
                    WHERE ta.guide_id = :guide_id 
                    ORDER BY ta.assigned_date DESC";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function canAccessTour($guideId, $tourId)
    {
        try {
            $sql = "SELECT COUNT(*) FROM guide_tour_access 
                    WHERE guide_id = :guide_id AND tour_id = :tour_id
                    AND (expires_date IS NULL OR expires_date > CURDATE())";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->bindParam(':tour_id', $tourId);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    private function getTourCustomers($tourId)
    {
        try {
            $sql = "SELECT DISTINCT c.* 
                    FROM customers c
                    INNER JOIN bookings b ON c.id = b.customer_id
                    INNER JOIN departure_bookings db ON b.id = db.booking_id
                    INNER JOIN departures d ON db.departure_id = d.id
                    INNER JOIN tour_versions tv ON d.tour_version_id = tv.id
                    WHERE tv.tour_id = :tour_id";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':tour_id', $tourId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function getTourBookings($tourId)
    {
        try {
            $sql = "SELECT b.*, c.name as customer_name, c.phone as customer_phone,
                           d.departure_date, d.return_date
                    FROM bookings b
                    INNER JOIN customers c ON b.customer_id = c.id
                    INNER JOIN departure_bookings db ON b.id = db.booking_id
                    INNER JOIN departures d ON db.departure_id = d.id
                    INNER JOIN tour_versions tv ON d.tour_version_id = tv.id
                    WHERE tv.tour_id = :tour_id
                    ORDER BY b.booking_date DESC";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':tour_id', $tourId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function hasRequestedTour($guideId, $tourId)
    {
        try {
            $sql = "SELECT COUNT(*) FROM tour_requests 
                    WHERE guide_id = :guide_id AND tour_id = :tour_id 
                    AND status = 'pending'";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->bindParam(':tour_id', $tourId);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    private function createTourRequest($guideId, $tourId, $message)
    {
        try {
            $sql = "INSERT INTO tour_requests (tour_id, guide_id, message) 
                    VALUES (:tour_id, :guide_id, :message)";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':tour_id', $tourId);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->bindParam(':message', $message);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    private function notifyAdminsNewRequest($guideId, $tourId)
    {
        try {
            // Lấy thông tin HDV và tour
            $guide = $this->getUserInfo($guideId);
            $tour = $this->tourModel->getTourById($tourId);
            
            // Lấy danh sách admin
            $sql = "SELECT id FROM users WHERE role IN ('admin', 'super_admin')";
            $stmt = connectDB()->prepare($sql);
            $stmt->execute();
            $admins = $stmt->fetchAll();

            $title = 'Yêu cầu nhận tour mới';
            $message = "HDV {$guide['full_name']} yêu cầu nhận tour {$tour['name']}";

            foreach ($admins as $admin) {
                $this->createNotification($admin['id'], $title, $message, 'info', 'tour_request');
            }
        } catch (Exception $e) {
            // Log error
        }
    }

    private function createNotification($userId, $title, $message, $type = 'info', $relatedType = null, $relatedId = null)
    {
        try {
            $sql = "INSERT INTO notifications (user_id, title, message, type, related_type, related_id) 
                    VALUES (:user_id, :title, :message, :type, :related_type, :related_id)";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':related_type', $relatedType);
            $stmt->bindParam(':related_id', $relatedId);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    private function getUserInfo($userId)
    {
        try {
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            return null;
        }
    }

    private function getAllNotifications($guideId)
    {
        try {
            $sql = "SELECT * FROM notifications 
                    WHERE user_id = :user_id 
                    ORDER BY created_at DESC LIMIT 20";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':user_id', $guideId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function markNotificationsAsRead($guideId)
    {
        try {
            $sql = "UPDATE notifications SET is_read = TRUE 
                    WHERE user_id = :user_id AND is_read = FALSE";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':user_id', $guideId);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    private function getGuideRequests($guideId)
    {
        try {
            $sql = "SELECT tr.*, t.name as tour_name, t.tour_code,
                           u.full_name as reviewed_by_name
                    FROM tour_requests tr
                    INNER JOIN tours t ON tr.tour_id = t.id
                    LEFT JOIN users u ON tr.reviewed_by = u.id
                    WHERE tr.guide_id = :guide_id
                    ORDER BY tr.request_date DESC";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function updateAssignmentStatus($assignmentId, $guideId, $status)
    {
        try {
            $sql = "UPDATE tour_assignments 
                    SET status = :status, response_date = NOW() 
                    WHERE id = :id AND guide_id = :guide_id";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $assignmentId);
            $stmt->bindParam(':guide_id', $guideId);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // API methods for AJAX calls
    public function getNotificationCount()
    {
        header('Content-Type: application/json');
        $guideId = $_SESSION['user']['id'];
        
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
            $stmt->execute([$guideId]);
            $count = $stmt->fetchColumn();
            
            echo json_encode(['count' => $count]);
        } catch (Exception $e) {
            echo json_encode(['count' => 0]);
        }
        exit;
    }
    
    public function markNotificationRead()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $notificationId = $data['id'] ?? 0;
        $guideId = $_SESSION['user']['id'];
        
        try {
            $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
            $success = $stmt->execute([$notificationId, $guideId]);
            
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
        exit;
    }
    
    public function markNotificationsRead()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $notificationIds = $data['ids'] ?? [];
        $guideId = $_SESSION['user']['id'];
        
        if (empty($notificationIds)) {
            echo json_encode(['success' => false, 'message' => 'No notifications selected']);
            exit;
        }
        
        try {
            $placeholders = implode(',', array_fill(0, count($notificationIds), '?'));
            $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id IN ($placeholders) AND user_id = ?");
            $params = array_merge($notificationIds, [$guideId]);
            $success = $stmt->execute($params);
            
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
        exit;
    }
    
    public function deleteNotification()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $notificationId = $data['id'] ?? 0;
        $guideId = $_SESSION['user']['id'];
        
        try {
            $stmt = $this->db->prepare("DELETE FROM notifications WHERE id = ? AND user_id = ?");
            $success = $stmt->execute([$notificationId, $guideId]);
            
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
        exit;
    }
    
    public function deleteNotifications()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $notificationIds = $data['ids'] ?? [];
        $guideId = $_SESSION['user']['id'];
        
        if (empty($notificationIds)) {
            echo json_encode(['success' => false, 'message' => 'No notifications selected']);
            exit;
        }
        
        try {
            $placeholders = implode(',', array_fill(0, count($notificationIds), '?'));
            $stmt = $this->db->prepare("DELETE FROM notifications WHERE id IN ($placeholders) AND user_id = ?");
            $params = array_merge($notificationIds, [$guideId]);
            $success = $stmt->execute($params);
            
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
        exit;
    }
    
    public function cancelRequest()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $requestId = $data['request_id'] ?? 0;
        $guideId = $_SESSION['user']['id'];
        
        try {
            // Verify request belongs to this guide and is still pending
            $stmt = $this->db->prepare("SELECT * FROM tour_requests WHERE id = ? AND guide_id = ? AND status = 'pending'");
            $stmt->execute([$requestId, $guideId]);
            $request = $stmt->fetch();
            
            if (!$request) {
                echo json_encode(['success' => false, 'message' => 'Yêu cầu không tồn tại hoặc không thể hủy']);
                exit;
            }
            
            // Cancel the request
            $stmt = $this->db->prepare("UPDATE tour_requests SET status = 'cancelled', updated_at = NOW() WHERE id = ?");
            $success = $stmt->execute([$requestId]);
            
            if ($success) {
                // Notify admins about cancellation
                $this->notifyAdminRequestCancelled($requestId, $request['tour_id']);
                echo json_encode(['success' => true, 'message' => 'Yêu cầu đã được hủy thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi hủy yêu cầu']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
        exit;
    }

    private function notifyAdminRequestCancelled($requestId, $tourId)
    {
        try {
            // Get tour info
            $stmt = $this->db->prepare("SELECT tour_name FROM tours WHERE id = ?");
            $stmt->execute([$tourId]);
            $tour = $stmt->fetch();
            
            // Get guide info  
            $guideId = $_SESSION['user']['id'];
            $stmt = $this->db->prepare("SELECT full_name FROM users WHERE id = ?");
            $stmt->execute([$guideId]);
            $guide = $stmt->fetch();
            
            // Get admin users
            $stmt = $this->db->prepare("SELECT id FROM users WHERE role IN ('admin', 'super_admin')");
            $stmt->execute();
            $admins = $stmt->fetchAll();
            
            $title = "Yêu cầu tour đã bị hủy";
            $message = "Hướng dẫn viên {$guide['full_name']} đã hủy yêu cầu tham gia tour '{$tour['tour_name']}'.";
            
            foreach ($admins as $admin) {
                $stmt = $this->db->prepare("INSERT INTO notifications (user_id, title, message, type, created_at) VALUES (?, ?, ?, 'tour_request', NOW())");
                $stmt->execute([$admin['id'], $title, $message]);
            }
        } catch (Exception $e) {
            // Log error
        }
    }

    // Xem đánh giá
    public function viewReviews()
    {
        $guideId = $_SESSION['user']['id'];
        
        // Tạm thời để trống - có thể mở rộng sau
        $reviews = [];
        
        require_once './views/admin/guide_reviews.php';
    }

    // Xem thông tin cá nhân
    public function viewProfile()
    {
        $guideId = $_SESSION['user']['id'];
        
        // Lấy thông tin user từ session
        $userInfo = $_SESSION['user'];
        
        require_once './views/admin/guide_profile.php';
    }

    private function notifyAdminAssignmentResponse($assignmentId, $action)
    {
        try {
            // Lấy thông tin assignment và tour
            $sql = "SELECT ta.*, t.name as tour_name, t.tour_code, u.full_name as guide_name
                    FROM tour_assignments ta
                    INNER JOIN tours t ON ta.tour_id = t.id
                    INNER JOIN users u ON ta.guide_id = u.id
                    WHERE ta.id = :assignment_id";
            
            $stmt = connectDB()->prepare($sql);
            $stmt->bindParam(':assignment_id', $assignmentId);
            $stmt->execute();
            $assignment = $stmt->fetch();
            
            if ($assignment) {
                $actionText = $action === 'accepted' ? 'đã chấp nhận' : 'đã từ chối';
                $title = "HDV phản hồi phân công tour";
                $message = "HDV {$assignment['guide_name']} {$actionText} tour '{$assignment['tour_name']}'";
                
                // Gửi thông báo cho admin đã phân công
                $this->createNotification($assignment['assigned_by'], $title, $message, 'assignment_response');
                
                // Gửi cho tất cả super admin
                $sql2 = "SELECT id FROM users WHERE role = 'super_admin'";
                $stmt2 = connectDB()->prepare($sql2);
                $stmt2->execute();
                $superAdmins = $stmt2->fetchAll();
                
                foreach ($superAdmins as $admin) {
                    if ($admin['id'] != $assignment['assigned_by']) { // Không gửi lại cho người đã phân công
                        $this->createNotification($admin['id'], $title, $message, 'assignment_response');
                    }
                }
            }
        } catch (Exception $e) {
            // Log error
        }
    }

    // ==================== BOOKING ASSIGNMENT MANAGEMENT ====================

    // Hiển thị danh sách booking assignments cho HDV
    public function bookingAssignments()
    {
        $guide_id = $_SESSION['user']['id'];
        
        // Lấy tất cả booking assignments
        $allAssignments = $this->bookingAssignmentModel->getBookingAssignmentsForGuide($guide_id);
        
        // Phân loại theo status
        $pendingAssignments = array_filter($allAssignments, function($assignment) {
            return $assignment['assignment_status'] === 'pending';
        });
        
        $respondedAssignments = array_filter($allAssignments, function($assignment) {
            return in_array($assignment['assignment_status'], ['accepted', 'declined', 'cancelled']);
        });
        
        $expiredAssignments = array_filter($allAssignments, function($assignment) {
            return $assignment['assignment_status'] === 'expired';
        });
        
        // Thống kê
        $stats = $this->bookingAssignmentModel->getBookingAssignmentStats($guide_id, 'tour_guide');
        
        require_once './views/guide/booking_assignments.php';
    }

    // Xem chi tiết booking assignment
    public function viewBookingAssignment()
    {
        $assignment_id = $_GET['id'] ?? null;
        $guide_id = $_SESSION['user']['id'];
        
        if (!$assignment_id) {
            header('Location: ?act=guide-booking-assignments');
            exit;
        }
        
        $assignment = $this->bookingAssignmentModel->getBookingAssignmentById($assignment_id);
        
        // Kiểm tra quyền xem (chỉ HDV được phân công)
        if (!$assignment || $assignment['guide_id'] != $guide_id) {
            header('Location: ?act=guide-booking-assignments');
            exit;
        }
        
        require_once './views/guide/booking_assignment_detail.php';
    }

    // Phản hồi booking assignment
    public function respondToBookingAssignment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assignment_id = $_POST['assignment_id'];
            $status = $_POST['status']; // 'accepted' hoặc 'declined'
            $response = $_POST['response'] ?? '';
            $guide_id = $_SESSION['user']['id'];
            
            // Validate status
            if (!in_array($status, ['accepted', 'declined'])) {
                echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
                exit;
            }
            
            // Kiểm tra quyền phản hồi
            $assignment = $this->bookingAssignmentModel->getBookingAssignmentById($assignment_id);
            if (!$assignment || $assignment['guide_id'] != $guide_id) {
                echo json_encode(['success' => false, 'message' => 'Không có quyền phản hồi assignment này']);
                exit;
            }
            
            // Kiểm tra trạng thái hiện tại
            if ($assignment['status'] !== 'pending') {
                echo json_encode(['success' => false, 'message' => 'Assignment này đã được phản hồi rồi']);
                exit;
            }
            
            $result = $this->bookingAssignmentModel->respondToBookingAssignment($assignment_id, $guide_id, $status, $response);
            
            if ($result) {
                $message = $status === 'accepted' ? 'Đã chấp nhận booking assignment' : 'Đã từ chối booking assignment';
                echo json_encode(['success' => true, 'message' => $message]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi phản hồi booking assignment']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
        exit;
    }

    // Lấy danh sách booking assignments cho API
    public function getBookingAssignments()
    {
        $guide_id = $_SESSION['user']['id'];
        $status = $_GET['status'] ?? null;
        
        $assignments = $this->bookingAssignmentModel->getBookingAssignmentsForGuide($guide_id, $status);
        
        header('Content-Type: application/json');
        echo json_encode($assignments);
        exit;
    }

    // Lấy thống kê booking assignments cho API
    public function getBookingAssignmentStats()
    {
        $guide_id = $_SESSION['user']['id'];
        
        $stats = $this->bookingAssignmentModel->getBookingAssignmentStats($guide_id, 'tour_guide');
        
        header('Content-Type: application/json');
        echo json_encode($stats);
        exit;
    }

    // Đánh dấu đã đọc thông báo booking assignment
    public function markBookingAssignmentAsRead()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assignment_id = $_POST['assignment_id'];
            $guide_id = $_SESSION['user']['id'];
            
            // Kiểm tra quyền
            $assignment = $this->bookingAssignmentModel->getBookingAssignmentById($assignment_id);
            if (!$assignment || $assignment['guide_id'] != $guide_id) {
                echo json_encode(['success' => false, 'message' => 'Không có quyền']);
                exit;
            }
            
            // Đánh dấu các thông báo liên quan là đã đọc
            try {
                $sql = "UPDATE notifications 
                        SET is_read = 1 
                        WHERE user_id = :user_id 
                        AND type = 'booking_assignment' 
                        AND related_id = :assignment_id";
                $stmt = connectDB()->prepare($sql);
                $result = $stmt->execute([
                    ':user_id' => $guide_id,
                    ':assignment_id' => $assignment_id
                ]);
                
                echo json_encode(['success' => $result, 'message' => 'Đã đánh dấu đã đọc']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
        exit;
    }
}
?>