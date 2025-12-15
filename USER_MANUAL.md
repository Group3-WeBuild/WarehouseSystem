# WeBuild Warehouse Management System
## User Manual

---

## Welcome

Welcome to the WeBuild Warehouse Management System! This manual will guide you through using the system based on your role.

---

## Table of Contents

1. [Getting Started](#1-getting-started)
2. [Dashboard Overview](#2-dashboard-overview)
3. [Warehouse Manager Guide](#3-warehouse-manager-guide)
4. [Inventory Auditor Guide](#4-inventory-auditor-guide)
5. [Procurement Officer Guide](#5-procurement-officer-guide)
6. [Accounts Payable Guide](#6-accounts-payable-guide)
7. [Accounts Receivable Guide](#7-accounts-receivable-guide)
8. [IT Administrator Guide](#8-it-administrator-guide)
9. [Top Management Guide](#9-top-management-guide)
10. [Analytics & Reports](#10-analytics--reports)

---

## 1. Getting Started

### Logging In

1. Open your web browser (Chrome, Firefox, Edge recommended)
2. Navigate to the system URL provided by your administrator
3. Enter your email address and password
4. Click **Login**

![Login Screen](images/login.png)

### Changing Your Password

1. Click on your name in the top-right corner
2. Select **Profile**
3. Enter your current password
4. Enter your new password twice
5. Click **Update Password**

### Logging Out

Click the **Logout** button in the top-right corner of any page.

---

## 2. Dashboard Overview

After logging in, you'll see your role-specific dashboard with:

- **Quick Stats**: Key metrics relevant to your role
- **Alerts**: Important notifications requiring attention
- **Recent Activity**: Latest actions and updates
- **Quick Actions**: Common tasks you perform frequently

---

## 3. Warehouse Manager Guide

### Your Dashboard

Your dashboard shows:
- Total inventory items
- Low stock alerts
- Pending orders
- Recent stock movements

### Managing Inventory

#### Viewing Inventory
1. Click **Inventory** in the sidebar
2. Use the search box to find specific items
3. Use filters to narrow results by category, warehouse, or status

#### Adding New Items
1. Go to **Inventory** page
2. Click **Add New Item**
3. Fill in the required fields:
   - Product Name
   - SKU (unique identifier)
   - Category
   - Quantity
   - Unit Price
   - Reorder Point
   - Warehouse
4. Click **Save**

#### Updating Items
1. Find the item in the inventory list
2. Click the **Edit** button (pencil icon)
3. Modify the necessary fields
4. Click **Update**

#### Stock Adjustments
1. Click **Stock Movements** in sidebar
2. Click **New Adjustment**
3. Select the item
4. Enter the adjustment quantity (positive or negative)
5. Select reason (Damage, Theft, Recount, etc.)
6. Add notes explaining the adjustment
7. Click **Submit**

### Stock Movements

#### Recording Stock In
1. Go to **Stock Movements**
2. Click **Stock In**
3. Scan barcode or select item
4. Enter quantity received
5. Select source warehouse
6. Add reference number (PO number, etc.)
7. Click **Confirm**

#### Recording Stock Out
1. Go to **Stock Movements**
2. Click **Stock Out**
3. Select items being issued
4. Enter quantities
5. Add reference (order number, etc.)
6. Click **Confirm**

### Inter-Warehouse Transfers
1. Go to **Transfer Inventory**
2. Select source warehouse
3. Select destination warehouse
4. Choose items to transfer
5. Enter quantities
6. Click **Create Transfer**

### Processing Orders
1. Go to **Orders**
2. Click on a pending order
3. Review order details
4. Click **Process Order** to begin picking
5. Once items are picked, click **Complete Order**

### Low Stock Alerts
- Alerts appear on your dashboard when items fall below reorder point
- Click on an alert to view item details
- Consider creating a purchase requisition for low stock items

---

## 4. Inventory Auditor Guide

### Your Dashboard
- Scheduled count sessions
- Pending discrepancies to resolve
- Audit completion statistics

### Physical Inventory Counts

#### Starting a Count Session
1. Go to **Count Sessions**
2. Click **Start New Count**
3. Select warehouse to audit
4. Select items to count (all or specific categories)
5. Click **Begin Count**

#### Recording Counts
1. Go to your active count session
2. For each item:
   - Scan barcode or search for item
   - Enter the physical count quantity
   - Click **Save Count**
3. Continue until all items are counted

#### Completing a Count
1. Review all counted items
2. Click **Complete Count**
3. System will identify discrepancies automatically

### Discrepancy Resolution

1. Go to **Discrepancy Review**
2. View items with count differences
3. For each discrepancy:
   - Click **Review**
   - Investigate the difference
   - Select resolution action:
     - **Accept Count**: Update system to match physical
     - **Recount**: Schedule another count
     - **Adjust**: Make partial adjustment
   - Add explanation notes
   - Click **Resolve**

### Audit Reports
1. Go to **Reports**
2. Select report type:
   - Count Accuracy Report
   - Discrepancy Summary
   - Audit History
3. Select date range
4. Click **Generate Report**
5. Export as PDF or Excel if needed

---

## 5. Procurement Officer Guide

### Your Dashboard
- Pending requisitions
- Active purchase orders
- Delivery schedule
- Vendor performance metrics

### Purchase Requisitions

#### Creating a Requisition
1. Go to **Requisitions**
2. Click **New Requisition**
3. Add items needed:
   - Select item from inventory
   - Enter quantity
   - Add justification
4. Click **Add Item** for more items
5. Click **Submit Requisition**

#### Tracking Requisitions
- **Draft**: Not yet submitted
- **Submitted**: Awaiting approval
- **Approved**: Ready for PO creation
- **Rejected**: See reason and resubmit if needed

### Purchase Orders

#### Creating a PO
1. Go to **Purchase Orders**
2. Click **Create PO**
3. Select approved requisition(s)
4. Select vendor
5. Confirm items and quantities
6. Review pricing
7. Click **Generate PO**

#### Sending PO to Vendor
1. Open the purchase order
2. Click **Send to Vendor**
3. Choose delivery method (email, print)
4. Confirm sending

### Receiving Deliveries

1. Go to **Delivery Tracking**
2. Find the expected delivery
3. Click **Record Receipt**
4. For each item:
   - Enter quantity received
   - Note any damages or discrepancies
5. Click **Complete Receipt**
6. This automatically updates inventory

---

## 6. Accounts Payable Guide

### Your Dashboard
- Pending invoices for approval
- Upcoming payment due dates
- Payment processing queue
- Vendor balances

### Managing Vendor Invoices

#### Recording an Invoice
1. Go to **Pending Invoices**
2. Click **New Invoice**
3. Select vendor
4. Enter invoice details:
   - Invoice number
   - Invoice date
   - Due date
   - Line items and amounts
5. Attach invoice scan (if available)
6. Click **Save**

#### Approving Invoices
1. Open pending invoice
2. Match to purchase order
3. Verify amounts
4. Click **Approve** or **Reject**
5. Add notes if rejecting

### Payment Processing

1. Go to **Payment Processing**
2. Select approved invoices for payment
3. Choose payment method
4. Enter payment details
5. Click **Process Payment**
6. Print or save payment confirmation

### Vendor Management
1. Go to **Vendor Management**
2. View all vendors
3. Click a vendor to see:
   - Contact information
   - Outstanding balances
   - Payment history
   - Performance rating

---

## 7. Accounts Receivable Guide

### Your Dashboard
- Outstanding invoices
- Overdue accounts
- Payment collection summary
- Customer balances

### Managing Customer Invoices

#### Creating an Invoice
1. Go to **Manage Invoices**
2. Click **New Invoice**
3. Select customer
4. Add line items:
   - Select product/service
   - Enter quantity
   - Set price
5. Apply any discounts
6. Set payment terms
7. Click **Create Invoice**

#### Sending Invoices
1. Open the invoice
2. Click **Send to Customer**
3. Choose delivery method
4. Confirm sending

### Recording Payments

1. Go to **Record Payments**
2. Click **New Payment**
3. Select customer
4. Select invoice(s) being paid
5. Enter payment amount
6. Select payment method
7. Enter reference number
8. Click **Record Payment**

### Overdue Follow-ups
1. Go to **Overdue Follow-ups**
2. View all overdue invoices
3. Click on an invoice to see customer contact
4. Record follow-up action taken
5. Schedule reminder if needed

### Aging Report
1. Go to **Aging Report**
2. View receivables by age:
   - Current
   - 1-30 days
   - 31-60 days
   - 61-90 days
   - Over 90 days
3. Export for further analysis

---

## 8. IT Administrator Guide

### Your Dashboard
- System health status
- Active user sessions
- Recent security events
- Storage usage

### User Management

#### Creating Users
1. Go to **User Accounts**
2. Click **Add User**
3. Enter user details:
   - Full name
   - Email address
   - Password (temporary)
   - Role
4. Click **Create User**
5. User will receive login credentials

#### Managing Users
- **Activate/Deactivate**: Toggle user access
- **Reset Password**: Generate temporary password
- **Change Role**: Modify user permissions
- **Delete User**: Remove user (with confirmation)

### Audit Logs
1. Go to **Audit Logs**
2. View all system activities
3. Filter by:
   - User
   - Action type
   - Date range
   - Affected module
4. Export logs for compliance

### Backup & Recovery

#### Creating a Backup
1. Go to **Backup & Recovery**
2. Click **Create Backup**
3. Wait for backup to complete
4. Download backup file

#### Restoring from Backup
1. Go to **Backup & Recovery**
2. Select backup to restore
3. Click **Restore**
4. Confirm restoration (WARNING: This overwrites current data)

### System Health
Monitor:
- Database status
- Storage capacity
- Error logs
- Performance metrics

---

## 9. Top Management Guide

### Executive Dashboard
Your dashboard provides a high-level view:
- Inventory value
- Revenue trends
- Key performance indicators
- Department summaries

### Analytics & Forecasting
1. Go to **Analytics Dashboard**
2. View:
   - Demand forecasts
   - Inventory KPIs
   - Warehouse performance
   - Financial metrics

### Reports

#### Generating Executive Reports
1. Go to **Executive Reports**
2. Select report type:
   - Monthly Summary
   - Quarterly Analysis
   - Annual Report
3. Select date range
4. Click **Generate**
5. Download or share report

### Approval Workflows
- Review and approve high-value transactions
- Approve budget exceptions
- Sign off on major adjustments

---

## 10. Analytics & Reports

### Accessing Analytics
1. Click **Analytics** in the sidebar
2. Choose analytics module:
   - Dashboard
   - Demand Forecasting
   - Inventory KPIs
   - Warehouse Performance
   - Financial KPIs
   - Trend Analysis
   - Reorder Recommendations

### Demand Forecasting
1. Go to **Demand Forecasting**
2. Select a product
3. View predictions:
   - Moving Average forecast
   - Exponential Smoothing forecast
   - Seasonal patterns

### Inventory KPIs
- **ABC Analysis**: Items classified by value (A=High, B=Medium, C=Low)
- **Turnover Ratio**: How fast inventory sells
- **Days of Supply**: How long until stockout
- **Reorder Points**: Calculated reorder levels

### Exporting Reports
1. Navigate to any report page
2. Click **Export**
3. Choose format (PDF, Excel, CSV)
4. Save or print

---

## Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| Ctrl + S | Save current form |
| Ctrl + N | New item/record |
| Ctrl + F | Search/Find |
| Esc | Close modal/dialog |

---

## Getting Help

### In-App Help
- Look for **?** icons next to features for tooltips
- Hover over fields for additional guidance

### Support
Contact your system administrator for:
- Password resets
- Access issues
- Technical problems

### Training
Request additional training through your supervisor or IT department.

---

*WeBuild Warehouse Management System - User Manual*
*Version 1.0 | December 2024*
