<?php require_once './views/quantri/layout/header.php'; ?>

<div class="user-management-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center p-4">
            <h1 class="mb-0">
                <i class="fas fa-users me-3"></i>Quản lý Tài khoản
            </h1>
            <a href="<?= BASE_URL ?>?act=admin-user-add" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm Tài khoản
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Danh sách Tài khoản</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control form-control-sm" id="searchInput" 
                               placeholder="Tìm kiếm tài khoản..." style="width: 250px;">
                        <select class="form-select form-select-sm" id="roleFilter" style="width: 150px;">
                            <option value="">Tất cả vai trò</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="admin">Admin</option>
                            <option value="tour_guide">Hướng dẫn viên</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table user-table" id="usersTable">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                    <th><i class="fas fa-user-circle me-2"></i>Avatar</th>
                                    <th><i class="fas fa-user me-2"></i>Tên đăng nhập</th>
                                    <th><i class="fas fa-id-card me-2"></i>Họ tên</th>
                                    <th><i class="fas fa-envelope me-2"></i>Email</th>
                                    <th><i class="fas fa-user-tag me-2"></i>Vai trò</th>
                                    <th><i class="fas fa-toggle-on me-2"></i>Trạng thái</th>
                                    <th><i class="fas fa-calendar me-2"></i>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td class="fw-bold"><?= $user['id'] ?></td>
                                            <td>
                                                <div class="user-icon">
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
                                                    <i class="<?= $iconClass ?> <?= $iconColor ?>" style="font-size: 24px;"></i>
                                                </div>
                                            </td>
                                            <td class="fw-semibold"><?= htmlspecialchars($user['username']) ?></td>
                                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td>
                                                <?php
                                                $roleClass = '';
                                                $roleText = '';
                                                switch($user['role']) {
                                                    case 'super_admin':
                                                        $roleClass = 'bg-danger';
                                                        $roleText = 'Super Admin';
                                                        break;
                                                    case 'admin':
                                                        $roleClass = 'bg-primary';
                                                        $roleText = 'Admin';
                                                        break;
                                                    case 'tour_guide':
                                                        $roleClass = 'bg-success';
                                                        $roleText = 'Hướng dẫn viên';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $roleClass ?>"><?= $roleText ?></span>
                                            </td>
                                            <td>
                                                <?php if ($user['status'] === 'active'): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>Hoạt động
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-pause-circle me-1"></i>Tạm khóa
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="<?= BASE_URL ?>?act=admin-user-edit&id=<?= $user['id'] ?>" 
                                                       class="btn btn-sm btn-warning" 
                                                       title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <?php if ($user['role'] !== 'super_admin'): ?>
                                                        <a href="<?= BASE_URL ?>?act=admin-user-toggle-status&id=<?= $user['id'] ?>" 
                                                           class="btn btn-sm <?= $user['status'] === 'active' ? 'btn-secondary' : 'btn-success' ?>" 
                                                           title="<?= $user['status'] === 'active' ? 'Khóa tài khoản' : 'Kích hoạt tài khoản' ?>"
                                                           onclick="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái tài khoản này?')">
                                                            <i class="fas fa-<?= $user['status'] === 'active' ? 'lock' : 'unlock' ?>"></i>
                                                        </a>
                                                        
                                                        <a href="<?= BASE_URL ?>?act=admin-user-delete&id=<?= $user['id'] ?>" 
                                                           class="btn btn-sm btn-danger" 
                                                           title="Xóa tài khoản"
                                                           onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này? Hành động này không thể hoàn tác!')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="no-data">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Chưa có tài khoản nào</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const table = document.getElementById('usersTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value;
        
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const username = row.cells[2].textContent.toLowerCase();
            const fullName = row.cells[3].textContent.toLowerCase();
            const email = row.cells[4].textContent.toLowerCase();
            const roleCell = row.cells[5];
            const roleSpan = roleCell.querySelector('.badge');
            const userRole = roleSpan ? roleSpan.getAttribute('class').includes('bg-danger') ? 'super_admin' : 
                                        roleSpan.getAttribute('class').includes('bg-primary') ? 'admin' : 'tour_guide' : '';
            
            const matchesSearch = username.includes(searchTerm) || 
                                fullName.includes(searchTerm) || 
                                email.includes(searchTerm);
            const matchesRole = selectedRole === '' || userRole === selectedRole;
            
            if (matchesSearch && matchesRole) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }
    
    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);
});
</script>

<?php require_once './views/quantri/layout/footer.php'; ?>