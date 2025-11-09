<?php require_once './views/quantri/layout/header.php'; ?>

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

    <!-- Dashboard Content Grid -->
    <div class="dashboard-content">
        <!-- Recent Bookings Panel -->
        <div class="content-panel">
            <div class="panel-header">
                <h3 class="panel-title">
                    <i class="fas fa-ticket-alt"></i>
                    Bookings gần đây
                </h3>
                <a href="?act=admin-bookings" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>Xem tất cả
                </a>
            </div>
            <div class="table-responsive">
                <table class="dashboard-table">
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
                            <td>
                                <strong><?= htmlspecialchars($booking['booking_code']) ?></strong>
                            </td>
                            <td>
                                <div>
                                    <strong><?= htmlspecialchars($booking['customer_name']) ?></strong>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($booking['customer_phone'] ?? '') ?></small>
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-calendar me-1"></i>
                                <?= date('d/m/Y', strtotime($booking['booking_date'])) ?>
                            </td>
                            <td>
                                <strong class="text-success">
                                    <?= number_format($booking['total_amount'], 0, ',', '.') ?> VNĐ
                                </strong>
                            </td>
                            <td>
                                <?php
                                $status_classes = [
                                    'pending' => 'warning',
                                    'confirmed' => 'success',
                                    'completed' => 'primary',
                                    'cancelled' => 'danger'
                                ];
                                $status_icons = [
                                    'pending' => 'fas fa-clock',
                                    'confirmed' => 'fas fa-check-circle',
                                    'completed' => 'fas fa-flag-checkered',
                                    'cancelled' => 'fas fa-times-circle'
                                ];
                                $status_class = $status_classes[$booking['status']] ?? 'secondary';
                                $status_icon = $status_icons[$booking['status']] ?? 'fas fa-question';
                                ?>
                                <span class="status-badge badge-<?= $status_class ?>">
                                    <i class="<?= $status_icon ?>"></i>
                                    <?= ucfirst($booking['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <br>Chưa có booking nào
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Activity Feed Panel -->
        <div class="content-panel">
            <div class="panel-header">
                <h3 class="panel-title">
                    <i class="fas fa-bell"></i>
                    Hoạt động gần đây
                </h3>
            </div>
            <div class="activity-feed">
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <h6>Khách hàng mới đăng ký</h6>
                        <p>5 phút trước</p>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="activity-content">
                        <h6>Booking mới được tạo</h6>
                        <p>10 phút trước</p>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-plane-departure"></i>
                    </div>
                    <div class="activity-content">
                        <h6>Đoàn tour khởi hành</h6>
                        <p>1 giờ trước</p>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="activity-content">
                        <h6>Nhận đánh giá 5 sao</h6>
                        <p>2 giờ trước</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="?act=admin-bookings" class="quick-action">
            <i class="fas fa-plus-circle"></i>
            <h5>Tạo Booking</h5>
        </a>
        <a href="?act=admin-tours" class="quick-action">
            <i class="fas fa-map-marked-alt"></i>
            <h5>Quản lý Tours</h5>
        </a>
        <a href="?act=admin-customers" class="quick-action">
            <i class="fas fa-users"></i>
            <h5>Khách hàng</h5>
        </a>
        <a href="?act=admin-booking-assignments" class="quick-action">
            <i class="fas fa-tasks"></i>
            <h5>Phân công</h5>
        </a>
    </div>

    <!-- Upcoming Departures Section -->
    <div class="content-panel" style="margin-top: 2rem;">
        <div class="panel-header">
            <h3 class="panel-title">
                <i class="fas fa-calendar-alt"></i>
                Đoàn sắp khởi hành
            </h3>
            <a href="?act=admin-departures" class="btn btn-sm btn-outline-success">
                <i class="fas fa-calendar me-1"></i>Xem lịch
            </a>
        </div>
        <div class="table-responsive">
            <table class="dashboard-table">
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
                        <td>
                            <div>
                                <strong><?= htmlspecialchars($departure['tour_name']) ?></strong>
                                <br>
                                <small class="text-muted"><?= htmlspecialchars($departure['tour_code'] ?? '') ?></small>
                            </div>
                        </td>
                        <td>
                            <i class="fas fa-plane-departure me-1 text-primary"></i>
                            <?= date('d/m/Y', strtotime($departure['departure_date'])) ?>
                        </td>
                        <td>
                            <i class="fas fa-plane-arrival me-1 text-success"></i>
                            <?= date('d/m/Y', strtotime($departure['return_date'])) ?>
                        </td>
                        <td>
                            <?php if (!empty($departure['guide_name'])): ?>
                                <i class="fas fa-user-tie me-1"></i>
                                <?= htmlspecialchars($departure['guide_name']) ?>
                            <?php else: ?>
                                <span class="text-muted">
                                    <i class="fas fa-user-slash me-1"></i>
                                    Chưa phân công
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $departure_status_classes = [
                                'scheduled' => 'info',
                                'departed' => 'warning',
                                'completed' => 'success',
                                'cancelled' => 'danger'
                            ];
                            $departure_status_icons = [
                                'scheduled' => 'fas fa-clock',
                                'departed' => 'fas fa-plane',
                                'completed' => 'fas fa-check-circle',
                                'cancelled' => 'fas fa-times-circle'
                            ];
                            $departure_status_class = $departure_status_classes[$departure['status']] ?? 'secondary';
                            $departure_status_icon = $departure_status_icons[$departure['status']] ?? 'fas fa-question';
                            ?>
                            <span class="status-badge badge-<?= $departure_status_class ?>">
                                <i class="<?= $departure_status_icon ?>"></i>
                                <?= ucfirst($departure['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <br>Chưa có đoàn nào sắp khởi hành
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once './views/quantri/layout/footer.php'; ?>
