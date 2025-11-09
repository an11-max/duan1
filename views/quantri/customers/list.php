<?php require_once './views/quantri/layout/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Quản lý Khách hàng</h1>
    <a href="<?= BASE_URL ?>?act=admin-customer-add" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Khách hàng mới
    </a>
</div>

<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên khách hàng</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Địa chỉ</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= $customer['id'] ?></td>
                        <td><?= htmlspecialchars($customer['name']) ?></td>
                        <td><?= htmlspecialchars($customer['phone']) ?></td>
                        <td><?= htmlspecialchars($customer['email']) ?></td>
                        <td><?= htmlspecialchars($customer['address']) ?></td>
                        <td class="action-buttons">
                            <a href="<?= BASE_URL ?>?act=admin-customer-edit&id=<?= $customer['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= BASE_URL ?>?act=admin-customer-delete&id=<?= $customer['id'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Chưa có khách hàng nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once './views/quantri/layout/footer.php'; ?>

