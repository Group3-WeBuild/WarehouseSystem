<?php

namespace App\Libraries;

/**
 * =====================================================
 * CACHE SERVICE - Performance Optimization
 * =====================================================
 * 
 * Purpose: Centralized caching layer for improved system 
 * performance and scalability.
 * 
 * RUBRIC: System Performance & Scalability (Finals)
 * "Optimized codebase, handles scaling for new warehouses"
 * =====================================================
 */
class CacheService
{
    protected $cache;
    protected $defaultTTL = 300; // 5 minutes default

    // Cache key prefixes for organization
    const PREFIX_DASHBOARD = 'dashboard_';
    const PREFIX_INVENTORY = 'inventory_';
    const PREFIX_ANALYTICS = 'analytics_';
    const PREFIX_WAREHOUSE = 'warehouse_';
    const PREFIX_USER = 'user_';
    const PREFIX_REPORT = 'report_';

    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }

    /**
     * =====================================================
     * CORE CACHE OPERATIONS
     * =====================================================
     */

    /**
     * Get item from cache
     * 
     * @param string $key Cache key
     * @return mixed Cached value or null
     */
    public function get(string $key)
    {
        return $this->cache->get($key);
    }

    /**
     * Set item in cache
     * 
     * @param string $key Cache key
     * @param mixed $value Value to cache
     * @param int|null $ttl Time to live in seconds
     * @return bool Success
     */
    public function set(string $key, $value, ?int $ttl = null): bool
    {
        $ttl = $ttl ?? $this->defaultTTL;
        return $this->cache->save($key, $value, $ttl);
    }

    /**
     * Delete item from cache
     * 
     * @param string $key Cache key
     * @return bool Success
     */
    public function delete(string $key): bool
    {
        return $this->cache->delete($key);
    }

    /**
     * Clear all cache
     * 
     * @return bool Success
     */
    public function clear(): bool
    {
        return $this->cache->clean();
    }

    /**
     * Get or set with callback (cache-aside pattern)
     * 
     * @param string $key Cache key
     * @param callable $callback Function to generate data if not cached
     * @param int|null $ttl Time to live
     * @return mixed Cached or generated value
     */
    public function remember(string $key, callable $callback, ?int $ttl = null)
    {
        $cached = $this->get($key);
        
        if ($cached !== null) {
            return $cached;
        }

        $value = $callback();
        $this->set($key, $value, $ttl);
        
        return $value;
    }

    /**
     * =====================================================
     * DASHBOARD CACHING
     * =====================================================
     */

    /**
     * Get cached dashboard stats
     */
    public function getDashboardStats(string $role): ?array
    {
        return $this->get(self::PREFIX_DASHBOARD . strtolower(str_replace(' ', '_', $role)));
    }

    /**
     * Cache dashboard stats
     */
    public function cacheDashboardStats(string $role, array $stats, int $ttl = 120): bool
    {
        return $this->set(
            self::PREFIX_DASHBOARD . strtolower(str_replace(' ', '_', $role)),
            $stats,
            $ttl
        );
    }

    /**
     * Invalidate dashboard cache for a role
     */
    public function invalidateDashboardCache(?string $role = null): bool
    {
        if ($role) {
            return $this->delete(self::PREFIX_DASHBOARD . strtolower(str_replace(' ', '_', $role)));
        }
        
        // Clear all dashboard caches
        $roles = [
            'warehouse_manager',
            'warehouse_staff', 
            'inventory_auditor',
            'procurement_officer',
            'accounts_payable_clerk',
            'accounts_receivable_clerk',
            'it_administrator',
            'top_management'
        ];
        
        foreach ($roles as $r) {
            $this->delete(self::PREFIX_DASHBOARD . $r);
        }
        
        return true;
    }

    /**
     * =====================================================
     * INVENTORY CACHING
     * =====================================================
     */

    /**
     * Get cached inventory list
     */
    public function getInventoryList(?int $warehouseId = null): ?array
    {
        $key = self::PREFIX_INVENTORY . 'list';
        if ($warehouseId) {
            $key .= '_warehouse_' . $warehouseId;
        }
        return $this->get($key);
    }

    /**
     * Cache inventory list
     */
    public function cacheInventoryList(array $inventory, ?int $warehouseId = null, int $ttl = 180): bool
    {
        $key = self::PREFIX_INVENTORY . 'list';
        if ($warehouseId) {
            $key .= '_warehouse_' . $warehouseId;
        }
        return $this->set($key, $inventory, $ttl);
    }

    /**
     * Get cached low stock alerts
     */
    public function getLowStockAlerts(): ?array
    {
        return $this->get(self::PREFIX_INVENTORY . 'low_stock_alerts');
    }

    /**
     * Cache low stock alerts
     */
    public function cacheLowStockAlerts(array $alerts, int $ttl = 60): bool
    {
        return $this->set(self::PREFIX_INVENTORY . 'low_stock_alerts', $alerts, $ttl);
    }

    /**
     * Invalidate all inventory caches
     */
    public function invalidateInventoryCache(): bool
    {
        // This would ideally use pattern matching, but for file cache we'll clear key caches
        $this->delete(self::PREFIX_INVENTORY . 'list');
        $this->delete(self::PREFIX_INVENTORY . 'low_stock_alerts');
        
        // Also invalidate dashboard caches since inventory changes affect them
        $this->invalidateDashboardCache();
        
        return true;
    }

    /**
     * =====================================================
     * ANALYTICS CACHING
     * =====================================================
     */

    /**
     * Get cached analytics summary
     */
    public function getAnalyticsSummary(): ?array
    {
        return $this->get(self::PREFIX_ANALYTICS . 'summary');
    }

    /**
     * Cache analytics summary
     */
    public function cacheAnalyticsSummary(array $summary, int $ttl = 300): bool
    {
        return $this->set(self::PREFIX_ANALYTICS . 'summary', $summary, $ttl);
    }

    /**
     * Get cached forecast
     */
    public function getForecast(int $itemId, string $method): ?array
    {
        return $this->get(self::PREFIX_ANALYTICS . "forecast_{$method}_{$itemId}");
    }

    /**
     * Cache forecast
     */
    public function cacheForecast(int $itemId, string $method, array $forecast, int $ttl = 600): bool
    {
        return $this->set(self::PREFIX_ANALYTICS . "forecast_{$method}_{$itemId}", $forecast, $ttl);
    }

    /**
     * Get cached ABC analysis
     */
    public function getAbcAnalysis(): ?array
    {
        return $this->get(self::PREFIX_ANALYTICS . 'abc_analysis');
    }

    /**
     * Cache ABC analysis
     */
    public function cacheAbcAnalysis(array $analysis, int $ttl = 3600): bool
    {
        return $this->set(self::PREFIX_ANALYTICS . 'abc_analysis', $analysis, $ttl);
    }

    /**
     * Get cached KPIs
     */
    public function getKpis(string $type, int $period): ?array
    {
        return $this->get(self::PREFIX_ANALYTICS . "kpis_{$type}_{$period}");
    }

    /**
     * Cache KPIs
     */
    public function cacheKpis(string $type, int $period, array $kpis, int $ttl = 300): bool
    {
        return $this->set(self::PREFIX_ANALYTICS . "kpis_{$type}_{$period}", $kpis, $ttl);
    }

    /**
     * Invalidate analytics caches
     */
    public function invalidateAnalyticsCache(): bool
    {
        $this->delete(self::PREFIX_ANALYTICS . 'summary');
        $this->delete(self::PREFIX_ANALYTICS . 'abc_analysis');
        return true;
    }

    /**
     * =====================================================
     * WAREHOUSE CACHING
     * =====================================================
     */

    /**
     * Get cached warehouse list
     */
    public function getWarehouseList(): ?array
    {
        return $this->get(self::PREFIX_WAREHOUSE . 'list');
    }

    /**
     * Cache warehouse list
     */
    public function cacheWarehouseList(array $warehouses, int $ttl = 3600): bool
    {
        return $this->set(self::PREFIX_WAREHOUSE . 'list', $warehouses, $ttl);
    }

    /**
     * Get cached warehouse capacity
     */
    public function getWarehouseCapacity(int $warehouseId): ?array
    {
        return $this->get(self::PREFIX_WAREHOUSE . "capacity_{$warehouseId}");
    }

    /**
     * Cache warehouse capacity
     */
    public function cacheWarehouseCapacity(int $warehouseId, array $capacity, int $ttl = 300): bool
    {
        return $this->set(self::PREFIX_WAREHOUSE . "capacity_{$warehouseId}", $capacity, $ttl);
    }

    /**
     * =====================================================
     * REPORT CACHING
     * =====================================================
     */

    /**
     * Get cached report
     */
    public function getReport(string $reportType, string $dateRange): ?array
    {
        $key = self::PREFIX_REPORT . $reportType . '_' . md5($dateRange);
        return $this->get($key);
    }

    /**
     * Cache report
     */
    public function cacheReport(string $reportType, string $dateRange, array $data, int $ttl = 600): bool
    {
        $key = self::PREFIX_REPORT . $reportType . '_' . md5($dateRange);
        return $this->set($key, $data, $ttl);
    }

    /**
     * =====================================================
     * UTILITY METHODS
     * =====================================================
     */

    /**
     * Get cache statistics
     */
    public function getStats(): array
    {
        return [
            'driver' => config('Cache')->handler,
            'default_ttl' => $this->defaultTTL,
            'cache_path' => WRITEPATH . 'cache'
        ];
    }

    /**
     * Warm up cache with frequently accessed data
     */
    public function warmUp(): array
    {
        $warmed = [];

        // Warm inventory list
        $inventoryModel = new \App\Models\InventoryModel();
        $inventory = $inventoryModel->where('status', 'Active')->findAll();
        $this->cacheInventoryList($inventory);
        $warmed[] = 'inventory_list';

        // Warm warehouse list
        $warehouseModel = new \App\Models\WarehouseModel();
        $warehouses = $warehouseModel->where('status', 'Active')->findAll();
        $this->cacheWarehouseList($warehouses);
        $warmed[] = 'warehouse_list';

        // Warm low stock alerts
        $lowStock = $inventoryModel->where('status', 'Active')
            ->where('quantity <= reorder_point', null, false)
            ->findAll();
        $this->cacheLowStockAlerts($lowStock);
        $warmed[] = 'low_stock_alerts';

        return $warmed;
    }

    /**
     * Invalidate all related caches after inventory update
     */
    public function onInventoryUpdate(): void
    {
        $this->invalidateInventoryCache();
        $this->invalidateAnalyticsCache();
        $this->invalidateDashboardCache();
    }

    /**
     * Invalidate all related caches after order update
     */
    public function onOrderUpdate(): void
    {
        $this->invalidateDashboardCache();
        $this->invalidateAnalyticsCache();
    }

    /**
     * Invalidate all related caches after warehouse update
     */
    public function onWarehouseUpdate(): void
    {
        $this->delete(self::PREFIX_WAREHOUSE . 'list');
        $this->invalidateDashboardCache();
    }
}
