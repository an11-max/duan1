-- ==========================================
-- TOURISM MANAGEMENT DATABASE - Fixed Version
-- Đã sửa thứ tự tạo bảng để tránh lỗi foreign key
-- ==========================================

-- Xóa database cũ nếu có
DROP DATABASE IF EXISTS TourismManagement;
CREATE DATABASE TourismManagement;
USE TourismManagement;

-- ==========================================
-- 1. Tạo bảng users trước (không có foreign key)
-- ==========================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    avatar VARCHAR(255) DEFAULT 'default-avatar.png',
    role ENUM('super_admin', 'admin', 'tour_guide') NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Thêm tài khoản hệ thống phân quyền
INSERT INTO users (username, password, email, full_name, role) VALUES 
('superadmin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin@tourism.com', 'Super Administrator', 'super_admin'),
('admin1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin1@tourism.com', 'Admin Nguyễn Văn A', 'admin'),
('guide1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guide1@tourism.com', 'HDV Trần Thị B', 'tour_guide'),
('guide2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guide2@tourism.com', 'HDV Lê Văn C', 'tour_guide'),
('guide3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guide3@tourism.com', 'HDV Phạm Thị D', 'tour_guide');
-- Tất cả tài khoản có password mặc định: password

-- ==========================================
-- 2. Tạo bảng suppliers (không có foreign key)
-- ==========================================
CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    supplier_type ENUM('Hotel', 'Transport', 'Restaurant', 'Other') NOT NULL,
    address VARCHAR(255)
);

INSERT INTO suppliers (name, contact_person, phone, email, supplier_type, address) VALUES
('Khách sạn Mường Thanh', 'Nguyễn Văn E', '0906789012', 'muongthanh@hotel.com', 'Hotel', 'Hà Nội'),
('Nhà hàng Ngon', 'Trần Văn F', '0907890123', 'nhahangngon@restaurant.com', 'Restaurant', 'TP.HCM'),
('Công ty Vận tải ABC', 'Lê Thị G', '0908901234', 'vantaiabc@transport.com', 'Transport', 'Đà Nẵng');

-- ==========================================
-- 3. Tạo bảng tours (không có foreign key)
-- ==========================================
CREATE TABLE tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_code VARCHAR(50) NOT NULL UNIQUE,
    tour_name VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    destination VARCHAR(255),
    departure_location VARCHAR(255),
    departure_date DATE,
    return_date DATE,
    duration VARCHAR(50),
    price DECIMAL(12,2),
    max_participants INT DEFAULT 30,
    current_participants INT DEFAULT 0,
    available_slots INT GENERATED ALWAYS AS (max_participants - current_participants) STORED,
    itinerary TEXT,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    is_international BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO tours (tour_code, tour_name, name, description, destination, departure_location, departure_date, return_date, duration, price, max_participants, current_participants, itinerary, is_international) VALUES
('VN-HN-001', 'Tour Hà Nội - Hạ Long 3N2Đ', 'Tour Hà Nội - Hạ Long', 'Khám phá vẻ đẹp của Vịnh Hạ Long - Di sản thiên nhiên thế giới', 'Vịnh Hạ Long', 'Hà Nội', '2024-12-15', '2024-12-17', '3 ngày 2 đêm', 5500000, 30, 8, 'Ngày 1: Hà Nội - Hạ Long\nNgày 2: Tham quan Vịnh Hạ Long\nNgày 3: Hạ Long - Hà Nội', 0),
('VN-DN-001', 'Tour Đà Nẵng - Hội An 4N3Đ', 'Tour Đà Nẵng - Hội An', 'Trải nghiệm phố cổ Hội An và thành phố Đà Nẵng', 'Đà Nẵng - Hội An', 'TP.HCM', '2024-12-20', '2024-12-23', '4 ngày 3 đêm', 7500000, 25, 12, 'Ngày 1: TP.HCM - Đà Nẵng\nNgày 2: Đà Nẵng - Hội An\nNgày 3: Hội An\nNgày 4: Đà Nẵng - TP.HCM', 0),
('TH-BK-001', 'Tour Bangkok - Pattaya 5N4Đ', 'Tour Bangkok - Pattaya', 'Khám phá xứ sở chùa vàng Thái Lan', 'Bangkok - Pattaya', 'TP.HCM', '2025-01-05', '2025-01-09', '5 ngày 4 đêm', 12500000, 35, 15, 'Ngày 1: TP.HCM - Bangkok\nNgày 2-3: Bangkok\nNgày 4: Pattaya\nNgày 5: Bangkok - TP.HCM', 1),
('SG-MY-001', 'Tour Singapore - Malaysia 6N5Đ', 'Tour Singapore - Malaysia', 'Trải nghiệm 2 quốc gia Singapore và Malaysia', 'Singapore - Malaysia', 'Hà Nội', '2025-01-10', '2025-01-15', '6 ngày 5 đêm', 18500000, 20, 5, 'Ngày 1: Hà Nội - Singapore\nNgày 2-3: Singapore\nNgày 4-5: Malaysia\nNgày 6: Singapore - Hà Nội', 1);

-- ==========================================
-- 4. Tạo bảng customers (không có foreign key)
-- ==========================================
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    address VARCHAR(255),
    history_notes TEXT
);

INSERT INTO customers (name, phone, email, address, history_notes) VALUES
('Nguyễn Văn H', '0909012345', 'nguyenvanh@email.com', 'Hà Nội', 'Khách hàng thân thiết'),
('Trần Thị I', '0910123456', 'tranthii@email.com', 'TP.HCM', 'Đã đi tour 3 lần'),
('Lê Văn K', '0911234567', 'levank@email.com', 'Đà Nẵng', 'Khách hàng mới'),
('Phạm Thị L', '0912345678', 'phamthil@email.com', 'Hải Phòng', 'Thích tour quốc tế');

-- ==========================================
-- 5. Tạo các bảng có foreign key reference đến tours và users
-- ==========================================

-- Bảng tour_assignments (Phân công tour cho HDV)
CREATE TABLE tour_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    guide_id INT NOT NULL,
    assigned_by INT NOT NULL,
    assigned_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('assigned', 'accepted', 'declined', 'cancelled') DEFAULT 'assigned',
    notes TEXT,
    response_date TIMESTAMP NULL,
    FOREIGN KEY (tour_id) REFERENCES tours(id),
    FOREIGN KEY (guide_id) REFERENCES users(id),
    FOREIGN KEY (assigned_by) REFERENCES users(id)
);

-- Bảng tour_requests (Yêu cầu nhận tour từ HDV)
CREATE TABLE tour_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    guide_id INT NOT NULL,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    message TEXT,
    admin_response TEXT,
    reviewed_by INT NULL,
    reviewed_date TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (tour_id) REFERENCES tours(id),
    FOREIGN KEY (guide_id) REFERENCES users(id),
    FOREIGN KEY (reviewed_by) REFERENCES users(id)
);

-- Bảng guide_tour_access (Tour mà HDV được phép xem)
CREATE TABLE guide_tour_access (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guide_id INT NOT NULL,
    tour_id INT NOT NULL,
    access_type ENUM('view', 'assigned', 'completed') DEFAULT 'view',
    granted_by INT NOT NULL,
    granted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_date DATE NULL,
    FOREIGN KEY (guide_id) REFERENCES users(id),
    FOREIGN KEY (tour_id) REFERENCES tours(id),
    FOREIGN KEY (granted_by) REFERENCES users(id),
    UNIQUE KEY unique_guide_tour (guide_id, tour_id)
);

-- ==========================================
-- 6. Tạo các bảng khác của hệ thống cũ
-- ==========================================

-- Bảng tour_versions
CREATE TABLE tour_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    version_name VARCHAR(100) NOT NULL,
    start_date DATE,
    end_date DATE,
    base_price DECIMAL(12,2),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    FOREIGN KEY (tour_id) REFERENCES tours(id)
);

INSERT INTO tour_versions (tour_id, version_name, start_date, end_date, base_price, status) VALUES
(1, 'Hè 2024', '2024-06-01', '2024-08-31', 5500000, 'Active'),
(2, 'Hè 2024', '2024-06-01', '2024-08-31', 7500000, 'Active'),
(3, 'Hè 2024', '2024-06-01', '2024-08-31', 12500000, 'Active'),
(4, 'Hè 2024', '2024-06-01', '2024-08-31', 18500000, 'Active');

-- Bảng tour_services
CREATE TABLE tour_services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_version_id INT NOT NULL,
    supplier_id INT NOT NULL,
    service_name VARCHAR(100) NOT NULL,
    unit_cost DECIMAL(12,2),
    quantity INT,
    notes TEXT,
    FOREIGN KEY (tour_version_id) REFERENCES tour_versions(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);

-- Bảng bookings
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    booking_code VARCHAR(50) NOT NULL UNIQUE,
    booking_date DATE,
    total_amount DECIMAL(12,2),
    deposit_amount DECIMAL(12,2),
    status ENUM('Pending', 'Deposited', 'Completed', 'Cancelled') DEFAULT 'Pending',
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

INSERT INTO bookings (customer_id, booking_code, booking_date, total_amount, deposit_amount, status) VALUES
(1, 'BK20240601001', '2024-06-01', 11000000, 3300000, 'Deposited'),
(2, 'BK20240602001', '2024-06-02', 7500000, 2250000, 'Completed'),
(3, 'BK20240603001', '2024-06-03', 12500000, 3750000, 'Pending'),
(4, 'BK20240604001', '2024-06-04', 37000000, 11100000, 'Deposited');

-- Bảng notifications (Thông báo hệ thống)  
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('tour_request', 'tour_assignment', 'booking_assignment', 'booking_response', 'system', 'general') DEFAULT 'general',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    related_type ENUM('tour_request', 'tour_assignment', 'booking_assignment', 'booking_response', 'general') NULL,
    related_id INT NULL,
    tour_id INT NULL,
    booking_id INT NULL,
    request_id INT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tour_id) REFERENCES tours(id),
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

-- Bảng departures  
CREATE TABLE departures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_version_id INT NOT NULL,
    departure_date DATE,
    return_date DATE,
    actual_guide_id INT,
    status ENUM('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
    min_pax INT DEFAULT 1,
    max_pax INT,
    departure_location VARCHAR(255),
    FOREIGN KEY (tour_version_id) REFERENCES tour_versions(id),
    FOREIGN KEY (actual_guide_id) REFERENCES users(id)
);

INSERT INTO departures (tour_version_id, departure_date, return_date, actual_guide_id, status, min_pax, max_pax, departure_location) VALUES
(1, '2024-07-15', '2024-07-17', 3, 'Scheduled', 10, 30, 'Hà Nội'),
(2, '2024-07-20', '2024-07-23', 3, 'Scheduled', 10, 25, 'TP.HCM'),
(3, '2024-08-01', '2024-08-05', 3, 'Scheduled', 15, 35, 'TP.HCM'),
(1, '2024-06-10', '2024-06-12', 3, 'Completed', 10, 30, 'Hà Nội');

-- ==========================================
-- Thêm các bảng còn thiếu cho hệ thống cũ
-- ==========================================

-- Bảng departure_assignments
CREATE TABLE departure_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    departure_id INT NOT NULL,
    assigned_type ENUM('HDV','Driver','Vehicle') NOT NULL,
    assigned_id INT NOT NULL,
    assignment_notes TEXT,
    FOREIGN KEY (departure_id) REFERENCES departures(id)
);

-- Bảng departure_bookings
CREATE TABLE departure_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    departure_id INT NOT NULL,
    booking_id INT NOT NULL,
    pax_count INT DEFAULT 1,
    FOREIGN KEY (departure_id) REFERENCES departures(id),
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

INSERT INTO departure_bookings (departure_id, booking_id, pax_count) VALUES
(1, 1, 2),
(2, 2, 1),
(3, 3, 1),
(4, 4, 2);

-- Bảng tour_diaries
CREATE TABLE tour_diaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    departure_id INT NOT NULL,
    tour_guide_id INT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    content TEXT,
    incident_type VARCHAR(50),
    image VARCHAR(255),
    FOREIGN KEY (departure_id) REFERENCES departures(id),
    FOREIGN KEY (tour_guide_id) REFERENCES users(id)
);

-- Bảng transactions
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_code VARCHAR(50) NOT NULL UNIQUE,
    type ENUM('REVENUE','EXPENSE') NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    transaction_date DATE,
    description TEXT
);

-- Bảng revenue_items
CREATE TABLE revenue_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    booking_id INT NOT NULL,
    payment_method VARCHAR(50),
    is_deposit BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id),
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

-- Bảng expense_items
CREATE TABLE expense_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    supplier_id INT,
    departure_id INT,
    cost_type VARCHAR(50),
    FOREIGN KEY (transaction_id) REFERENCES transactions(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (departure_id) REFERENCES departures(id)
);

-- Bảng tour_images
CREATE TABLE tour_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    name_img VARCHAR(255),
    FOREIGN KEY (tour_id) REFERENCES tours(id)
);

-- Bảng booking_guests
CREATE TABLE booking_guests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    customer_id INT NOT NULL,
    guest_name VARCHAR(100),
    guest_phone VARCHAR(20),
    guest_email VARCHAR(100),
    special_notes TEXT,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- Bảng booking_assignments (Gửi booking cho HDV)
CREATE TABLE booking_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    guide_id INT NOT NULL,
    assigned_by INT NOT NULL,
    assigned_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'accepted', 'declined', 'cancelled') DEFAULT 'pending',
    notes TEXT COMMENT 'Ghi chú từ admin',
    guide_response TEXT COMMENT 'Phản hồi từ HDV',
    response_date TIMESTAMP NULL,
    deadline DATETIME NULL COMMENT 'Thời hạn phản hồi',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (guide_id) REFERENCES users(id),
    FOREIGN KEY (assigned_by) REFERENCES users(id)
);

-- ==========================================
-- 7. Thêm dữ liệu mẫu cho hệ thống workflow HDV
-- ==========================================

-- Phân quyền xem tour cho HDV (HDV chỉ được xem những tour được cấp quyền)
INSERT INTO guide_tour_access (guide_id, tour_id, access_type, granted_by) VALUES 
(3, 1, 'view', 1),  -- guide1 được xem tour 1 bởi superadmin
(3, 2, 'assigned', 1),  -- guide1 được phân công tour 2
(4, 1, 'view', 1),  -- guide2 được xem tour 1
(4, 3, 'view', 2),  -- guide2 được xem tour 3 bởi admin1
(5, 3, 'view', 2),  -- guide3 được xem tour 3 bởi admin1
(5, 4, 'view', 1);  -- guide3 được xem tour 4 bởi superadmin

-- Phân công tour mẫu
INSERT INTO tour_assignments (tour_id, guide_id, assigned_by, status, notes) VALUES 
(2, 3, 1, 'assigned', 'Tour Đà Nẵng - Hội An 4N3Đ - Khởi hành 20/12'),
(1, 4, 2, 'accepted', 'Tour Hà Nội - Hạ Long 3N2Đ - HDV đã chấp nhận');

-- Yêu cầu nhận tour mẫu
INSERT INTO tour_requests (tour_id, guide_id, message, status) VALUES 
(3, 3, 'Tôi muốn nhận tour Bangkok này vì đã có kinh nghiệm dẫn tour quốc tế', 'pending'),
(4, 4, 'Xin được nhận tour Singapore - Malaysia, tôi rất quen thuộc với các điểm du lịch ở đây', 'pending'),
(1, 5, 'Tôi muốn nhận tour Hà Nội - Hạ Long vì đã có kinh nghiệm về vùng này', 'approved');

-- Thông báo mẫu
INSERT INTO notifications (user_id, title, message, type, related_type, related_id, tour_id, booking_id, priority) VALUES 
(1, 'Yêu cầu nhận tour mới', 'HDV Trần Thị B yêu cầu nhận tour Bangkok - Pattaya 5N4Đ', 'tour_request', 'tour_request', 1, 3, NULL, 'medium'),
(2, 'Yêu cầu nhận tour mới', 'HDV Lê Văn C yêu cầu nhận tour Singapore - Malaysia 6N5Đ', 'tour_request', 'tour_request', 2, 4, NULL, 'medium'),
(3, 'Tour đã được phân công', 'Bạn đã được phân công tour Đà Nẵng - Hội An 4N3Đ', 'tour_assignment', 'tour_assignment', 1, 2, NULL, 'high'),
(4, 'Tour đã được chấp thuận', 'Yêu cầu nhận tour Hà Nội - Hạ Long của bạn đã được chấp thuận', 'tour_assignment', 'tour_assignment', 2, 1, NULL, 'medium'),
(5, 'Yêu cầu đã được duyệt', 'Yêu cầu nhận tour Hà Nội - Hạ Long của bạn đã được phê duyệt', 'tour_request', 'tour_request', 3, 1, NULL, 'medium'),
(3, 'Booking mới cần xác nhận', 'Bạn có booking mới cần xác nhận: BK20240601001 - Tour Hà Nội', 'booking_assignment', 'booking_assignment', 1, NULL, 1, 'high'),
(4, 'Booking được giao', 'Admin đã giao booking BK20240602001 cho bạn', 'booking_assignment', 'booking_assignment', 2, NULL, 2, 'medium'),
(1, 'HDV đã chấp nhận booking', 'HDV Trần Thị B đã chấp nhận booking BK20240601001', 'booking_response', 'booking_response', 1, NULL, 1, 'medium'),
(2, 'HDV đã từ chối booking', 'HDV Lê Văn C đã từ chối booking BK20240602001 với lý do: Đã có lịch trình khác', 'booking_response', 'booking_response', 2, NULL, 2, 'medium');

-- Dữ liệu mẫu cho booking_assignments
INSERT INTO booking_assignments (booking_id, guide_id, assigned_by, status, notes, guide_response, deadline, priority) VALUES
(1, 3, 1, 'accepted', 'Booking khách VIP, cần chú ý đặc biệt', 'Tôi sẽ chuẩn bị kỹ lưỡng cho khách VIP này', '2024-12-15 23:59:59', 'high'),
(2, 4, 2, 'declined', 'Booking tour Đà Nẵng', 'Xin lỗi, tôi đã có lịch trình khác vào thời gian này', '2024-12-10 23:59:59', 'medium'),
(3, 5, 1, 'pending', 'Booking tour Bangkok cần HDV có kinh nghiệm quốc tế', NULL, '2024-12-20 23:59:59', 'high'),
(4, 3, 2, 'pending', 'Tour Singapore cho nhóm gia đình', NULL, '2024-12-25 23:59:59', 'medium');

-- ==========================================
-- 12. Tạo bảng tour_guides (bổ sung)
-- ==========================================
CREATE TABLE tour_guides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    license_info VARCHAR(255) COMMENT 'Thông tin chứng chỉ HDV',
    image VARCHAR(255) DEFAULT 'default-guide.jpg',
    status ENUM('Available', 'Busy', 'Inactive') DEFAULT 'Available',
    speciality TEXT COMMENT 'Chuyên môn/kỹ năng đặc biệt',
    experience_years INT DEFAULT 0,
    languages VARCHAR(255) DEFAULT 'Tiếng Việt' COMMENT 'Ngôn ngữ có thể sử dụng',
    rating DECIMAL(3,2) DEFAULT 0.00 COMMENT 'Đánh giá trung bình',
    total_tours INT DEFAULT 0 COMMENT 'Tổng số tour đã dẫn',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Thêm dữ liệu mẫu cho tour_guides
INSERT INTO tour_guides (user_id, name, phone, email, license_info, speciality, experience_years, languages, rating, total_tours) VALUES
(3, 'HDV Trần Thị B', '0987654321', 'guide1@tourism.com', 'Chứng chỉ HDV Quốc gia số: HDV2021001', 'Chuyên tour văn hóa lịch sử, tour tâm linh', 5, 'Tiếng Việt, English', 4.80, 125),
(4, 'HDV Lê Văn C', '0976543210', 'guide2@tourism.com', 'Chứng chỉ HDV Quốc gia số: HDV2021002', 'Chuyên tour thiên nhiên, eco-tour', 3, 'Tiếng Việt, English, 한국어', 4.65, 89),
(5, 'HDV Phạm Thị D', '0965432109', 'guide3@tourism.com', 'Chứng chỉ HDV Quốc gia số: HDV2021003', 'Chuyên tour ẩm thực, tour đô thị', 4, 'Tiếng Việt, English, 中文', 4.90, 156);

-- ==========================================
-- Hoàn thành tạo database
-- ==========================================
SELECT 'Database TourismManagement đã được tạo thành công!' as message;