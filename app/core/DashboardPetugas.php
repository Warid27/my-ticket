<?php

class DashboardPetugas
{
    public static function getSidebarMenu(string $activeMenu = 'dashboard'): string
    {
        return '
            <li class="sidebar-title">Menu</li>
            <li class="sidebar-item ' . ($activeMenu === 'dashboard' ? 'active' : '') . '">
                <a href="index.php?page=dashboard&action=petugas" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-title">Operations</li>
            <li class="sidebar-item ' . ($activeMenu === 'checkin' ? 'active' : '') . '">
                <a href="index.php?page=attendee&action=index" class="sidebar-link">
                    <i class="bi bi-qr-code-scan"></i>
                    <span>Check-in</span>
                </a>
            </li>
            <li class="sidebar-item ' . ($activeMenu === 'orders' ? 'active' : '') . '">
                <a href="index.php?page=order&action=index" class="sidebar-link">
                    <i class="bi bi-cart-fill"></i>
                    <span>View Orders</span>
                </a>
            </li>
            <li class="sidebar-title">Account</li>
            <li class="sidebar-item">
                <a href="index.php?page=auth&action=logout" class="sidebar-link">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </li>';
    }
}
