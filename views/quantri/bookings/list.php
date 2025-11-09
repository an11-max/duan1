<?php require_once './views/quantri/layout/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Quản lý Bookings</h1>
    <a href="<?= BASE_URL ?>?act=admin-booking-add" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Booking mới
    </a>
</div>

<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã Booking</th>
                <th>Khách hàng</th>
                <th>Số điện thoại</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Đặt cọc</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bookings)): ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= $booking['id'] ?></td>
                        <td><?= htmlspecialchars($booking['booking_code']) ?></td>
                        <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                        <td><?= htmlspecialchars($booking['customer_phone']) ?></td>
                        <td><?= date('d/m/Y', strtotime($booking['booking_date'])) ?></td>
                        <td><?= number_format($booking['total_amount'], 0, ',', '.') ?> VNĐ</td>
                        <td><?= number_format($booking['deposit_amount'], 0, ',', '.') ?> VNĐ</td>
                        <td>
                            <span class="badge badge-<?= strtolower($booking['status']) ?>">
                                <?= $booking['status'] ?>
                            </span>
                        </td>
                        <td class="action-buttons">
                            <a href="<?= BASE_URL ?>?act=admin-booking-edit&id=<?= $booking['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= BASE_URL ?>?act=assign-booking-form&booking_id=<?= $booking['id'] ?>" 
                               class="btn btn-sm btn-info" title="Gửi cho HDV">
                                <i class="fas fa-paper-plane"></i>
                            </a>
                            <a href="<?= BASE_URL ?>?act=admin-booking-delete&id=<?= $booking['id'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa booking này?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">Chưa có booking nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once './views/quantri/layout/footer.php'; ?>

