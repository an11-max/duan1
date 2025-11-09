<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch trình tour - HDV Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .schedule-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: none;
            border-radius: 12px;
            border-left: 4px solid #28a745;
        }
        
        .schedule-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .upcoming-tour {
            border-left-color: #007bff;
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
        }
        
        .current-tour {
            border-left-color: #28a745;
            background: linear-gradient(135deg, #f0fff4 0%, #ffffff 100%);
        }
        
        .past-tour {
            border-left-color: #6c757d;
            opacity: 0.8;
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline:before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        
        .timeline-item:before {
            content: '';
            position: absolute;
            left: -26px;
            top: 8px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #28a745;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #28a745;
        }
        
        .timeline-item.upcoming:before {
            background: #007bff;
            box-shadow: 0 0 0 2px #007bff;
        }
        
        .timeline-item.past:before {
            background: #6c757d;
            box-shadow: 0 0 0 2px #6c757d;
        }
        
        .tour-status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                <div>
                    <h2><i class="fas fa-calendar-alt text-success me-2"></i>Lịch trình tour</h2>
                    <p class="text-muted mb-0">Các tour đã được xác nhận và lịch trình của bạn</p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>?act=guide_assignments" class="btn btn-warning me-2">
                        <i class="fas fa-tasks me-1"></i>Tour chờ xác nhận
                    </a>
                    <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Về Dashboard
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mt-4 mb-4">
                <?php
                $acceptedTours = array_filter($assignedTours, function($tour) {
                    return $tour['status'] == 'accepted';
                });
                
                $upcomingTours = array_filter($acceptedTours, function($tour) {
                    return strtotime($tour['start_date']) > time();
                });
                
                $currentTours = array_filter($acceptedTours, function($tour) {
                    $startTime = strtotime($tour['start_date']);
                    $endTime = strtotime($tour['end_date']);
                    $now = time();
                    return $startTime <= $now && $now <= $endTime;
                });
                
                $pastTours = array_filter($acceptedTours, function($tour) {
                    return strtotime($tour['end_date']) < time();
                });
                ?>
                
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h4><?= count($upcomingTours) ?></h4>
                            <small>Tour sắp tới</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-play-circle fa-2x mb-2"></i>
                            <h4><?= count($currentTours) ?></h4>
                            <small>Đang diễn ra</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h4><?= count($pastTours) ?></h4>
                            <small>Đã hoàn thành</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check fa-2x mb-2"></i>
                            <h4><?= count($acceptedTours) ?></h4>
                            <small>Tổng tour</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills justify-content-center mb-4" id="scheduleFilter">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-filter="all">
                                <i class="fas fa-list me-1"></i>Tất cả
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="upcoming">
                                <i class="fas fa-clock me-1"></i>Sắp tới
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="current">
                                <i class="fas fa-play me-1"></i>Đang diễn ra
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="past">
                                <i class="fas fa-history me-1"></i>Đã hoàn thành
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Schedule Cards -->
            <div class="row" id="scheduleContainer">
                <?php if (empty($acceptedTours)): ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Chưa có tour nào trong lịch trình</h4>
                            <p class="text-muted">Bạn chưa chấp nhận tour nào. Hãy kiểm tra các tour được phân công.</p>
                            <a href="<?= BASE_URL ?>?act=guide_assignments" class="btn btn-warning">
                                <i class="fas fa-tasks me-1"></i>Xem tour chờ xác nhận
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php 
                    // Sắp xếp theo thời gian bắt đầu
                    usort($acceptedTours, function($a, $b) {
                        return strtotime($a['start_date']) - strtotime($b['start_date']);
                    });
                    
                    foreach ($acceptedTours as $tour): 
                        $startTime = strtotime($tour['start_date']);
                        $endTime = strtotime($tour['end_date']);
                        $now = time();
                        
                        if ($startTime > $now) {
                            $cardClass = 'upcoming-tour';
                            $statusText = 'Sắp tới';
                            $statusIcon = 'fas fa-clock text-primary';
                            $dataStatus = 'upcoming';
                        } elseif ($startTime <= $now && $now <= $endTime) {
                            $cardClass = 'current-tour';
                            $statusText = 'Đang diễn ra';
                            $statusIcon = 'fas fa-play text-success';
                            $dataStatus = 'current';
                        } else {
                            $cardClass = 'past-tour';
                            $statusText = 'Đã hoàn thành';
                            $statusIcon = 'fas fa-check text-muted';
                            $dataStatus = 'past';
                        }
                    ?>
                        <div class="col-lg-6 col-xl-4 mb-4 schedule-item" data-status="<?= $dataStatus ?>">
                            <div class="card schedule-card shadow-sm <?= $cardClass ?>">
                                <div class="card-body">
                                    <!-- Tour Header -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="card-title mb-1"><?= htmlspecialchars($tour['tour_name']) ?></h5>
                                            <code class="bg-light px-2 py-1 rounded"><?= htmlspecialchars($tour['tour_code']) ?></code>
                                        </div>
                                        <div class="text-end">
                                            <i class="<?= $statusIcon ?>"></i>
                                            <div><small><?= $statusText ?></small></div>
                                        </div>
                                    </div>

                                    <!-- Tour Image -->
                                    <?php if (!empty($tour['image'])): ?>
                                        <img src="<?= BASE_URL ?>uploads/tours/<?= htmlspecialchars($tour['image']) ?>" 
                                             class="card-img-top rounded mb-3" 
                                             style="height: 120px; object-fit: cover;"
                                             alt="<?= htmlspecialchars($tour['tour_name']) ?>"
                                             onerror="this.src='<?= BASE_URL ?>assets/images/default-tour.jpg'">
                                    <?php endif; ?>

                                    <!-- Schedule Info -->
                                    <div class="bg-light p-3 rounded mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Bắt đầu:</small>
                                                <div><strong><?= date('d/m/Y', $startTime) ?></strong></div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Kết thúc:</small>
                                                <div><strong><?= date('d/m/Y', $endTime) ?></strong></div>
                                            </div>
                                        </div>
                                        
                                        <hr class="my-2">
                                        
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <i class="fas fa-clock text-primary"></i>
                                                <div><small><?= htmlspecialchars($tour['duration'] ?? 'N/A') ?></small></div>
                                            </div>
                                            <div class="col-4">
                                                <i class="fas fa-users text-success"></i>
                                                <div><small><?= htmlspecialchars($tour['max_participants'] ?? '0') ?></small></div>
                                            </div>
                                            <div class="col-4">
                                                <i class="fas fa-dollar-sign text-warning"></i>
                                                <div><small><?= number_format($tour['price'] ?? 0) ?>đ</small></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Assignment Info -->
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <strong>Đã chấp nhận:</strong> <?= date('d/m/Y H:i', strtotime($tour['response_date'])) ?>
                                        </small>
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-outline-primary btn-sm" onclick="viewTourDetail(<?= $tour['tour_id'] ?>)">
                                            <i class="fas fa-eye me-1"></i>Xem chi tiết tour
                                        </button>
                                        <?php if ($dataStatus == 'current'): ?>
                                            <button class="btn btn-success btn-sm" onclick="startTour(<?= $tour['tour_id'] ?>)">
                                                <i class="fas fa-play me-1"></i>Bắt đầu hướng dẫn
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterLinks = document.querySelectorAll('#scheduleFilter .nav-link');
    const scheduleItems = document.querySelectorAll('.schedule-item');
    
    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active tab
            filterLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            // Filter items
            const filter = this.getAttribute('data-filter');
            
            scheduleItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-status') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});

function viewTourDetail(tourId) {
    window.location.href = '<?= BASE_URL ?>?act=guide-tour-detail&id=' + tourId;
}

function startTour(tourId) {
    if (confirm('Bắt đầu hướng dẫn tour này?')) {
        window.location.href = '<?= BASE_URL ?>?act=guide-tour-start&id=' + tourId;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
