<?php require_once './views/quantri/layout/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Quản lý Đoàn khởi hành</h1>
</div>

<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tour</th>
                <th>Mã Tour</th>
                <th>Ngày khởi hành</th>
                <th>Ngày về</th>
                <th>HDV</th>
                <th>Min PAX</th>
                <th>Max PAX</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($departures)): ?>
                <?php foreach ($departures as $departure): ?>
                    <tr>
                        <td><?= $departure['id'] ?></td>
                        <td><?= htmlspecialchars($departure['tour_name']) ?></td>
                        <td><?= htmlspecialchars($departure['tour_code']) ?></td>
                        <td><?= date('d/m/Y', strtotime($departure['departure_date'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($departure['return_date'])) ?></td>
                        <td><?= htmlspecialchars($departure['guide_name'] ?? 'Chưa phân công') ?></td>
                        <td><?= $departure['min_pax'] ?></td>
                        <td><?= $departure['max_pax'] ?></td>
                        <td>
                            <span class="badge badge-<?= strtolower($departure['status']) ?>">
                                <?= $departure['status'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">Chưa có đoàn nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once './views/quantri/layout/footer.php'; ?>

