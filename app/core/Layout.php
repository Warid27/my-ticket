<?php

class Layout
{
    private array $sections = [];
    private string $layout = 'standard';
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = $this->detectBaseUrl();
    }

    private function detectBaseUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/';
        $dir = dirname($scriptName);
        return $protocol . '://' . $host . ($dir === '/' || $dir === '\\' ? '' : $dir);
    }

    public function asset(string $path): string
    {
        // Check if file exists in app/assets first, otherwise fall back to dist/assets
        $appAssetPath = 'app/assets/' . ltrim($path, '/');
        $distAssetPath = 'dist/assets/' . ltrim($path, '/');

        if (file_exists($appAssetPath)) {
            return $this->baseUrl . '/' . $appAssetPath;
        }

        return $this->baseUrl . '/' . $distAssetPath;
    }

    public function extend(string $layout): void
    {
        $this->layout = $layout;
    }

    public function section(string $name, string $content): void
    {
        $this->sections[$name] = $content;
    }

    public function getSection(string $name): string
    {
        return $this->sections[$name] ?? '';
    }

    public function render(string $view, array $data = []): void
    {
        extract($data);

        ob_start();
        $viewFile = "app/views/{$view}.php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new Exception("View not found: {$viewFile}");
        }
        $content = ob_get_clean();

        switch ($this->layout) {
            case 'mazer-dashboard':
                $this->renderMazerDashboard($content, $data);
                break;
            case 'mazer-auth':
                $this->renderMazerAuth($content, $data);
                break;
            case 'dashboard':
                $this->renderDashboard($content, $data);
                break;
            default:
                $this->renderStandard($content, $data);
        }
    }

    private function renderStandard(string $content, array $data = []): void
    {
        extract($data);
        $title = $data['title'] ?? 'MyTicket';
        require 'app/views/partials/header.php';
        echo $content;
        require 'app/views/partials/footer.php';
    }

    private function renderDashboard(string $content, array $data = []): void
    {
        extract($data);
        $title = $data['title'] ?? 'Dashboard - MyTicket';
        $sidebar = $this->getSection('sidebar');
        require 'app/views/partials/dashboard-layout.php';
    }

    private function renderMazerDashboard(string $content, array $data = []): void
    {
        extract($data);
        $title = $data['title'] ?? 'Dashboard - MyTicket';
        $sidebarMenu = $this->getSection('sidebarMenu') ?? '';
        $activeMenu = $data['activeMenu'] ?? '';
        require 'app/views/partials/mazer-dashboard-layout.php';
    }

    private function renderMazerAuth(string $content, array $data = []): void
    {
        extract($data);
        $title = $data['title'] ?? 'MyTicket';
        require 'app/views/partials/mazer-auth-layout.php';
    }
}
