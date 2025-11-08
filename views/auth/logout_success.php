<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng xuất thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <h3>Đăng xuất thành công!</h3>
                        <p class="text-muted">Bạn đã đăng xuất khỏi hệ thống.</p>
                        <a href="<?= BASE_URL ?>?act=login" class="btn btn-primary">Đăng nhập lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>