<?php require_once './views/quantri/layout/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Quản lý Booking Assignments</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="?act=admin-dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Booking Assignments</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 stats-cards-row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-white mb-1"><?= $stats['total_assignments'] ?? 0 ?></h5>
                            <p class="card-text mb-0">Tổng assignments</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-tasks fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-white mb-1"><?= $stats['pending_assignments'] ?? 0 ?></h5>
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
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Danh sách Booking Assignments</h5>
                    <a href="?act=admin-bookings" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Gửi Booking mới
                    </a>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Filters -->
                    <div class="filter-section">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="pending">Chờ phản hồi</option>
                                    <option value="accepted">Đã chấp nhận</option>
                                    <option value="declined">Đã từ chối</option>
                                    <option value="cancelled">Đã hủy</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Mức độ ưu tiên</label>
                                <select class="form-select" id="priorityFilter">
                                    <option value="">Tất cả mức độ</option>
                                    <option value="urgent">Khẩn cấp</option>
                                    <option value="high">Cao</option>
                                    <option value="medium">Trung bình</option>
                                    <option value="low">Thấp</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tìm kiếm</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchInput" placeholder="Booking code, tên khách hàng, HDV...">
                                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="assignment-table-container">
                        <table class="table assignment-table" id="assignmentsTable">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-barcode me-2"></i>Booking Code</th>
                                    <th><i class="fas fa-user me-2"></i>Khách hàng</th>
                                    <th><i class="fas fa-user-tie me-2"></i>HDV</th>
                                    <th><i class="fas fa-user-cog me-2"></i>Người giao</th>
                                    <th><i class="fas fa-calendar me-2"></i>Ngày giao</th>
                                    <th><i class="fas fa-clock me-2"></i>Deadline</th>
                                    <th><i class="fas fa-flag me-2"></i>Mức độ</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($assignments)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                                <p class="text-muted">Chưa có booking assignment nào</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($assignments as $assignment): ?>
                                        <tr data-status="<?= $assignment['status'] ?>" 
                                            data-priority="<?= $assignment['priority'] ?>"
                                            data-search="<?= strtolower($assignment['booking_code'] . ' ' . $assignment['customer_name'] . ' ' . $assignment['guide_name']) ?>">
                                            <td>
                                                <strong><?= htmlspecialchars($assignment['booking_code']) ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <?= number_format($assignment['total_amount'], 0, ',', '.') ?> VNĐ
                                                </small>
                                            </td>
                                            <td>
                                                <div class="customer-info-card">
                                                    <strong><?= htmlspecialchars($assignment['customer_name']) ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone me-1"></i><?= htmlspecialchars($assignment['customer_phone']) ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="guide-info-card">
                                                    <strong><?= htmlspecialchars($assignment['guide_name']) ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone me-1"></i><?= htmlspecialchars($assignment['guide_phone']) ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="fas fa-user-shield me-1"></i>
                                                <?= htmlspecialchars($assignment['assigned_by_name']) ?>
                                            </td>
                                            <td>
                                                <i class="fas fa-calendar-plus me-1"></i>
                                                <small><?= date('d/m/Y H:i', strtotime($assignment['assigned_date'])) ?></small>
                                            </td>
                                            <td>
                                                <?php if ($assignment['deadline']): ?>
                                                    <i class="fas fa-clock me-1"></i>
                                                    <small class="<?= strtotime($assignment['deadline']) < time() ? 'text-danger fw-bold' : 'text-warning' ?>">
                                                        <?= date('d/m/Y H:i', strtotime($assignment['deadline'])) ?>
                                                        <?php if (strtotime($assignment['deadline']) < time()): ?>
                                                            <br><span class="badge bg-danger">Quá hạn</span>
                                                        <?php endif; ?>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Không giới hạn</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $priority_classes = [
                                                    'urgent' => 'priority-high',
                                                    'high' => 'priority-high', 
                                                    'medium' => 'priority-medium',
                                                    'low' => 'priority-low'
                                                ];
                                                $priority_labels = [
                                                    'urgent' => 'Khẩn cấp',
                                                    'high' => 'Cao',
                                                    'medium' => 'Trung bình',
                                                    'low' => 'Thấp'
                                                ];
                                                $priority_icons = [
                                                    'urgent' => 'fas fa-exclamation-triangle',
                                                    'high' => 'fas fa-arrow-up',
                                                    'medium' => 'fas fa-minus',
                                                    'low' => 'fas fa-arrow-down'
                                                ];
                                                ?>
                                                <span class="priority-indicator <?= $priority_classes[$assignment['priority']] ?>">
                                                    <i class="<?= $priority_icons[$assignment['priority']] ?>"></i>
                                                    <?= $priority_labels[$assignment['priority']] ?>
                                                </span>
                                            </td>
                                            <td>
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
                                                $status_icons = [
                                                    'pending' => 'fas fa-clock',
                                                    'accepted' => 'fas fa-check-circle',
                                                    'declined' => 'fas fa-times-circle',
                                                    'cancelled' => 'fas fa-ban'
                                                ];
                                                ?>
                                                <span class="status-badge badge-<?= $status_classes[$assignment['status']] ?>">
                                                    <i class="<?= $status_icons[$assignment['status']] ?>"></i>
                                                    <?= $status_labels[$assignment['status']] ?>
                                                </span>
                                                <?php if ($assignment['response_date']): ?>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-check me-1"></i>
                                                        <?= date('d/m/Y H:i', strtotime($assignment['response_date'])) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="?act=admin-booking-assignment-detail&id=<?= $assignment['id'] ?>" 
                                                       class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($assignment['status'] === 'pending'): ?>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger cancel-assignment-btn" 
                                                                data-assignment-id="<?= $assignment['id'] ?>"
                                                                title="Hủy assignment">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('assignmentsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    // Filter function
    function filterTable() {
        const statusValue = statusFilter.value;
        const priorityValue = priorityFilter.value;
        const searchValue = searchInput.value.toLowerCase();
        
        for (let row of rows) {
            if (row.cells.length === 1) continue; // Skip empty state row
            
            const status = row.getAttribute('data-status');
            const priority = row.getAttribute('data-priority');
            const searchText = row.getAttribute('data-search');
            
            let showRow = true;
            
            if (statusValue && status !== statusValue) showRow = false;
            if (priorityValue && priority !== priorityValue) showRow = false;
            if (searchValue && !searchText.includes(searchValue)) showRow = false;
            
            row.style.display = showRow ? '' : 'none';
        }
    }
    
    // Event listeners
    statusFilter.addEventListener('change', filterTable);
    priorityFilter.addEventListener('change', filterTable);
    searchInput.addEventListener('input', filterTable);
    document.getElementById('searchBtn').addEventListener('click', filterTable);
    
    // Cancel assignment functionality
    let assignmentIdToCancel = null;
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.cancel-assignment-btn')) {
            const btn = e.target.closest('.cancel-assignment-btn');
            assignmentIdToCancel = btn.getAttribute('data-assignment-id');
            const modal = new bootstrap.Modal(document.getElementById('cancelAssignmentModal'));
            modal.show();
        }
    });
    
    document.getElementById('confirmCancelAssignment').addEventListener('click', function() {
        if (assignmentIdToCancel) {
            // Show loading overlay
            showLoadingOverlay();
            
            fetch('?act=cancel-booking-assignment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'assignment_id=' + assignmentIdToCancel
            })
            .then(response => response.json())
            .then(data => {
                hideLoadingOverlay();
                if (data.success) {
                    // Show success message
                    showAlert('success', 'Assignment đã được hủy thành công!');
                    // Reload page after 1 second
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert('error', 'Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                hideLoadingOverlay();
                console.error('Error:', error);
                showAlert('error', 'Có lỗi xảy ra khi hủy assignment');
            });
        }
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('cancelAssignmentModal'));
        modal.hide();
    });
    
    // Helper functions
    function showLoadingOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = '<div class="loading-spinner"></div>';
        overlay.id = 'loadingOverlay';
        document.body.appendChild(overlay);
    }
    
    function hideLoadingOverlay() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.remove();
        }
    }
    
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insert at the top of card body
        const cardBody = document.querySelector('.card-body');
        cardBody.insertAdjacentHTML('afterbegin', alertHtml);
    }
});
</script>

<?php require_once './views/quantri/layout/footer.php'; ?>