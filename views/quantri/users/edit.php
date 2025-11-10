<?php require_once './views/quantri/layout/header.php'; ?>

<div class="user-management-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center p-4">
            <h1 class="mb-0">
                <i class="fas fa-user-edit me-3"></i>Chỉnh sửa Tài khoản
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Thông tin Tài khoản</h5>
                    <div class="user-info">
                        <span class="badge bg-secondary">ID: <?= $user['id'] ?></span>
                        <span class="badge bg-info ms-2">
                            <?= $user['role'] === 'super_admin' ? 'Super Admin' : 
                                ($user['role'] === 'admin' ? 'Admin' : 'Hướng dẫn viên') ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>?act=admin-user-update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Tên đăng nhập</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="username" 
                                               value="<?= htmlspecialchars($user['username']) ?>" readonly>
                                    </div>
                                    <div class="form-text text-muted">Tên đăng nhập không thể thay đổi</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu mới</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Để trống nếu không muốn thay đổi mật khẩu</div>
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
                                               value="<?= htmlspecialchars($user['full_name']) ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label required">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?= htmlspecialchars($user['email']) ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Vai trò</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        <select class="form-select" id="role" name="role" disabled>
                                            <option value="super_admin" <?= $user['role'] === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                            <option value="tour_guide" <?= $user['role'] === 'tour_guide' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                                        </select>
                                    </div>
                                    <div class="form-text text-muted">Vai trò không thể thay đổi</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-<?= $user['status'] === 'active' ? 'check-circle text-success' : 'pause-circle text-secondary' ?>"></i>
                                        </span>
                                        <input type="text" class="form-control" 
                                               value="<?= $user['status'] === 'active' ? 'Hoạt động' : 'Tạm khóa' ?>" readonly>
                                    </div>
                                    <div class="form-text text-muted">
                                        Sử dụng nút Khóa/Mở khóa ở danh sách để thay đổi trạng thái
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Biểu tượng hiện tại</label>
                                    <div class="current-icon p-3 text-center border rounded">
                                        <?php
                                        $iconClass = '';
                                        $iconColor = '';
                                        switch($user['role']) {
                                            case 'super_admin':
                                                $iconClass = 'fas fa-user-shield';
                                                $iconColor = 'text-danger';
                                                break;
                                            case 'admin':
                                                $iconClass = 'fas fa-user-cog';
                                                $iconColor = 'text-primary';
                                                break;
                                            case 'tour_guide':
                                                $iconClass = 'fas fa-user-tie';
                                                $iconColor = 'text-success';
                                                break;
                                            default:
                                                $iconClass = 'fas fa-user';
                                                $iconColor = 'text-secondary';
                                        }
                                        ?>
                                        <i class="<?= $iconClass ?> <?= $iconColor ?>" style="font-size: 48px;"></i>
                                        <div class="mt-2 text-muted">Icon dựa trên vai trò hiện tại</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Icon mới (nếu thay đổi vai trò)</label>
                                    <div class="new-icon p-3 text-center border rounded">
                                        <i class="fas fa-user text-secondary" style="font-size: 48px;" id="newRoleIcon"></i>
                                        <div class="mt-2 text-muted">Icon sẽ thay đổi khi chọn vai trò mới</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="text-muted">
                                    <small>
                                        <i class="fas fa-info-circle me-1"></i>
                                        Ngày tạo: <?= date('d/m/Y H:i', strtotime($user['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="<?= BASE_URL ?>?act=admin-users" class="btn btn-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Hủy
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Cập nhật
                                </button>
                            </div>
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

// Update icon when role is changed
document.getElementById('role').addEventListener('change', function() {
    const newRoleIcon = document.getElementById('newRoleIcon');
    const role = this.value;
    
    // Remove existing classes
    newRoleIcon.className = '';
    
    // Add new classes based on role
    switch(role) {
        case 'super_admin':
            newRoleIcon.className = 'fas fa-user-shield text-danger';
            break;
        case 'admin':
            newRoleIcon.className = 'fas fa-user-cog text-primary';
            break;
        case 'tour_guide':
            newRoleIcon.className = 'fas fa-user-tie text-success';
            break;
        default:
            newRoleIcon.className = 'fas fa-user text-secondary';
    }
    newRoleIcon.style.fontSize = '48px';
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    
    if (password.length > 0 && password.length < 6) {
        e.preventDefault();
        alert('Mật khẩu phải có ít nhất 6 ký tự!');
        return;
    }
});
</script>

<?php require_once './views/quantri/layout/footer.php'; ?>