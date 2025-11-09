<?php require_once './views/quantri/layout/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Thêm Khách hàng mới</h1>
    <a href="<?= BASE_URL ?>?act=admin-customers" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="form-container">
    <form action="" method="POST" class="admin-form">
        <div class="form-row">
            <div class="form-group">
                <label for="name">Tên khách hàng <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại <span class="required">*</span></label>
                <input type="text" id="phone" name="phone" class="form-control" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control">
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" id="address" name="address" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="history_notes">Ghi chú lịch sử</label>
            <textarea id="history_notes" name="history_notes" class="form-control" rows="4"></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu Khách hàng
            </button>
            <a href="<?= BASE_URL ?>?act=admin-customers" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>

<?php require_once './views/quantri/layout/footer.php'; ?>

