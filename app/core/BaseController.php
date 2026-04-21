<?php
class BaseController
{
    protected array $allRoles = ['user', 'admin', 'petugas'];
    protected array $adminRoles = ['admin'];
    protected array $petugasRoles = ['petugas'];
    protected array $userRoles = ['user'];

    protected function isLoggedIn()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Anda belum masuk!";
            header('Location: index.php?page=auth&action=login');
            exit;
        }
    }
    protected function guard(array $roles): void
    {
        $this->isLoggedIn();

        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
            $_SESSION['error'] = "Anda tidak memiliki cukup akses!";
            header('Location: index.php?page=auth&action=login');
            exit;
        }
    }

    protected function hasRole(string $role): bool
    {
        return in_array($role, $this->allRoles);
    }
}