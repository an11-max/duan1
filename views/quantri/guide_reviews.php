<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đánh giá - HDV Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                <div>
                    <h2><i class="fas fa-star text-warning me-2"></i>Đánh giá của tôi</h2>
                    <p class="text-muted mb-0">Xem các đánh giá từ khách hàng</p>
                </div>
                <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Về Dashboard
                </a>
            </div>

            <div class="row mt-4">
                <!-- Stats Cards -->
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-4 text-warning mb-2">
                                <i class="fas fa-star"></i>
                            </div>
                            <h3 class="mb-1">4.8</h3>
                            <p class="text-muted mb-0">Điểm trung bình</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-4 text-primary mb-2">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h3 class="mb-1">0</h3>
                            <p class="text-muted mb-0">Tổng đánh giá</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-4 text-success mb-2">
                                <i class="fas fa-thumbs-up"></i>
                            </div>
                            <h3 class="mb-1">0</h3>
                            <p class="text-muted mb-0">Đánh giá tích cực</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="display-4 text-info mb-2">
                                <i class="fas fa-medal"></i>
                            </div>
                            <h3 class="mb-1">A+</h3>
                            <p class="text-muted mb-0">Xếp hạng</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">Đánh giá gần đây</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="fas fa-star-half-alt fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">Chưa có đánh giá nào</h4>
                                <p class="text-muted">Các đánh giá từ khách hàng sẽ hiển thị tại đây.</p>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Tính năng này sẽ được phát triển trong tương lai
                                </small>
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
