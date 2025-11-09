<?php require_once './views/quantri/layout/header.php'; ?>

<div class="dashboard">
    <h1 class="page-title">Dashboard Hướng dẫn viên</h1>
    <p class="welcome-text">Chào mừng <strong><?= $_SESSION['user']['full_name'] ?></strong>! Bạn có thể xem thông tin các tour du lịch.</p>

    <!-- Tours Information -->
    <div class="dashboard-section">
        <h2>Danh sách Tours</h2>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã Tour</th>
                        <th>Tên Tour</th>
                        <th>Thời gian</th>
                        <th>Loại Tour</th>
                        <th>Mô tả</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tours)): ?>
                        <?php foreach ($tours as $tour): ?>
                            <tr>
                                <td><?= htmlspecialchars($tour['tour_code']) ?></td>
                                <td><?= htmlspecialchars($tour['name']) ?></td>
                                <td><?= $tour['duration'] ?> ngày</td>
                                <td>
                                    <span class="badge badge-<?= $tour['is_international'] ? 'international' : 'domestic' ?>">
                                        <?= $tour['is_international'] ? 'Quốc tế' : 'Trong nước' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="tour-description">
                                        <?= nl2br(htmlspecialchars(substr($tour['description'], 0, 100))) ?>
                                        <?= strlen($tour['description']) > 100 ? '...' : '' ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Chưa có tour nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="info-cards">
        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="info-content">
                <h3>Lưu ý cho HDV</h3>
                <ul>
                    <li>Bạn chỉ có quyền xem thông tin tours</li>
                    <li>Để được phân công dẫn tour, liên hệ với Admin</li>
                    <li>Cập nhật thông tin cá nhân qua menu Profile</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.welcome-text {
    background: #e3f2fd;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 30px;
    border-left: 4px solid #2196f3;
}

.tour-description {
    max-width: 300px;
    font-size: 13px;
    line-height: 1.4;
}

.badge-international {
    background: #f39c12;
    color: white;
}

.badge-domestic {
    background: #27ae60;
    color: white;
}

.info-cards {
    margin-top: 30px;
}

.info-card {
    display: flex;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid #667eea;
}

.info-icon {
    margin-right: 20px;
    font-size: 2rem;
    color: #667eea;
}

.info-content h3 {
    margin-bottom: 15px;
    color: #333;
}

.info-content ul {
    margin: 0;
    padding-left: 20px;
}

.info-content li {
    margin-bottom: 8px;
    color: #666;
}
</style>

<?php require_once './views/quantri/layout/footer.php'; ?>
