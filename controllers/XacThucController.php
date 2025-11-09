<?php

class AuthController
{
    public $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Hiển thị form đăng nhập
    public function showLogin()
    {
        // Nếu đã đăng nhập thì chuyển về dashboard
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?act=admin-dashboard');
            exit;
        }
        
        require_once './views/auth/login.php';
    }

    // Xử lý đăng nhập
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $errors = [];

            // Validate
            if (empty($username)) {
                $errors[] = 'Vui lòng nhập tên đăng nhập';
            }
            if (empty($password)) {
                $errors[] = 'Vui lòng nhập mật khẩu';
            }

            if (empty($errors)) {
                $user = $this->userModel->login($username, $password);
                
                if ($user) {
                    // Lưu thông tin user vào session
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'full_name' => $user['full_name'],
                        'avatar' => $user['avatar'],
                        'role' => $user['role']
                    ];
                    
                    header('Location: ' . BASE_URL . '?act=admin-dashboard');
                    exit;
                } else {
                    $errors[] = 'Tên đăng nhập hoặc mật khẩu không đúng';
                }
            }

            // Nếu có lỗi, hiển thị lại form với thông báo lỗi
            require_once './views/auth/login.php';
        } else {
            $this->showLogin();
        }
    }

    // Đăng xuất
    public function logout()
    {
        // Kiểm tra headers chưa được send
        if (headers_sent()) {
            echo "<script>window.location.href = 'index.php?act=login';</script>";
            exit;
        }
        
        // Xóa tất cả session variables
        $_SESSION = array();
        
        // Xóa session cookie nếu có
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy session
        session_destroy();
        
        // Redirect về trang login - sử dụng relative URL
        header('Location: index.php?act=login');
        exit;
    }

    // Hiển thị form đăng ký (chỉ super admin)
    public function showRegister()
    {
        $this->checkSuperAdminPermission();
        require_once './views/auth/register.php';
    }

    // Xử lý đăng ký (chỉ super admin)
    public function register()
    {
        $this->checkSuperAdminPermission();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => trim($_POST['username'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'email' => trim($_POST['email'] ?? ''),
                'full_name' => trim($_POST['full_name'] ?? ''),
                'role' => $_POST['role'] ?? '',
                'avatar' => 'default-avatar.png'
            ];

            $errors = $this->validateRegisterData($data);

            // Xử lý upload avatar nếu có
            if (!empty($_FILES['avatar']['name'])) {
                $uploadResult = $this->uploadAvatar($_FILES['avatar']);
                if ($uploadResult['success']) {
                    $data['avatar'] = $uploadResult['filename'];
                } else {
                    $errors[] = $uploadResult['error'];
                }
            }

            if (empty($errors)) {
                if ($this->userModel->createUser($data)) {
                    $_SESSION['success'] = 'Tạo tài khoản thành công!';
                    header('Location: ' . BASE_URL . '?act=user-list');
                    exit;
                } else {
                    $errors[] = 'Có lỗi xảy ra khi tạo tài khoản';
                }
            }

            require_once './views/auth/register.php';
        } else {
            $this->showRegister();
        }
    }

    // Validate dữ liệu đăng ký
    private function validateRegisterData($data)
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors[] = 'Vui lòng nhập tên đăng nhập';
        } elseif (strlen($data['username']) < 3) {
            $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
        } elseif ($this->userModel->usernameExists($data['username'])) {
            $errors[] = 'Tên đăng nhập đã tồn tại';
        }

        if (empty($data['password'])) {
            $errors[] = 'Vui lòng nhập mật khẩu';
        } elseif (strlen($data['password']) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }

        if ($data['password'] !== $data['confirm_password']) {
            $errors[] = 'Xác nhận mật khẩu không khớp';
        }

        if (empty($data['email'])) {
            $errors[] = 'Vui lòng nhập email';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        } elseif ($this->userModel->emailExists($data['email'])) {
            $errors[] = 'Email đã tồn tại';
        }

        if (empty($data['full_name'])) {
            $errors[] = 'Vui lòng nhập họ tên';
        }

        if (empty($data['role']) || !in_array($data['role'], ['admin', 'tour_guide'])) {
            $errors[] = 'Vui lòng chọn quyền hợp lệ';
        }

        return $errors;
    }

    // Upload avatar
    private function uploadAvatar($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'error' => 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF)'];
        }

        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'File ảnh không được vượt quá 2MB'];
        }

        $uploadDir = './uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = uniqid() . '_' . $file['name'];
        $uploadPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'error' => 'Không thể upload file'];
        }
    }

    // Kiểm tra quyền super admin
    private function checkSuperAdminPermission()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'super_admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này';
            header('Location: index.php?act=admin-dashboard');
            exit;
        }
    }

    // Kiểm tra đăng nhập
    public static function checkLogin()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?act=login');
            exit;
        }
    }

    // Kiểm tra quyền admin (admin hoặc super_admin)
    public static function checkAdminPermission()
    {
        self::checkLogin();
        if (!in_array($_SESSION['user']['role'], ['admin', 'super_admin'])) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này';
            header('Location: ' . BASE_URL . '?act=admin-dashboard');
            exit;
        }
    }

    // Kiểm tra quyền super admin
    public static function checkSuperAdminPermissionStatic()
    {
        self::checkLogin();
        if ($_SESSION['user']['role'] !== 'super_admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này';
            header('Location: ' . BASE_URL . '?act=admin-dashboard');
            exit;
        }
    }

    // Danh sách users (chỉ super admin)
    public function userList()
    {
        $this->checkSuperAdminPermission();
        $users = $this->userModel->getAllUsers();
        require_once './views/auth/user-list.php';
    }

    // Xóa user (chỉ super admin)
    public function deleteUser()
    {
        $this->checkSuperAdminPermission();
        
        $id = $_GET['id'] ?? 0;
        if ($this->userModel->deleteUser($id)) {
            $_SESSION['success'] = 'Xóa tài khoản thành công!';
        } else {
            $_SESSION['error'] = 'Không thể xóa tài khoản này!';
        }
        
        header('Location: index.php?act=user-list');
        exit;
    }

    // Thay đổi trạng thái user
    public function toggleUserStatus()
    {
        $this->checkSuperAdminPermission();
        
        $id = $_GET['id'] ?? 0;
        if ($this->userModel->toggleUserStatus($id)) {
            $_SESSION['success'] = 'Cập nhật trạng thái thành công!';
        } else {
            $_SESSION['error'] = 'Không thể cập nhật trạng thái!';
        }
        
        header('Location: index.php?act=user-list');
        exit;
    }
}
?>