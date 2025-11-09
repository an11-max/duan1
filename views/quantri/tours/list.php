<?php require_once './views/quantri/layout/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Quản lý Tours</h1>
    <a href="<?= BASE_URL ?>?act=admin-tour-add" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Tour mới
    </a>
</div>

<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Hình ảnh</th>
                <th>Mã Tour</th>
                <th>Tên Tour</th>
                <th>Thời gian</th>
                <th>Loại</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tours)): ?>
                <?php foreach ($tours as $tour): ?>
                    <tr>
                        <td><?= $tour['id'] ?></td>
                        <td>
                            <?php if ($tour['image']): ?>
                                <img src="<?= BASE_URL . $tour['image'] ?>" alt="<?= htmlspecialchars($tour['name']) ?>" class="table-img">
                            <?php else: ?>
                                <img src="<?= BASE_URL ?>assets/images/no-image.png" alt="No image" class="table-img">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($tour['tour_code']) ?></td>
                        <td><?= htmlspecialchars($tour['name']) ?></td>
                        <td><?= htmlspecialchars($tour['duration']) ?></td>
                        <td>
                            <?php if ($tour['is_international']): ?>
                                <span class="badge badge-info">Quốc tế</span>
                            <?php else: ?>
                                <span class="badge badge-success">Trong nước</span>
                            <?php endif; ?>
                        </td>
                        <td class="action-buttons">
                            <a href="<?= BASE_URL ?>?act=admin-tour-edit&id=<?= $tour['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= BASE_URL ?>?act=admin-tour-delete&id=<?= $tour['id'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa tour này?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Chưa có tour nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once './views/quantri/layout/footer.php'; ?>

