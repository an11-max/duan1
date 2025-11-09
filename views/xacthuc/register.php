<?php require_once './views/admin/layout/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Tạo tài khoản mới</h1>
    <div class="page-breadcrumb">
        <a href="<?= BASE_URL ?>?act=user-list">Quản lý tài khoản</a> / Tạo mới
    </div>
</div>

<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3>Thông tin tài khoản</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <div><?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>?act=register" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" id="username" name="username" class="form-control" 
                                   value="<?= htmlspecialchars($data['username'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="full_name">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" id="full_name" name="full_name" class="form-control" 
                           value="<?= htmlspecialchars($data['full_name'] ?? '') ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            <small class="form-text text-muted">Tối thiểu 6 ký tự</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="confirm_password">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">Quyền <span class="text-danger">*</span></label>
                            <select id="role" name="role" class="form-control" required>
                                <option value="">-- Chọn quyền --</option>
                                <option value="admin" <?= ($data['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="tour_guide" <?= ($data['role'] ?? '') == 'tour_guide' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="avatar">Ảnh đại diện</label>
                            <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Chấp nhận JPG, PNG, GIF. Tối đa 2MB</small>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Tạo tài khoản
                    </button>
                    <a href="<?= BASE_URL ?>?act=user-list" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once './views/admin/layout/footer.php'; ?>