<?php require_once './views/admin/layout/header.php'; ?>

<div class="dashboard">
    <h1 class="page-title">Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon tours">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <div class="stat-info">
                <h3><?= $tourStats['total_tours'] ?? 0 ?></h3>
                <p>Tổng số Tours</p>
                <small><?= $tourStats['international_tours'] ?? 0 ?> quốc tế | <?= $tourStats['domestic_tours'] ?? 0 ?>
                    trong nước</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bookings">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="stat-info">
                <h3><?= $bookingStats['total_bookings'] ?? 0 ?></h3>
                <p>Tổng số Bookings</p>
                <small><?= $bookingStats['pending_bookings'] ?? 0 ?> đang chờ |
                    <?= $bookingStats['completed_bookings'] ?? 0 ?> hoàn thành</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon revenue">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-info">
                <h3><?= number_format($bookingStats['total_revenue'] ?? 0, 0, ',', '.') ?> VNĐ</h3>
                <p>Tổng doanh thu</p>
                <small>Đặt cọc: <?= number_format($bookingStats['total_deposits'] ?? 0, 0, ',', '.') ?> VNĐ</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon departures">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-info">
                <h3><?= $departureStats['scheduled_departures'] ?? 0 ?></h3>
                <p>Đoàn sắp khởi hành</p>
                <small><?= $departureStats['completed_departures'] ?? 0 ?> đã hoàn thành</small>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="dashboard-section">
        <h2>Bookings gần đây</h2>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã Booking</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentBookings)): ?>
                    <?php foreach (array_slice($recentBookings, 0, 5) as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['booking_code']) ?></td>
                        <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                        <td><?= date('d/m/Y', strtotime($booking['booking_date'])) ?></td>
                        <td><?= number_format($booking['total_amount'], 0, ',', '.') ?> VNĐ</td>
                        <td>
                            <span class="badge badge-<?= strtolower($booking['status']) ?>">
                                <?= $booking['status'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Chưa có booking nào</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upcoming Departures -->
    <div class="dashboard-section">
        <h2>Đoàn sắp khởi hành</h2>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tour</th>
                        <th>Ngày khởi hành</th>
                        <th>Ngày về</th>
                        <th>HDV</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($upcomingDepartures)): ?>
                    <?php foreach (array_slice($upcomingDepartures, 0, 5) as $departure): ?>
                    <tr>
                        <td><?= htmlspecialchars($departure['tour_name']) ?></td>
                        <td><?= date('d/m/Y', strtotime($departure['departure_date'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($departure['return_date'])) ?></td>
                        <td><?= htmlspecialchars($departure['guide_name'] ?? 'Chưa phân công') ?></td>
                        <td>
                            <span class="badge badge-<?= strtolower($departure['status']) ?>">
                                <?= $departure['status'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Chưa có đoàn nào sắp khởi hành</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once './views/admin/layout/footer.php'; ?>