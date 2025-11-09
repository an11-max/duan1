<?php require_once './views/quantri/layout/header.php'; ?>

<!-- Custom CSS cho quản lý người dùng -->
<style>
.users-management {
    padding: 20px;
    background: #f8f9fa;
    min-height: 100vh;
}

.page-header {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title i {
    color: #667eea;
    font-size: 32px;
}

.btn-create-user {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-create-user:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

.filters-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}

.filters-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 15px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-label {
    font-weight: 600;
    color: #555;
    font-size: 14px;
}

.filter-input, .filter-select {
    padding: 10px 12px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-filter {
    padding: 10px 20px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    height: fit-content;
}

.btn-filter:hover {
    background: #218838;
    transform: translateY(-1px);
}

.users-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.user-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.user-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.user-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.user-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.user-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    position: relative;
}

.user-avatar.super_admin {
    background: linear-gradient(135deg, #ffd700, #ffed4a);
    color: #8b5a00;
    box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
}

.user-avatar.admin {
    background: linear-gradient(135deg, #667eea, #764ba2);
    box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
}

.user-avatar.tour_guide {
    background: linear-gradient(135deg, #51cf66, #40c057);
    box-shadow: 0 0 20px rgba(81, 207, 102, 0.3);
}

.user-info h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
}

.user-info p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.user-role {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 15px 0;
}

.role-super-admin {
    background: rgba(255, 215, 0, 0.1);
    color: #b8860b;
    border: 1px solid rgba(255, 215, 0, 0.3);
}

.role-admin {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    border: 1px solid rgba(102, 126, 234, 0.3);
}

.role-tour-guide {
    background: rgba(81, 207, 102, 0.1);
    color: #51cf66;
    border: 1px solid rgba(81, 207, 102, 0.3);
}

.user-status {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 10px 0;
}

.status-badge {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-active {
    background: #28a745;
    box-shadow: 0 0 10px rgba(40, 167, 69, 0.3);
}

.status-inactive {
    background: #dc3545;
    box-shadow: 0 0 10px rgba(220, 53, 69, 0.3);
}

.user-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin: 15px 0;
    font-size: 12px;
    color: #666;
}

.user-actions {
    display: flex;
    gap: 8px;
    margin-top: 20px;
}

.btn-action {
    flex: 1;
    padding: 8px 12px;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.btn-edit {
    background: #17a2b8;
    color: white;
}

.btn-edit:hover {
    background: #138496;
    color: white;
}

.btn-toggle {
    background: #ffc107;
    color: #212529;
}

.btn-toggle:hover {
    background: #e0a800;
}

.btn-delete {
    background: #dc3545;
    color: white;
}

.btn-delete:hover {
    background: #c82333;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    text-align: center;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 5px;
}

.stat-label {
    color: #666;
    font-size: 14px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .filters-row {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .users-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-row {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<div class="users-management">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-users-cog"></i>
            Quản lý người dùng
        </h1>
        <a href="index.php?act=register" class="btn-create-user">
            <i class="fas fa-user-plus"></i>
            Tạo tài khoản mới
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-triangle"></i> <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Statistics Row -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-number"><?= $totalUsers ?? count($users ?? []) ?></div>
            <div class="stat-label">Tổng người dùng</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= count(array_filter($users ?? [], fn($u) => $u['status'] === 'active')) ?></div>
            <div class="stat-label">Đang hoạt động</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= count(array_filter($users ?? [], fn($u) => in_array($u['role'], ['admin', 'super_admin']))) ?></div>
            <div class="stat-label">Quản trị viên</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= count(array_filter($users ?? [], fn($u) => $u['role'] === 'tour_guide')) ?></div>
            <div class="stat-label">Hướng dẫn viên</div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <form method="GET" action="index.php">
            <input type="hidden" name="act" value="user-list">
            <div class="filters-row">
                <div class="filter-group">
                    <label class="filter-label">Tìm kiếm</label>
                    <input type="text" name="search" class="filter-input" 
                           placeholder="Tìm theo tên, email, username..." 
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Vai trò</label>
                    <select name="role" class="filter-select">
                        <option value="">Tất cả vai trò</option>
                        <option value="super_admin" <?= ($_GET['role'] ?? '') === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                        <option value="admin" <?= ($_GET['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="tour_guide" <?= ($_GET['role'] ?? '') === 'tour_guide' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Trạng thái</label>
                    <select name="status" class="filter-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                    </select>
                </div>
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Users Grid -->
    <div class="users-grid">
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <div class="user-card">
                    <div class="user-header">
                        <div class="user-avatar <?= $user['role'] ?>">
                            <?php
                            switch ($user['role']) {
                                case 'super_admin':
                                    echo '<i class="fas fa-crown"></i>';
                                    break;
                                case 'admin':
                                    echo '<i class="fas fa-user-shield"></i>';
                                    break;
                                case 'tour_guide':
                                    echo '<i class="fas fa-user-tie"></i>';
                                    break;
                                default:
                                    echo '<i class="fas fa-user"></i>';
                            }
                            ?>
                        </div>
                        <div class="user-info">
                            <h3><?= htmlspecialchars($user['full_name']) ?></h3>
                            <p>@<?= htmlspecialchars($user['username']) ?></p>
                        </div>
                    </div>

                    <div class="user-role role-<?= str_replace('_', '-', $user['role']) ?>">
                        <?php
                        switch ($user['role']) {
                            case 'super_admin':
                                echo 'Super Administrator';
                                break;
                            case 'admin':
                                echo 'Quản trị viên';
                                break;
                            case 'tour_guide':
                                echo 'Hướng dẫn viên';
                                break;
                        }
                        ?>
                    </div>

                    <div class="user-status">
                        <span class="status-badge status-<?= $user['status'] ?>"></span>
                        <span><?= $user['status'] === 'active' ? 'Đang hoạt động' : 'Không hoạt động' ?></span>
                    </div>

                    <div class="user-meta">
                        <div>
                            <strong>Email:</strong><br>
                            <?= htmlspecialchars($user['email']) ?>
                        </div>
                        <div>
                            <strong>Ngày tạo:</strong><br>
                            <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                        </div>
                        <?php if (isset($user['last_login']) && $user['last_login']): ?>
                        <div style="grid-column: 1/-1;">
                            <strong>Đăng nhập cuối:</strong><br>
                            <?= date('d/m/Y H:i', strtotime($user['last_login'])) ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($user['role'] !== 'super_admin' || $_SESSION['user']['role'] === 'super_admin'): ?>
                    <div class="user-actions">
                        <?php if ($user['role'] !== 'super_admin'): ?>
                        <a href="index.php?act=toggle-user-status&id=<?= $user['id'] ?>" 
                           class="btn-action btn-toggle"
                           onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái tài khoản này?')">
                            <i class="fas fa-power-off"></i>
                            <?= $user['status'] === 'active' ? 'Khóa' : 'Mở khóa' ?>
                        </a>
                        <a href="index.php?act=delete-user&id=<?= $user['id'] ?>" 
                           class="btn-action btn-delete"
                           onclick="return confirm('Bạn có chắc muốn xóa tài khoản này? Hành động này không thể hoàn tác!')">
                            <i class="fas fa-trash"></i> Xóa
                        </a>
                        <?php else: ?>
                        <div class="btn-action" style="background: #e9ecef; color: #6c757d; cursor: not-allowed;">
                            <i class="fas fa-shield-alt"></i> Được bảo vệ
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: #666;">
                <i class="fas fa-users" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
                <h3>Không tìm thấy người dùng nào</h3>
                <p>Thử điều chỉnh bộ lọc hoặc tạo người dùng mới</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript for enhanced functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced confirm dialogs
    const deleteLinks = document.querySelectorAll('.btn-delete');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const userName = this.closest('.user-card').querySelector('.user-info h3').textContent;
            
            if (confirm(`Bạn có chắc chắn muốn xóa tài khoản "${userName}"?\n\nHành động này không thể hoàn tác!`)) {
                window.location.href = this.href;
            }
        });
    });

    // Status toggle confirmation
    const toggleLinks = document.querySelectorAll('.btn-toggle');
    toggleLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const userName = this.closest('.user-card').querySelector('.user-info h3').textContent;
            const action = this.textContent.includes('Khóa') ? 'khóa' : 'mở khóa';
            
            if (confirm(`Bạn có chắc muốn ${action} tài khoản "${userName}"?`)) {
                window.location.href = this.href;
            }
        });
    });

    // Auto search functionality
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;
    
    if (searchInput) {
        const form = searchInput.closest('form');
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 800);
        });
    }

    // Auto submit on filter change
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>

<?php require_once './views/quantri/layout/footer.php'; ?>