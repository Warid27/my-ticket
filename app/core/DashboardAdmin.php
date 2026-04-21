<?php

class DashboardAdmin
{
    public static function getSidebarMenu(string $activeMenu = 'dashboard'): string
    {
        return '
            <li class="sidebar-title">Menu</li>
            <li class="sidebar-item ' . ($activeMenu === 'dashboard' ? 'active' : '') . '">
                <a href="index.php?page=dashboard&action=admin" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-title">Management</li>
            <li class="sidebar-item ' . ($activeMenu === 'users' ? 'active' : '') . '">
                <a href="index.php?page=user&action=index" class="sidebar-link">
                    <i class="bi bi-people-fill"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="sidebar-item ' . ($activeMenu === 'venues' ? 'active' : '') . '">
                <a href="index.php?page=venue&action=index" class="sidebar-link">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>Venues</span>
                </a>
            </li>
            <li class="sidebar-item ' . ($activeMenu === 'events' ? 'active' : '') . '">
                <a href="index.php?page=event&action=index" class="sidebar-link">
                    <i class="bi bi-calendar-event-fill"></i>
                    <span>Events</span>
                </a>
            </li>
            <li class="sidebar-item ' . ($activeMenu === 'tickets' ? 'active' : '') . '">
                <a href="index.php?page=ticket&action=index" class="sidebar-link">
                    <i class="bi bi-ticket-perforated-fill"></i>
                    <span>Tickets</span>
                </a>
            </li>
            <li class="sidebar-item ' . ($activeMenu === 'vouchers' ? 'active' : '') . '">
                <a href="index.php?page=voucher&action=index" class="sidebar-link">
                    <i class="bi bi-tag-fill"></i>
                    <span>Vouchers</span>
                </a>
            </li>
            <li class="sidebar-title">Operations</li>
            <li class="sidebar-item ' . ($activeMenu === 'orders' ? 'active' : '') . '">
                <a href="index.php?page=order&action=index" class="sidebar-link">
                    <i class="bi bi-cart-fill"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li class="sidebar-item ' . ($activeMenu === 'checkin' ? 'active' : '') . '">
                <a href="index.php?page=attendee&action=index" class="sidebar-link">
                    <i class="bi bi-qr-code-scan"></i>
                    <span>Check-in</span>
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
