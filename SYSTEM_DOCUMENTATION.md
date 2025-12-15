# WeBuild Warehouse Management System
## Complete System Documentation

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [Architecture & Design](#2-architecture--design)
3. [Database Schema](#3-database-schema)
4. [User Roles & Permissions](#4-user-roles--permissions)
5. [Module Documentation](#5-module-documentation)
6. [Analytics & Forecasting](#6-analytics--forecasting)
7. [Security & Audit Trail](#7-security--audit-trail)
8. [Performance Optimization](#8-performance-optimization)
9. [API Reference](#9-api-reference)
10. [Installation & Setup](#10-installation--setup)

---

## 1. System Overview

### Purpose
The WeBuild Warehouse Management System is a comprehensive solution for managing warehouse operations, inventory tracking, procurement, financial transactions, and analytics. Built on CodeIgniter 4, it provides a robust, scalable platform for multi-warehouse management.

### Key Features
- **Multi-Warehouse Support**: Manage inventory across multiple warehouse locations
- **Role-Based Access Control (RBAC)**: 8 distinct user roles with granular permissions
- **Real-Time Inventory Tracking**: Barcode/QR scanning, stock movements, batch tracking
- **Demand Forecasting**: Predictive analytics using Moving Average and Exponential Smoothing
- **Financial Management**: Accounts Payable/Receivable integration
- **Comprehensive Audit Trail**: Complete activity logging for compliance
- **Performance Optimization**: Caching layer for optimal response times

### Technology Stack
| Component | Technology |
|-----------|------------|
| Framework | CodeIgniter 4.6.3 |
| Language | PHP 8.1+ |
| Database | MySQL 8.0+ |
| Frontend | Bootstrap 5.3, Chart.js |
| Caching | File-based (configurable) |
| Authentication | Session-based |

---

## 2. Architecture & Design

### MVC Pattern
```
┌─────────────────────────────────────────────────────────────┐
│                      PRESENTATION LAYER                      │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │   Views     │  │  JavaScript │  │    CSS/Bootstrap    │  │
│  └─────────────┘  └─────────────┘  └─────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                     APPLICATION LAYER                        │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │ Controllers │  │   Filters   │  │     Libraries       │  │
│  └─────────────┘  └─────────────┘  └─────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                       DATA LAYER                             │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │   Models    │  │  Migrations │  │      Seeders        │  │
│  └─────────────┘  └─────────────┘  └─────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                     DATABASE (MySQL)                         │
│  23 Normalized Tables with Foreign Key Constraints           │
└─────────────────────────────────────────────────────────────┘
```

### Directory Structure
```
WarehouseSystem/
├── app/
│   ├── Config/           # Configuration files
│   │   ├── Routes.php    # URL routing definitions
│   │   ├── Filters.php   # Filter configurations
│   │   └── Database.php  # Database settings
│   ├── Controllers/      # Request handlers
│   │   ├── Admin.php
│   │   ├── Analytics.php
│   │   ├── WarehouseManager.php
│   │   └── ...
│   ├── Models/           # Database models
│   ├── Views/            # UI templates
│   ├── Libraries/        # Custom libraries
│   │   ├── AnalyticsEngine.php
│   │   ├── AuditService.php
│   │   └── CacheService.php
│   ├── Filters/          # Request filters
│   │   ├── RoleFilter.php
│   │   └── AuditFilter.php
│   └── Database/
│       ├── Migrations/   # Database schema changes
│       └── Seeds/        # Sample data
├── public/               # Web root
├── writable/             # Logs, cache, uploads
└── system/               # CI4 core (don't modify)
```

---

## 3. Database Schema

### Entity Relationship Overview

The database is fully normalized (3NF) with 23 tables and 28+ foreign key constraints.

### Core Tables

#### Users & Authentication
```sql
users
├── id (PK)
├── name
├── email (UNIQUE)
├── password (bcrypt hashed)
├── role
├── status
└── timestamps

roles
├── id (PK)
├── name
├── description
└── permissions (JSON)
```

#### Inventory Management
```sql
inventory
├── id (PK)
├── product_name
├── sku (UNIQUE)
├── category
├── quantity
├── unit_price
├── reorder_point
├── warehouse_id (FK → warehouses)
└── status

warehouses
├── id (PK)
├── name
├── location
├── capacity
└── status

stock_movements
├── id (PK)
├── item_id (FK → inventory)
├── movement_type (Stock In/Out/Transfer/Adjustment)
├── quantity
├── source_warehouse_id (FK)
├── destination_warehouse_id (FK)
└── performed_by (FK → users)
```

#### Orders (Normalized)
```sql
orders
├── id (PK)
├── order_number (UNIQUE)
├── order_type
├── client_id (FK)
├── total_amount
├── status
└── timestamps

order_items  -- Normalized from JSON
├── id (PK)
├── order_id (FK → orders)
├── inventory_id (FK → inventory)
├── quantity
├── unit_price
└── total_price
```

#### Financial
```sql
ar_invoices (Accounts Receivable)
├── id (PK)
├── invoice_number
├── client_id (FK)
├── total_amount
├── paid_amount
├── status
└── due_date

ap_invoices (Accounts Payable)
├── id (PK)
├── invoice_number
├── vendor_id (FK)
├── total_amount
├── status
└── due_date
```

### Audit Trail
```sql
audit_trail
├── id (PK)
├── user_id (FK → users)
├── action_type
├── table_affected
├── record_id
├── old_values (JSON)
├── new_values (JSON)
├── ip_address
└── created_at
```

---

## 4. User Roles & Permissions

### Role Hierarchy

| Role | Level | Access Areas |
|------|-------|-------------|
| IT Administrator | 1 | Full system access, user management, database, security |
| Top Management | 2 | Executive dashboards, analytics, all reports |
| Warehouse Manager | 3 | Inventory CRUD, stock movements, orders, warehouse reports |
| Warehouse Staff | 4 | Stock movements, order processing (limited) |
| Inventory Auditor | 5 | Inventory counts, discrepancy resolution, audit reports |
| Procurement Officer | 6 | Purchase requisitions, POs, vendor management |
| Accounts Payable Clerk | 7 | Vendor invoices, payment processing |
| Accounts Receivable Clerk | 8 | Customer invoices, payment recording |

### Permission Matrix

```
                          │ View │ Create │ Update │ Delete │ Approve │
──────────────────────────┼──────┼────────┼────────┼────────┼─────────┤
IT Administrator          │  ✓   │   ✓    │   ✓    │   ✓    │    ✓    │
Top Management            │  ✓   │   ✗    │   ✗    │   ✗    │    ✓    │
Warehouse Manager         │  ✓   │   ✓    │   ✓    │   ✓    │    ✓    │
Warehouse Staff           │  ✓   │   ✓    │   ✓    │   ✗    │    ✗    │
Inventory Auditor         │  ✓   │   ✓    │   ✓    │   ✗    │    ✓    │
Procurement Officer       │  ✓   │   ✓    │   ✓    │   ✗    │    ✗    │
Accounts Payable          │  ✓   │   ✓    │   ✓    │   ✗    │    ✗    │
Accounts Receivable       │  ✓   │   ✓    │   ✓    │   ✗    │    ✗    │
```

---

## 5. Module Documentation

### 5.1 Warehouse Management Module

**Controller**: `WarehouseManager.php`

**Routes**:
- `GET /warehouse-manager/dashboard` - Main dashboard
- `GET /warehouse-manager/inventory` - Inventory list
- `POST /warehouse-manager/add-item` - Add inventory item
- `POST /warehouse-manager/update-item/{id}` - Update item
- `POST /warehouse-manager/delete-item/{id}` - Delete item
- `POST /warehouse-manager/adjust-stock` - Stock adjustment
- `POST /warehouse-manager/transfer-inventory` - Inter-warehouse transfer

**Features**:
- Real-time inventory tracking
- Low stock alerts
- Batch tracking with expiry dates
- Barcode/QR code scanning
- Multi-warehouse transfers

### 5.2 Procurement Module

**Controller**: `Procurement.php`

**Routes**:
- `GET /procurement/dashboard` - Dashboard
- `GET /procurement/requisitions` - Purchase requisitions
- `GET /procurement/purchase-orders` - Purchase orders
- `POST /procurement/create-requisition` - Create PR
- `POST /procurement/create-purchase-order` - Create PO
- `POST /procurement/receive-po` - Receive goods

### 5.3 Accounts Payable Module

**Controller**: `AccountsPayable.php`

**Routes**:
- `GET /accounts-payable/dashboard` - Dashboard
- `GET /accounts-payable/pending-invoices` - Pending invoices
- `POST /accounts-payable/create-invoice` - Create invoice
- `POST /accounts-payable/approve-invoice/{id}` - Approve
- `POST /accounts-payable/process-payment` - Process payment

### 5.4 Accounts Receivable Module

**Controller**: `AccountsReceivable.php`

**Routes**:
- `GET /accounts-receivable/dashboard` - Dashboard
- `GET /accounts-receivable/manage-invoices` - Invoice management
- `POST /accounts-receivable/create-invoice` - Create invoice
- `POST /accounts-receivable/create-payment` - Record payment

### 5.5 Inventory Auditor Module

**Controller**: `InventoryAuditor.php`

**Routes**:
- `GET /inventory-auditor/dashboard` - Dashboard
- `GET /inventory-auditor/count-sessions` - Count sessions
- `POST /inventory-auditor/start-count` - Start physical count
- `POST /inventory-auditor/record-item-count` - Record count
- `POST /inventory-auditor/resolve-discrepancy` - Resolve discrepancy

### 5.6 Admin Module

**Controller**: `Admin.php`

**Routes**:
- `GET /admin/dashboard` - IT Admin dashboard
- `GET /admin/user-accounts` - User management
- `POST /admin/create-user` - Create user
- `GET /admin/audit-logs` - View audit logs
- `GET /admin/backup-recovery` - Database backup
- `POST /admin/backup-database` - Create backup

---

## 6. Analytics & Forecasting

### AnalyticsEngine Library

Located at: `app/Libraries/AnalyticsEngine.php`

### Forecasting Methods

#### 1. Moving Average Forecast
```php
$analytics = new AnalyticsEngine();
$forecast = $analytics->movingAverageForecast($itemId, $historicalDays, $forecastDays);

// Returns:
[
    'predicted_demand' => 15.5,      // Daily demand prediction
    'total_period_demand' => 217,    // Total for forecast period
    'data_points' => 30,             // Historical data points used
    'historical_days' => 30,
    'forecast_period' => 14
]
```

#### 2. Exponential Smoothing
```php
$forecast = $analytics->exponentialSmoothingForecast($itemId, $alpha, $forecastDays);

// Alpha (α) controls responsiveness:
// α = 0.1-0.3: Stable, less reactive to recent changes
// α = 0.5-0.7: Balanced
// α = 0.8-0.9: Highly reactive to recent data
```

#### 3. Seasonal Analysis
```php
$seasonal = $analytics->seasonalAnalysis($itemId, $days);

// Returns day-of-week demand patterns:
[
    'day_of_week' => [
        0 => ['average' => 12.5, 'index' => 0.85],  // Sunday
        1 => ['average' => 18.2, 'index' => 1.24],  // Monday
        // ...
    ]
]
```

### Inventory KPIs

#### ABC Analysis
```php
$abc = $analytics->abcAnalysis();

// Returns items classified by value contribution:
[
    'A' => [...],  // Top 20% by value (80% of total value)
    'B' => [...],  // Next 30% (15% of value)
    'C' => [...]   // Bottom 50% (5% of value)
]
```

#### Inventory Turnover Ratio
```php
$turnover = $analytics->inventoryTurnoverRatio();

// Returns per-item metrics:
[
    'product_name' => 'Widget A',
    'turnover_ratio' => 6.5,      // Higher = faster moving
    'days_of_supply' => 56,       // Days until stockout
    'cogs' => 15000.00
]
```

#### Reorder Points
```php
$reorderPoints = $analytics->calculateReorderPoints($leadTimeDays);

// Returns calculated ROP with safety stock:
[
    'product_name' => 'Widget A',
    'current_stock' => 50,
    'avg_daily_usage' => 5.2,
    'reorder_point' => 42,        // Calculated ROP
    'safety_stock' => 16,         // Buffer stock
    'needs_reorder' => true
]
```

### Analytics API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/analytics/api/forecast/{itemId}` | GET | Get forecasts for item |
| `/analytics/api/abc-analysis` | GET | Get ABC classification |
| `/analytics/api/warehouse-performance` | GET | Warehouse metrics |
| `/analytics/api/financial-kpis/{period}` | GET | Financial KPIs |
| `/analytics/api/trends/{days}` | GET | Trend data |
| `/analytics/export/{type}` | GET | Export report (JSON) |

---

## 7. Security & Audit Trail

### AuditService Library

Located at: `app/Libraries/AuditService.php`

### Logging Methods

```php
$audit = new AuditService();

// General action logging
$audit->logAction('Inventory', 'update', 'Updated item #123', [
    'field' => 'quantity',
    'old_value' => 100,
    'new_value' => 150
]);

// Authentication events
$audit->logAuth('login', true, 'Successful login');
$audit->logAuth('login', false, 'Invalid password');

// Inventory changes
$audit->logInventoryChange($inventoryId, 'update', $oldValues, $newValues);

// Stock movements
$audit->logStockMovement($movementId, 'Stock In', 50, 'item_id' => 15);

// Financial transactions
$audit->logFinancialTransaction('payment', 'ap_invoices', $invoiceId, 5000.00);

// Security events
$audit->logSecurityEvent('access_denied', 'Attempted to access admin panel');
```

### RoleFilter (RBAC)

Located at: `app/Filters/RoleFilter.php`

**Configuration** in `app/Config/Filters.php`:
```php
public array $filters = [
    'role:warehouse' => ['before' => ['warehouse/*', 'warehouse-manager/*']],
    'role:procurement' => ['before' => ['procurement/*']],
    'role:accounts-payable' => ['before' => ['accounts-payable/*']],
    'role:accounts-receivable' => ['before' => ['accounts-receivable/*']],
    'role:management' => ['before' => ['management/*']],
    'role:admin' => ['before' => ['admin/*']],
    'role:analytics' => ['before' => ['analytics/*']],
];
```

### AuditFilter (Automatic Logging)

Located at: `app/Filters/AuditFilter.php`

Automatically logs:
- All POST, PUT, PATCH, DELETE requests
- Important page views (dashboards, reports, settings)
- Request duration and performance metrics

---

## 8. Performance Optimization

### CacheService Library

Located at: `app/Libraries/CacheService.php`

### Caching Strategies

```php
$cache = new CacheService();

// Cache-aside pattern
$data = $cache->remember('inventory_list', function() {
    return $this->inventoryModel->findAll();
}, 300); // 5-minute TTL

// Dashboard stats caching
$cache->cacheDashboardStats('warehouse_manager', $stats, 120);

// Invalidation on updates
$cache->onInventoryUpdate();  // Clears inventory-related caches
$cache->onOrderUpdate();      // Clears order-related caches
```

### Cache TTL Guidelines

| Data Type | TTL (seconds) | Rationale |
|-----------|---------------|-----------|
| Dashboard stats | 120 | Frequently viewed, moderate freshness |
| Inventory list | 180 | Changes moderately often |
| Warehouse list | 3600 | Rarely changes |
| Low stock alerts | 60 | Time-sensitive |
| ABC Analysis | 3600 | Computation-heavy, stable |
| Forecasts | 600 | Analytics data, moderate refresh |

### Database Optimization

1. **Indexes**: Foreign keys, frequently queried columns
2. **Eager Loading**: Relationships loaded in single queries
3. **Pagination**: Large datasets paginated server-side
4. **Query Optimization**: Aggregations done at database level

---

## 9. API Reference

### Authentication

All API endpoints require session authentication.

### Common Response Format

```json
{
    "success": true,
    "data": { ... },
    "message": "Operation completed successfully"
}
```

### Error Response

```json
{
    "success": false,
    "error": "Error description",
    "code": 400
}
```

### Endpoints Summary

#### Inventory
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/warehouse-manager/inventory` | List inventory |
| POST | `/warehouse-manager/add-item` | Create item |
| POST | `/warehouse-manager/update-item/{id}` | Update item |
| POST | `/warehouse-manager/delete-item/{id}` | Delete item |
| POST | `/warehouse-manager/adjust-stock` | Adjust stock |
| POST | `/warehouse-manager/transfer-inventory` | Transfer between warehouses |

#### Orders
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/warehouse-manager/orders` | List orders |
| POST | `/warehouse-manager/process-order/{id}` | Process order |
| POST | `/warehouse-manager/complete-order/{id}` | Complete order |

#### Analytics
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/analytics/api/forecast/{itemId}` | Demand forecast |
| GET | `/analytics/api/abc-analysis` | ABC classification |
| GET | `/analytics/api/warehouse-performance` | Warehouse KPIs |
| GET | `/analytics/api/financial-kpis/{period}` | Financial metrics |
| GET | `/analytics/api/trends/{days}` | Trend data |

---

## 10. Installation & Setup

### Requirements

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Apache/Nginx with mod_rewrite
- Composer
- Git

### Installation Steps

1. **Clone Repository**
```bash
git clone <repository-url>
cd WarehouseSystem
```

2. **Install Dependencies**
```bash
composer install
```

3. **Environment Configuration**
```bash
cp env .env
# Edit .env with database credentials
```

4. **Database Setup**
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE warehouse_system"

# Run migrations
php spark migrate

# Seed sample data
php spark db:seed DatabaseSeeder
```

5. **Set Permissions**
```bash
chmod -R 755 writable/
```

6. **Start Development Server**
```bash
php spark serve
```

### Default Users

| Email | Password | Role |
|-------|----------|------|
| admin@webuild.com | password123 | IT Administrator |
| manager@webuild.com | password123 | Warehouse Manager |
| auditor@webuild.com | password123 | Inventory Auditor |
| procurement@webuild.com | password123 | Procurement Officer |
| ap@webuild.com | password123 | Accounts Payable Clerk |
| ar@webuild.com | password123 | Accounts Receivable Clerk |
| executive@webuild.com | password123 | Top Management |

### Production Deployment

1. Set `CI_ENVIRONMENT = production` in `.env`
2. Configure proper database credentials
3. Set up SSL certificate
4. Configure web server (Apache/Nginx)
5. Set proper file permissions
6. Enable caching
7. Configure backup schedules

---

## Appendix

### Troubleshooting

**Issue**: Database connection failed
- Check `.env` database credentials
- Verify MySQL service is running

**Issue**: 404 errors on routes
- Ensure mod_rewrite is enabled
- Check `.htaccess` file exists

**Issue**: Permission errors
- Run `chmod -R 755 writable/`

### Support

For technical support or bug reports, contact the development team.

---

*Documentation generated for WeBuild Warehouse Management System*
*Last Updated: December 2024*
