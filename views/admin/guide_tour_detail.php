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
    <title>Chi tiết Tour - Hướng dẫn viên</title>
    <link rel="stylesheet" href="./assets/css/admin.css">
    <style>
        .tour-detail {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #16a085, #1abc9c);
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
        
        .tour-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }
        
        .tour-main {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .tour-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .tour-image {
            width: 100%;
            height: 300px;
            background: linear-gradient(45deg, #16a085, #1abc9c);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4em;
            margin-bottom: 25px;
        }
        
        .tour-title {
            font-size: 2em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .tour-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #16a085;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        
        .info-value {
            color: #2c3e50;
            font-weight: 600;
        }
        
        .tour-description {
            line-height: 1.6;
            color: #495057;
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 1.3em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 2px solid #16a085;
            padding-bottom: 8px;
        }
        
        .customers-list {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }
        
        .customer-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .customer-item:last-child {
            border-bottom: none;
        }
        
        .customer-info {
            flex: 1;
        }
        
        .customer-name {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 3px;
        }
        
        .customer-contact {
            color: #6c757d;
            font-size: 0.9em;
        }
        
        .booking-status {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .price-card {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            text-align: center;
            border-radius: 10px;
        }
        
        .price-amount {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .price-label {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .status-card {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            text-align: center;
            border-radius: 10px;
        }
        
        .status-icon {
            font-size: 3em;
            margin-bottom: 10px;
        }
        
        .status-text {
            font-size: 1.2em;
            font-weight: bold;
        }
        
        .contact-card {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border-radius: 10px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .contact-item:last-child {
            margin-bottom: 0;
        }
        
        .contact-icon {
            font-size: 1.2em;
        }
        
        .itinerary {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .day-item {
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            border-left: 4px solid #16a085;
        }
        
        .day-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .day-activities {
            color: #495057;
            line-height: 1.5;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #7f8c8d;
        }
        
        .empty-icon {
            font-size: 3em;
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .tour-content {
                grid-template-columns: 1fr;
            }
            
            .tour-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="tour-detail">
        <div class="page-header">
            <h1 class="page-title">🗺️ Chi tiết Tour</h1>
            <a href="<?= BASE_URL ?>?act=guide-tours" class="back-button">← Quay lại danh sách</a>
        </div>

        <?php if ($tour): ?>
            <div class="tour-content">
                <div class="tour-main">
                    <div class="tour-image">🏖️</div>
                    
                    <h2 class="tour-title"><?= htmlspecialchars($tour['tour_name']) ?></h2>
                    
                    <div class="tour-info-grid">
                        <div class="info-item">
                            <span class="info-label">📍 Điểm đến:</span>
                            <span class="info-value"><?= htmlspecialchars($tour['destination']) ?></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">🚌 Khởi hành:</span>
                            <span class="info-value"><?= htmlspecialchars($tour['departure_location']) ?></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">📅 Ngày đi:</span>
                            <span class="info-value"><?= date('d/m/Y', strtotime($tour['departure_date'])) ?></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">📅 Ngày về:</span>
                            <span class="info-value"><?= date('d/m/Y', strtotime($tour['return_date'])) ?></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">⏰ Thời gian:</span>
                            <span class="info-value"><?= $tour['duration'] ?> ngày</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">👥 Số người:</span>
                            <span class="info-value"><?= $tour['current_participants'] ?>/<?= $tour['max_participants'] ?> người</span>
                        </div>
                    </div>
                    
                    <div class="tour-description">
                        <h3 class="section-title">📋 Mô tả Tour</h3>
                        <p><?= nl2br(htmlspecialchars($tour['description'])) ?></p>
                    </div>
                    
                    <?php if (!empty($tour['itinerary'])): ?>
                        <div class="itinerary">
                            <h3 class="section-title">🗓️ Lịch trình chi tiết</h3>
                            <?php 
                            $itinerary = json_decode($tour['itinerary'], true);
                            if ($itinerary && is_array($itinerary)):
                                foreach ($itinerary as $day => $activities): ?>
                                    <div class="day-item">
                                        <div class="day-title">📅 <?= htmlspecialchars($day) ?></div>
                                        <div class="day-activities"><?= nl2br(htmlspecialchars($activities)) ?></div>
                                    </div>
                                <?php endforeach;
                            else: ?>
                                <p><?= nl2br(htmlspecialchars($tour['itinerary'])) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="customers-section">
                        <h3 class="section-title">👥 Danh sách khách hàng (<?= count($customers) ?> người)</h3>
                        
                        <?php if (!empty($customers)): ?>
                            <div class="customers-list">
                                <?php foreach ($customers as $customer): ?>
                                    <div class="customer-item">
                                        <div class="customer-info">
                                            <div class="customer-name">
                                                👤 <?= htmlspecialchars($customer['full_name']) ?>
                                            </div>
                                            <div class="customer-contact">
                                                📞 <?= htmlspecialchars($customer['phone']) ?> 
                                                • ✉️ <?= htmlspecialchars($customer['email']) ?>
                                            </div>
                                        </div>
                                        <div class="booking-status status-<?= $customer['booking_status'] ?>">
                                            <?php
                                            switch ($customer['booking_status']) {
                                                case 'confirmed': echo '✅ Đã xác nhận'; break;
                                                case 'pending': echo '⏳ Chờ xác nhận'; break;
                                                case 'cancelled': echo '❌ Đã hủy'; break;
                                                default: echo $customer['booking_status'];
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">👥</div>
                                <p>Chưa có khách hàng đăng ký tour này.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="tour-sidebar">
                    <div class="info-card price-card">
                        <div class="price-amount"><?= number_format($tour['price'], 0, ',', '.') ?></div>
                        <div class="price-label">VND / người</div>
                    </div>
                    
                    <div class="info-card status-card">
                        <div class="status-icon">
                            <?php
                            $status = $tour['status'] ?? 'active';
                            switch ($status) {
                                case 'active': echo '✅'; break;
                                case 'completed': echo '🏁'; break;
                                case 'cancelled': echo '❌'; break;
                                default: echo '❓';
                            }
                            ?>
                        </div>
                        <div class="status-text">
                            <?php
                            switch ($status) {
                                case 'active': echo 'Tour đang hoạt động'; break;
                                case 'completed': echo 'Tour đã hoàn thành'; break;
                                case 'cancelled': echo 'Tour đã bị hủy'; break;
                                default: echo 'Trạng thái không xác định';
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="info-card contact-card">
                        <h4 style="margin-top: 0; margin-bottom: 15px;">📞 Thông tin liên hệ</h4>
                        
                        <div class="contact-item">
                            <span class="contact-icon">🏢</span>
                            <span>Công ty Du lịch</span>
                        </div>
                        
                        <div class="contact-item">
                            <span class="contact-icon">📞</span>
                            <span>Hotline: 1900-xxxx</span>
                        </div>
                        
                        <div class="contact-item">
                            <span class="contact-icon">✉️</span>
                            <span>support@company.com</span>
                        </div>
                        
                        <div class="contact-item">
                            <span class="contact-icon">🌐</span>
                            <span>www.company.com</span>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <h4 style="margin-top: 0; margin-bottom: 15px;">ℹ️ Ghi chú quan trọng</h4>
                        <ul style="margin: 0; padding-left: 20px; color: #495057;">
                            <li>Vui lòng có mặt trước 30 phút</li>
                            <li>Mang theo CMND/CCCD gốc</li>
                            <li>Trang phục phù hợp với thời tiết</li>
                            <li>Liên hệ HDV khi cần hỗ trợ</li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">🚫</div>
                <h3>Không tìm thấy tour</h3>
                <p>Tour này không tồn tại hoặc bạn không có quyền truy cập.</p>
                <a href="<?= BASE_URL ?>?act=guide-tours" class="back-button" style="display: inline-block; margin-top: 15px;">
                    ← Quay lại danh sách tours
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>