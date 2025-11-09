<?php require_once './views/quantri/layout/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Chi tiết Booking Assignment</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="?act=admin-dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="?act=admin-booking-assignments">Booking Assignments</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Assignment Overview -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Thông tin Assignment</h5>
                    <div>
                        <?php
                        $status_classes = [
                            'pending' => 'warning',
                            'accepted' => 'success',
                            'declined' => 'danger',
                            'cancelled' => 'secondary'
                        ];
                        $status_labels = [
                            'pending' => 'Chờ phản hồi',
                            'accepted' => 'Đã chấp nhận',
                            'declined' => 'Đã từ chối',
                            'cancelled' => 'Đã hủy'
                        ];
                        ?>
                        <span class="badge bg-<?= $status_classes[$assignment['status']] ?> fs-6">
                            <?= $status_labels[$assignment['status']] ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Thông tin Booking</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="120"><strong>Booking Code:</strong></td>
                                    <td>
                                        <span class="fw-bold text-primary">
                                            <?= htmlspecialchars($assignment['booking_code']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Khách hàng:</strong></td>
                                    <td>
                                        <?= htmlspecialchars($assignment['customer_name']) ?>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-phone fa-sm me-1"></i><?= htmlspecialchars($assignment['customer_phone']) ?><br>
                                            <i class="fas fa-envelope fa-sm me-1"></i><?= htmlspecialchars($assignment['customer_email']) ?>
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày booking:</strong></td>
                                    <td><?= date('d/m/Y', strtotime($assignment['booking_date'])) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tổng tiền:</strong></td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            <?= number_format($assignment['total_amount'], 0, ',', '.') ?> VNĐ
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Trạng thái booking:</strong></td>
                                    <td>
                                        <?php
                                        $booking_status_classes = [
                                            'Pending' => 'warning',
                                            'Deposited' => 'info',
                                            'Completed' => 'success',
                                            'Cancelled' => 'danger'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $booking_status_classes[$assignment['booking_status']] ?? 'secondary' ?>">
                                            <?= htmlspecialchars($assignment['booking_status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Thông tin HDV</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="100"><strong>Tên HDV:</strong></td>
                                    <td>
                                        <span class="fw-bold">
                                            <?= htmlspecialchars($assignment['guide_name']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Liên hệ:</strong></td>
                                    <td>
                                        <i class="fas fa-phone fa-sm me-1"></i><?= htmlspecialchars($assignment['guide_phone']) ?><br>
                                        <i class="fas fa-envelope fa-sm me-1"></i><?= htmlspecialchars($assignment['guide_email']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Người giao:</strong></td>
                                    <td><?= htmlspecialchars($assignment['assigned_by_name']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày giao:</strong></td>
                                    <td><?= date('d/m/Y H:i', strtotime($assignment['assigned_date'])) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Mức độ:</strong></td>
                                    <td>
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
                                    </td>
                                </tr>
                                <?php if ($assignment['deadline']): ?>
                                <tr>
                                    <td><strong>Deadline:</strong></td>
                                    <td>
                                        <span class="<?= strtotime($assignment['deadline']) < time() && $assignment['status'] === 'pending' ? 'text-danger fw-bold' : '' ?>">
                                            <?= date('d/m/Y H:i', strtotime($assignment['deadline'])) ?>
                                            <?php if (strtotime($assignment['deadline']) < time() && $assignment['status'] === 'pending'): ?>
                                                <i class="fas fa-exclamation-triangle text-danger ms-1" title="Đã quá hạn"></i>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    
                    <?php if ($assignment['notes']): ?>
                    <div class="mt-3">
                        <h6 class="text-muted">Ghi chú từ Admin:</h6>
                        <div class="alert alert-light">
                            <?= nl2br(htmlspecialchars($assignment['notes'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($assignment['guide_response']): ?>
                    <div class="mt-3">
                        <h6 class="text-muted">Phản hồi từ HDV:</h6>
                        <div class="alert alert-<?= $assignment['status'] === 'accepted' ? 'success' : 'danger' ?>">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-<?= $assignment['status'] === 'accepted' ? 'check-circle' : 'times-circle' ?> fa-lg me-2 mt-1"></i>
                                <div>
                                    <strong>
                                        HDV đã <?= $assignment['status'] === 'accepted' ? 'chấp nhận' : 'từ chối' ?> vào 
                                        <?= date('d/m/Y H:i', strtotime($assignment['response_date'])) ?>
                                    </strong>
                                    <p class="mb-0 mt-2"><?= nl2br(htmlspecialchars($assignment['guide_response'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Actions Panel -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Thao tác</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="?act=admin-booking-assignments" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                        </a>
                        
                        <a href="?act=admin-booking-detail&id=<?= $assignment['booking_id'] ?>" class="btn btn-info">
                            <i class="fas fa-info-circle me-2"></i>Xem chi tiết Booking
                        </a>
                        
                        <?php if ($assignment['status'] === 'pending'): ?>
                        <button type="button" class="btn btn-warning" id="extendDeadlineBtn">
                            <i class="fas fa-clock me-2"></i>Gia hạn deadline
                        </button>
                        
                        <button type="button" class="btn btn-danger" id="cancelAssignmentBtn" 
                                data-assignment-id="<?= $assignment['id'] ?>">
                            <i class="fas fa-times me-2"></i>Hủy Assignment
                        </button>
                        <?php endif; ?>
                        
                        <?php if ($assignment['status'] === 'accepted'): ?>
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            HDV đã chấp nhận assignment này
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Timeline -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Assignment được tạo</h6>
                                <p class="timeline-text">
                                    Bởi <?= htmlspecialchars($assignment['assigned_by_name']) ?>
                                </p>
                                <small class="text-muted">
                                    <?= date('d/m/Y H:i', strtotime($assignment['assigned_date'])) ?>
                                </small>
                            </div>
                        </div>
                        
                        <?php if ($assignment['response_date']): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-<?= $assignment['status'] === 'accepted' ? 'success' : 'danger' ?>"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">
                                    HDV <?= $assignment['status'] === 'accepted' ? 'chấp nhận' : 'từ chối' ?>
                                </h6>
                                <p class="timeline-text">
                                    <?= htmlspecialchars($assignment['guide_name']) ?> đã phản hồi
                                </p>
                                <small class="text-muted">
                                    <?= date('d/m/Y H:i', strtotime($assignment['response_date'])) ?>
                                </small>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Chờ phản hồi</h6>
                                <p class="timeline-text">HDV chưa phản hồi</p>
                                <?php if ($assignment['deadline']): ?>
                                <small class="text-muted">
                                    Deadline: <?= date('d/m/Y H:i', strtotime($assignment['deadline'])) ?>
                                </small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Assignment Modal -->
<div class="modal fade" id="cancelAssignmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hủy Booking Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn hủy booking assignment này không?</p>
                <p class="text-muted">HDV sẽ nhận được thông báo về việc hủy này.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-danger" id="confirmCancelAssignment">Hủy Assignment</button>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    height: 100%;
    width: 2px;
    background-color: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    padding-left: 1rem;
}

.timeline-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.timeline-text {
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cancel assignment functionality
    document.getElementById('cancelAssignmentBtn')?.addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('cancelAssignmentModal'));
        modal.show();
    });
    
    document.getElementById('confirmCancelAssignment')?.addEventListener('click', function() {
        const assignmentId = document.getElementById('cancelAssignmentBtn').getAttribute('data-assignment-id');
        
        fetch('?act=cancel-booking-assignment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'assignment_id=' + assignmentId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '?act=admin-booking-assignments';
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi hủy assignment');
        });
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('cancelAssignmentModal'));
        modal.hide();
    });
});
</script>

<?php require_once './views/quantri/layout/footer.php'; ?>
