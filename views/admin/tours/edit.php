<?php require_once './views/admin/layout/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Chỉnh sửa Tour</h1>
    <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="form-container">
    <form action="" method="POST" enctype="multipart/form-data" class="admin-form">
        <div class="form-row">
            <div class="form-group">
                <label for="tour_code">Mã Tour <span class="required">*</span></label>
                <input type="text" id="tour_code" name="tour_code" class="form-control" value="<?= htmlspecialchars($tour['tour_code']) ?>" required>
            </div>

            <div class="form-group">
                <label for="name">Tên Tour <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($tour['name']) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="duration">Thời gian <span class="required">*</span></label>
                <input type="text" id="duration" name="duration" class="form-control" value="<?= htmlspecialchars($tour['duration']) ?>" required>
            </div>

            <div class="form-group">
                <label for="image">Hình ảnh</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                <?php if ($tour['image']): ?>
                    <div class="current-image">
                        <img src="<?= BASE_URL . $tour['image'] ?>" alt="Current image" style="max-width: 200px; margin-top: 10px;">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" class="form-control" rows="5"><?= htmlspecialchars($tour['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_international" value="1" <?= $tour['is_international'] ? 'checked' : '' ?>>
                Tour quốc tế
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật Tour
            </button>
            <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>

<?php require_once './views/admin/layout/footer.php'; ?>

