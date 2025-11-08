<?php

class WorkflowModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // ==========================================
    // TOUR REQUESTS (Yêu cầu nhận tour từ HDV)
    // ==========================================

    public function getPendingRequests()
    {
        try {
            $sql = "SELECT tr.*, t.name as tour_name, t.tour_code,
                           u.full_name as guide_name, u.email as guide_email
                    FROM tour_requests tr
                    INNER JOIN tours t ON tr.tour_id = t.id
                    INNER JOIN users u ON tr.guide_id = u.id
                    WHERE tr.status = 'pending'
                    ORDER BY tr.request_date ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function approveRequest($requestId, $adminId, $adminResponse = '')
    {
        try {
            $this->conn->beginTransaction();

            // Cập nhật status request
            $sql = "UPDATE tour_requests 
                    SET status = 'approved', reviewed_by = :admin_id, 
                        reviewed_date = NOW(), admin_response = :admin_response
                    WHERE id = :request_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':admin_id', $adminId);
            $stmt->bindParam(':admin_response', $adminResponse);
            $stmt->bindParam(':request_id', $requestId);
            $stmt->execute();

            // Lấy thông tin request
            $request = $this->getRequestById($requestId);
            
            // Tạo tour assignment
            $sql = "INSERT INTO tour_assignments (tour_id, guide_id, assigned_by, status, notes) 
                    VALUES (:tour_id, :guide_id, :assigned_by, 'assigned', :notes)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':tour_id', $request['tour_id']);
            $stmt->bindParam(':guide_id', $request['guide_id']);
            $stmt->bindParam(':assigned_by', $adminId);
            $stmt->bindParam(':notes', $adminResponse);
            $stmt->execute();

            // Cấp quyền xem tour
            $this->grantTourAccess($request['guide_id'], $request['tour_id'], 'assigned', $adminId);

            // Tạo thông báo cho HDV
            $this->createNotification(
                $request['guide_id'],
                'Yêu cầu được chấp thuận',
                "Yêu cầu nhận tour {$request['tour_name']} của bạn đã được chấp thuận",
                'success',
                'tour_assignment'
            );

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function rejectRequest($requestId, $adminId, $adminResponse = '')
    {
        try {
            $sql = "UPDATE tour_requests 
                    SET status = 'rejected', reviewed_by = :admin_id, 
                        reviewed_date = NOW(), admin_response = :admin_response
                    WHERE id = :request_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':admin_id', $adminId);
            $stmt->bindParam(':admin_response', $adminResponse);
            $stmt->bindParam(':request_id', $requestId);
            $stmt->execute();

            // Lấy thông tin request và tạo thông báo
            $request = $this->getRequestById($requestId);
            $this->createNotification(
                $request['guide_id'],
                'Yêu cầu bị từ chối',
                "Yêu cầu nhận tour {$request['tour_name']} của bạn đã bị từ chối. Lý do: $adminResponse",
                'warning',
                'tour_request'
            );

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // ==========================================
    // TOUR ASSIGNMENTS (Phân công tour)
    // ==========================================

    public function assignTourToGuide($tourId, $guideId, $assignedBy, $notes = '')
    {
        try {
            $this->conn->beginTransaction();

            // Tạo assignment
            $sql = "INSERT INTO tour_assignments (tour_id, guide_id, assigned_by, status, notes) 
                    VALUES (:tour_id, :guide_id, :assigned_by, 'assigned', :notes)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':tour_id', $tourId);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->bindParam(':assigned_by', $assignedBy);
            $stmt->bindParam(':notes', $notes);
            $stmt->execute();

            // Cấp quyền xem tour
            $this->grantTourAccess($guideId, $tourId, 'assigned', $assignedBy);

            // Tạo thông báo cho HDV
            $tour = $this->getTourInfo($tourId);
            $this->createNotification(
                $guideId,
                'Tour mới được phân công',
                "Bạn đã được phân công tour {$tour['name']}. Vui lòng xác nhận chấp nhận/từ chối",
                'info',
                'tour_assignment'
            );

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function getAssignments($status = null)
    {
        try {
            $sql = "SELECT ta.*, t.name as tour_name, t.tour_code,
                           g.full_name as guide_name, g.email as guide_email,
                           a.full_name as assigned_by_name
                    FROM tour_assignments ta
                    INNER JOIN tours t ON ta.tour_id = t.id
                    INNER JOIN users g ON ta.guide_id = g.id
                    INNER JOIN users a ON ta.assigned_by = a.id";
            
            if ($status) {
                $sql .= " WHERE ta.status = :status";
            }
            
            $sql .= " ORDER BY ta.assigned_date DESC";
            
            $stmt = $this->conn->prepare($sql);
            if ($status) {
                $stmt->bindParam(':status', $status);
            }
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    // ==========================================
    // GUIDE TOUR ACCESS (Quyền xem tour)
    // ==========================================

    public function grantTourAccess($guideId, $tourId, $accessType = 'view', $grantedBy = null, $expiresDate = null)
    {
        try {
            $sql = "INSERT INTO guide_tour_access (guide_id, tour_id, access_type, granted_by, expires_date) 
                    VALUES (:guide_id, :tour_id, :access_type, :granted_by, :expires_date)
                    ON DUPLICATE KEY UPDATE 
                    access_type = VALUES(access_type),
                    granted_by = VALUES(granted_by),
                    granted_date = NOW(),
                    expires_date = VALUES(expires_date)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->bindParam(':tour_id', $tourId);
            $stmt->bindParam(':access_type', $accessType);
            $stmt->bindParam(':granted_by', $grantedBy);
            $stmt->bindParam(':expires_date', $expiresDate);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function revokeTourAccess($guideId, $tourId)
    {
        try {
            $sql = "DELETE FROM guide_tour_access 
                    WHERE guide_id = :guide_id AND tour_id = :tour_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':guide_id', $guideId);
            $stmt->bindParam(':tour_id', $tourId);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function getGuideAccess($guideId = null)
    {
        try {
            $sql = "SELECT gta.*, t.name as tour_name, t.tour_code,
                           u.full_name as guide_name, g.full_name as granted_by_name
                    FROM guide_tour_access gta
                    INNER JOIN tours t ON gta.tour_id = t.id
                    INNER JOIN users u ON gta.guide_id = u.id
                    INNER JOIN users g ON gta.granted_by = g.id";
            
            if ($guideId) {
                $sql .= " WHERE gta.guide_id = :guide_id";
            }
            
            $sql .= " ORDER BY gta.granted_date DESC";
            
            $stmt = $this->conn->prepare($sql);
            if ($guideId) {
                $stmt->bindParam(':guide_id', $guideId);
            }
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    // ==========================================
    // NOTIFICATIONS (Thông báo)
    // ==========================================

    public function createNotification($userId, $title, $message, $type = 'info', $relatedType = null, $relatedId = null)
    {
        try {
            $sql = "INSERT INTO notifications (user_id, title, message, type, related_type, related_id) 
                    VALUES (:user_id, :title, :message, :type, :related_type, :related_id)";
            
            $stmt = $this->conn->prepare($sql);
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

    public function getUnreadNotifications($userId)
    {
        try {
            $sql = "SELECT * FROM notifications 
                    WHERE user_id = :user_id AND is_read = FALSE 
                    ORDER BY created_at DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function getUnreadNotificationCount($userId)
    {
        try {
            $sql = "SELECT COUNT(*) FROM notifications 
                    WHERE user_id = :user_id AND is_read = FALSE";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    private function getRequestById($requestId)
    {
        try {
            $sql = "SELECT tr.*, t.name as tour_name, t.tour_code
                    FROM tour_requests tr
                    INNER JOIN tours t ON tr.tour_id = t.id
                    WHERE tr.id = :request_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':request_id', $requestId);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            return null;
        }
    }

    private function getTourInfo($tourId)
    {
        try {
            $sql = "SELECT * FROM tours WHERE id = :tour_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':tour_id', $tourId);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            return null;
        }
    }

    // Lấy danh sách HDV có thể phân công
    public function getAvailableGuides()
    {
        try {
            $sql = "SELECT id, full_name, email FROM users 
                    WHERE role = 'tour_guide' AND status = 'active'
                    ORDER BY full_name";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    // Lấy danh sách tour chưa có HDV
    public function getUnassignedTours()
    {
        try {
            $sql = "SELECT t.* FROM tours t
                    LEFT JOIN tour_assignments ta ON t.id = ta.tour_id AND ta.status IN ('assigned', 'accepted')
                    WHERE ta.id IS NULL
                    ORDER BY t.name";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
}
?>