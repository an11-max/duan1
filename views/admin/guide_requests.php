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
    <title>Yêu cầu của tôi - Hướng dẫn viên</title>
    <link rel="stylesheet" href="./assets/css/admin.css">
    <style>
        .guide-requests {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #8e44ad, #9b59b6);
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
        
        .requests-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 4px solid #8e44ad;
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
        
        .filter-section {
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
            background: #8e44ad;
            color: white;
            border-color: #8e44ad;
        }
        
        .filter-tab:hover {
            border-color: #8e44ad;
            color: #8e44ad;
        }
        
        .filter-tab.active:hover {
            color: white;
        }
        
        .requests-list {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .request-item {
            padding: 25px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.3s ease;
        }
        
        .request-item:last-child {
            border-bottom: none;
        }
        
        .request-item:hover {
            background: #f8f9fa;
        }
        
        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .request-info {
            flex: 1;
        }
        
        .request-title {
            font-size: 1.3em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .request-meta {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
        
        .request-status {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-assigned {
            background: #cce7ff;
            color: #004085;
        }
        
        .request-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 3px solid #8e44ad;
        }
        
        .detail-label {
            font-weight: 500;
            color: #495057;
        }
        
        .detail-value {
            color: #2c3e50;
            font-weight: bold;
        }
        
        .request-notes {
            background: #fff8e1;
            border: 1px solid #ffe082;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            color: #f57f17;
        }
        
        .notes-title {
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .request-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 15px;
        }
        
        .action-button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-view {
            background: #3498db;
            color: white;
        }
        
        .btn-view:hover {
            background: #2980b9;
        }
        
        .btn-cancel {
            background: #e74c3c;
            color: white;
        }
        
        .btn-cancel:hover {
            background: #c0392b;
        }
        
        .btn-disabled {
            background: #bdc3c7;
            color: #7f8c8d;
            cursor: not-allowed;
        }
        
        .response-section {
            background: #e8f4fd;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }
        
        .response-title {
            font-weight: bold;
            color: #0c5460;
            margin-bottom: 8px;
        }
        
        .response-content {
            color: #0c5460;
            line-height: 1.5;
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
            margin-top: 15px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #8e44ad;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #8e44ad;
        }
        
        .timeline-time {
            font-size: 0.8em;
            color: #7f8c8d;
            margin-bottom: 3px;
        }
        
        .timeline-content {
            color: #2c3e50;
            font-weight: 500;
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
        
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .filter-tabs {
                flex-direction: column;
            }
            
            .request-header {
                flex-direction: column;
                gap: 10px;
            }
            
            .request-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="guide-requests">
        <div class="page-header">
            <h1 class="page-title">📋 Yêu cầu của tôi</h1>
            <a href="<?= BASE_URL ?>?act=guide-dashboard" class="back-button">← Quay lại Dashboard</a>
        </div>

        <div class="requests-stats">
            <div class="stat-card">
                <div class="stat-number"><?= count(array_filter($requests, fn($r) => $r['status'] === 'pending')) ?></div>
                <div class="stat-label">Chờ duyệt</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count(array_filter($requests, fn($r) => $r['status'] === 'approved')) ?></div>
                <div class="stat-label">Đã duyệt</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count(array_filter($requests, fn($r) => $r['status'] === 'assigned')) ?></div>
                <div class="stat-label">Đã phân công</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count(array_filter($requests, fn($r) => $r['status'] === 'rejected')) ?></div>
                <div class="stat-label">Bị từ chối</div>
            </div>
        </div>

        <div class="filter-section">
            <div class="filter-tabs">
                <a href="<?= BASE_URL ?>?act=guide-requests" 
                   class="filter-tab <?= (!isset($_GET['status']) || $_GET['status'] === '') ? 'active' : '' ?>">
                    📋 Tất cả
                </a>
                <a href="<?= BASE_URL ?>?act=guide-requests&status=pending" 
                   class="filter-tab <?= (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'active' : '' ?>">
                    ⏳ Chờ duyệt
                </a>
                <a href="<?= BASE_URL ?>?act=guide-requests&status=approved" 
                   class="filter-tab <?= (isset($_GET['status']) && $_GET['status'] === 'approved') ? 'active' : '' ?>">
                    ✅ Đã duyệt
                </a>
                <a href="<?= BASE_URL ?>?act=guide-requests&status=assigned" 
                   class="filter-tab <?= (isset($_GET['status']) && $_GET['status'] === 'assigned') ? 'active' : '' ?>">
                    🎯 Đã phân công
                </a>
                <a href="<?= BASE_URL ?>?act=guide-requests&status=rejected" 
                   class="filter-tab <?= (isset($_GET['status']) && $_GET['status'] === 'rejected') ? 'active' : '' ?>">
                    ❌ Bị từ chối
                </a>
            </div>
        </div>

        <div class="requests-list">
            <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $request): ?>
                    <div class="request-item">
                        <div class="request-header">
                            <div class="request-info">
                                <div class="request-title">
                                    🎯 <?= htmlspecialchars($request['tour_name']) ?>
                                </div>
                                <div class="request-meta">
                                    📅 Gửi lúc: <?= date('d/m/Y H:i', strtotime($request['created_at'])) ?>
                                    <?php if ($request['updated_at']): ?>
                                        • Cập nhật: <?= date('d/m/Y H:i', strtotime($request['updated_at'])) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="request-status status-<?= $request['status'] ?>">
                                <?php
                                switch ($request['status']) {
                                    case 'pending': echo '⏳ Chờ duyệt'; break;
                                    case 'approved': echo '✅ Đã duyệt'; break;
                                    case 'rejected': echo '❌ Bị từ chối'; break;
                                    case 'assigned': echo '🎯 Đã phân công'; break;
                                    default: echo $request['status'];
                                }
                                ?>
                            </div>
                        </div>

                        <div class="request-details">
                            <div class="detail-item">
                                <span class="detail-label">📍 Điểm đến:</span>
                                <span class="detail-value"><?= htmlspecialchars($request['destination']) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">📅 Ngày khởi hành:</span>
                                <span class="detail-value"><?= date('d/m/Y', strtotime($request['departure_date'])) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">⏰ Thời gian:</span>
                                <span class="detail-value"><?= $request['duration'] ?> ngày</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">💰 Giá tour:</span>
                                <span class="detail-value"><?= number_format($request['price'], 0, ',', '.') ?> VND</span>
                            </div>
                        </div>

                        <?php if (!empty($request['notes'])): ?>
                            <div class="request-notes">
                                <div class="notes-title">💬 Ghi chú của bạn:</div>
                                <?= nl2br(htmlspecialchars($request['notes'])) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($request['admin_response'])): ?>
                            <div class="response-section">
                                <div class="response-title">
                                    <?= $request['status'] === 'approved' ? '✅ Phản hồi từ Admin:' : '❌ Lý do từ chối:' ?>
                                </div>
                                <div class="response-content">
                                    <?= nl2br(htmlspecialchars($request['admin_response'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-time"><?= date('d/m/Y H:i', strtotime($request['created_at'])) ?></div>
                                <div class="timeline-content">📤 Bạn đã gửi yêu cầu tham gia tour</div>
                            </div>
                            
                            <?php if ($request['status'] !== 'pending'): ?>
                                <div class="timeline-item">
                                    <div class="timeline-time"><?= date('d/m/Y H:i', strtotime($request['updated_at'])) ?></div>
                                    <div class="timeline-content">
                                        <?php
                                        switch ($request['status']) {
                                            case 'approved': echo '✅ Admin đã duyệt yêu cầu'; break;
                                            case 'rejected': echo '❌ Admin đã từ chối yêu cầu'; break;
                                            case 'assigned': echo '🎯 Tour đã được phân công cho bạn'; break;
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="request-actions">
                            <a href="<?= BASE_URL ?>?act=guide-tour-detail&id=<?= $request['tour_id'] ?>" 
                               class="action-button btn-view">
                                👁️ Xem tour
                            </a>

                            <?php if ($request['status'] === 'pending'): ?>
                                <button onclick="cancelRequest(<?= $request['id'] ?>)" 
                                        class="action-button btn-cancel">
                                    ❌ Hủy yêu cầu
                                </button>
                            <?php elseif ($request['status'] === 'assigned'): ?>
                                <a href="<?= BASE_URL ?>?act=guide-tour-detail&id=<?= $request['tour_id'] ?>" 
                                   class="action-button btn-view" style="background: #27ae60;">
                                    🎯 Quản lý tour
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <div class="empty-message">Chưa có yêu cầu nào</div>
                    <div class="empty-suggestion">
                        Bạn chưa gửi yêu cầu tham gia tour nào. 
                        <a href="<?= BASE_URL ?>?act=guide-tours">Xem danh sách tours</a> để đăng ký.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function cancelRequest(requestId) {
            if (confirm('Bạn có chắc chắn muốn hủy yêu cầu này không?')) {
                fetch('<?= BASE_URL ?>?act=guide-cancel-request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        request_id: requestId 
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Yêu cầu đã được hủy thành công!');
                        location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                });
            }
        }
    </script>
</body>
</html>