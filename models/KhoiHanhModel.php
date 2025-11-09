<?php

class KhoiHanhModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả departures
    public function getAllDepartures()
    {
        try {
            $sql = 'SELECT d.*, tv.version_name, t.name as tour_name, t.tour_code,
                    tg.name as guide_name
                    FROM departures d
                    LEFT JOIN tour_versions tv ON d.tour_version_id = tv.id
                    LEFT JOIN tours t ON tv.tour_id = t.id
                    LEFT JOIN tour_guides tg ON d.actual_guide_id = tg.id
                    ORDER BY d.departure_date DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Lấy departure theo ID
    public function getDepartureById($id)
    {
        try {
            $sql = 'SELECT d.*, tv.version_name, t.name as tour_name, t.tour_code,
                    tg.name as guide_name
                    FROM departures d
                    LEFT JOIN tour_versions tv ON d.tour_version_id = tv.id
                    LEFT JOIN tours t ON tv.tour_id = t.id
                    LEFT JOIN tour_guides tg ON d.actual_guide_id = tg.id
                    WHERE d.id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Thêm departure mới
    public function insertDeparture($tour_version_id, $departure_date, $return_date, $actual_guide_id, $status, $min_pax, $max_pax)
    {
        try {
            $sql = 'INSERT INTO departures (tour_version_id, departure_date, return_date, actual_guide_id, status, min_pax, max_pax) 
                    VALUES (:tour_version_id, :departure_date, :return_date, :actual_guide_id, :status, :min_pax, :max_pax)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':tour_version_id' => $tour_version_id,
                ':departure_date' => $departure_date,
                ':return_date' => $return_date,
                ':actual_guide_id' => $actual_guide_id,
                ':status' => $status,
                ':min_pax' => $min_pax,
                ':max_pax' => $max_pax
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Cập nhật departure
    public function updateDeparture($id, $tour_version_id, $departure_date, $return_date, $actual_guide_id, $status, $min_pax, $max_pax)
    {
        try {
            $sql = 'UPDATE departures SET 
                    tour_version_id = :tour_version_id,
                    departure_date = :departure_date,
                    return_date = :return_date,
                    actual_guide_id = :actual_guide_id,
                    status = :status,
                    min_pax = :min_pax,
                    max_pax = :max_pax
                    WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':tour_version_id' => $tour_version_id,
                ':departure_date' => $departure_date,
                ':return_date' => $return_date,
                ':actual_guide_id' => $actual_guide_id,
                ':status' => $status,
                ':min_pax' => $min_pax,
                ':max_pax' => $max_pax
            ]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Xóa departure
    public function deleteDeparture($id)
    {
        try {
            $sql = 'DELETE FROM departures WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Lấy departures theo trạng thái
    public function getDeparturesByStatus($status)
    {
        try {
            $sql = 'SELECT d.*, tv.version_name, t.name as tour_name, t.tour_code
                    FROM departures d
                    LEFT JOIN tour_versions tv ON d.tour_version_id = tv.id
                    LEFT JOIN tours t ON tv.tour_id = t.id
                    WHERE d.status = :status
                    ORDER BY d.departure_date DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':status' => $status]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Lấy thống kê departures
    public function getDepartureStats()
    {
        try {
            $sql = 'SELECT 
                    COUNT(*) as total_departures,
                    SUM(CASE WHEN status = "Scheduled" THEN 1 ELSE 0 END) as scheduled_departures,
                    SUM(CASE WHEN status = "Completed" THEN 1 ELSE 0 END) as completed_departures,
                    SUM(CASE WHEN status = "Cancelled" THEN 1 ELSE 0 END) as cancelled_departures
                    FROM departures';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}