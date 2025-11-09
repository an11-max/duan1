<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tour_guide') {
    header('Location: ' . BASE_URL . '?act=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển - Hướng dẫn viên</title>
    <link rel="stylesheet" href="./assets/css/admin.css">
    <style>
        .guide-dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 4px solid #3498db;
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 1.1em;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .action-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
        }
        
        .action-icon {
            font-size: 3em;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .action-title {
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .action-description {
            color: #7f8c8d;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .action-button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s ease;
            font-weight: bold;
        }
        
        .action-button:hover {
            background: #2980b9;
        }
        
        .recent-activities {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .section-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .activity-item {
            padding: 15px;
            border-left: 3px solid #3498db;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 0 5px 5px 0;
        }
        
        .activity-time {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 5px;
        }
        
        .activity-content {
            color: #2c3e50;
            font-weight: 500;
        }
        
        .notification-badge {
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 0.8em;
            font-weight: bold;
            position: absolute;
            top: -5px;
            right: -5px;
        }
        
        .action-card {
            position: relative;
        }
        
        .user-info {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .user-name {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .user-role {
            font-size: 0.9em;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="guide-dashboard">
        <div class="dashboard-header">
            <h1>🌟 Chào mừng, Hướng dẫn viên!</h1>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($_SESSION['username']) ?></div>
                <div class="user-role">Hướng dẫn viên du lịch</div>
            </div>
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-number"><?= $stats['assigned_tours'] ?? 0 ?></div>
                <div class="stat-label">Tour được phân công</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['pending_requests'] ?? 0 ?></div>
                <div class="stat-label">Yêu cầu chờ duyệt</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['completed_tours'] ?? 0 ?></div>
                <div class="stat-label">Tour đã hoàn thành</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['unread_notifications'] ?? 0 ?></div>
                <div class="stat-label">Thông báo chưa đọc</div>
            </div>
        </div>

        <div class="quick-actions">
            <div class="action-card">
                <div class="action-icon">🗺️</div>
                <div class="action-title">Xem Tours Available</div>
                <div class="action-description">
                    Xem danh sách các tour du lịch hiện tại mà bạn có thể đăng ký tham gia.
                </div>
                <a href="<?= BASE_URL ?>?act=guide-tours" class="action-button">Xem Tours</a>
            </div>

            <div class="action-card">
                <?php if (($stats['unread_notifications'] ?? 0) > 0): ?>
                    <span class="notification-badge"><?= $stats['unread_notifications'] ?></span>
                <?php endif; ?>
                <div class="action-icon">🔔</div>
                <div class="action-title">Thông báo</div>
                <div class="action-description">
                    Kiểm tra các thông báo và cập nhật mới nhất từ công ty.
                </div>
                <a href="<?= BASE_URL ?>?act=guide-notifications" class="action-button">Xem Thông báo</a>
            </div>

            <div class="action-card">
                <div class="action-icon">📋</div>
                <div class="action-title">Tour của tôi</div>
                <div class="action-description">
                    Quản lý các tour mà bạn đã được phân công hoặc đang yêu cầu.
                </div>
                <a href="<?= BASE_URL ?>?act=guide-my-tours" class="action-button">Quản lý Tours</a>
            </div>

            <div class="action-card">
                <div class="action-icon">✉️</div>
                <div class="action-title">Gửi yêu cầu</div>
                <div class="action-description">
                    Gửi yêu cầu tham gia tour mới đến ban quản lý để được xem xét.
                </div>
                <a href="<?= BASE_URL ?>?act=guide-request" class="action-button">Tạo yêu cầu</a>
            </div>
        </div>

        <div class="recent-activities">
            <h3 class="section-title">📈 Hoạt động gần đây</h3>
            <?php if (!empty($recent_activities)): ?>
                <?php foreach ($recent_activities as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-time"><?= date('d/m/Y H:i', strtotime($activity['created_at'])) ?></div>
                        <div class="activity-content"><?= htmlspecialchars($activity['content']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="activity-item">
                    <div class="activity-content">Chưa có hoạt động nào gần đây.</div>
                </div>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="<?= BASE_URL ?>?act=logout" class="action-button" style="background: #e74c3c;">
                🚪 Đăng xuất
            </a>
        </div>
    </div>

    <script>
        // Auto refresh notifications count every 30 seconds
        setInterval(function() {
            fetch('<?= BASE_URL ?>?act=guide-notification-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.notification-badge');
                    const statNumber = document.querySelector('.stat-card:last-child .stat-number');
                    
                    if (data.count > 0) {
                        if (badge) {
                            badge.textContent = data.count;
                        } else {
                            // Create badge if it doesn't exist
                            const actionCard = document.querySelector('.action-card:nth-child(2)');
                            const newBadge = document.createElement('span');
                            newBadge.className = 'notification-badge';
                            newBadge.textContent = data.count;
                            actionCard.appendChild(newBadge);
                        }
                    } else {
                        if (badge) {
                            badge.remove();
                        }
                    }
                    
                    if (statNumber) {
                        statNumber.textContent = data.count;
                    }
                });
        }, 30000);
    </script>
</body>
</html>