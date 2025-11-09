<?php

class PhanCongDatTourModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Gửi booking cho HDV (Admin/Super Admin)
    public function assignBookingToGuide($booking_id, $guide_id, $assigned_by, $notes = '', $deadline = null, $priority = 'medium')
    {
        try {
            $sql = 'INSERT INTO booking_assignments (booking_id, guide_id, assigned_by, notes, deadline, priority) 
                    VALUES (:booking_id, :guide_id, :assigned_by, :notes, :deadline, :priority)';
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':booking_id' => $booking_id,
                ':guide_id' => $guide_id,
                ':assigned_by' => $assigned_by,
                ':notes' => $notes,
                ':deadline' => $deadline,
                ':priority' => $priority
            ]);
            
            if ($result) {
                $assignment_id = $this->conn->lastInsertId();
                
                // Tạo thông báo cho HDV
                $this->createNotificationForGuide($assignment_id, $booking_id, $guide_id, $assigned_by);
                
                return $assignment_id;
            }
            return false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Tạo thông báo cho HDV khi có booking mới được giao
    private function createNotificationForGuide($assignment_id, $booking_id, $guide_id, $assigned_by)
    {
        try {
            // Lấy thông tin booking
            $booking = $this->getBookingInfo($booking_id);
            $assigner = $this->getUserInfo($assigned_by);
            
            $title = 'Booking mới cần xác nhận';
            $message = "Bạn có booking mới cần xác nhận: {$booking['booking_code']} - {$booking['customer_name']}. Được giao bởi {$assigner['name']}.";
            
            $sql = 'INSERT INTO notifications (user_id, title, message, type, related_type, related_id, booking_id, priority) 
                    VALUES (:user_id, :title, :message, :type, :related_type, :related_id, :booking_id, :priority)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $guide_id,
                ':title' => $title,
                ':message' => $message,
                ':type' => 'booking_assignment',
                ':related_type' => 'booking_assignment',
                ':related_id' => $assignment_id,
                ':booking_id' => $booking_id,
                ':priority' => 'high'
            ]);
        } catch (Exception $e) {
            echo "Error creating notification: " . $e->getMessage();
        }
    }

    // HDV phản hồi booking assignment
    public function respondToBookingAssignment($assignment_id, $guide_id, $status, $response = '')
    {
        try {
            $sql = 'UPDATE booking_assignments 
                    SET status = :status, guide_response = :response, response_date = NOW() 
                    WHERE id = :assignment_id AND guide_id = :guide_id';
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':status' => $status,
                ':response' => $response,
                ':assignment_id' => $assignment_id,
                ':guide_id' => $guide_id
            ]);
            
            if ($result) {
                // Tạo thông báo cho Admin
                $this->createNotificationForAdmin($assignment_id, $status, $response);
                return true;
            }
            return false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Tạo thông báo cho Admin khi HDV phản hồi
    private function createNotificationForAdmin($assignment_id, $status, $response)
    {
        try {
            // Lấy thông tin assignment
            $assignment = $this->getAssignmentInfo($assignment_id);
            
            $status_text = [
                'accepted' => 'đã chấp nhận',
                'declined' => 'đã từ chối'
            ];
            
            $title = 'HDV phản hồi booking assignment';
            $message = "HDV {$assignment['guide_name']} {$status_text[$status]} booking {$assignment['booking_code']}";
            if ($response) {
                $message .= " với lý do: {$response}";
            }
            
            $sql = 'INSERT INTO notifications (user_id, title, message, type, related_type, related_id, booking_id, priority) 
                    VALUES (:user_id, :title, :message, :type, :related_type, :related_id, :booking_id, :priority)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $assignment['assigned_by'],
                ':title' => $title,
                ':message' => $message,
                ':type' => 'booking_response',
                ':related_type' => 'booking_response',
                ':related_id' => $assignment_id,
                ':booking_id' => $assignment['booking_id'],
                ':priority' => 'medium'
            ]);
        } catch (Exception $e) {
            echo "Error creating admin notification: " . $e->getMessage();
        }
    }

    // Lấy danh sách booking assignments cho HDV
    public function getBookingAssignmentsForGuide($guide_id, $status = null)
    {
        try {
            $where_clause = 'WHERE ba.guide_id = :guide_id';
            $params = [':guide_id' => $guide_id];
            
            if ($status) {
                $where_clause .= ' AND ba.status = :status';
                $params[':status'] = $status;
            }
            
            $sql = "SELECT ba.*, 
                           b.booking_code, b.booking_date, b.total_amount, b.status as booking_status,
                           c.name as customer_name, c.phone as customer_phone, c.email as customer_email,
                           u.full_name as assigned_by_name,
                           CASE 
                               WHEN ba.deadline < NOW() AND ba.status = 'pending' THEN 'expired'
                               ELSE ba.status 
                           END as assignment_status
                    FROM booking_assignments ba
                    JOIN bookings b ON ba.booking_id = b.id
                    JOIN customers c ON b.customer_id = c.id
                    JOIN users u ON ba.assigned_by = u.id
                    {$where_clause}
                    ORDER BY ba.assigned_date DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Lấy danh sách booking assignments cho Admin
    public function getBookingAssignmentsByAdmin($assigned_by = null)
    {
        try {
            $where_clause = '';
            $params = [];
            
            if ($assigned_by) {
                $where_clause = 'WHERE ba.assigned_by = :assigned_by';
                $params[':assigned_by'] = $assigned_by;
            }
            
            $sql = "SELECT ba.*, 
                           b.booking_code, b.booking_date, b.total_amount, b.status as booking_status,
                           c.name as customer_name, c.phone as customer_phone,
                           g.full_name as guide_name, tg.phone as guide_phone,
                           u.full_name as assigned_by_name
                    FROM booking_assignments ba
                    JOIN bookings b ON ba.booking_id = b.id
                    JOIN customers c ON b.customer_id = c.id
                    JOIN users g ON ba.guide_id = g.id
                    LEFT JOIN tour_guides tg ON g.id = tg.user_id
                    JOIN users u ON ba.assigned_by = u.id
                    {$where_clause}
                    ORDER BY ba.assigned_date DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Lấy thông tin booking
    private function getBookingInfo($booking_id)
    {
        $sql = 'SELECT b.*, c.name as customer_name 
                FROM bookings b 
                JOIN customers c ON b.customer_id = c.id 
                WHERE b.id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $booking_id]);
        return $stmt->fetch();
    }

    // Lấy thông tin user
    private function getUserInfo($user_id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $user_id]);
        return $stmt->fetch();
    }

    // Lấy thông tin assignment
    private function getAssignmentInfo($assignment_id)
    {
        $sql = 'SELECT ba.*, b.booking_code, g.full_name as guide_name 
                FROM booking_assignments ba
                JOIN bookings b ON ba.booking_id = b.id
                JOIN users g ON ba.guide_id = g.id
                WHERE ba.id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $assignment_id]);
        return $stmt->fetch();
    }

    // Lấy thống kê booking assignments
    public function getBookingAssignmentStats($user_id = null, $role = null)
    {
        try {
            $where_clause = '';
            $params = [];
            
            if ($role === 'tour_guide') {
                $where_clause = 'WHERE ba.guide_id = :user_id';
                $params[':user_id'] = $user_id;
            } elseif ($user_id && $role !== 'super_admin') {
                $where_clause = 'WHERE ba.assigned_by = :user_id';
                $params[':user_id'] = $user_id;
            }
            
            $sql = "SELECT 
                        COUNT(*) as total_assignments,
                        SUM(CASE WHEN ba.status = 'pending' THEN 1 ELSE 0 END) as pending_assignments,
                        SUM(CASE WHEN ba.status = 'accepted' THEN 1 ELSE 0 END) as accepted_assignments,
                        SUM(CASE WHEN ba.status = 'declined' THEN 1 ELSE 0 END) as declined_assignments,
                        SUM(CASE WHEN ba.deadline < NOW() AND ba.status = 'pending' THEN 1 ELSE 0 END) as expired_assignments
                    FROM booking_assignments ba
                    {$where_clause}";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Lấy booking assignments theo ID
    public function getBookingAssignmentById($assignment_id)
    {
        try {
            $sql = "SELECT ba.*, 
                           b.booking_code, b.booking_date, b.total_amount, b.status as booking_status,
                           c.name as customer_name, c.phone as customer_phone, c.email as customer_email,
                           g.full_name as guide_name, tg.phone as guide_phone, tg.email as guide_email,
                           u.full_name as assigned_by_name
                    FROM booking_assignments ba
                    JOIN bookings b ON ba.booking_id = b.id
                    JOIN customers c ON b.customer_id = c.id
                    JOIN users g ON ba.guide_id = g.id
                    LEFT JOIN tour_guides tg ON g.id = tg.user_id
                    JOIN users u ON ba.assigned_by = u.id
                    WHERE ba.id = :assignment_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':assignment_id' => $assignment_id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    // Hủy booking assignment
    public function cancelBookingAssignment($assignment_id, $cancelled_by)
    {
        try {
            $sql = 'UPDATE booking_assignments 
                    SET status = "cancelled", response_date = NOW() 
                    WHERE id = :assignment_id';
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([':assignment_id' => $assignment_id]);
            
            if ($result) {
                // Tạo thông báo hủy
                $this->createCancellationNotification($assignment_id, $cancelled_by);
                return true;
            }
            return false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Tạo thông báo hủy
    private function createCancellationNotification($assignment_id, $cancelled_by)
    {
        try {
            $assignment = $this->getAssignmentInfo($assignment_id);
            $canceller = $this->getUserInfo($cancelled_by);
            
            $title = 'Booking assignment đã bị hủy';
            $message = "Booking assignment {$assignment['booking_code']} đã bị hủy bởi {$canceller['full_name']}";
            
            // Thông báo cho HDV
            $sql = 'INSERT INTO notifications (user_id, title, message, type, related_type, related_id, booking_id, priority) 
                    VALUES (:user_id, :title, :message, :type, :related_type, :related_id, :booking_id, :priority)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $assignment['guide_id'],
                ':title' => $title,
                ':message' => $message,
                ':type' => 'booking_assignment',
                ':related_type' => 'booking_assignment',
                ':related_id' => $assignment_id,
                ':booking_id' => $assignment['booking_id'],
                ':priority' => 'medium'
            ]);
        } catch (Exception $e) {
            echo "Error creating cancellation notification: " . $e->getMessage();
        }
    }
}