<?php

class TourModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả tours
    public function getAllTours()
    {
        try {
            $sql = 'SELECT * FROM tours ORDER BY id DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Lấy tour theo ID
    public function getTourById($id)
    {
        try {
            $sql = 'SELECT * FROM tours WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Thêm tour mới
    public function insertTour($tour_code, $name, $image, $description, $duration, $is_international)
    {
        try {
            $sql = 'INSERT INTO tours (tour_code, name, image, description, duration, is_international) 
                    VALUES (:tour_code, :name, :image, :description, :duration, :is_international)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':tour_code' => $tour_code,
                ':name' => $name,
                ':image' => $image,
                ':description' => $description,
                ':duration' => $duration,
                ':is_international' => $is_international
            ]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Cập nhật tour
    public function updateTour($id, $tour_code, $name, $image, $description, $duration, $is_international)
    {
        try {
            $sql = 'UPDATE tours SET 
                    tour_code = :tour_code,
                    name = :name,
                    image = :image,
                    description = :description,
                    duration = :duration,
                    is_international = :is_international
                    WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':tour_code' => $tour_code,
                ':name' => $name,
                ':image' => $image,
                ':description' => $description,
                ':duration' => $duration,
                ':is_international' => $is_international
            ]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Xóa tour
    public function deleteTour($id)
    {
        try {
            $sql = 'DELETE FROM tours WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Lấy tour versions theo tour_id
    public function getTourVersions($tour_id)
    {
        try {
            $sql = 'SELECT * FROM tour_versions WHERE tour_id = :tour_id ORDER BY id DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Thêm tour version
    public function insertTourVersion($tour_id, $version_name, $start_date, $end_date, $base_price, $status)
    {
        try {
            $sql = 'INSERT INTO tour_versions (tour_id, version_name, start_date, end_date, base_price, status) 
                    VALUES (:tour_id, :version_name, :start_date, :end_date, :base_price, :status)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':tour_id' => $tour_id,
                ':version_name' => $version_name,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':base_price' => $base_price,
                ':status' => $status
            ]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Lấy thống kê tours
    public function getTourStats()
    {
        try {
            $sql = 'SELECT 
                    COUNT(*) as total_tours,
                    SUM(CASE WHEN is_international = 1 THEN 1 ELSE 0 END) as international_tours,
                    SUM(CASE WHEN is_international = 0 THEN 1 ELSE 0 END) as domestic_tours
                    FROM tours';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Lấy tours được phân công cho HDV
    public function getToursByGuideId($guide_id)
    {
        try {
            $sql = 'SELECT t.*, ta.assigned_date 
                    FROM tours t 
                    INNER JOIN tour_assignments ta ON t.id = ta.tour_id 
                    WHERE ta.guide_id = :guide_id 
                    AND ta.status IN ("assigned", "accepted")
                    ORDER BY ta.assigned_date DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':guide_id' => $guide_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}

