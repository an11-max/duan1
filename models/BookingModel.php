<?php

class BookingModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả bookings
    public function getAllBookings()
    {
        try {
            $sql = 'SELECT b.*, c.name as customer_name, c.phone as customer_phone, c.email as customer_email
                    FROM bookings b
                    LEFT JOIN customers c ON b.customer_id = c.id
                    ORDER BY b.id DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Lấy booking theo ID
    public function getBookingById($id)
    {
        try {
            $sql = 'SELECT b.*, c.name as customer_name, c.phone as customer_phone, c.email as customer_email
                    FROM bookings b
                    LEFT JOIN customers c ON b.customer_id = c.id
                    WHERE b.id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Thêm booking mới
    public function insertBooking($customer_id, $booking_code, $booking_date, $total_amount, $deposit_amount, $status)
    {
        try {
            $sql = 'INSERT INTO bookings (customer_id, booking_code, booking_date, total_amount, deposit_amount, status) 
                    VALUES (:customer_id, :booking_code, :booking_date, :total_amount, :deposit_amount, :status)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':customer_id' => $customer_id,
                ':booking_code' => $booking_code,
                ':booking_date' => $booking_date,
                ':total_amount' => $total_amount,
                ':deposit_amount' => $deposit_amount,
                ':status' => $status
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Cập nhật booking
    public function updateBooking($id, $customer_id, $booking_code, $booking_date, $total_amount, $deposit_amount, $status)
    {
        try {
            $sql = 'UPDATE bookings SET 
                    customer_id = :customer_id,
                    booking_code = :booking_code,
                    booking_date = :booking_date,
                    total_amount = :total_amount,
                    deposit_amount = :deposit_amount,
                    status = :status
                    WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':customer_id' => $customer_id,
                ':booking_code' => $booking_code,
                ':booking_date' => $booking_date,
                ':total_amount' => $total_amount,
                ':deposit_amount' => $deposit_amount,
                ':status' => $status
            ]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Xóa booking
    public function deleteBooking($id)
    {
        try {
            $sql = 'DELETE FROM bookings WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Lấy thống kê bookings
    public function getBookingStats()
    {
        try {
            $sql = 'SELECT 
                    COUNT(*) as total_bookings,
                    SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending_bookings,
                    SUM(CASE WHEN status = "Deposited" THEN 1 ELSE 0 END) as deposited_bookings,
                    SUM(CASE WHEN status = "Completed" THEN 1 ELSE 0 END) as completed_bookings,
                    SUM(CASE WHEN status = "Cancelled" THEN 1 ELSE 0 END) as cancelled_bookings,
                    SUM(total_amount) as total_revenue,
                    SUM(deposit_amount) as total_deposits
                    FROM bookings';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Lấy bookings theo trạng thái
    public function getBookingsByStatus($status)
    {
        try {
            $sql = 'SELECT b.*, c.name as customer_name, c.phone as customer_phone
                    FROM bookings b
                    LEFT JOIN customers c ON b.customer_id = c.id
                    WHERE b.status = :status
                    ORDER BY b.booking_date DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':status' => $status]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}