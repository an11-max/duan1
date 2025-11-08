# Tourism Management System - Dự án 1

## Giới thiệu
Hệ thống quản lý du lịch với đầy đủ tính năng cho Admin, Super Admin và HDV (Hướng dẫn viên du lịch).

## Tính năng chính

### 🔐 Quản lý người dùng
- Đăng nhập/đăng xuất an toàn
- Phân quyền: Super Admin, Admin, HDV
- Quản lý tài khoản và profile

### 🗺️ Quản lý Tours
- CRUD tours với thông tin chi tiết
- Upload hình ảnh tours
- Phân công HDV cho tours
- Theo dõi trạng thái tours

### 🎫 Quản lý Bookings
- Tạo và quản lý bookings
- Theo dõi thanh toán và cọc
- Gửi bookings cho HDV xác nhận
- Hệ thống thông báo real-time

### 👥 Quản lý Khách hàng
- Database khách hàng chi tiết
- Lịch sử booking
- Thông tin liên hệ

### 🚌 Quản lý Departures
- Lên lịch khởi hành
- Phân công HDV và tài xế
- Theo dõi tình trạng đoàn

### 🧑‍💼 Hệ thống HDV
- Dashboard riêng cho HDV
- Quản lý tour assignments
- Booking assignments với phản hồi
- Lịch trình cá nhân
- Hệ thống thông báo

### 📧 Booking Assignments (Mới)
- Admin gửi booking cho HDV
- HDV phản hồi chấp nhận/từ chối
- Thông báo hai chiều
- Quản lý deadline và ưu tiên
- Dashboard thống kê

## Công nghệ sử dụng

- **Backend**: PHP 8+ với MVC Architecture
- **Database**: MySQL 8+
- **Frontend**: Bootstrap 5, HTML5, CSS3, JavaScript
- **Icons**: Font Awesome 6
- **AJAX**: Fetch API cho real-time interactions

## Cấu trúc thư mục

```
mvc-oop-basic-duan1/
├── assets/
│   ├── css/
│   ├── images/
│   └── js/
├── commons/
│   ├── env.php
│   └── function.php
├── controllers/
│   ├── AdminController.php
│   ├── AuthController.php
│   ├── ProductController.php
│   └── TourGuideController.php
├── models/
│   ├── BookingAssignmentModel.php
│   ├── BookingModel.php
│   ├── CustomerModel.php
│   ├── DepartureModel.php
│   ├── TourGuideModel.php
│   ├── TourModel.php
│   ├── UserModel.php
│   └── WorkflowModel.php
├── uploads/
│   ├── imgproduct/
│   └── tours/
├── views/
│   ├── admin/
│   │   ├── booking_assignments/
│   │   ├── bookings/
│   │   ├── customers/
│   │   ├── departures/
│   │   ├── layout/
│   │   ├── tour_guides/
│   │   └── tours/
│   ├── guide/
│   └── trangchu.php
├── database.sql
├── index.php
└── README.md
```

## Cài đặt

### Yêu cầu hệ thống
- PHP 8.0+
- MySQL 8.0+
- Apache/Nginx
- Extension: mysqli, pdo_mysql

### Hướng dẫn cài đặt

1. **Clone repository**
```bash
git clone https://github.com/an11-max/duan1.git
cd duan1
```

2. **Cấu hình database**
```bash
# Tạo database
mysql -u root -p
CREATE DATABASE TourismManagement;
USE TourismManagement;

# Import schema và data
mysql -u root -p TourismManagement < database.sql
```

3. **Cấu hình ứng dụng**
```php
# Chỉnh sửa commons/env.php
define('BASE_URL', 'http://localhost/duan1/');
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'TourismManagement');
```

4. **Phân quyền thư mục**
```bash
chmod 755 uploads/
chmod 755 uploads/tours/
chmod 755 uploads/imgproduct/
```

## Tài khoản mặc định

| Loại tài khoản | Username | Password | Quyền |
|----------------|----------|----------|-------|
| Super Admin | superadmin | 123456 | Toàn quyền |
| Admin | admin1 | 123456 | Quản lý cơ bản |
| HDV | guide1 | 123456 | Hướng dẫn viên |

## Sử dụng

### Đăng nhập Admin
1. Truy cập `/` hoặc `/?act=admin-login`
2. Đăng nhập với tài khoản admin
3. Sử dụng các chức năng quản lý

### Đăng nhập HDV
1. Đăng nhập với tài khoản HDV
2. Dashboard hiển thị:
   - Tour assignments
   - Booking assignments
   - Lịch trình
   - Thông báo

### Booking Assignments Workflow
1. **Admin**: Vào `Quản lý Bookings` → Nhấn 📧 → Chọn HDV → Gửi
2. **HDV**: Nhận thông báo → Vào `Booking Assignments` → Phản hồi
3. **Admin**: Nhận thông báo phản hồi → Xử lý tiếp

## API Documentation

### Admin Endpoints
- `GET /?act=admin-booking-assignments` - Danh sách assignments
- `GET /?act=assign-booking-form&booking_id={id}` - Form gửi booking
- `POST /?act=process-booking-assignment` - Xử lý gửi booking
- `GET /?act=admin-booking-assignment-detail&id={id}` - Chi tiết assignment
- `POST /?act=cancel-booking-assignment` - Hủy assignment

### HDV Endpoints
- `GET /?act=guide-booking-assignments` - Dashboard assignments HDV
- `GET /?act=guide-booking-assignment-detail&id={id}` - Chi tiết assignment
- `POST /?act=guide-respond-booking-assignment` - Phản hồi assignment
- `GET /?act=guide-get-booking-assignments` - API lấy assignments
- `GET /?act=guide-get-booking-assignment-stats` - Thống kê

## Database Schema

### Bảng chính
- `users` - Người dùng hệ thống
- `tours` - Tours du lịch
- `bookings` - Đặt tour
- `booking_assignments` - Giao booking cho HDV
- `notifications` - Thông báo hệ thống
- `tour_guides` - Thông tin HDV
- `customers` - Khách hàng

### Foreign Keys
```sql
booking_assignments.booking_id → bookings.id
booking_assignments.guide_id → users.id
booking_assignments.assigned_by → users.id
notifications.user_id → users.id
notifications.booking_id → bookings.id
```

## Đóng góp

1. Fork project
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## License

Distributed under the MIT License. See `LICENSE` for more information.

## Liên hệ

- GitHub: [@an11-max](https://github.com/an11-max)
- Project Link: [https://github.com/an11-max/duan1](https://github.com/an11-max/duan1)

## Changelog

### v2.0.0 (2024-11-09)
- ✨ Thêm hệ thống Booking Assignments
- ✨ Dashboard HDV với workflow management
- ✨ Hệ thống thông báo real-time
- ✨ Tour assignment management
- 🐛 Sửa lỗi session management
- 🐛 Khắc phục database foreign key issues
- 💄 UI/UX improvements với Bootstrap 5

### v1.0.0
- 🎉 Phiên bản đầu tiên
- ✨ Quản lý cơ bản tours, bookings, customers
- ✨ Hệ thống đăng nhập với phân quyền