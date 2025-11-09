<?php

class HuongDanVienModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả tour guides
    public function getAllTourGuides()
    {
        try {
            $sql = 'SELECT * FROM tour_guides ORDER BY id DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Lấy tour guide theo ID
    public function getTourGuideById($id)
    {
        try {
            $sql = 'SELECT * FROM tour_guides WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Thêm tour guide mới
    public function insertTourGuide($user_id, $name, $phone, $email, $license_info, $image, $status)
    {
        try {
            $sql = 'INSERT INTO tour_guides (user_id, name, phone, email, license_info, image, status) 
                    VALUES (:user_id, :name, :phone, :email, :license_info, :image, :status)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $user_id,
                ':name' => $name,
                ':phone' => $phone,
                ':email' => $email,
                ':license_info' => $license_info,
                ':image' => $image,
                ':status' => $status
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Cập nhật tour guide
    public function updateTourGuide($id, $user_id, $name, $phone, $email, $license_info, $image, $status)
    {
        try {
            $sql = 'UPDATE tour_guides SET 
                    user_id = :user_id,
                    name = :name,
                    phone = :phone,
                    email = :email,
                    license_info = :license_info,
                    image = :image,
                    status = :status
                    WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':user_id' => $user_id,
                ':name' => $name,
                ':phone' => $phone,
                ':email' => $email,
                ':license_info' => $license_info,
                ':image' => $image,
                ':status' => $status
            ]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Xóa tour guide
    public function deleteTourGuide($id)
    {
        try {
            $sql = 'DELETE FROM tour_guides WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Lấy tour guides theo trạng thái
    public function getTourGuidesByStatus($status)
    {
        try {
            $sql = 'SELECT * FROM tour_guides WHERE status = :status ORDER BY name ASC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':status' => $status]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Lấy tour guides available
    public function getAvailableTourGuides()
    {
        try {
            $sql = 'SELECT * FROM tour_guides WHERE status = "Available" ORDER BY name ASC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Alias method cho getAvailableTourGuides
    public function getAvailableGuides()
    {
        return $this->getAvailableTourGuides();
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}