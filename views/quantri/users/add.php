<?php require_once './views/quantri/layout/header.php'; ?>

<div class="user-management-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center p-4">
            <h1 class="mb-0">
                <i class="fas fa-user-plus me-3"></i>Thêm Tài khoản Mới
            </h1>
            <a href="<?= BASE_URL ?>?act=admin-users" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin Tài khoản</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>?act=admin-user-create" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label required">Tên đăng nhập</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="username" name="username" 
                                               value="<?= $_POST['username'] ?? '' ?>" required>
                                    </div>
                                    <div class="form-text">Tên đăng nhập phải duy nhất và không thể thay đổi sau khi tạo</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label required">Mật khẩu</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Mật khẩu tối thiểu 6 ký tự</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label required">Họ và tên</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control" id="full_name" name="full_name" 
                                               value="<?= $_POST['full_name'] ?? '' ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label required">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?= $_POST['email'] ?? '' ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label required">Vai trò</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        <select class="form-select" id="role" name="role" required>
                                            <option value="">-- Chọn vai trò --</option>
                                            <option value="admin" <?= ($_POST['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                            <option value="tour_guide" <?= ($_POST['role'] ?? '') === 'tour_guide' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                                        </select>
                                    </div>
                                    <div class="form-text">
                                        <small>
                                            <strong>Admin:</strong> Quản lý tour, booking, khách hàng<br>
                                            <strong>Hướng dẫn viên:</strong> Xem và nhận tour, quản lý lịch trình
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Biểu tượng</label>
                                    <div class="form-text mb-2">Icon sẽ được tự động chọn dựa trên vai trò</div>
                                    <div class="icon-preview p-3 text-center border rounded">
                                        <i class="fas fa-user text-secondary" style="font-size: 48px;" id="roleIcon"></i>
                                        <div class="mt-2 text-muted">Icon sẽ thay đổi theo vai trò được chọn</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="text-end">
                            <a href="<?= BASE_URL ?>?act=admin-users" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Tạo Tài khoản
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = event.target.querySelector('i') || event.target;
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Update icon when role is selected
document.getElementById('role').addEventListener('change', function() {
    const roleIcon = document.getElementById('roleIcon');
    const role = this.value;
    
    // Remove existing classes
    roleIcon.className = '';
    
    // Add new classes based on role
    switch(role) {
        case 'super_admin':
            roleIcon.className = 'fas fa-user-shield text-danger';
            break;
        case 'admin':
            roleIcon.className = 'fas fa-user-cog text-primary';
            break;
        case 'tour_guide':
            roleIcon.className = 'fas fa-user-tie text-success';
            break;
        default:
            roleIcon.className = 'fas fa-user text-secondary';
    }
    roleIcon.style.fontSize = '48px';
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const username = document.getElementById('username').value;
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Mật khẩu phải có ít nhất 6 ký tự!');
        return;
    }
    
    if (username.length < 3) {
        e.preventDefault();
        alert('Tên đăng nhập phải có ít nhất 3 ký tự!');
        return;
    }
});
</script>

<?php require_once './views/quantri/layout/footer.php'; ?>