<?php require_once './views/admin/layout/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Booking Assignments</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="?act=guide-dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Booking Assignments</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-white mb-1"><?= count($pendingAssignments) ?></h5>
                            <p class="card-text mb-0">Chờ phản hồi</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-white mb-1"><?= $stats['accepted_assignments'] ?? 0 ?></h5>
                            <p class="card-text mb-0">Đã chấp nhận</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-white mb-1"><?= $stats['declined_assignments'] ?? 0 ?></h5>
                            <p class="card-text mb-0">Đã từ chối</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-white mb-1"><?= count($expiredAssignments) ?></h5>
                            <p class="card-text mb-0">Đã quá hạn</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="assignmentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" 
                                    data-bs-target="#pending" type="button" role="tab">
                                Chờ phản hồi 
                                <span class="badge bg-warning ms-1"><?= count($pendingAssignments) ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="responded-tab" data-bs-toggle="tab" 
                                    data-bs-target="#responded" type="button" role="tab">
                                Đã phản hồi 
                                <span class="badge bg-secondary ms-1"><?= count($respondedAssignments) ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="expired-tab" data-bs-toggle="tab" 
                                    data-bs-target="#expired" type="button" role="tab">
                                Quá hạn 
                                <span class="badge bg-danger ms-1"><?= count($expiredAssignments) ?></span>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="assignmentTabsContent">
                        <!-- Pending Assignments -->
                        <div class="tab-pane fade show active" id="pending" role="tabpanel">
                            <?php if (empty($pendingAssignments)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Không có booking assignment nào đang chờ phản hồi</h5>
                                    <p class="text-muted">Các booking assignment mới sẽ xuất hiện tại đây</p>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($pendingAssignments as $assignment): ?>
                                        <div class="col-lg-6 col-xl-4 mb-4">
                                            <div class="card border-warning h-100">
                                                <div class="card-header bg-warning bg-opacity-10 d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($assignment['booking_code']) ?></h6>
                                                    <?php
                                                    $priority_classes = [
                                                        'urgent' => 'danger',
                                                        'high' => 'warning',
                                                        'medium' => 'info',
                                                        'low' => 'secondary'
                                                    ];
                                                    $priority_labels = [
                                                        'urgent' => 'Khẩn cấp',
                                                        'high' => 'Cao',
                                                        'medium' => 'Trung bình',
                                                        'low' => 'Thấp'
                                                    ];
                                                    ?>
                                                    <span class="badge bg-<?= $priority_classes[$assignment['priority']] ?>">
                                                        <?= $priority_labels[$assignment['priority']] ?>
                                                    </span>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <strong>Khách hàng:</strong>
                                                        <p class="mb-1"><?= htmlspecialchars($assignment['customer_name']) ?></p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-phone fa-sm me-1"></i><?= htmlspecialchars($assignment['customer_phone']) ?>
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <strong>Tổng tiền:</strong>
                                                        <p class="mb-0 text-success fw-bold">
                                                            <?= number_format($assignment['total_amount'], 0, ',', '.') ?> VNĐ
                                                        </p>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <strong>Ngày giao:</strong>
                                                        <p class="mb-0"><?= date('d/m/Y H:i', strtotime($assignment['assigned_date'])) ?></p>
                                                    </div>
                                                    
                                                    <?php if ($assignment['deadline']): ?>
                                                    <div class="mb-3">
                                                        <strong>Deadline:</strong>
                                                        <p class="mb-0 <?= strtotime($assignment['deadline']) < time() ? 'text-danger fw-bold' : 'text-warning' ?>">
                                                            <?= date('d/m/Y H:i', strtotime($assignment['deadline'])) ?>
                                                            <?php if (strtotime($assignment['deadline']) < time()): ?>
                                                                <i class="fas fa-exclamation-triangle ms-1" title="Đã quá hạn"></i>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($assignment['notes']): ?>
                                                    <div class="mb-3">
                                                        <strong>Ghi chú:</strong>
                                                        <p class="mb-0 text-muted small">
                                                            <?= nl2br(htmlspecialchars(substr($assignment['notes'], 0, 100))) ?>
                                                            <?php if (strlen($assignment['notes']) > 100): ?>...<?php endif; ?>
                                                        </p>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="card-footer bg-transparent">
                                                    <div class="d-grid gap-2">
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-success respond-btn" 
                                                                    data-assignment-id="<?= $assignment['id'] ?>"
                                                                    data-action="accepted">
                                                                <i class="fas fa-check me-1"></i>Chấp nhận
                                                            </button>
                                                            <button type="button" class="btn btn-danger respond-btn" 
                                                                    data-assignment-id="<?= $assignment['id'] ?>"
                                                                    data-action="declined">
                                                                <i class="fas fa-times me-1"></i>Từ chối
                                                            </button>
                                                        </div>
                                                        <a href="?act=guide-booking-assignment-detail&id=<?= $assignment['id'] ?>" 
                                                           class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Responded Assignments -->
                        <div class="tab-pane fade" id="responded" role="tabpanel">
                            <?php if (empty($respondedAssignments)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-check-circle fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Chưa có booking assignment nào đã phản hồi</h5>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Booking Code</th>
                                                <th>Khách hàng</th>
                                                <th>Tổng tiền</th>
                                                <th>Ngày phản hồi</th>
                                                <th>Trạng thái</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($respondedAssignments as $assignment): ?>
                                                <tr>
                                                    <td>
                                                        <strong><?= htmlspecialchars($assignment['booking_code']) ?></strong>
                                                    </td>
                                                    <td><?= htmlspecialchars($assignment['customer_name']) ?></td>
                                                    <td>
                                                        <span class="fw-bold text-success">
                                                            <?= number_format($assignment['total_amount'], 0, ',', '.') ?> VNĐ
                                                        </span>
                                                    </td>
                                                    <td><?= date('d/m/Y H:i', strtotime($assignment['response_date'])) ?></td>
                                                    <td>
                                                        <?php
                                                        $status_classes = [
                                                            'accepted' => 'success',
                                                            'declined' => 'danger',
                                                            'cancelled' => 'secondary'
                                                        ];
                                                        $status_labels = [
                                                            'accepted' => 'Đã chấp nhận',
                                                            'declined' => 'Đã từ chối',
                                                            'cancelled' => 'Đã hủy'
                                                        ];
                                                        ?>
                                                        <span class="badge bg-<?= $status_classes[$assignment['status']] ?>">
                                                            <?= $status_labels[$assignment['status']] ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="?act=guide-booking-assignment-detail&id=<?= $assignment['id'] ?>" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Expired Assignments -->
                        <div class="tab-pane fade" id="expired" role="tabpanel">
                            <?php if (empty($expiredAssignments)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-clock fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Không có booking assignment nào quá hạn</h5>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Các booking assignment dưới đây đã quá hạn phản hồi
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Booking Code</th>
                                                <th>Khách hàng</th>
                                                <th>Tổng tiền</th>
                                                <th>Deadline</th>
                                                <th>Quá hạn</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($expiredAssignments as $assignment): ?>
                                                <tr class="table-danger">
                                                    <td>
                                                        <strong><?= htmlspecialchars($assignment['booking_code']) ?></strong>
                                                    </td>
                                                    <td><?= htmlspecialchars($assignment['customer_name']) ?></td>
                                                    <td>
                                                        <span class="fw-bold">
                                                            <?= number_format($assignment['total_amount'], 0, ',', '.') ?> VNĐ
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?= date('d/m/Y H:i', strtotime($assignment['deadline'])) ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $overdue_hours = floor((time() - strtotime($assignment['deadline'])) / 3600);
                                                        if ($overdue_hours < 24) {
                                                            echo $overdue_hours . ' giờ';
                                                        } else {
                                                            echo floor($overdue_hours / 24) . ' ngày';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="?act=guide-booking-assignment-detail&id=<?= $assignment['id'] ?>" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Response Modal -->
<div class="modal fade" id="responseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="responseModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="responseText" class="form-label">Lý do/Ghi chú phản hồi:</label>
                    <textarea class="form-control" id="responseText" rows="4" 
                              placeholder="Nhập lý do chấp nhận/từ chối hoặc ghi chú khác..."></textarea>
                    <div class="form-text">Phản hồi này sẽ được gửi cho admin</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmResponse">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentAssignmentId = null;
    let currentAction = null;
    
    // Handle response buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.respond-btn')) {
            const btn = e.target.closest('.respond-btn');
            currentAssignmentId = btn.getAttribute('data-assignment-id');
            currentAction = btn.getAttribute('data-action');
            
            const modal = new bootstrap.Modal(document.getElementById('responseModal'));
            const modalTitle = document.getElementById('responseModalTitle');
            const confirmBtn = document.getElementById('confirmResponse');
            
            if (currentAction === 'accepted') {
                modalTitle.textContent = 'Chấp nhận Booking Assignment';
                confirmBtn.className = 'btn btn-success';
                confirmBtn.innerHTML = '<i class="fas fa-check me-1"></i>Chấp nhận';
            } else {
                modalTitle.textContent = 'Từ chối Booking Assignment';
                confirmBtn.className = 'btn btn-danger';
                confirmBtn.innerHTML = '<i class="fas fa-times me-1"></i>Từ chối';
            }
            
            modal.show();
        }
    });
    
    // Confirm response
    document.getElementById('confirmResponse').addEventListener('click', function() {
        const responseText = document.getElementById('responseText').value;
        
        if (currentAction === 'declined' && !responseText.trim()) {
            alert('Vui lòng nhập lý do từ chối');
            return;
        }
        
        fetch('?act=guide-respond-booking-assignment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `assignment_id=${currentAssignmentId}&status=${currentAction}&response=${encodeURIComponent(responseText)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi phản hồi assignment');
        });
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('responseModal'));
        modal.hide();
    });
    
    // Clear modal when hidden
    document.getElementById('responseModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('responseText').value = '';
        currentAssignmentId = null;
        currentAction = null;
    });
});
</script>

<?php require_once './views/admin/layout/footer.php'; ?>