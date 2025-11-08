<?php require_once './views/admin/layout/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Gửi Booking cho HDV</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="?act=admin-dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="?act=admin-booking-assignments">Booking Assignments</a></li>
                        <li class="breadcrumb-item active">Gửi Booking</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin Booking Assignment</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Booking Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">Thông tin Booking</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Booking Code:</strong></td>
                                            <td><?= htmlspecialchars($booking['booking_code']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Khách hàng:</strong></td>
                                            <td>
                                                <?= htmlspecialchars($booking['customer_name']) ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($booking['customer_phone']) ?><br>
                                                    <?= htmlspecialchars($booking['customer_email']) ?>
                                                </small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ngày booking:</strong></td>
                                            <td><?= date('d/m/Y', strtotime($booking['booking_date'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tổng tiền:</strong></td>
                                            <td>
                                                <strong class="text-primary">
                                                    <?= number_format($booking['total_amount'], 0, ',', '.') ?> VNĐ
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tiền cọc:</strong></td>
                                            <td><?= number_format($booking['deposit_amount'], 0, ',', '.') ?> VNĐ</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Trạng thái:</strong></td>
                                            <td>
                                                <?php
                                                $status_classes = [
                                                    'Pending' => 'warning',
                                                    'Deposited' => 'info',
                                                    'Completed' => 'success',
                                                    'Cancelled' => 'danger'
                                                ];
                                                ?>
                                                <span class="badge bg-<?= $status_classes[$booking['status']] ?? 'secondary' ?>">
                                                    <?= htmlspecialchars($booking['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <form method="POST" action="?act=process-booking-assignment" id="assignmentForm">
                                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                
                                <div class="mb-3">
                                    <label for="guide_id" class="form-label">Chọn HDV <span class="text-danger">*</span></label>
                                    <select class="form-select" id="guide_id" name="guide_id" required>
                                        <option value="">-- Chọn HDV --</option>
                                        <?php foreach ($availableGuides as $guide): ?>
                                            <option value="<?= $guide['user_id'] ?>" 
                                                    data-speciality="<?= htmlspecialchars($guide['speciality']) ?>"
                                                    data-experience="<?= $guide['experience_years'] ?>"
                                                    data-rating="<?= $guide['rating'] ?>"
                                                    data-languages="<?= htmlspecialchars($guide['languages']) ?>">
                                                <?= htmlspecialchars($guide['name']) ?> 
                                                (<?= $guide['experience_years'] ?> năm kinh nghiệm, 
                                                Rating: <?= $guide['rating'] ?>/5)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <!-- Guide Information Display -->
                                <div id="guideInfo" class="alert alert-info" style="display: none;">
                                    <h6>Thông tin HDV:</h6>
                                    <div id="guideDetails"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Mức độ ưu tiên</label>
                                    <select class="form-select" id="priority" name="priority">
                                        <option value="medium">Trung bình</option>
                                        <option value="low">Thấp</option>
                                        <option value="high">Cao</option>
                                        <option value="urgent">Khẩn cấp</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="deadline" class="form-label">Thời hạn phản hồi</label>
                                    <input type="datetime-local" class="form-control" id="deadline" name="deadline">
                                    <div class="form-text">Để trống nếu không giới hạn thời gian</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Ghi chú cho HDV</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="4" 
                                              placeholder="Nhập ghi chú, yêu cầu đặc biệt hoặc thông tin quan trọng cho HDV..."></textarea>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="?act=admin-booking-assignments" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Gửi cho HDV
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const guideSelect = document.getElementById('guide_id');
    const guideInfo = document.getElementById('guideInfo');
    const guideDetails = document.getElementById('guideDetails');
    const deadlineInput = document.getElementById('deadline');
    
    // Set default deadline to 24 hours from now
    const now = new Date();
    now.setHours(now.getHours() + 24);
    deadlineInput.value = now.toISOString().slice(0, 16);
    
    // Show guide information when selected
    guideSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const speciality = selectedOption.getAttribute('data-speciality');
            const experience = selectedOption.getAttribute('data-experience');
            const rating = selectedOption.getAttribute('data-rating');
            const languages = selectedOption.getAttribute('data-languages');
            
            guideDetails.innerHTML = `
                <p><strong>Chuyên môn:</strong> ${speciality}</p>
                <p><strong>Kinh nghiệm:</strong> ${experience} năm</p>
                <p><strong>Đánh giá:</strong> ${rating}/5 sao</p>
                <p><strong>Ngôn ngữ:</strong> ${languages}</p>
            `;
            guideInfo.style.display = 'block';
        } else {
            guideInfo.style.display = 'none';
        }
    });
    
    // Form validation
    document.getElementById('assignmentForm').addEventListener('submit', function(e) {
        const guideId = document.getElementById('guide_id').value;
        
        if (!guideId) {
            e.preventDefault();
            alert('Vui lòng chọn HDV');
            return false;
        }
        
        // Confirm submission
        const guideName = document.getElementById('guide_id').options[document.getElementById('guide_id').selectedIndex].text;
        const bookingCode = '<?= htmlspecialchars($booking['booking_code']) ?>';
        
        if (!confirm(`Bạn có chắc chắn muốn gửi booking ${bookingCode} cho HDV ${guideName}?`)) {
            e.preventDefault();
            return false;
        }
    });
});
</script>

<?php require_once './views/admin/layout/footer.php'; ?>