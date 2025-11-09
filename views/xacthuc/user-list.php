<?php require_once './views/admin/layout/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Quản lý tài khoản</h1>
    <div class="page-actions">
        <a href="index.php?act=register" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo tài khoản mới
        </a>
    </div>
</div>

<div class="content-wrapper">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h3>Danh sách tài khoản</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Thông tin</th>
                            <th>Quyền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <div class="user-avatar">
                                            <img src="<?= BASE_URL ?>uploads/avatars/<?= $user['avatar'] ?>" 
                                                 alt="Avatar" onerror="this.src='<?= BASE_URL ?>assets/images/default-avatar.png'">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="user-info">
                                            <strong><?= htmlspecialchars($user['full_name']) ?></strong><br>
                                            <small>@<?= htmlspecialchars($user['username']) ?></small><br>
                                            <small><?= htmlspecialchars($user['email']) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $user['role'] ?>">
                                            <?php
                                            switch ($user['role']) {
                                                case 'super_admin':
                                                    echo 'Super Admin';
                                                    break;
                                                case 'admin':
                                                    echo 'Admin';
                                                    break;
                                                case 'tour_guide':
                                                    echo 'HDV';
                                                    break;
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $user['status'] == 'active' ? 'success' : 'danger' ?>">
                                            <?= $user['status'] == 'active' ? 'Hoạt động' : 'Bị khóa' ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($user['role'] !== 'super_admin'): ?>
                                                <a href="index.php?act=toggle-user-status&id=<?= $user['id'] ?>" 
                                                   class="btn btn-sm btn-warning" 
                                                   onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái?')">
                                                    <i class="fas fa-<?= $user['status'] == 'active' ? 'lock' : 'unlock' ?>"></i>
                                                </a>
                                                <a href="index.php?act=delete-user&id=<?= $user['id'] ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Bạn có chắc muốn xóa tài khoản này?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Không thể thao tác</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Chưa có tài khoản nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.user-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.user-info strong {
    font-size: 14px;
}

.user-info small {
    color: #666;
    font-size: 12px;
}

.badge-super_admin {
    background: #e74c3c;
}

.badge-admin {
    background: #3498db;
}

.badge-tour_guide {
    background: #2ecc71;
}

.action-buttons {
    display: flex;
    gap: 5px;
}
</style>

<?php require_once './views/admin/layout/footer.php'; ?>