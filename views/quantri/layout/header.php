<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý Tour Du lịch</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-plane"></i> Tourism Admin</h2>
            </div>
            
            <!-- User Profile Section -->
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="<?php
                        switch ($_SESSION['user']['role']) {
                            case 'super_admin':
                                echo 'fas fa-crown';
                                break;
                            case 'admin':
                                echo 'fas fa-user-shield';
                                break;
                            case 'tour_guide':
                                echo 'fas fa-user-tie';
                                break;
                            default:
                                echo 'fas fa-user';
                        }
                    ?>"></i>
                </div>
                <div class="user-details">
                    <div class="user-name"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></div>
                    <div class="user-role">
                        <?php
                        switch ($_SESSION['user']['role']) {
                            case 'super_admin':
                                echo 'Super Administrator';
                                break;
                            case 'admin':
                                echo 'Administrator';
                                break;
                            case 'tour_guide':
                                echo 'Hướng dẫn viên';
                                break;
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="<?= BASE_URL ?>?act=admin-dashboard"
                            class="<?= ($_GET['act'] ?? '') == 'admin-dashboard' || ($_GET['act'] ?? '') == '/' ? 'active' : '' ?>">
                            <i class="fas fa-dashboard"></i> Dashboard
                        </a>
                    </li>
                    
                    <!-- Tours - tất cả role đều xem được -->
                    <li>
                        <a href="<?= BASE_URL ?>?act=admin-tours"
                            class="<?= ($_GET['act'] ?? '') == 'admin-tours' ? 'active' : '' ?>">
                            <i class="fas fa-map-marked-alt"></i> 
                            <?= $_SESSION['user']['role'] == 'tour_guide' ? 'Xem Tours' : 'Quản lý Tours' ?>
                        </a>
                    </li>
                    
                    <?php if ($_SESSION['user']['role'] == 'tour_guide'): ?>
                    <!-- Menu dành riêng cho HDV -->
                    <li>
                        <a href="<?= BASE_URL ?>?act=guide_assignments"
                            class="<?= ($_GET['act'] ?? '') == 'guide_assignments' ? 'active' : '' ?>">
                            <i class="fas fa-tasks"></i> Tour Assignments
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>?act=guide_schedule"
                            class="<?= ($_GET['act'] ?? '') == 'guide_schedule' ? 'active' : '' ?>">
                            <i class="fas fa-calendar-check"></i> Lịch trình Tours
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>?act=guide-booking-assignments"
                            class="<?= strpos($_GET['act'] ?? '', 'guide-booking-assignment') !== false ? 'active' : '' ?>">
                            <i class="fas fa-clipboard-list"></i> Booking Assignments
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>?act=guide-notifications"
                            class="<?= ($_GET['act'] ?? '') == 'guide-notifications' ? 'active' : '' ?>">
                            <i class="fas fa-bell"></i> Thông báo
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (in_array($_SESSION['user']['role'], ['admin', 'super_admin'])): ?>
                    <!-- Chỉ Admin và Super Admin -->
                    <li>
                        <a href="<?= BASE_URL ?>?act=admin-bookings"
                            class="<?= ($_GET['act'] ?? '') == 'admin-bookings' ? 'active' : '' ?>">
                            <i class="fas fa-ticket-alt"></i> Quản lý Bookings
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>?act=admin-booking-assignments"
                            class="<?= strpos($_GET['act'] ?? '', 'booking-assignment') !== false ? 'active' : '' ?>">
                            <i class="fas fa-share-square"></i> Booking Assignments
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>?act=admin-customers"
                            class="<?= ($_GET['act'] ?? '') == 'admin-customers' ? 'active' : '' ?>">
                            <i class="fas fa-users"></i> Quản lý Khách hàng
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>?act=admin-departures"
                            class="<?= ($_GET['act'] ?? '') == 'admin-departures' ? 'active' : '' ?>">
                            <i class="fas fa-calendar-alt"></i> Quản lý Đoàn
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>?act=admin-tour-guides"
                            class="<?= ($_GET['act'] ?? '') == 'admin-tour-guides' ? 'active' : '' ?>">
                            <i class="fas fa-user-tie"></i> Quản lý HDV
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($_SESSION['user']['role'] == 'super_admin'): ?>
                    <!-- Chỉ Super Admin -->
                    <li>
                        <a href="<?= BASE_URL ?>?act=user-list"
                            class="<?= ($_GET['act'] ?? '') == 'user-list' ? 'active' : '' ?>">
                            <i class="fas fa-user-cog"></i> Quản lý Tài khoản
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <!-- Logout -->
                    <li class="logout-item">
                        <a href="<?= BASE_URL ?>?act=logout" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <header class="top-header">
                <div class="header-left">
                    <button class="toggle-sidebar" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div class="header-right">
                    <div class="user-info">
                        <i class="<?php
                            switch ($_SESSION['user']['role']) {
                                case 'super_admin':
                                    echo 'fas fa-crown';
                                    break;
                                case 'admin':
                                    echo 'fas fa-user-shield';
                                    break;
                                case 'tour_guide':
                                    echo 'fas fa-user-tie';
                                    break;
                                default:
                                    echo 'fas fa-user';
                            }
                        ?> header-avatar"></i>
                        <div class="user-text">
                            <span class="user-name"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></span>
                            <small class="user-role-small">
                                <?php
                                switch ($_SESSION['user']['role']) {
                                    case 'super_admin':
                                        echo 'Super Admin';
                                        break;
                                    case 'admin':
                                        echo 'Admin';
                                        break;
                                    case 'tour_guide':
                                        echo 'HDV';
                                        break;
                                }
                                ?>
                            </small>
                        </div>
                    </div>
                </div>
            </header>

            <main class="content">