<?php require_once './views/admin/layout/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Chi tiết Booking Assignment</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="?act=guide-dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="?act=guide-booking-assignments">Booking Assignments</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Assignment Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Booking Assignment</h5>
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
                    <!-- Booking Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-clipboard-list me-2"></i>Thông tin Booking
                            </h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td width="120"><strong>Booking Code:</strong></td>
                                                    <td>
                                                        <span class="fw-bold text-primary fs-5">
                                                            <?= htmlspecialchars($assignment['booking_code']) ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Ngày booking:</strong></td>
                                                    <td><?= date('d/m/Y', strtotime($assignment['booking_date'])) ?></td>
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
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td width="100"><strong>Tổng tiền:</strong></td>
                                                    <td>
                                                        <span class="fw-bold text-success fs-5">
                                                            <?= number_format($assignment['total_amount'], 0, ',', '.') ?> VNĐ
                                                        </span>
                                                    </td>
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
                                                            <i class="fas fa-flag me-1"></i>
                                                            <?= $priority_labels[$assignment['priority']] ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <?php if ($assignment['deadline']): ?>
                                                <tr>
                                                    <td><strong>Deadline:</strong></td>
                                                    <td>
                                                        <span class="<?= strtotime($assignment['deadline']) < time() && $assignment['status'] === 'pending' ? 'text-danger fw-bold' : 'text-info' ?>">
                                                            <i class="fas fa-clock me-1"></i>
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>Thông tin Khách hàng
                            </h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary bg-soft rounded-circle me-3">
                                                    <i class="fas fa-user fa-lg text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1"><?= htmlspecialchars($assignment['customer_name']) ?></h6>
                                                    <small class="text-muted">Khách hàng</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column">
                                                <div class="mb-2">
                                                    <i class="fas fa-phone text-primary me-2"></i>
                                                    <a href="tel:<?= htmlspecialchars($assignment['customer_phone']) ?>" class="text-decoration-none">
                                                        <?= htmlspecialchars($assignment['customer_phone']) ?>
                                                    </a>
                                                </div>
                                                <div>
                                                    <i class="fas fa-envelope text-primary me-2"></i>
                                                    <a href="mailto:<?= htmlspecialchars($assignment['customer_email']) ?>" class="text-decoration-none">
                                                        <?= htmlspecialchars($assignment['customer_email']) ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-tasks me-2"></i>Thông tin Assignment
                            </h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td width="120"><strong>Người giao:</strong></td>
                                                    <td><?= htmlspecialchars($assignment['assigned_by_name']) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Ngày giao:</strong></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($assignment['assigned_date'])) ?></td>
                                                </tr>
                                                <?php if ($assignment['response_date']): ?>
                                                <tr>
                                                    <td><strong>Ngày phản hồi:</strong></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($assignment['response_date'])) ?></td>
                                                </tr>
                                                <?php endif; ?>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <?php if ($assignment['status'] === 'pending'): ?>
                                                <div class="alert alert-warning mb-0">
                                                    <i class="fas fa-clock me-2"></i>
                                                    <strong>Chờ phản hồi từ bạn</strong>
                                                    <p class="mb-0 mt-1">Vui lòng xem xét và phản hồi assignment này</p>
                                                </div>
                                            <?php else: ?>
                                                <div class="alert alert-<?= $assignment['status'] === 'accepted' ? 'success' : 'danger' ?> mb-0">
                                                    <i class="fas fa-<?= $assignment['status'] === 'accepted' ? 'check-circle' : 'times-circle' ?> me-2"></i>
                                                    <strong>
                                                        Bạn đã <?= $assignment['status'] === 'accepted' ? 'chấp nhận' : 'từ chối' ?> assignment này
                                                    </strong>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <?php if ($assignment['notes']): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-sticky-note me-2"></i>Ghi chú từ Admin
                            </h6>
                            <div class="alert alert-info">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-info-circle fa-lg me-2 mt-1 text-info"></i>
                                    <div>
                                        <?= nl2br(htmlspecialchars($assignment['notes'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Guide Response -->
                    <?php if ($assignment['guide_response']): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-reply me-2"></i>Phản hồi của bạn
                            </h6>
                            <div class="alert alert-<?= $assignment['status'] === 'accepted' ? 'success' : 'danger' ?>">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-<?= $assignment['status'] === 'accepted' ? 'check-circle' : 'times-circle' ?> fa-lg me-2 mt-1"></i>
                                    <div>
                                        <strong>
                                            Bạn đã <?= $assignment['status'] === 'accepted' ? 'chấp nhận' : 'từ chối' ?> assignment này
                                        </strong>
                                        <p class="mb-0 mt-2"><?= nl2br(htmlspecialchars($assignment['guide_response'])) ?></p>
                                        <small class="text-muted">
                                            Phản hồi vào <?= date('d/m/Y H:i', strtotime($assignment['response_date'])) ?>
                                        </small>
                                    </div>
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
                        <a href="?act=guide-booking-assignments" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                        
                        <?php if ($assignment['status'] === 'pending'): ?>
                        <button type="button" class="btn btn-success respond-btn" 
                                data-assignment-id="<?= $assignment['id'] ?>"
                                data-action="accepted">
                            <i class="fas fa-check me-2"></i>Chấp nhận
                        </button>
                        
                        <button type="button" class="btn btn-danger respond-btn" 
                                data-assignment-id="<?= $assignment['id'] ?>"
                                data-action="declined">
                            <i class="fas fa-times me-2"></i>Từ chối
                        </button>
                        <?php endif; ?>
                        
                        <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>In trang
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Thông tin nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Assignment ID:</span>
                            <strong>#<?= $assignment['id'] ?></strong>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Trạng thái:</span>
                            <span class="badge bg-<?= $status_classes[$assignment['status']] ?>">
                                <?= $status_labels[$assignment['status']] ?>
                            </span>
                        </div>
                    </div>
                    
                    <?php if ($assignment['deadline']): ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="text-muted">Thời gian còn lại:</span>
                            <div class="text-end">
                                <?php
                                $remaining_time = strtotime($assignment['deadline']) - time();
                                if ($remaining_time > 0 && $assignment['status'] === 'pending') {
                                    $days = floor($remaining_time / 86400);
                                    $hours = floor(($remaining_time % 86400) / 3600);
                                    $minutes = floor(($remaining_time % 3600) / 60);
                                    
                                    if ($days > 0) {
                                        echo "<small class='text-warning'>{$days} ngày {$hours} giờ</small>";
                                    } else if ($hours > 0) {
                                        echo "<small class='text-warning'>{$hours} giờ {$minutes} phút</small>";
                                    } else {
                                        echo "<small class='text-danger'>{$minutes} phút</small>";
                                    }
                                } else if ($assignment['status'] === 'pending') {
                                    echo "<small class='text-danger'>Đã quá hạn</small>";
                                } else {
                                    echo "<small class='text-muted'>Đã phản hồi</small>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
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

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-soft {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
}

@media print {
    .card-header, .btn, .breadcrumb, .page-title-box {
        display: none !important;
    }
    
    .col-md-4 {
        display: none !important;
    }
    
    .col-md-8 {
        width: 100% !important;
    }
}
</style>

<?php require_once './views/admin/layout/footer.php'; ?>