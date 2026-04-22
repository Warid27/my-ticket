<?php

class DashboardUser
{
    public static function getSidebarMenu(string $activeMenu = 'dashboard'): string
    {
        return '
            <li class="sidebar-title">Menu</li>
            <li class="sidebar-item ' . ($activeMenu === 'dashboard' ? 'aktif' : '') . '">
                <a href="index.php?page=dashboard&action=user" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-title">Discover</li>
            <li class="sidebar-item ' . ($activeMenu === 'events' ? 'aktif' : '') . '">
                <a href="index.php?page=event&action=index" class="sidebar-link">
                    <i class="bi bi-calendar-event-fill"></i>
                    <span>Browse Events</span>
                </a>
            </li>
            <li class="sidebar-title">My Activity</li>
            <li class="sidebar-item ' . ($activeMenu === 'orders' ? 'aktif' : '') . '">
                <a href="index.php?page=order&action=history" class="sidebar-link">
                    <i class="bi bi-cart-fill"></i>
                    <span>My Orders</span>
                </a>
            </li>
            <li class="sidebar-item ' . ($activeMenu === 'tickets' ? 'aktif' : '') . '">
                <a href="index.php?page=ticket&action=index" class="sidebar-link">
                    <i class="bi bi-ticket-perforated-fill"></i>
                    <span>My Tickets</span>
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
