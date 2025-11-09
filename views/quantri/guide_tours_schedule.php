<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tours & Lịch trình - HDV Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .tour-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: none;
            border-radius: 12px;
        }
        
        .tour-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .tour-status-assigned {
            border-left: 4px solid #ffc107;
        }
        
        .tour-status-accepted {
            border-left: 4px solid #28a745;
        }
        
        .tour-status-declined {
            border-left: 4px solid #dc3545;
        }
        
        .tour-status-cancelled {
            border-left: 4px solid #6c757d;
        }
        
        .timeline-item {
            position: relative;
            padding-left: 20px;
        }
        
        .timeline-item:before {
            content: '';
            position: absolute;
            left: 6px;
            top: 8px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #007bff;
        }
        
        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 0.375rem;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                <div>
                    <h2><i class="fas fa-calendar-alt text-primary me-2"></i>Tours được phân công & Lịch trình</h2>
                    <p class="text-muted mb-0">Quản lý các tour được phân công theo thời gian</p>
                </div>
                <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Về Dashboard
                </a>
            </div>

            <!-- Filter Tabs -->
            <div class="row mt-4">
                <div class="col-12">
                    <ul class="nav nav-pills justify-content-center mb-4" id="statusFilter">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-filter="all">
                                <i class="fas fa-list me-1"></i>Tất cả
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="assigned">
                                <i class="fas fa-clock me-1"></i>Chờ xác nhận
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="accepted">
                                <i class="fas fa-check-circle me-1"></i>Đã chấp nhận
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="declined">
                                <i class="fas fa-times-circle me-1"></i>Đã từ chối
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tours Grid -->
            <div class="row" id="toursContainer">
                <?php if (empty($assignedTours)): ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Chưa có tour nào được phân công</h4>
                            <p class="text-muted">Bạn chưa có tour nào được phân công từ quản trị viên.</p>
                            <a href="<?= BASE_URL ?>?act=guide-request-form" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Yêu cầu tour mới
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($assignedTours as $tour): ?>
                        <div class="col-lg-6 col-xl-4 mb-4 tour-item" data-status="<?= $tour['status'] ?>">
                            <div class="card tour-card shadow-sm tour-status-<?= $tour['status'] ?>">
                                <div class="card-body">
                                    <!-- Tour Header -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="card-title mb-1"><?= htmlspecialchars($tour['tour_name']) ?></h5>
                                            <p class="text-muted mb-0">
                                                <code class="bg-light px-2 py-1 rounded"><?= htmlspecialchars($tour['tour_code']) ?></code>
                                            </p>
                                        </div>
                                        <div>
                                            <?php if ($tour['status'] == 'assigned'): ?>
                                                <span class="badge bg-warning">Chờ xác nhận</span>
                                            <?php elseif ($tour['status'] == 'accepted'): ?>
                                                <span class="badge bg-success">Đã chấp nhận</span>
                                            <?php elseif ($tour['status'] == 'declined'): ?>
                                                <span class="badge bg-danger">Đã từ chối</span>
                                            <?php elseif ($tour['status'] == 'cancelled'): ?>
                                                <span class="badge bg-secondary">Đã hủy</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Tour Image -->
                                    <?php if (!empty($tour['image'])): ?>
                                        <img src="<?= BASE_URL ?>uploads/tours/<?= htmlspecialchars($tour['image']) ?>" 
                                             class="card-img-top rounded mb-3" 
                                             style="height: 150px; object-fit: cover;"
                                             alt="<?= htmlspecialchars($tour['tour_name']) ?>"
                                             onerror="this.src='<?= BASE_URL ?>assets/images/default-tour.jpg'">
                                    <?php else: ?>
                                        <div class="mb-3 bg-light d-flex align-items-center justify-content-center rounded" style="height: 150px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Tour Info -->
                                    <div class="mb-3">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="border-end">
                                                    <i class="fas fa-clock text-primary"></i>
                                                    <div><small><?= htmlspecialchars($tour['duration']) ?></small></div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <?php if ($tour['is_international']): ?>
                                                    <i class="fas fa-globe text-info"></i>
                                                    <div><small>Quốc tế</small></div>
                                                <?php else: ?>
                                                    <i class="fas fa-map text-success"></i>
                                                    <div><small>Trong nước</small></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Assignment Info -->
                                    <div class="bg-light p-3 rounded mb-3">
                                        <div class="timeline-item">
                                            <strong>Được phân công bởi:</strong> <?= htmlspecialchars($tour['assigned_by_name']) ?>
                                            <div class="text-muted small">
                                                <?= $tour['assigned_by_role'] == 'super_admin' ? 'Super Admin' : 'Admin' ?>
                                            </div>
                                        </div>
                                        <div class="timeline-item mt-2">
                                            <strong>Thời gian phân công:</strong>
                                            <div class="text-muted small">
                                                <?= date('d/m/Y H:i', strtotime($tour['assigned_date'])) ?>
                                            </div>
                                        </div>
                                        <?php if ($tour['response_date']): ?>
                                            <div class="timeline-item mt-2">
                                                <strong>Thời gian phản hồi:</strong>
                                                <div class="text-muted small">
                                                    <?= date('d/m/Y H:i', strtotime($tour['response_date'])) ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Notes -->
                                    <?php if (!empty($tour['notes'])): ?>
                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <strong>Ghi chú:</strong> <?= htmlspecialchars($tour['notes']) ?>
                                            </small>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Action Buttons -->
                                    <div class="d-grid gap-2">
                                        <?php if ($tour['status'] == 'assigned'): ?>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-success btn-sm" onclick="respondAssignment(<?= $tour['id'] ?>, 'accepted')">
                                                    <i class="fas fa-check me-1"></i>Chấp nhận
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="respondAssignment(<?= $tour['id'] ?>, 'declined')">
                                                    <i class="fas fa-times me-1"></i>Từ chối
                                                </button>
                                            </div>
                                        <?php elseif ($tour['status'] == 'accepted'): ?>
                                            <button class="btn btn-info btn-sm" onclick="viewTourDetail(<?= $tour['tour_id'] ?>)">
                                                <i class="fas fa-calendar-check me-1"></i>Xem lịch trình chi tiết
                                            </button>
                                        <?php endif; ?>
                                        
                                        <button class="btn btn-outline-secondary btn-sm" onclick="viewTourDetail(<?= $tour['tour_id'] ?>)">
                                            <i class="fas fa-eye me-1"></i>Xem thông tin tour
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterLinks = document.querySelectorAll('#statusFilter .nav-link');
    const tourItems = document.querySelectorAll('.tour-item');
    
    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active tab
            filterLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            // Filter tours
            const filter = this.getAttribute('data-filter');
            tourItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-status') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});

function respondAssignment(assignmentId, action) {
    if (confirm(`Bạn có chắc muốn ${action === 'accepted' ? 'chấp nhận' : 'từ chối'} tour này?`)) {
        const formData = new FormData();
        formData.append('assignment_id', assignmentId);
        formData.append('action', action);
        
        fetch('<?= BASE_URL ?>?act=guide-respond-assignment', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'Cập nhật thành công!');
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra. Vui lòng thử lại!');
        });
    }
}

function viewTourDetail(tourId) {
    window.location.href = '<?= BASE_URL ?>?act=guide-tour-detail&id=' + tourId;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>