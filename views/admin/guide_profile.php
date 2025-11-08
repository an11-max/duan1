<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân - HDV Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                <div>
                    <h2><i class="fas fa-user-cog text-secondary me-2"></i>Thông tin cá nhân</h2>
                    <p class="text-muted mb-0">Quản lý thông tin tài khoản của bạn</p>
                </div>
                <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Về Dashboard
                </a>
            </div>

            <div class="row mt-4">
                <div class="col-lg-4">
                    <!-- Profile Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="avatar-circle mx-auto mb-3" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-tie text-white" style="font-size: 40px;"></i>
                            </div>
                            <h4 class="mb-1"><?= htmlspecialchars($userInfo['full_name']) ?></h4>
                            <p class="text-muted mb-3">Hướng dẫn viên</p>
                            
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="h5 mb-1">0</div>
                                        <small class="text-muted">Tours</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="h5 mb-1">4.8</div>
                                        <small class="text-muted">Rating</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="h5 mb-1">A+</div>
                                    <small class="text-muted">Rank</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="mb-0">Thao tác nhanh</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?= BASE_URL ?>?act=guide-tours" class="btn btn-outline-primary">
                                    <i class="fas fa-map-marked-alt me-2"></i>Xem Tours
                                </a>
                                <a href="<?= BASE_URL ?>?act=guide-requests" class="btn btn-outline-info">
                                    <i class="fas fa-hand-paper me-2"></i>Yêu cầu của tôi
                                </a>
                                <a href="<?= BASE_URL ?>?act=guide-notifications" class="btn btn-outline-warning">
                                    <i class="fas fa-bell me-2"></i>Thông báo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Account Information -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">Thông tin tài khoản</h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tên đăng nhập</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($userInfo['username']) ?>" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="<?= htmlspecialchars($userInfo['email']) ?>" readonly>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($userInfo['full_name']) ?>" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Vai trò</label>
                                        <input type="text" class="form-control" value="Hướng dẫn viên" readonly>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Để thay đổi thông tin cá nhân, vui lòng liên hệ quản trị viên.
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Activity Log -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">Hoạt động gần đây</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item d-flex align-items-center mb-3">
                                    <div class="timeline-marker bg-primary rounded-circle me-3" style="width: 12px; height: 12px;"></div>
                                    <div>
                                        <small class="text-muted">Hôm nay, <?= date('H:i') ?></small>
                                        <div>Đăng nhập vào hệ thống</div>
                                    </div>
                                </div>
                                
                                <div class="timeline-item d-flex align-items-center mb-3">
                                    <div class="timeline-marker bg-success rounded-circle me-3" style="width: 12px; height: 12px;"></div>
                                    <div>
                                        <small class="text-muted">Hôm qua</small>
                                        <div>Tài khoản được tạo</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>