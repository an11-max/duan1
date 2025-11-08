# Hệ thống Booking Assignments

## Tổng quan

Hệ thống Booking Assignments cho phép Admin và Super Admin gửi booking cho HDV để xác nhận, và HDV có thể phản hồi chấp nhận hoặc từ chối.

## Tính năng chính

### Cho Admin/Super Admin:

1. **Gửi Booking cho HDV**

   - Truy cập: `Quản lý Bookings` → Nhấn nút `📧` (Gửi cho HDV)
   - Chọn HDV phù hợp dựa trên chuyên môn, kinh nghiệm, đánh giá
   - Thiết lập mức độ ưu tiên (Thấp, Trung bình, Cao, Khẩn cấp)
   - Đặt deadline phản hồi
   - Thêm ghi chú hướng dẫn cho HDV

2. **Quản lý Booking Assignments**

   - Truy cập: Menu `Booking Assignments`
   - Xem danh sách tất cả assignments đã gửi
   - Lọc theo trạng thái, mức độ ưu tiên
   - Tìm kiếm theo booking code, tên khách hàng, HDV
   - Xem chi tiết phản hồi của HDV
   - Hủy assignment nếu cần

3. **Thông báo real-time**
   - Nhận thông báo khi HDV phản hồi
   - Biết ngay khi HDV chấp nhận/từ chối booking

### Cho HDV (Tour Guide):

1. **Xem Booking Assignments**

   - Truy cập: Menu `Booking Assignments`
   - Tab "Chờ phản hồi": Assignments cần xử lý
   - Tab "Đã phản hồi": Lịch sử phản hồi
   - Tab "Quá hạn": Assignments đã quá deadline

2. **Phản hồi Assignments**

   - Chấp nhận hoặc từ chối booking
   - Thêm lý do/ghi chú phản hồi
   - Phản hồi sẽ được gửi ngay cho Admin

3. **Thông báo**
   - Nhận thông báo khi có booking assignment mới
   - Thông báo ưu tiên cao sẽ được highlight
   - Đếm số assignments đang chờ xử lý

## Quy trình làm việc

### 1. Admin gửi booking cho HDV

```
Admin → Chọn booking → Chọn HDV → Thiết lập thông tin → Gửi
```

### 2. HDV nhận và xử lý

```
HDV → Nhận thông báo → Xem chi tiết → Chấp nhận/Từ chối → Gửi phản hồi
```

### 3. Admin nhận kết quả

```
Admin → Nhận thông báo phản hồi → Xem chi tiết → Xử lý tiếp theo
```

## Trạng thái Assignment

| Trạng thái  | Mô tả            | Màu sắc    |
| ----------- | ---------------- | ---------- |
| `pending`   | Chờ HDV phản hồi | 🟡 Vàng    |
| `accepted`  | HDV đã chấp nhận | 🟢 Xanh lá |
| `declined`  | HDV đã từ chối   | 🔴 Đỏ      |
| `cancelled` | Admin đã hủy     | ⚫ Xám     |

## Mức độ ưu tiên

| Mức độ   | Mô tả      | Màu sắc       |
| -------- | ---------- | ------------- |
| `low`    | Thấp       | ⚫ Xám        |
| `medium` | Trung bình | 🔵 Xanh dương |
| `high`   | Cao        | 🟡 Vàng       |
| `urgent` | Khẩn cấp   | 🔴 Đỏ         |

## Database Schema

### Bảng `booking_assignments`

```sql
- id: Primary key
- booking_id: ID booking được giao
- guide_id: ID HDV được giao
- assigned_by: ID admin giao việc
- assigned_date: Thời gian giao
- status: Trạng thái (pending/accepted/declined/cancelled)
- notes: Ghi chú từ admin
- guide_response: Phản hồi từ HDV
- response_date: Thời gian phản hồi
- deadline: Thời hạn phản hồi
- priority: Mức độ ưu tiên
```

### Bảng `notifications` (đã cập nhật)

```sql
- booking_id: ID booking liên quan (mới)
- type: booking_assignment, booking_response (mới)
- priority: low/medium/high/urgent (mới)
```

## API Endpoints

### Admin Routes

- `admin-booking-assignments`: Danh sách assignments
- `assign-booking-form`: Form gửi booking
- `process-booking-assignment`: Xử lý gửi booking
- `admin-booking-assignment-detail`: Chi tiết assignment
- `cancel-booking-assignment`: Hủy assignment
- `admin-get-booking-assignment-stats`: Thống kê assignments

### HDV Routes

- `guide-booking-assignments`: Danh sách assignments cho HDV
- `guide-booking-assignment-detail`: Chi tiết assignment
- `guide-respond-booking-assignment`: Phản hồi assignment
- `guide-get-booking-assignments`: API lấy assignments
- `guide-get-booking-assignment-stats`: Thống kê cho HDV

## Tính năng nâng cao

1. **Thống kê real-time**: Dashboard hiển thị số assignments theo trạng thái
2. **Lọc và tìm kiếm**: Lọc theo nhiều tiêu chí
3. **Timeline**: Theo dõi lịch sử assignment
4. **Responsive design**: Tương thích mobile
5. **Notification system**: Thông báo tức thời
6. **Print support**: In trang chi tiết assignment

## Cách sử dụng

### Đối với Admin:

1. Đăng nhập với tài khoản admin
2. Vào `Quản lý Bookings`
3. Nhấn nút 📧 bên cạnh booking muốn gửi
4. Chọn HDV và điền thông tin
5. Nhấn "Gửi cho HDV"
6. Theo dõi phản hồi tại `Booking Assignments`

### Đối với HDV:

1. Đăng nhập với tài khoản HDV
2. Kiểm tra thông báo hoặc vào `Booking Assignments`
3. Xem chi tiết assignments trong tab "Chờ phản hồi"
4. Nhấn "Chấp nhận" hoặc "Từ chối"
5. Nhập lý do/ghi chú nếu cần
6. Xác nhận phản hồi

## Lưu ý quan trọng

- HDV nên phản hồi trong thời hạn để tránh quá deadline
- Admin có thể hủy assignment bất kỳ lúc nào
- Assignments với mức độ khẩn cấp cần được ưu tiên xử lý
- Hệ thống tự động tính toán thời gian quá hạn
- Tất cả hành động đều được ghi lại và thông báo

## Troubleshooting

1. **Không nhận được thông báo**: Kiểm tra quyền user và database notifications
2. **Lỗi gửi assignment**: Kiểm tra HDV có tồn tại và active không
3. **Không thể phản hồi**: Kiểm tra assignment có đúng trạng thái pending không
