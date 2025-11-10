<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Record Payments | WeBuild</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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

    /* Page container */
    .page-container {
      background-color: #fff;
      margin: 25px;
      padding: 25px;
      border-radius: 6px;
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
    }

    .table th, .table td {
      vertical-align: middle;
    }

    .badge {
      font-size: 0.8rem;
    }
  </style>
</head>

<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
      <h5>WeBuild</h5>
      <a href="dashboard.php">Dashboard</a>
      <a href="manage_invoices.php">Manage Invoices</a>
      <a href="record_payments.php" class="active">Record Payments</a>
      <a href="client_management.php">Client Management</a>
      <a href="overdue_followups.php">Overdue Follow-ups</a>
      <a href="reports_analytics.php">Reports & Analytics</a>
      <a href="aging_report.php">Aging Report</a>
      <a href="settings.php">Settings</a>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-0">
      <!-- Top Bar -->
      <div class="topbar">
        <input type="text" class="form-control w-25" placeholder="Search">
        <div>
          <span class="me-3">Date | Time | Accounts Receivable Clerk | <strong>Username</strong></span>
          <button class="btn btn-outline-secondary btn-sm">Logout</button>
        </div>
      </div>

      <!-- Page Content -->
      <div class="page-container">
        <h5><strong>Payment Recording</strong></h5>
        <p class="text-muted mb-4">Customer Payment Processing Center</p>

        <!-- Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0"><strong>Recent Payments</strong></h6>
          <div>
            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#paymentModal">+ Record Payment</button>
            <button class="btn btn-secondary btn-sm">Export</button>
          </div>
        </div>

        <!-- Table -->
        <div>
          <h6><strong>Client Management</strong></h6>
          <table class="table table-bordered table-sm mt-2">
            <thead class="table-light">
              <tr>
                <th>Payment Date</th>
                <th>Invoice#</th>
                <th>Client</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Reference</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                  <button class="btn btn-outline-info btn-sm">View</button>
                  <button class="btn btn-outline-success btn-sm">Receipt</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Record Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Invoice Number</label>
            <select class="form-select">
              <option selected>Select Invoice</option>
              <option>INV-001</option>
              <option>INV-002</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Payment Date</label>
            <input type="date" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Amount Received</label>
            <input type="number" class="form-control" placeholder="Enter Amount">
          </div>
          <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <select class="form-select">
              <option selected>Select Method</option>
              <option>Cash</option>
              <option>Bank Transfer</option>
              <option>Check</option>
              <option>Credit Card</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Reference Number</label>
            <input type="text" class="form-control" placeholder="Transaction/Check number">
          </div>
          <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea class="form-control" rows="3" placeholder="Additional notes ..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Record Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
