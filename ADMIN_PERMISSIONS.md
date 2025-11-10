# Hệ thống phân quyền Admin vs Super Admin

## ✅ Tài khoản ADMIN có thể:

### 📊 **Dashboard & Thống kê**

- Xem dashboard đầy đủ với thống kê tour, booking, departure
- Truy cập tất cả báo cáo và biểu đồ

### 🗺️ **Quản lý Tours**

- Xem danh sách tours
- Thêm tour mới
- Chỉnh sửa tours
- Xóa tours
- Quản lý hình ảnh tours

### 🎫 **Quản lý Bookings**

- Xem danh sách bookings
- Thêm booking mới
- Chỉnh sửa bookings
- Xóa bookings
- Quản lý trạng thái bookings

### 📋 **Booking Assignments**

- Xem danh sách assignments
- Giao tour cho HDV
- Xem chi tiết assignments
- Hủy assignments (chỉ những assignment do mình tạo)

### 👥 **Quản lý Khách hàng**

- Xem danh sách khách hàng
- Thêm khách hàng mới
- Chỉnh sửa thông tin khách hàng
- Xóa khách hàng

### 📅 **Quản lý Đoàn/Departures**

- Xem danh sách departures
- Thêm departure mới
- Chỉnh sửa departures
- Xóa departures
- Quản lý lịch trình

### 👨‍💼 **Quản lý Hướng dẫn viên**

- Xem danh sách HDV
- Thêm HDV mới
- Chỉnh sửa thông tin HDV
- Xóa HDV
- Quản lý lịch làm việc HDV

---

## 🚫 Tài khoản ADMIN KHÔNG thể:

### 🔐 **Quản lý Tài khoản (User Management)**

- ❌ Xem danh sách tài khoản hệ thống
- ❌ Tạo tài khoản admin/super admin mới
- ❌ Chỉnh sửa thông tin tài khoản khác
- ❌ Xóa tài khoản
- ❌ Khóa/mở khóa tài khoản
- ❌ Thay đổi vai trò người dùng

---

## 👑 Tài khoản SUPER ADMIN có thể:

### ✅ **TẤT CẢ quyền của Admin** + **Quản lý Tài khoản**

- Tất cả các chức năng mà Admin có
- Quản lý tài khoản hệ thống
- Tạo/sửa/xóa tài khoản
- Phân quyền người dùng
- Xem toàn bộ hoạt động hệ thống

---

## 🔒 Cơ chế bảo mật:

1. **Menu hiển thị**: Admin không thấy menu "Quản lý Tài khoản"
2. **Controller kiểm tra**: Tất cả user management methods kiểm tra `super_admin`
3. **Route protection**: URL user management redirect về dashboard nếu không phải super admin
4. **Error messages**: Hiển thị "Bạn không có quyền truy cập chức năng này"

## ✅ Kết luận:

Hệ thống đã được thiết lập đúng theo yêu cầu:

- **Admin**: Đầy đủ chức năng quản lý nghiệp vụ
- **Super Admin**: Admin + Quản lý tài khoản hệ thống
