# Hệ thống Quản lý Tour Du lịch - Admin Panel

## Giới thiệu
Đây là hệ thống quản lý tour du lịch được xây dựng theo mô hình MVC (Model-View-Controller) với PHP thuần.

## Cấu trúc dự án

```
mvc-oop-basic-duanmau/
├── assets/
│   ├── css/
│   │   └── admin.css          # CSS cho admin panel
│   ├── js/
│   │   └── admin.js           # JavaScript cho admin panel
│   └── images/                # Thư mục chứa hình ảnh
├── commons/
│   ├── env.php                # Cấu hình môi trường
│   └── function.php           # Các hàm hỗ trợ
├── controllers/
│   ├── AdminController.php    # Controller cho admin
│   └── ProductController.php  # Controller cho sản phẩm
├── models/
│   ├── TourModel.php          # Model cho Tours
│   ├── BookingModel.php       # Model cho Bookings
│   ├── CustomerModel.php      # Model cho Customers
│   ├── DepartureModel.php     # Model cho Departures
│   └── TourGuideModel.php     # Model cho Tour Guides
├── views/
│   └── admin/
│       ├── layout/
│       │   ├── header.php     # Header layout
│       │   └── footer.php     # Footer layout
│       ├── dashboard.php      # Trang dashboard
│       ├── tours/             # Quản lý tours
│       ├── bookings/          # Quản lý bookings
│       ├── customers/         # Quản lý khách hàng
│       ├── departures/        # Quản lý đoàn
│       └── tour_guides/       # Quản lý HDV
├── uploads/
│   └── tours/                 # Thư mục upload hình ảnh tours
└── index.php                  # File điều hướng chính
```

## Cài đặt

### 1. Cấu hình Database

Tạo database MySQL với tên `TourismManagement` và import file SQL đã cung cấp.

### 2. Cấu hình kết nối

Mở file `commons/env.php` và cập nhật thông tin kết nối database:

```php
define('DB_HOST'    , 'localhost');
define('DB_PORT'    , 3306);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME'    , 'TourismManagement');
```

### 3. Cấu hình BASE_URL

Cập nhật `BASE_URL` trong file `commons/env.php` theo đường dẫn của bạn:

```php
define('BASE_URL', 'http://localhost/du%20an%201/mvc-oop-basic-duanmau/');
```

### 4. Phân quyền thư mục

Đảm bảo thư mục `uploads/` có quyền ghi (chmod 755 hoặc 777).

## Sử dụng

### Truy cập Admin Panel

Truy cập vào URL: `http://localhost/du%20an%201/mvc-oop-basic-duanmau/?act=admin-dashboard`

### Các chức năng chính

#### 1. Dashboard
- URL: `?act=admin-dashboard`
- Hiển thị thống kê tổng quan:
  - Tổng số tours (quốc tế/trong nước)
  - Tổng số bookings theo trạng thái
  - Tổng doanh thu
  - Đoàn sắp khởi hành
- Danh sách bookings gần đây
- Danh sách đoàn sắp khởi hành

#### 2. Quản lý Tours
- **Danh sách**: `?act=admin-tours`
- **Thêm mới**: `?act=admin-tour-add`
- **Chỉnh sửa**: `?act=admin-tour-edit&id={id}`
- **Xóa**: `?act=admin-tour-delete&id={id}`

Chức năng:
- Thêm/sửa/xóa tour
- Upload hình ảnh tour
- Phân loại tour (quốc tế/trong nước)
- Quản lý thông tin: mã tour, tên, mô tả, thời gian

#### 3. Quản lý Bookings
- **Danh sách**: `?act=admin-bookings`
- **Thêm mới**: `?act=admin-booking-add`
- **Chỉnh sửa**: `?act=admin-booking-edit&id={id}`
- **Xóa**: `?act=admin-booking-delete&id={id}`

Chức năng:
- Tạo booking mới
- Cập nhật trạng thái: Pending, Deposited, Completed, Cancelled
- Quản lý thông tin thanh toán
- Liên kết với khách hàng

#### 4. Quản lý Khách hàng
- **Danh sách**: `?act=admin-customers`
- **Thêm mới**: `?act=admin-customer-add`
- **Chỉnh sửa**: `?act=admin-customer-edit&id={id}`
- **Xóa**: `?act=admin-customer-delete&id={id}`

Chức năng:
- Thêm/sửa/xóa khách hàng
- Lưu thông tin: tên, SĐT, email, địa chỉ
- Ghi chú lịch sử giao dịch

#### 5. Quản lý Đoàn khởi hành
- **Danh sách**: `?act=admin-departures`

Hiển thị:
- Thông tin tour
- Ngày khởi hành/về
- HDV phụ trách
- Số lượng khách (min/max PAX)
- Trạng thái: Scheduled, Completed, Cancelled

#### 6. Quản lý Hướng dẫn viên
- **Danh sách**: `?act=admin-tour-guides`

Hiển thị:
- Thông tin HDV
- Giấy phép hành nghề
- Trạng thái: Available, Busy, Inactive

## Cấu trúc Database

### Các bảng chính:
- `tours` - Thông tin tours
- `tour_versions` - Các phiên bản của tour
- `bookings` - Đặt tour
- `customers` - Khách hàng
- `departures` - Đoàn khởi hành
- `tour_guides` - Hướng dẫn viên
- `suppliers` - Nhà cung cấp
- `transactions` - Giao dịch thu/chi

## Tính năng nổi bật

### 1. Giao diện hiện đại
- Responsive design
- Gradient colors
- Smooth animations
- Font Awesome icons

### 2. Dashboard thống kê
- Thống kê theo thời gian thực
- Biểu đồ trực quan
- Dữ liệu tổng hợp

### 3. Quản lý file
- Upload hình ảnh
- Xóa file tự động khi xóa record
- Hỗ trợ nhiều định dạng

### 4. Form validation
- Validate phía client (JavaScript)
- Validate phía server (PHP)
- Thông báo lỗi rõ ràng

### 5. Bảo mật
- Prepared statements (PDO)
- XSS protection
- CSRF protection (có thể mở rộng)

## Mở rộng

### Thêm chức năng mới

1. Tạo Model trong `models/`
2. Tạo Controller method trong `controllers/AdminController.php`
3. Tạo View trong `views/admin/`
4. Thêm route trong `index.php`

### Ví dụ thêm route mới:

```php
// Trong index.php
match ($act) {
    // ... existing routes
    'admin-new-feature' => (new AdminController())->newFeature(),
}
```

## Lưu ý

1. **Bảo mật**: Hệ thống chưa có authentication. Cần thêm login/logout cho production.
2. **Phân quyền**: Chưa có phân quyền user. Tất cả đều có quyền admin.
3. **Backup**: Nên backup database thường xuyên.
4. **Upload**: Kiểm tra kích thước và loại file upload.

## Hỗ trợ

Nếu gặp vấn đề, kiểm tra:
1. Kết nối database
2. Quyền thư mục uploads
3. PHP version (>= 7.4)
4. PDO extension enabled

## Tác giả

Phát triển bởi Tourism Management Team
Version: 1.0
Date: 2024

