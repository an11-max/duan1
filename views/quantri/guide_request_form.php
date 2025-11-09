<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu Tour - HDV Dashboard</title>
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
        
        .tour-status-available {
            border-left: 4px solid #28a745;
        }
        
        .tour-status-requested {
            border-left: 4px solid #ffc107;
        }
        
        .tour-status-assigned {
            border-left: 4px solid #007bff;
        }
        
        .tour-image {
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                <div>
                    <h2><i class="fas fa-hand-paper text-primary me-2"></i>Yêu cầu Tour</h2>
                    <p class="text-muted mb-0">Chọn tour bạn muốn đăng ký và gửi yêu cầu cho quản trị viên</p>
                </div>
                <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Về Dashboard
                </a>
            </div>

            <!-- Filter Tabs -->
            <div class="row mt-4">
                <div class="col-12">
                    <ul class="nav nav-pills justify-content-center mb-4" id="tourFilter">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-filter="all">
                                <i class="fas fa-globe-asia me-1"></i>Tất cả Tours
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="available">
                                <i class="fas fa-check-circle me-1"></i>Có thể yêu cầu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="requested">
                                <i class="fas fa-clock me-1"></i>Đã yêu cầu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="assigned">
                                <i class="fas fa-user-check me-1"></i>Đã được phân công
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tours Grid -->
            <div class="row" id="toursContainer">
                <?php foreach ($allTours as $tour): ?>
                    <div class="col-lg-4 col-md-6 mb-4 tour-item" data-status="<?= $tour['status'] ?>">
                        <div class="card tour-card shadow-sm tour-status-<?= $tour['status'] ?>">
                            <div class="card-body">
                                <!-- Tour Header -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1"><?= htmlspecialchars($tour['name']) ?></h5>
                                        <p class="text-muted mb-0">
                                            <code class="bg-light px-2 py-1 rounded"><?= htmlspecialchars($tour['tour_code']) ?></code>
                                        </p>
                                    </div>
                                    <div>
                                        <?php if ($tour['status'] == 'available'): ?>
                                            <span class="badge bg-success">Có thể yêu cầu</span>
                                        <?php elseif ($tour['status'] == 'requested'): ?>
                                            <span class="badge bg-warning">Đã yêu cầu</span>
                                        <?php elseif ($tour['status'] == 'assigned'): ?>
                                            <span class="badge bg-primary">Đã phân công</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Tour Image -->
                                <?php if (!empty($tour['image'])): ?>
                                    <img src="<?= BASE_URL ?>uploads/tours/<?= htmlspecialchars($tour['image']) ?>" 
                                         class="card-img-top tour-image mb-3" 
                                         alt="<?= htmlspecialchars($tour['name']) ?>"
                                         onerror="this.src='<?= BASE_URL ?>assets/images/default-tour.jpg'">
                                <?php else: ?>
                                    <div class="tour-image mb-3 bg-light d-flex align-items-center justify-content-center">
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

                                <!-- Tour Description -->
                                <?php if (!empty($tour['description'])): ?>
                                    <p class="card-text text-muted small mb-3">
                                        <?= htmlspecialchars(substr($tour['description'], 0, 100)) ?>
                                        <?= strlen($tour['description']) > 100 ? '...' : '' ?>
                                    </p>
                                <?php endif; ?>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <?php if ($tour['status'] == 'available'): ?>
                                        <button class="btn btn-success btn-sm" onclick="showRequestModal(<?= $tour['id'] ?>, '<?= htmlspecialchars($tour['name']) ?>')">
                                            <i class="fas fa-paper-plane me-1"></i>Gửi yêu cầu
                                        </button>
                                    <?php elseif ($tour['status'] == 'requested'): ?>
                                        <button class="btn btn-warning btn-sm" disabled>
                                            <i class="fas fa-clock me-1"></i>Đang chờ duyệt
                                        </button>
                                    <?php elseif ($tour['status'] == 'assigned'): ?>
                                        <button class="btn btn-primary btn-sm" disabled>
                                            <i class="fas fa-check me-1"></i>Đã được phân công
                                        </button>
                                    <?php endif; ?>
                                    
                                    <button class="btn btn-outline-secondary btn-sm" onclick="viewTourDetail(<?= $tour['id'] ?>)">
                                        <i class="fas fa-eye me-1"></i>Xem chi tiết
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($allTours)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Chưa có tour nào</h4>
                    <p class="text-muted">Hiện tại chưa có tour nào trong hệ thống.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Request Modal -->
<div class="modal fade" id="requestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-paper-plane text-primary me-2"></i>
                    Gửi yêu cầu tour
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="requestForm">
                    <input type="hidden" id="tourId" name="tour_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Tour được chọn:</label>
                        <div class="alert alert-info">
                            <i class="fas fa-map-marked-alt me-2"></i>
                            <strong id="selectedTourName"></strong>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Lời nhắn cho quản trị viên:</label>
                        <textarea class="form-control" id="message" name="message" rows="4" 
                                  placeholder="Nhập lý do bạn muốn đăng ký tour này..."></textarea>
                        <div class="form-text">Hãy cho quản trị viên biết tại sao bạn muốn đăng ký tour này.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="submitRequest()">
                    <i class="fas fa-paper-plane me-1"></i>Gửi yêu cầu
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterLinks = document.querySelectorAll('#tourFilter .nav-link');
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

function showRequestModal(tourId, tourName) {
    document.getElementById('tourId').value = tourId;
    document.getElementById('selectedTourName').textContent = tourName;
    new bootstrap.Modal(document.getElementById('requestModal')).show();
}

function submitRequest() {
    const form = document.getElementById('requestForm');
    const formData = new FormData(form);
    
    // Show loading
    const submitBtn = event.target;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang gửi...';
    submitBtn.disabled = true;
    
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
        alert('Có lỗi xảy ra. Vui lòng thử lại!');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        bootstrap.Modal.getInstance(document.getElementById('requestModal')).hide();
    });
}

function viewTourDetail(tourId) {
    window.location.href = '<?= BASE_URL ?>?act=guide-tour-detail&id=' + tourId;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>