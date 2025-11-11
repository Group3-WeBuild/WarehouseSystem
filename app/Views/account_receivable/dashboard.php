<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accounts Receivable Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- 
  =====================================================
  FRONTEND - CSS STYLING
  =====================================================
  All styling for the dashboard interface
  =====================================================
  -->
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6fa;
      margin: 0;
      padding: 0;
    }

    /* Sidebar */
    .sidebar {
      background-color: #0d47a1;
      color: #fff;
      min-height: 100vh;
      padding-top: 20px;
    }

    .sidebar h5 {
      text-align: center;
      font-weight: 600;
      margin-bottom: 25px;
    }

    .sidebar a {
      display: block;
      color: #cfd8dc;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 5px;
      margin: 5px 10px;
      font-size: 15px;
      transition: 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #1565c0;
      color: #fff;
    }

    /* Topbar */
    .topbar {
      background-color: #e9ecef;
      border-bottom: 1px solid #ccc;
      padding: 10px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* Dashboard container */
    .dashboard-container {
      background-color: #fff;
      margin: 25px;
      padding: 25px;
      border-radius: 6px;
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
    }

    .stat-box {
      background-color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
      text-align: center;
      padding: 20px;
      border-radius: 8px;
      transition: 0.3s;
    }

    .stat-box:hover {
      transform: translateY(-3px);
    }

    .stat-box h4 {
      color: #0d47a1;
      font-weight: 700;
    }

    .stat-box p {
      color: #212529;
      margin-bottom: 0;
    }

    /* Quick Actions */
    .quick-actions button {
      margin-right: 10px;
      margin-bottom: 10px;
    }

    /* Table */
    .recent-table th, .recent-table td {
      vertical-align: middle;
    }

    .recent-table thead {
      background-color: #f8f9fa;
    }

    .badge {
      font-size: 0.8rem;
    }
  </style>
</head>

<body>
<!-- 
=====================================================
FRONTEND - DASHBOARD LAYOUT
=====================================================
Main dashboard interface with sidebar navigation
=====================================================
-->
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
      <h5>WeBuild</h5>
      <a href="<?= base_url('accounts-receivable/dashboard') ?>" class="active">Dashboard</a>
      <a href="<?= base_url('accounts-receivable/manage-invoices') ?>">Manage Invoices</a>
      <a href="<?= base_url('accounts-receivable/record-payments') ?>">Record Payments</a>
      <a href="<?= base_url('accounts-receivable/client-management') ?>">Client Management</a>
      <a href="<?= base_url('accounts-receivable/overdue-followups') ?>">Overdue Follow-ups</a>
      <a href="<?= base_url('accounts-receivable/reports-analytics') ?>">Reports & Analytics</a>
      <a href="<?= base_url('accounts-receivable/aging-report') ?>">Aging Report</a>
      <a href="<?= base_url('accounts-receivable/settings') ?>">Settings</a>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-0">
      <!-- Top Bar -->
      <div class="topbar">
        <input type="text" class="form-control w-25" placeholder="Search">
        <div>
          <span class="me-3"><?= date('F d, Y | h:i A') ?> | <?= esc($user['role']) ?> | <strong><?= esc($user['username']) ?></strong></span>
          <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm">Logout</a>
        </div>
      </div>

      <!-- Dashboard Content -->
      <div class="dashboard-container">
        <h5><strong>Accounts Receivable Dashboard</strong></h5>
        <p class="text-muted mb-4">Monitor, Track & Manage Customer Payments</p>

        <!-- 
        =====================================================
        BACKEND DATA DISPLAY - Statistics
        =====================================================
        These values come from the controller's dashboard() method
        Data source: InvoiceModel and PaymentModel
        =====================================================
        -->
        <!-- Stats Boxes -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="stat-box">
              <h4>₱<?= number_format($stats['totalOutstanding'], 2) ?></h4>
              <p>Total Outstanding</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>₱<?= number_format($stats['overdueAmount'], 2) ?></h4>
              <p>Overdue Amount</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4><?= $stats['pendingInvoices'] ?></h4>
              <p>Pending Invoices</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <h4>₱<?= number_format($stats['monthlyCollections'], 2) ?></h4>
              <p>This Month Collections</p>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-4">
          <h6><strong>Quick Actions</strong></h6>
          <!-- FRONTEND: Action buttons linking to backend endpoints -->
          <div class="quick-actions mt-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#invoiceModal">Create New Invoice</button>
            <a href="<?= base_url('accounts-receivable/record-payments') ?>" class="btn btn-success btn-sm">Record Payment</a>
            <button class="btn btn-warning btn-sm text-dark">Send Reminders</button>
            <a href="<?= base_url('accounts-receivable/reports-analytics') ?>" class="btn btn-secondary btn-sm">Generate Report</a>
          </div>
        </div>

        <!-- 
        =====================================================
        BACKEND DATA DISPLAY - Recent Activities
        =====================================================
        Data source: $recentActivities from controller
        Generated by: InvoiceModel->getRecentActivities()
        =====================================================
        -->
        <!-- Recent Activities -->
        <div>
          <h6><strong>Recent Activities</strong></h6>
          <table class="table table-bordered table-sm mt-2 recent-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Invoice Number</th>
                <th>Client</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($recentActivities)): ?>
                <?php foreach ($recentActivities as $activity): ?>
                  <tr>
                    <td><?= date('M d, Y', strtotime($activity['updated_at'])) ?></td>
                    <td><?= esc($activity['invoice_number']) ?></td>
                    <td><?= esc($activity['client_name']) ?></td>
                    <td>₱<?= number_format($activity['amount'], 2) ?></td>
                    <td>
                      <?php
                        $badgeClass = 'bg-secondary';
                        if ($activity['status'] == 'Paid') {
                          $badgeClass = 'bg-success';
                        } elseif ($activity['status'] == 'Pending') {
                          $badgeClass = 'bg-warning text-dark';
                        } elseif ($activity['status'] == 'Overdue') {
                          $badgeClass = 'bg-danger';
                        }
                      ?>
                      <span class="badge <?= $badgeClass ?>"><?= esc($activity['status']) ?></span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center">No recent activities</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- 
=====================================================
FRONTEND - CREATE INVOICE MODAL
=====================================================
Modal form for creating new invoices
Connected to backend: AccountsReceivable->createInvoice()
=====================================================
-->
<!-- Create Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="invoiceModalLabel">Create New Invoice</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="invoiceForm">
        <div class="modal-body">
          <div id="invoiceAlert" class="alert d-none"></div>
          <div class="mb-3">
            <label class="form-label">Client *</label>
            <select name="client_id" class="form-select" required>
              <option value="">Select Client</option>
              <?php if (!empty($recentActivities)): ?>
                <?php 
                  $clients = [];
                  foreach ($recentActivities as $activity) {
                    if (!isset($clients[$activity['client_id']])) {
                      $clients[$activity['client_id']] = $activity['client_name'];
                    }
                  }
                  foreach ($clients as $id => $name):
                ?>
                  <option value="<?= $id ?>"><?= esc($name) ?></option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Issue Date *</label>
            <input type="date" name="issue_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Amount *</label>
            <input type="number" name="amount" class="form-control" placeholder="Enter Amount" step="0.01" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Due Date *</label>
            <input type="date" name="due_date" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Invoice description ..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Save Invoice</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- 
=====================================================
BACKEND AJAX INTEGRATION - Invoice Creation
=====================================================
JavaScript code that sends form data to backend
Endpoint: /accounts-receivable/create-invoice
Method: POST
Response: JSON with success/error message
=====================================================
-->
<script>
$(document).ready(function() {
  // Handle invoice form submission
  $('#invoiceForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
      url: '<?= base_url('accounts-receivable/create-invoice') ?>',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          $('#invoiceAlert').removeClass('d-none alert-danger').addClass('alert-success').text(response.message);
          setTimeout(function() {
            location.reload();
          }, 1500);
        } else {
          $('#invoiceAlert').removeClass('d-none alert-success').addClass('alert-danger').text(response.message);
        }
      },
      error: function() {
        $('#invoiceAlert').removeClass('d-none alert-success').addClass('alert-danger').text('An error occurred. Please try again.');
      }
    });
  });
});
</script>
</body>
</html>
