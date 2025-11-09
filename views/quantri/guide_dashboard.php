<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HDV Dashboard - Quản lý Tour Du lịch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .sidebar {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.8) !important;
        transition: all 0.3s ease;
        border-radius: 8px;
        margin: 2px 0;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        transform: translateX(5px);
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        transform: translateX(5px);
    }

    .avatar-circle {
        transition: transform 0.3s ease;
    }

    .avatar-circle:hover {
        transform: scale(1.1);
    }

    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        border-radius: 0.375rem;
    }

    body {
        background-color: #f8f9fa;
    }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <!-- Header -->
                    <div class="text-center mb-4 pb-3 border-bottom border-light border-opacity-25">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-plane me-2"></i>Tourism HDV
                        </h4>
                    </div>

                    <!-- Profile Section -->
                    <div class="text-center mb-4 pb-3 border-bottom border-light border-opacity-25">
                        <div class="avatar-circle mx-auto mb-2"
                            style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-tie text-white" style="font-size: 24px;"></i>
                        </div>
                        <h6 class="text-white mb-1"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></h6>
                        <small class="text-white-50">Hướng dẫn viên</small>
                    </div>

                    <!-- Navigation Menu -->
                    <ul class="nav flex-column px-2">
                        <li class="nav-item mb-1">
                            <a class="nav-link active d-flex align-items-center py-2 px-3" href="<?= BASE_URL ?>">
                                <i class="fas fa-tachometer-alt me-2" style="width: 20px;"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item mb-1">
                            <a class="nav-link d-flex align-items-center py-2 px-3"
                                href="<?= BASE_URL ?>?act=guide_assignments">
                                <i class="fas fa-tasks me-2" style="width: 20px;"></i>
                                <span>Tour chờ xác nhận</span>
                                <span class="badge bg-warning ms-auto" id="pendingAssignments">0</span>
                            </a>
                        </li>

                        <li class="nav-item mb-1">
                            <a class="nav-link d-flex align-items-center py-2 px-3"
                                href="<?= BASE_URL ?>?act=guide_schedule">
                                <i class="fas fa-calendar-check me-2" style="width: 20px;"></i>
                                <span>Lịch trình tour</span>
                            </a>
                        </li>

                        <li class="nav-item mb-1">
                            <a class="nav-link d-flex align-items-center py-2 px-3"
                                href="<?= BASE_URL ?>?act=guide-request-form">
                                <i class="fas fa-hand-paper me-2" style="width: 20px;"></i>
                                <span>Yêu cầu tour</span>
                                <span class="badge bg-warning ms-auto">2</span>
                            </a>
                        </li>

                        <li class="nav-item mb-1">
                            <a class="nav-link d-flex align-items-center py-2 px-3"
                                href="<?= BASE_URL ?>?act=guide-notifications">
                                <i class="fas fa-bell me-2" style="width: 20px;"></i>
                                <span>Thông báo</span>
                                <?php if (isset($notificationCount) && $notificationCount > 0): ?>
                                    <span class="badge bg-danger ms-auto"><?= $notificationCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>

                        <li class="nav-item mb-1">
                            <a class="nav-link d-flex align-items-center py-2 px-3"
                                href="<?= BASE_URL ?>?act=guide-reviews">
                                <i class="fas fa-star me-2" style="width: 20px;"></i>
                                <span>Đánh giá của tôi</span>
                            </a>
                        </li>

                        <li class="nav-item mb-1">
                            <a class="nav-link d-flex align-items-center py-2 px-3"
                                href="<?= BASE_URL ?>?act=guide-profile">
                                <i class="fas fa-user-cog me-2" style="width: 20px;"></i>
                                <span>Thông tin cá nhân</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Logout Section -->
                    <div class="mt-auto pt-4 border-top border-light border-opacity-25 px-2">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2 px-3"
                                    href="<?= BASE_URL ?>?act=logout"
                                    onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
                                    <i class="fas fa-sign-out-alt me-2" style="width: 20px;"></i>
                                    <span>Đăng xuất</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main role="main" class="col-md-9 col-lg-10 px-4">
                <!-- Header -->
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-3 mb-4 border-bottom">
                    <div>
                        <h1 class="h2 mb-1">
                            <i class="fas fa-tachometer-alt text-primary me-2"></i>
                            Dashboard Hướng dẫn viên
                        </h1>
                        <p class="text-muted mb-0">Chào mừng trở lại,
                            <?= htmlspecialchars($_SESSION['user']['full_name']) ?>!</p>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-calendar me-1"></i>
                                Hôm nay
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-calendar-week me-1"></i>
                                Tuần này
                            </button>
                        </div>
                    </div>
                </div>

            <!-- Thông báo & Nhắc nhở -->
            <?php if (isset($notifications) && !empty($notifications)): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-0 text-dark">
                                <i class="fas fa-bell text-warning me-2"></i>
                                Thông báo & Nhắc nhở
                            </h5>
                            <span class="badge bg-warning"><?= count($notifications) ?> thông báo</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php foreach ($notifications as $notification): ?>
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-circle p-2 bg-<?= $notification['color'] ?> bg-opacity-10">
                                        <i class="<?= $notification['icon'] ?> text-<?= $notification['color'] ?>"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="mb-0 text-dark"><?= htmlspecialchars($notification['title']) ?></h6>
                                        <small class="text-muted">
                                            <?php if ($notification['type'] == 'new_assignment'): ?>
                                                <?= date('d/m H:i', strtotime($notification['assigned_date'])) ?>
                                            <?php elseif ($notification['type'] == 'upcoming_tour'): ?>
                                                <?php if ($notification['days_left'] == 0): ?>
                                                    Hôm nay
                                                <?php elseif ($notification['days_left'] == 1): ?>
                                                    Ngày mai
                                                <?php else: ?>
                                                    <?= $notification['days_left'] ?> ngày nữa
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <p class="mb-2 text-muted"><?= htmlspecialchars($notification['message']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <strong><?= htmlspecialchars($notification['tour_code']) ?></strong>
                                        </small>
                                        <?php if ($notification['type'] == 'new_assignment'): ?>
                                            <div>
                                                <button class="btn btn-success btn-xs me-1" onclick="respondAssignment(<?= $notification['assignment_id'] ?>, 'accepted')">
                                                    <i class="fas fa-check"></i> Chấp nhận
                                                </button>
                                                <button class="btn btn-danger btn-xs" onclick="respondAssignment(<?= $notification['assignment_id'] ?>, 'declined')">
                                                    <i class="fas fa-times"></i> Từ chối
                                                </button>
                                            </div>
                                        <?php elseif ($notification['type'] == 'upcoming_tour'): ?>
                                            <a href="<?= BASE_URL ?>?act=guide_schedule" class="btn btn-outline-primary btn-xs">
                                                <i class="fas fa-calendar"></i> Xem lịch trình
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tours được phân công -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-0 text-dark">
                                <i class="fas fa-map-marked-alt text-primary me-2"></i>
                                Tours được phân công
                            </h5>
                            <a href="<?= BASE_URL ?>?act=guide_assignments" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>
                                Xem tất cả
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($assigned_tours)): ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <div class="rounded-circle mx-auto mb-3"
                                    style="width: 80px; height: 80px; background: linear-gradient(135deg, #e9ecef, #f8f9fa); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-map-marked-alt fa-2x text-muted"></i>
                                </div>
                            </div>
                            <h6 class="text-muted mb-2">Chưa có tour nào</h6>
                            <p class="text-muted mb-3">Bạn chưa được phân công tour nào.</p>
                            <a href="<?= BASE_URL ?>?act=guide-request-form" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Yêu cầu tour mới
                            </a>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 border-0">
                                            <small class="text-uppercase fw-bold text-muted">Mã Tour</small>
                                        </th>
                                        <th class="px-4 py-3 border-0">
                                            <small class="text-uppercase fw-bold text-muted">Tên Tour</small>
                                        </th>
                                        <th class="px-4 py-3 border-0">
                                            <small class="text-uppercase fw-bold text-muted">Thời gian</small>
                                        </th>
                                        <th class="px-4 py-3 border-0">
                                            <small class="text-uppercase fw-bold text-muted">Loại</small>
                                        </th>
                                        <th class="px-4 py-3 border-0">
                                            <small class="text-uppercase fw-bold text-muted">Trạng thái</small>
                                        </th>
                                        <th class="px-4 py-3 border-0">
                                            <small class="text-uppercase fw-bold text-muted">Thao tác</small>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($assigned_tours as $tour): ?>
                                    <tr>
                                        <td class="px-4 py-3">
                                            <code
                                                class="bg-light px-2 py-1 rounded"><?= htmlspecialchars($tour['tour_code']) ?></code>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="fw-bold"><?= htmlspecialchars($tour['name']) ?></div>
                                            <small class="text-muted">Phân công:
                                                <?= date('d/m/Y', strtotime($tour['assigned_date'])) ?></small>
                                        </td>
                                        <td class="px-4 py-3">
                                            <i class="fas fa-clock text-muted me-1"></i>
                                            <?= htmlspecialchars($tour['duration']) ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <?php if ($tour['is_international']): ?>
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                                                <i class="fas fa-globe me-1"></i>Quốc tế
                                            </span>
                                            <?php else: ?>
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success border border-success">
                                                <i class="fas fa-map me-1"></i>Trong nước
                                            </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                                <i class="fas fa-circle me-1" style="font-size: 6px;"></i>Đang hoạt động
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="<?= BASE_URL ?>?act=guide-tour-detail&id=<?= $tour['id'] ?>"
                                                class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
    function respondAssignment(assignmentId, action) {
        if (confirm(`Bạn có chắc muốn ${action === 'accepted' ? 'chấp nhận' : 'từ chối'} tour này?`)) {
            const formData = new FormData();
            formData.append('assignment_id', assignmentId);
            formData.append('action', action);
            
            fetch('<?= BASE_URL ?>?act=guide-respond-assignment', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Cập nhật thành công!');
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
            })
            .catch(error => {
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
            });
        }
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
