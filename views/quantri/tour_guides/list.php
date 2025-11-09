<?php require_once './views/quantri/layout/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Quản lý Hướng dẫn viên</h1>
</div>

<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Hình ảnh</th>
                <th>Tên HDV</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Giấy phép</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tourGuides)): ?>
                <?php foreach ($tourGuides as $guide): ?>
                    <tr>
                        <td><?= $guide['id'] ?></td>
                        <td>
                            <?php if ($guide['image']): ?>
                                <img src="<?= BASE_URL . $guide['image'] ?>" alt="<?= htmlspecialchars($guide['name']) ?>" class="table-img">
                            <?php else: ?>
                                <img src="<?= BASE_URL ?>assets/images/no-avatar.png" alt="No image" class="table-img">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($guide['name']) ?></td>
                        <td><?= htmlspecialchars($guide['phone']) ?></td>
                        <td><?= htmlspecialchars($guide['email']) ?></td>
                        <td><?= htmlspecialchars($guide['license_info']) ?></td>
                        <td>
                            <span class="badge badge-<?= strtolower($guide['status']) ?>">
                                <?= $guide['status'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Chưa có hướng dẫn viên nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once './views/quantri/layout/footer.php'; ?>

