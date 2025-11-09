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
    <title>Thông báo - Hướng dẫn viên</title>
    <link rel="stylesheet" href="./assets/css/admin.css">
    <style>
        .guide-notifications {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 1.8em;
            font-weight: bold;
            margin: 0;
        }
        
        .back-button {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .back-button:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .notification-filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .filter-tab {
            padding: 10px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #666;
            font-weight: 500;
        }
        
        .filter-tab.active {
            background: #9b59b6;
            color: white;
            border-color: #9b59b6;
        }
        
        .filter-tab:hover {
            border-color: #9b59b6;
            color: #9b59b6;
        }
        
        .filter-tab.active:hover {
            color: white;
        }
        
        .bulk-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .bulk-button {
            background: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background 0.3s ease;
        }
        
        .bulk-button:hover {
            background: #2980b9;
        }
        
        .bulk-button.danger {
            background: #e74c3c;
        }
        
        .bulk-button.danger:hover {
            background: #c0392b;
        }
        
        .notifications-list {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .notification-item {
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            transition: background 0.3s ease;
            position: relative;
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-item:hover {
            background: #f8f9fa;
        }
        
        .notification-item.unread {
            background: #fff9f0;
            border-left: 4px solid #f39c12;
        }
        
        .notification-checkbox {
            margin-top: 5px;
        }
        
        .notification-icon {
            font-size: 2em;
            min-width: 50px;
            text-align: center;
            margin-top: 5px;
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        
        .notification-title {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.1em;
            margin-bottom: 5px;
        }
        
        .notification-time {
            color: #7f8c8d;
            font-size: 0.9em;
            min-width: 120px;
            text-align: right;
        }
        
        .notification-message {
            color: #34495e;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        
        .notification-actions {
            display: flex;
            gap: 10px;
        }
        
        .notification-action {
            padding: 5px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85em;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #666;
        }
        
        .notification-action:hover {
            background: #f0f0f0;
        }
        
        .notification-action.primary {
            background: #3498db;
            border-color: #3498db;
            color: white;
        }
        
        .notification-action.primary:hover {
            background: #2980b9;
        }
        
        .notification-action.success {
            background: #27ae60;
            border-color: #27ae60;
            color: white;
        }
        
        .notification-action.success:hover {
            background: #229954;
        }
        
        .notification-action.danger {
            background: #e74c3c;
            border-color: #e74c3c;
            color: white;
        }
        
        .notification-action.danger:hover {
            background: #c0392b;
        }
        
        .notification-type {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75em;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .type-tour-request {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .type-tour-assignment {
            background: #e8f5e8;
            color: #388e3c;
        }
        
        .type-system {
            background: #f3e5f5;
            color: #7b1fa2;
        }
        
        .type-general {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }
        
        .empty-icon {
            font-size: 4em;
            margin-bottom: 20px;
        }
        
        .empty-message {
            font-size: 1.2em;
            margin-bottom: 15px;
        }
        
        .empty-suggestion {
            font-size: 1em;
            color: #95a5a6;
        }
        
        .unread-indicator {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 10px;
            height: 10px;
            background: #e74c3c;
            border-radius: 50%;
        }
        
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .filter-tabs {
                flex-direction: column;
            }
            
            .bulk-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .notification-item {
                flex-direction: column;
                gap: 10px;
            }
            
            .notification-header {
                flex-direction: column;
                gap: 5px;
            }
            
            .notification-time {
                text-align: left;
                min-width: auto;
            }
        }
    </style>
</head>
<body>
    <div class="guide-notifications">
        <div class="page-header">
            <h1 class="page-title">🔔 Thông báo của tôi</h1>
            <a href="<?= BASE_URL ?>?act=guide-dashboard" class="back-button">← Quay lại Dashboard</a>
        </div>

        <div class="notification-filters">
            <div class="filter-tabs">
                <a href="<?= BASE_URL ?>?act=guide-notifications" 
                   class="filter-tab <?= (!isset($_GET['type']) || $_GET['type'] === '') ? 'active' : '' ?>">
                    📋 Tất cả (<?= $counts['total'] ?>)
                </a>
                <a href="<?= BASE_URL ?>?act=guide-notifications&type=unread" 
                   class="filter-tab <?= (isset($_GET['type']) && $_GET['type'] === 'unread') ? 'active' : '' ?>">
                    🔴 Chưa đọc (<?= $counts['unread'] ?>)
                </a>
                <a href="<?= BASE_URL ?>?act=guide-notifications&type=tour_request" 
                   class="filter-tab <?= (isset($_GET['type']) && $_GET['type'] === 'tour_request') ? 'active' : '' ?>">
                    🎯 Yêu cầu Tour (<?= $counts['tour_request'] ?>)
                </a>
                <a href="<?= BASE_URL ?>?act=guide-notifications&type=tour_assignment" 
                   class="filter-tab <?= (isset($_GET['type']) && $_GET['type'] === 'tour_assignment') ? 'active' : '' ?>">
                    ✅ Phân công (<?= $counts['tour_assignment'] ?>)
                </a>
            </div>
            
            <div class="bulk-actions">
                <label>
                    <input type="checkbox" id="selectAll"> Chọn tất cả
                </label>
                <button class="bulk-button" onclick="markAsRead()">📖 Đánh dấu đã đọc</button>
                <button class="bulk-button danger" onclick="deleteSelected()">🗑️ Xóa đã chọn</button>
            </div>
        </div>

        <div class="notifications-list">
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $notification): ?>
                    <div class="notification-item <?= $notification['is_read'] ? '' : 'unread' ?>" 
                         data-id="<?= $notification['id'] ?>">
                        
                        <?php if (!$notification['is_read']): ?>
                            <div class="unread-indicator"></div>
                        <?php endif; ?>
                        
                        <input type="checkbox" class="notification-checkbox" value="<?= $notification['id'] ?>">
                        
                        <div class="notification-icon">
                            <?php
                            switch ($notification['type']) {
                                case 'tour_request':
                                    echo '🎯';
                                    break;
                                case 'tour_assignment':
                                    echo '✅';
                                    break;
                                case 'system':
                                    echo '⚙️';
                                    break;
                                default:
                                    echo '📢';
                            }
                            ?>
                        </div>
                        
                        <div class="notification-content">
                            <div class="notification-header">
                                <div>
                                    <span class="notification-type type-<?= $notification['type'] ?>">
                                        <?php
                                        switch ($notification['type']) {
                                            case 'tour_request':
                                                echo 'Yêu cầu Tour';
                                                break;
                                            case 'tour_assignment':
                                                echo 'Phân công Tour';
                                                break;
                                            case 'system':
                                                echo 'Hệ thống';
                                                break;
                                            default:
                                                echo 'Thông báo';
                                        }
                                        ?>
                                    </span>
                                    <div class="notification-title"><?= htmlspecialchars($notification['title']) ?></div>
                                </div>
                                <div class="notification-time">
                                    <?= date('d/m/Y H:i', strtotime($notification['created_at'])) ?>
                                </div>
                            </div>
                            
                            <div class="notification-message">
                                <?= nl2br(htmlspecialchars($notification['message'])) ?>
                            </div>
                            
                            <div class="notification-actions">
                                <?php if (!$notification['is_read']): ?>
                                    <button class="notification-action primary" 
                                            onclick="markSingleAsRead(<?= $notification['id'] ?>)">
                                        📖 Đánh dấu đã đọc
                                    </button>
                                <?php endif; ?>
                                
                                <?php if ($notification['type'] === 'tour_assignment' && isset($notification['tour_id'])): ?>
                                    <a href="<?= BASE_URL ?>?act=guide-tour-detail&id=<?= $notification['tour_id'] ?>" 
                                       class="notification-action success">
                                        👁️ Xem Tour
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($notification['type'] === 'tour_request' && isset($notification['request_id'])): ?>
                                    <a href="<?= BASE_URL ?>?act=guide-request-detail&id=<?= $notification['request_id'] ?>" 
                                       class="notification-action success">
                                        👁️ Xem yêu cầu
                                    </a>
                                <?php endif; ?>
                                
                                <button class="notification-action danger" 
                                        onclick="deleteNotification(<?= $notification['id'] ?>)">
                                    🗑️ Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <div class="empty-message">Không có thông báo nào</div>
                    <div class="empty-suggestion">
                        <?php if (isset($_GET['type']) && $_GET['type'] !== ''): ?>
                            Không có thông báo loại này. <a href="<?= BASE_URL ?>?act=guide-notifications">Xem tất cả thông báo</a>
                        <?php else: ?>
                            Bạn sẽ nhận được thông báo khi có cập nhật mới về tours và yêu cầu.
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Select all functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.notification-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
        
        function markSingleAsRead(notificationId) {
            fetch('<?= BASE_URL ?>?act=guide-mark-notification-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: notificationId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
            });
        }
        
        function markAsRead() {
            const selectedIds = Array.from(document.querySelectorAll('.notification-checkbox:checked'))
                                   .map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) {
                alert('Vui lòng chọn ít nhất một thông báo!');
                return;
            }
            
            fetch('<?= BASE_URL ?>?act=guide-mark-notifications-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
            });
        }
        
        function deleteSelected() {
            const selectedIds = Array.from(document.querySelectorAll('.notification-checkbox:checked'))
                                   .map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) {
                alert('Vui lòng chọn ít nhất một thông báo!');
                return;
            }
            
            if (confirm(`Bạn có chắc chắn muốn xóa ${selectedIds.length} thông báo đã chọn không?`)) {
                fetch('<?= BASE_URL ?>?act=guide-delete-notifications', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ ids: selectedIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra. Vui lòng thử lại!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                });
            }
        }
        
        function deleteNotification(notificationId) {
            if (confirm('Bạn có chắc chắn muốn xóa thông báo này không?')) {
                fetch('<?= BASE_URL ?>?act=guide-delete-notification', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: notificationId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra. Vui lòng thử lại!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                });
            }
        }
        
        // Auto mark as read when viewing
        setTimeout(() => {
            const unreadNotifications = document.querySelectorAll('.notification-item.unread');
            if (unreadNotifications.length > 0) {
                const unreadIds = Array.from(unreadNotifications).map(item => item.dataset.id);
                
                fetch('<?= BASE_URL ?>?act=guide-mark-notifications-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ ids: unreadIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove unread styling
                        unreadNotifications.forEach(item => {
                            item.classList.remove('unread');
                            const indicator = item.querySelector('.unread-indicator');
                            if (indicator) indicator.remove();
                        });
                    }
                });
            }
        }, 2000); // Mark as read after 2 seconds of viewing
    </script>
</body>
</html>
