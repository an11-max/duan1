<?php
class NguoiDungModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Đăng nhập
    public function login($username, $password)
    {
        try {
            $sql = "SELECT * FROM users WHERE username = :username AND status = 'active'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    // Lấy thông tin user theo ID
    public function getUserById($id)
    {
        try {
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }

    // Tạo user mới (chỉ super admin được phép)
    public function createUser($data)
    {
        try {
            $sql = "INSERT INTO users (username, password, email, full_name, role, avatar) 
                    VALUES (:username, :password, :email, :full_name, :role, :avatar)";
            $stmt = $this->conn->prepare($sql);
            
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':full_name', $data['full_name']);
            $stmt->bindParam(':role', $data['role']);
            $stmt->bindParam(':avatar', $data['avatar']);
            
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Lấy danh sách users (chỉ super admin)
    public function getAllUsers()
    {
        try {
            $sql = "SELECT id, username, email, full_name, role, status, created_at, avatar FROM users ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    // Cập nhật user
    public function updateUser($id, $data)
    {
        try {
            $sql = "UPDATE users SET email = :email, full_name = :full_name";
            
            if (!empty($data['password'])) {
                $sql .= ", password = :password";
            }
            
            if (!empty($data['avatar'])) {
                $sql .= ", avatar = :avatar";
            }
            
            $sql .= " WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':full_name', $data['full_name']);
            
            if (!empty($data['password'])) {
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
                $stmt->bindParam(':password', $hashedPassword);
            }
            
            if (!empty($data['avatar'])) {
                $stmt->bindParam(':avatar', $data['avatar']);
            }
            
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Kiểm tra username đã tồn tại
    public function usernameExists($username, $excludeId = null)
    {
        try {
            $sql = "SELECT id FROM users WHERE username = :username";
            if ($excludeId) {
                $sql .= " AND id != :excludeId";
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            if ($excludeId) {
                $stmt->bindParam(':excludeId', $excludeId);
            }
            
            $stmt->execute();
            return $stmt->fetch() ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    // Kiểm tra email đã tồn tại
    public function emailExists($email, $excludeId = null)
    {
        try {
            $sql = "SELECT id FROM users WHERE email = :email";
            if ($excludeId) {
                $sql .= " AND id != :excludeId";
            }
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            if ($excludeId) {
                $stmt->bindParam(':excludeId', $excludeId);
            }
            
            $stmt->execute();
            return $stmt->fetch() ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    // Xóa user (chỉ super admin)
    public function deleteUser($id)
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id AND role != 'super_admin'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Thay đổi trạng thái user
    public function toggleUserStatus($id)
    {
        try {
            $sql = "UPDATE users SET status = CASE 
                        WHEN status = 'active' THEN 'inactive' 
                        ELSE 'active' 
                    END 
                    WHERE id = :id AND role != 'super_admin'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>