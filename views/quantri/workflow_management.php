<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    header('Location: ' . BASE_URL . '?act=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Workflow - Admin</title>
    <link rel="stylesheet" href="./assets/css/admin.css">
    <style>
        .workflow-management {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #e67e22, #d35400);
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
        
        .workflow-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .tab-button {
            padding: 12px 25px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #666;
            font-weight: 500;
            position: relative;
        }
        
        .tab-button.active {
            background: #e67e22;
            color: white;
            border-color: #e67e22;
        }
        
        .tab-button:hover {
            border-color: #e67e22;
            color: #e67e22;
        }
        
        .tab-button.active:hover {
            color: white;
        }
        
        .tab-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7em;
            font-weight: bold;
            min-width: 18px;
            text-align: center;
        }
        
        .workflow-stats {
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
            border-left: 4px solid #e67e22;
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
        
        .requests-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #e67e22;
            padding-bottom: 10px;
        }
        
        .request-item {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .request-item:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .request-header {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            margin-bottom: 15px;
            gap: 20px;
        }
        
        .request-info {
            flex: 1;
        }
        
        .request-title {
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .request-meta {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 10px;
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
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .detail-label {
            font-weight: 500;
            color: #495057;
        }
        
        .detail-value {
            color: #2c3e50;
            font-weight: bold;
        }
        
        .request-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
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
        
        .btn-approve {
            background: #27ae60;
            color: white;
        }
        
        .btn-approve:hover {
            background: #229954;
        }
        
        .btn-reject {
            background: #e74c3c;
            color: white;
        }
        
        .btn-reject:hover {
            background: #c0392b;
        }
        
        .btn-assign {
            background: #3498db;
            color: white;
        }
        
        .btn-assign:hover {
            background: #2980b9;
        }
        
        .btn-view {
            background: #6c757d;
            color: white;
        }
        
        .btn-view:hover {
            background: #5a6268;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8em;
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
        
        .guide-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
            min-width: 150px;
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
        
        .request-notes {
            background: #fff8e1;
            border: 1px solid #ffe082;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
            font-style: italic;
            color: #f57f17;
        }
        
        .priority-high {
            border-left: 4px solid #e74c3c !important;
        }
        
        .priority-medium {
            border-left: 4px solid #f39c12 !important;
        }
        
        .priority-low {
            border-left: 4px solid #27ae60 !important;
        }
        
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .workflow-tabs {
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
    <div class="workflow-management">
        <div class="page-header">
            <h1 class="page-title">⚡ Quản lý Workflow</h1>
            <a href="<?= BASE_URL ?>?act=dashboard" class="back-button">← Quay lại Dashboard</a>
        </div>

        <div class="workflow-tabs">
            <a href="<?= BASE_URL ?>?act=admin-workflow" 
               class="tab-button <?= (!isset($_GET['type']) || $_GET['type'] === '') ? 'active' : '' ?>">
                📋 Tất cả yêu cầu
                <?php if ($counts['total'] > 0): ?>
                    <span class="tab-badge"><?= $counts['total'] ?></span>
                <?php endif; ?>
            </a>
            
            <a href="<?= BASE_URL ?>?act=admin-workflow&type=pending" 
               class="tab-button <?= (isset($_GET['type']) && $_GET['type'] === 'pending') ? 'active' : '' ?>">
                ⏳ Chờ duyệt
                <?php if ($counts['pending'] > 0): ?>
                    <span class="tab-badge"><?= $counts['pending'] ?></span>
                <?php endif; ?>
            </a>
            
            <a href="<?= BASE_URL ?>?act=admin-workflow&type=approved" 
               class="tab-button <?= (isset($_GET['type']) && $_GET['type'] === 'approved') ? 'active' : '' ?>">
                ✅ Đã duyệt
                <?php if ($counts['approved'] > 0): ?>
                    <span class="tab-badge"><?= $counts['approved'] ?></span>
                <?php endif; ?>
            </a>
            
            <a href="<?= BASE_URL ?>?act=admin-workflow&type=rejected" 
               class="tab-button <?= (isset($_GET['type']) && $_GET['type'] === 'rejected') ? 'active' : '' ?>">
                ❌ Từ chối
                <?php if ($counts['rejected'] > 0): ?>
                    <span class="tab-badge"><?= $counts['rejected'] ?></span>
                <?php endif; ?>
            </a>
        </div>

        <div class="workflow-stats">
            <div class="stat-card">
                <div class="stat-number"><?= $counts['pending'] ?></div>
                <div class="stat-label">Yêu cầu chờ duyệt</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $counts['approved'] ?></div>
                <div class="stat-label">Đã duyệt hôm nay</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $counts['assignments_today'] ?></div>
                <div class="stat-label">Phân công hôm nay</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $counts['active_guides'] ?></div>
                <div class="stat-label">HDV đang hoạt động</div>
            </div>
        </div>

        <div class="requests-section">
            <h3 class="section-title">
                📝 Danh sách yêu cầu
                <?php if (isset($_GET['type']) && $_GET['type'] !== ''): ?>
                    - <?php
                    switch ($_GET['type']) {
                        case 'pending': echo 'Chờ duyệt'; break;
                        case 'approved': echo 'Đã duyệt'; break;
                        case 'rejected': echo 'Từ chối'; break;
                        default: echo 'Tất cả';
                    }
                    ?>
                <?php endif; ?>
            </h3>

            <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $request): ?>
                    <div class="request-item priority-<?= $request['priority'] ?? 'medium' ?>">
                        <div class="request-header">
                            <div class="request-info">
                                <div class="request-title">
                                    🎯 <?= htmlspecialchars($request['tour_name']) ?>
                                </div>
                                <div class="request-meta">
                                    👤 <strong><?= htmlspecialchars($request['guide_name']) ?></strong> 
                                    • 📅 <?= date('d/m/Y H:i', strtotime($request['created_at'])) ?>
                                    • <span class="status-badge status-<?= $request['status'] ?>">
                                        <?php
                                        switch ($request['status']) {
                                            case 'pending': echo '⏳ Chờ duyệt'; break;
                                            case 'approved': echo '✅ Đã duyệt'; break;
                                            case 'rejected': echo '❌ Từ chối'; break;
                                            case 'assigned': echo '🎯 Đã phân công'; break;
                                            default: echo $request['status'];
                                        }
                                        ?>
                                    </span>
                                </div>
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
                                <span class="detail-label">👥 Số chỗ:</span>
                                <span class="detail-value"><?= $request['available_slots'] ?>/<?= $request['max_participants'] ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">💰 Giá tour:</span>
                                <span class="detail-value"><?= number_format($request['price'], 0, ',', '.') ?> VND</span>
                            </div>
                        </div>

                        <?php if (!empty($request['notes'])): ?>
                            <div class="request-notes">
                                💬 <strong>Ghi chú:</strong> <?= htmlspecialchars($request['notes']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="request-actions">
                            <a href="<?= BASE_URL ?>?act=admin-tour-detail&id=<?= $request['tour_id'] ?>" 
                               class="action-button btn-view">
                                👁️ Xem tour
                            </a>

                            <?php if ($request['status'] === 'pending'): ?>
                                <button onclick="approveRequest(<?= $request['id'] ?>)" 
                                        class="action-button btn-approve">
                                    ✅ Duyệt
                                </button>
                                
                                <button onclick="rejectRequest(<?= $request['id'] ?>)" 
                                        class="action-button btn-reject">
                                    ❌ Từ chối
                                </button>
                            <?php endif; ?>

                            <?php if ($request['status'] === 'approved'): ?>
                                <select class="guide-select" id="guide-select-<?= $request['id'] ?>">
                                    <option value="">Chọn HDV</option>
                                    <?php foreach ($available_guides as $guide): ?>
                                        <option value="<?= $guide['id'] ?>" 
                                            <?= ($guide['id'] == $request['guide_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($guide['full_name']) ?>
                                            (<?= $guide['active_tours'] ?> tours)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <button onclick="assignTour(<?= $request['id'] ?>, <?= $request['tour_id'] ?>)" 
                                        class="action-button btn-assign">
                                    🎯 Phân công
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <div class="empty-message">Không có yêu cầu nào</div>
                    <div class="empty-suggestion">
                        <?php if (isset($_GET['type']) && $_GET['type'] !== ''): ?>
                            Không có yêu cầu loại này. <a href="<?= BASE_URL ?>?act=admin-workflow">Xem tất cả yêu cầu</a>
                        <?php else: ?>
                            Chưa có yêu cầu nào từ hướng dẫn viên.
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function approveRequest(requestId) {
            if (confirm('Bạn có chắc chắn muốn duyệt yêu cầu này không?')) {
                fetch('<?= BASE_URL ?>?act=admin-approve-request', {
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
                        alert(data.message || 'Yêu cầu đã được duyệt thành công!');
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
        
        function rejectRequest(requestId) {
            const reason = prompt('Vui lòng nhập lý do từ chối:');
            if (reason !== null && reason.trim() !== '') {
                fetch('<?= BASE_URL ?>?act=admin-reject-request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        request_id: requestId,
                        reason: reason.trim()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Yêu cầu đã bị từ chối!');
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
        
        function assignTour(requestId, tourId) {
            const guideSelect = document.getElementById(`guide-select-${requestId}`);
            const guideId = guideSelect.value;
            
            if (!guideId) {
                alert('Vui lòng chọn hướng dẫn viên!');
                return;
            }
            
            if (confirm('Bạn có chắc chắn muốn phân công tour này không?')) {
                fetch('<?= BASE_URL ?>?act=admin-assign-tour', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        request_id: requestId,
                        tour_id: tourId,
                        guide_id: guideId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Tour đã được phân công thành công!');
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
        
        // Auto refresh every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
