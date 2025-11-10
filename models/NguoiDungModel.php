<?php
class NguoiDungModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Đăng nhập với tăng cường bảo mật
    public function login($username, $password)
    {
        try {
            // Kiểm tra số lần đăng nhập thất bại
            if ($this->isAccountLocked($username)) {
                return ['error' => 'Tài khoản đã bị khóa do đăng nhập sai quá nhiều lần. Vui lòng thử lại sau 15 phút.'];
            }

            $sql = "SELECT * FROM users WHERE username = :username AND status = 'active'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Reset failed attempts khi đăng nhập thành công
                $this->resetFailedAttempts($username);
                // Cập nhật last login
                $this->updateLastLogin($user['id']);
                return $user;
            } else {
                // Tăng số lần đăng nhập thất bại
                $this->incrementFailedAttempts($username);
                return false;
            }
        } catch (Exception $e) {
            error_log("Lỗi đăng nhập: " . $e->getMessage());
            return false;
        }
    }

    // Kiểm tra tài khoản có bị khóa không
    private function isAccountLocked($username)
    {
        try {
            $sql = "SELECT failed_attempts, last_failed_attempt FROM users WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user && $user['failed_attempts'] >= 5) {
                $lastAttempt = strtotime($user['last_failed_attempt']);
                $now = time();
                // Khóa 15 phút
                if (($now - $lastAttempt) < 900) {
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    // Tăng số lần đăng nhập thất bại
    private function incrementFailedAttempts($username)
    {
        try {
            $sql = "UPDATE users SET failed_attempts = failed_attempts + 1, last_failed_attempt = NOW() WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Lỗi cập nhật failed attempts: " . $e->getMessage());
        }
    }

    // Reset số lần đăng nhập thất bại
    private function resetFailedAttempts($username)
    {
        try {
            $sql = "UPDATE users SET failed_attempts = 0, last_failed_attempt = NULL WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Lỗi reset failed attempts: " . $e->getMessage());
        }
    }

    // Cập nhật thời gian đăng nhập cuối
    private function updateLastLogin($userId)
    {
        try {
            $sql = "UPDATE users SET last_login = NOW() WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Lỗi cập nhật last login: " . $e->getMessage());
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

    // Lấy danh sách users với phân trang và tìm kiếm
    public function getAllUsers($page = 1, $limit = 10, $search = '', $role = '', $status = '')
    {
        try {
            $offset = ($page - 1) * $limit;
            
            $sql = "SELECT id, username, email, full_name, role, status, created_at, avatar FROM users WHERE 1=1";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (username LIKE :search OR email LIKE :search OR full_name LIKE :search)";
                $params[':search'] = "%$search%";
            }
            
            if (!empty($role)) {
                $sql .= " AND role = :role";
                $params[':role'] = $role;
            }
            
            if (!empty($status)) {
                $sql .= " AND status = :status";
                $params[':status'] = $status;
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
            
            $stmt = $this->conn->prepare($sql);
            
            // Bind parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Lỗi lấy danh sách users: " . $e->getMessage());
            return [];
        }
    }

    // Đếm tổng số users
    public function countUsers($search = '', $role = '', $status = '')
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE 1=1";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (username LIKE :search OR email LIKE :search OR full_name LIKE :search)";
                $params[':search'] = "%$search%";
            }
            
            if (!empty($role)) {
                $sql .= " AND role = :role";
                $params[':role'] = $role;
            }
            
            if (!empty($status)) {
                $sql .= " AND status = :status";
                $params[':status'] = $status;
            }
            
            $stmt = $this->conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'];
        } catch (Exception $e) {
            return 0;
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