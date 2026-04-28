<?php

class ErrorHandler
{
    private Layout $layout;

    public function __construct()
    {
        $this->layout = new Layout();
    }

    /**
     * Render 404 page
     */
    public function render404(): void
    {
        http_response_code(404);
        $this->layout->extend('mazer-auth');
        $this->layout->render('404', [
            'title' => 'Page Not Found - MyTicket'
        ]);
        exit;
    }

    /**
     * Render 403 page
     */
    public function render403(string $message = ''): void
    {
        http_response_code(403);
        $this->layout->extend('mazer-auth');
        $this->layout->render('403', [
            'title' => 'Access Denied - MyTicket'
        ]);
        exit;
    }

    /**
     * Render 500 page
     */
    public function render500(string $message = ''): void
    {
        http_response_code(500);
        $this->layout->extend('mazer-auth');
        $this->layout->render('500', [
            'title' => 'Server Error - MyTicket',
            'errorMessage' => APP_ENV === 'development' ? $message : ''
        ]);
        exit;
    }

    /**
     * Render custom error page
     */
    public function renderError(string $title, string $message, string $icon = 'bi-exclamation-circle', string $backUrl = 'index.php', string $backText = 'Go Back', ?string $details = null): void
    {
        http_response_code(500);
        $this->layout->extend('mazer-auth');
        $this->layout->render('error', [
            'title' => 'Error - MyTicket',
            'errorTitle' => $title,
            'errorMessage' => $message,
            'errorIcon' => $icon,
            'backUrl' => $backUrl,
            'backText' => $backText,
            'errorDetails' => $details
        ]);
        exit;
    }

    /**
     * Handle database constraint errors
     */
    public function handleDatabaseError(PDOException $e, string $backUrl = 'index.php'): void
    {
        $message = $e->getMessage();

        // Check for foreign key constraint violation
        if (str_contains($e->getMessage(), 'foreign key constraint fails') || 
            str_contains($e->getMessage(), 'Cannot delete or update a parent row')) {
            $message = 'Cannot delete this record because it is being used by other records.';
        }
        // Check for unique constraint violation
        elseif (str_contains($e->getMessage(), 'Duplicate entry') || 
                  str_contains($e->getMessage(), 'UNIQUE constraint')) {
            $message = 'This record already exists. Duplicate entries are not allowed.';
        }
        // Check for not null constraint
        elseif (str_contains($e->getMessage(), 'cannot be null')) {
            $message = 'Required field is missing. Please fill in all required fields.';
        }

        $this->renderError(
            'Database Error',
            $message,
            'bi-database-exclamation',
            $backUrl,
            'Go Back',
            APP_ENV === 'development' ? $e->getMessage() : null
        );
    }

    /**
     * Handle database constraint errors with page-specific back URL
     */
    public function handleDatabaseErrorWithContext(PDOException $e, string $page, string $action = 'index'): void
    {
        $backUrl = "index.php?page={$page}&action={$action}";
        $this->handleDatabaseError($e, $backUrl);
    }

    /**
     * Global exception handler
     */
    public static function handleException(Throwable $e): void
    {
        $handler = new self();
        
        if (APP_ENV === 'development') {
            $handler->renderError(
                get_class($e),
                $e->getMessage(),
                'bi-bug',
                'index.php',
                'Back to Home',
                $e->getTraceAsString()
            );
        } else {
            $handler->render500();
        }
    }

    /**
     * Register global error handlers
     */
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            if (error_reporting() & $errno) {
                throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
            }
            return false;
        });
    }
}
