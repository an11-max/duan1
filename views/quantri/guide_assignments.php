<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour được phân công - HDV Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .assignment-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: none;
            border-radius: 12px;
            border-left: 4px solid #ffc107;
        }
        
        .assignment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .priority-high {
            border-left-color: #dc3545;
        }
        
        .priority-medium {
            border-left-color: #ffc107;
        }
        
        .priority-low {
            border-left-color: #28a745;
        }
        
        .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ffc107;
            display: inline-block;
            margin-right: 8px;
        }
        
        .action-buttons .btn {
            margin: 2px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                <div>
                    <h2><i class="fas fa-tasks text-warning me-2"></i>Tour được phân công</h2>
                    <p class="text-muted mb-0">Danh sách các tour đang chờ bạn xác nhận</p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>?act=guide_schedule" class="btn btn-info me-2">
                        <i class="fas fa-calendar-alt me-1"></i>Xem lịch trình
                    </a>
                    <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Về Dashboard
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mt-4 mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h4><?= count(array_filter($assignedTours, function($t) { return $t['status'] == 'assigned'; })) ?></h4>
                            <small>Chờ xác nhận</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h4><?= count(array_filter($assignedTours, function($t) { return $t['status'] == 'accepted'; })) ?></h4>
                            <small>Đã chấp nhận</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-times-circle fa-2x mb-2"></i>
                            <h4><?= count(array_filter($assignedTours, function($t) { return $t['status'] == 'declined'; })) ?></h4>
                            <small>Đã từ chối</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-list fa-2x mb-2"></i>
                            <h4><?= count($assignedTours) ?></h4>
                            <small>Tổng cộng</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignments Grid -->
            <div class="row" id="assignmentsContainer">
                <?php 
                $pendingAssignments = array_filter($assignedTours, function($tour) {
                    return $tour['status'] == 'assigned';
                });
                ?>
                
                <?php if (empty($pendingAssignments)): ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-check fa-3x text-success mb-3"></i>
                            <h4 class="text-success">Tuyệt vời! Bạn đã xử lý hết các tour được phân công</h4>
                            <p class="text-muted">Hiện tại không có tour nào đang chờ bạn xác nhận.</p>
                            <a href="<?= BASE_URL ?>?act=guide_schedule" class="btn btn-primary">
                                <i class="fas fa-calendar-alt me-1"></i>Xem lịch trình tour
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($pendingAssignments as $assignment): ?>
                        <?php
                        // Xác định độ ưu tiên dựa trên thời gian phân công
                        $assignedTime = strtotime($assignment['assigned_date']);
                        $hoursSinceAssigned = (time() - $assignedTime) / 3600;
                        
                        if ($hoursSinceAssigned > 24) {
                            $priorityClass = 'priority-high';
                            $priorityText = 'Khẩn cấp';
                            $priorityIcon = 'fas fa-exclamation-triangle text-danger';
                        } elseif ($hoursSinceAssigned > 12) {
                            $priorityClass = 'priority-medium';
                            $priorityText = 'Ưu tiên';
                            $priorityIcon = 'fas fa-clock text-warning';
                        } else {
                            $priorityClass = 'priority-low';
                            $priorityText = 'Bình thường';
                            $priorityIcon = 'fas fa-info-circle text-info';
                        }
                        ?>
                        
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card assignment-card shadow-sm <?= $priorityClass ?>">
                                <div class="card-body">
                                    <!-- Assignment Header -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="card-title mb-1"><?= htmlspecialchars($assignment['tour_name']) ?></h5>
                                            <div class="d-flex align-items-center">
                                                <code class="bg-light px-2 py-1 rounded me-2"><?= htmlspecialchars($assignment['tour_code']) ?></code>
                                                <span class="badge bg-warning">Chờ xác nhận</span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <i class="<?= $priorityIcon ?> mb-1"></i>
                                            <div><small class="text-muted"><?= $priorityText ?></small></div>
                                        </div>
                                    </div>

                                    <!-- Tour Image -->
                                    <?php if (!empty($assignment['image'])): ?>
                                        <img src="<?= BASE_URL ?>uploads/tours/<?= htmlspecialchars($assignment['image']) ?>" 
                                             class="card-img-top rounded mb-3" 
                                             style="height: 120px; object-fit: cover;"
                                             alt="<?= htmlspecialchars($assignment['tour_name']) ?>"
                                             onerror="this.src='<?= BASE_URL ?>assets/images/default-tour.jpg'">
                                    <?php endif; ?>

                                    <!-- Tour Quick Info -->
                                    <div class="mb-3">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <i class="fas fa-clock text-primary"></i>
                                                <div><small><?= htmlspecialchars($assignment['duration'] ?? 'N/A') ?></small></div>
                                            </div>
                                            <div class="col-4">
                                                <i class="fas fa-users text-success"></i>
                                                <div><small><?= htmlspecialchars($assignment['max_participants'] ?? '0') ?> người</small></div>
                                            </div>
                                            <div class="col-4">
                                                <?php if (!empty($assignment['is_international'])): ?>
                                                    <i class="fas fa-globe text-info"></i>
                                                    <div><small>Quốc tế</small></div>
                                                <?php else: ?>
                                                    <i class="fas fa-map text-secondary"></i>
                                                    <div><small>Trong nước</small></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Assignment Timeline -->
                                    <div class="bg-light p-3 rounded mb-3">
                                        <div class="mb-2">
                                            <span class="timeline-dot"></span>
                                            <strong>Phân công bởi:</strong> <?= htmlspecialchars($assignment['assigned_by_name']) ?>
                                            <div class="ms-4">
                                                <small class="text-muted">
                                                    <?= $assignment['assigned_by_role'] == 'super_admin' ? 'Super Admin' : 'Admin' ?> • 
                                                    <?= date('d/m/Y H:i', strtotime($assignment['assigned_date'])) ?>
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <span class="timeline-dot" style="background: #6c757d;"></span>
                                            <strong>Thời gian chờ:</strong>
                                            <div class="ms-4">
                                                <small class="text-muted">
                                                    <?= round($hoursSinceAssigned) ?> giờ trước
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    <?php if (!empty($assignment['notes'])): ?>
                                        <div class="mb-3">
                                            <div class="border-start border-warning ps-3">
                                                <small><strong>Ghi chú từ admin:</strong></small>
                                                <div class="text-muted small"><?= htmlspecialchars($assignment['notes']) ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Action Buttons -->
                                    <div class="action-buttons">
                                        <div class="d-grid gap-2">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-success" onclick="respondAssignment(<?= $assignment['id'] ?>, 'accepted')">
                                                    <i class="fas fa-check me-1"></i>Chấp nhận
                                                </button>
                                                <button class="btn btn-danger" onclick="respondAssignment(<?= $assignment['id'] ?>, 'declined')">
                                                    <i class="fas fa-times me-1"></i>Từ chối
                                                </button>
                                            </div>
                                            
                                            <button class="btn btn-outline-info btn-sm" onclick="viewTourDetail(<?= $assignment['tour_id'] ?>)">
                                                <i class="fas fa-eye me-1"></i>Xem chi tiết tour
                                            </button>
                                        </div>
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
function respondAssignment(assignmentId, action) {
    const actionText = action === 'accepted' ? 'chấp nhận' : 'từ chối';
    
    if (confirm(`Bạn có chắc muốn ${actionText} tour này?\n\nHành động này không thể hoàn tác.`)) {
        // Hiển thị loading
        const buttonContainer = document.querySelector(`[onclick="respondAssignment(${assignmentId}, '${action}')"]`).closest('.action-buttons');
        buttonContainer.innerHTML = `
            <div class="text-center py-2">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Đang xử lý...</span>
                </div>
                <div class="small text-muted mt-1">Đang ${actionText}...</div>
            </div>
        `;
        
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
                // Hiển thị thông báo thành công
                buttonContainer.innerHTML = `
                    <div class="text-center py-2">
                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                        <div class="small text-success">${data.message}</div>
                    </div>
                `;
                
                // Reload trang sau 2 giây
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại!');
            location.reload();
        });
    }
}

function viewTourDetail(tourId) {
    window.location.href = '<?= BASE_URL ?>?act=guide-tour-detail&id=' + tourId;
}

// Auto refresh mỗi 5 phút để cập nhật thông tin mới
setInterval(() => {
    location.reload();
}, 300000);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>