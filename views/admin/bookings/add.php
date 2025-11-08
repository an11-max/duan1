<?php require_once './views/admin/layout/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Thêm Booking mới</h1>
    <a href="<?= BASE_URL ?>?act=admin-bookings" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="form-container">
    <form action="" method="POST" class="admin-form">
        <div class="form-row">
            <div class="form-group">
                <label for="booking_code">Mã Booking <span class="required">*</span></label>
                <input type="text" id="booking_code" name="booking_code" class="form-control" value="BK<?= date('YmdHis') ?>" required>
            </div>

            <div class="form-group">
                <label for="customer_id">Khách hàng <span class="required">*</span></label>
                <select id="customer_id" name="customer_id" class="form-control" required>
                    <option value="">-- Chọn khách hàng --</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?= $customer['id'] ?>"><?= htmlspecialchars($customer['name']) ?> - <?= htmlspecialchars($customer['phone']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="booking_date">Ngày đặt <span class="required">*</span></label>
                <input type="date" id="booking_date" name="booking_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label for="status">Trạng thái <span class="required">*</span></label>
                <select id="status" name="status" class="form-control" required>
                    <option value="Pending">Pending</option>
                    <option value="Deposited">Deposited</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="total_amount">Tổng tiền <span class="required">*</span></label>
                <input type="number" id="total_amount" name="total_amount" class="form-control" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="deposit_amount">Tiền đặt cọc <span class="required">*</span></label>
                <input type="number" id="deposit_amount" name="deposit_amount" class="form-control" step="0.01" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu Booking
            </button>
            <a href="<?= BASE_URL ?>?act=admin-bookings" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>

<?php require_once './views/admin/layout/footer.php'; ?>

