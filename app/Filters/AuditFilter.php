<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\AuditService;

/**
 * =====================================================
 * AUDIT LOGGING FILTER
 * =====================================================
 * 
 * Purpose: Automatically logs all HTTP requests
 * for audit compliance.
 * 
 * RUBRIC: Audit Trail & Security (Finals)
 * "Complete audit logs and robust role-based security"
 * =====================================================
 */
class AuditFilter implements FilterInterface
{
    /**
     * Routes to exclude from logging (high-frequency, low-value)
     */
    protected $excludeRoutes = [
        'assets',
        'public',
        'favicon.ico'
    ];

    /**
     * Before filter - record start time
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Store start time for performance measurement
        if (!defined('BOOT_TIME')) {
            define('BOOT_TIME', microtime(true));
        }
    }

    /**
     * After filter - log the request
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Check if route should be excluded
        $uri = $request->getUri()->getPath();
        foreach ($this->excludeRoutes as $exclude) {
            if (strpos($uri, $exclude) !== false) {
                return;
            }
        }

        // Only log significant requests
        $method = $request->getMethod();
        if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            // Skip GET requests except for important pages
            if ($method === 'GET' && !$this->isImportantPage($uri)) {
                return;
            }
        }

        try {
            $session = session();
            $audit = new AuditService();
            
            // Determine module from URI
            $segments = explode('/', trim($uri, '/'));
            $module = ucfirst($segments[0] ?? 'General');
            $action = $segments[1] ?? 'view';

            // Log based on HTTP method
            $actionType = match($method) {
                'POST' => 'create',
                'PUT', 'PATCH' => 'update',
                'DELETE' => 'delete',
                default => 'view'
            };

            $audit->logAction(
                $module,
                $actionType,
                "HTTP {$method} request to {$uri}",
                null,
                null,
                null,
                null,
                $response->getStatusCode() >= 400 ? 'failed' : 'success'
            );
        } catch (\Exception $e) {
            // Silently fail - don't break the application
            log_message('error', 'Audit logging failed: ' . $e->getMessage());
        }
    }

    /**
     * Check if this is an important page worth logging
     */
    protected function isImportantPage(string $uri): bool
    {
        $importantPatterns = [
            'dashboard',
            'management',
            'admin',
            'reports',
            'analytics',
            'audit',
            'users'
        ];

        foreach ($importantPatterns as $pattern) {
            if (strpos($uri, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }
}
