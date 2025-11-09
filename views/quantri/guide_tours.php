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
    <title>Danh sách Tours - Hướng dẫn viên</title>
    <link rel="stylesheet" href="./assets/css/admin.css">
    <style>
        .guide-tours {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
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
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }
        
        .filter-button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        
        .filter-button:hover {
            background: #2980b9;
        }
        
        .tours-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .tour-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .tour-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .tour-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(45deg, #3498db, #2ecc71);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3em;
            position: relative;
        }
        
        .tour-content {
            padding: 20px;
        }
        
        .tour-title {
            font-size: 1.3em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .tour-details {
            margin-bottom: 15px;
        }
        
        .tour-detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.95em;
        }
        
        .detail-label {
            color: #7f8c8d;
            font-weight: 500;
        }
        
        .detail-value {
            color: #2c3e50;
            font-weight: bold;
        }
        
        .tour-price {
            font-size: 1.4em;
            font-weight: bold;
            color: #e74c3c;
            text-align: center;
            margin: 15px 0;
        }
        
        .tour-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .tour-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .btn-request {
            background: #27ae60;
            color: white;
        }
        
        .btn-request:hover {
            background: #229954;
        }
        
        .btn-view {
            background: #3498db;
            color: white;
        }
        
        .btn-view:hover {
            background: #2980b9;
        }
        
        .btn-disabled {
            background: #bdc3c7;
            color: #7f8c8d;
            cursor: not-allowed;
        }
        
        .tour-status {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-available {
            background: #2ecc71;
            color: white;
        }
        
        .status-assigned {
            background: #f39c12;
            color: white;
        }
        
        .status-requested {
            background: #3498db;
            color: white;
        }
        
        .status-full {
            background: #e74c3c;
            color: white;
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
            
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .tours-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="guide-tours">
        <div class="page-header">
            <h1 class="page-title">🗺️ Danh sách Tours</h1>
            <a href="<?= BASE_URL ?>?act=guide-dashboard" class="back-button">← Quay lại Dashboard</a>
        </div>

        <div class="filter-section">
            <form method="GET" action="">
                <input type="hidden" name="act" value="guide-tours">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="departure_id">Điểm khởi hành:</label>
                        <select name="departure_id" id="departure_id">
                            <option value="">Tất cả điểm khởi hành</option>
                            <?php foreach ($departures as $departure): ?>
                                <option value="<?= $departure['id'] ?>" 
                                    <?= (isset($_GET['departure_id']) && $_GET['departure_id'] == $departure['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($departure['departure_location']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="price_min">Giá từ:</label>
                        <input type="number" name="price_min" id="price_min" 
                               value="<?= htmlspecialchars($_GET['price_min'] ?? '') ?>" 
                               placeholder="VND">
                    </div>
                    
                    <div class="filter-group">
                        <label for="price_max">Giá đến:</label>
                        <input type="number" name="price_max" id="price_max" 
                               value="<?= htmlspecialchars($_GET['price_max'] ?? '') ?>" 
                               placeholder="VND">
                    </div>
                    
                    <div class="filter-group">
                        <label for="status">Trạng thái:</label>
                        <select name="status" id="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="available" <?= (isset($_GET['status']) && $_GET['status'] == 'available') ? 'selected' : '' ?>>
                                Có thể đăng ký
                            </option>
                            <option value="assigned" <?= (isset($_GET['status']) && $_GET['status'] == 'assigned') ? 'selected' : '' ?>>
                                Đã có HDV
                            </option>
                            <option value="requested" <?= (isset($_GET['status']) && $_GET['status'] == 'requested') ? 'selected' : '' ?>>
                                Đã yêu cầu
                            </option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <button type="submit" class="filter-button">🔍 Lọc</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="tours-grid">
            <?php if (!empty($tours)): ?>
                <?php foreach ($tours as $tour): ?>
                    <div class="tour-card">
                        <div class="tour-image">
                            🏖️
                            <div class="tour-status status-<?= $tour['request_status'] ?? 'available' ?>">
                                <?php
                                switch ($tour['request_status'] ?? 'available') {
                                    case 'available':
                                        echo 'Có thể đăng ký';
                                        break;
                                    case 'assigned':
                                        echo 'Đã phân công';
                                        break;
                                    case 'requested':
                                        echo 'Đã yêu cầu';
                                        break;
                                    case 'pending':
                                        echo 'Chờ duyệt';
                                        break;
                                    default:
                                        echo 'Có thể đăng ký';
                                }
                                ?>
                            </div>
                        </div>
                        
                        <div class="tour-content">
                            <div class="tour-title"><?= htmlspecialchars($tour['tour_name']) ?></div>
                            
                            <div class="tour-details">
                                <div class="tour-detail-item">
                                    <span class="detail-label">📍 Điểm đến:</span>
                                    <span class="detail-value"><?= htmlspecialchars($tour['destination']) ?></span>
                                </div>
                                
                                <div class="tour-detail-item">
                                    <span class="detail-label">🚌 Khởi hành:</span>
                                    <span class="detail-value"><?= htmlspecialchars($tour['departure_location']) ?></span>
                                </div>
                                
                                <div class="tour-detail-item">
                                    <span class="detail-label">📅 Ngày đi:</span>
                                    <span class="detail-value"><?= date('d/m/Y', strtotime($tour['departure_date'])) ?></span>
                                </div>
                                
                                <div class="tour-detail-item">
                                    <span class="detail-label">⏰ Thời gian:</span>
                                    <span class="detail-value"><?= $tour['duration'] ?> ngày</span>
                                </div>
                                
                                <div class="tour-detail-item">
                                    <span class="detail-label">👥 Còn lại:</span>
                                    <span class="detail-value"><?= $tour['available_slots'] ?>/<?= $tour['max_participants'] ?> chỗ</span>
                                </div>
                            </div>
                            
                            <div class="tour-price">
                                💰 <?= number_format($tour['price'], 0, ',', '.') ?> VND
                            </div>
                            
                            <div class="tour-actions">
                                <a href="<?= BASE_URL ?>?act=guide-tour-detail&id=<?= $tour['id'] ?>" 
                                   class="tour-button btn-view">
                                    👁️ Xem chi tiết
                                </a>
                                
                                <?php if (($tour['request_status'] ?? 'available') === 'available'): ?>
                                    <button onclick="requestTour(<?= $tour['id'] ?>)" 
                                            class="tour-button btn-request">
                                        ✋ Đăng ký
                                    </button>
                                <?php elseif (($tour['request_status'] ?? '') === 'pending'): ?>
                                    <button class="tour-button btn-disabled" disabled>
                                        ⏳ Chờ duyệt
                                    </button>
                                <?php elseif (($tour['request_status'] ?? '') === 'assigned'): ?>
                                    <button class="tour-button btn-disabled" disabled>
                                        ✅ Đã được phân công
                                    </button>
                                <?php else: ?>
                                    <button class="tour-button btn-disabled" disabled>
                                        ❌ Không thể đăng ký
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">🚫</div>
                    <div class="empty-message">Không tìm thấy tour nào phù hợp</div>
                    <div class="empty-suggestion">Hãy thử thay đổi bộ lọc để tìm kiếm tours khác.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function requestTour(tourId) {
            if (confirm('Bạn có chắc chắn muốn đăng ký tour này không?')) {
                const formData = new FormData();
                formData.append('tour_id', tourId);
                
                fetch('<?= BASE_URL ?>?act=guide-request-tour', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Yêu cầu đã được gửi thành công!');
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
        
        // Auto-submit form when filters change
        document.querySelectorAll('select[name="departure_id"], select[name="status"]').forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });
    </script>
</body>
</html>
