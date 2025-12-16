<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Financial Reports | Top Management | WeBuild</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6fa;
      margin: 0;
      padding: 0;
    }

    /* Sidebar */
    .sidebar {
      min-height: 100vh;
      background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
    }
    .sidebar .nav-link {
      color: #ecf0f1;
      padding: 12px 20px;
      margin: 5px 10px;
      border-radius: 8px;
      transition: all 0.3s;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
      background: rgba(255,255,255,0.1);
      color: #fff;
    }
    .sidebar .nav-link i { margin-right: 10px; }

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
      box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    }

    .report-card {
      background-color: #fff;
      border: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      text-align: left;
      padding: 20px;
      border-radius: 8px;
      transition: 0.3s;
      cursor: pointer;
    }

    .report-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .report-card h6 {
      font-weight: 600;
      color: #0d47a1;
    }

    .report-card p {
      color: #212529;
      font-size: 0.9rem;
    }

    .filter-group {
      margin-bottom: 20px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

  </style>
</head>

<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 px-0 sidebar">
      <div class="text-center py-4">
        <h5 class="text-white mb-1">WeBuild</h5>
        <small class="text-white-50">Top Management</small>
      </div>
      <nav class="nav flex-column">
        <a class="nav-link" href="<?= base_url('management/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="nav-link active" href="<?= base_url('management/financial-reports') ?>"><i class="bi bi-graph-up"></i> Financial Reports</a>
        <a class="nav-link" href="<?= base_url('management/inventory-overview') ?>"><i class="bi bi-boxes"></i> Inventory Overview</a>
        <a class="nav-link" href="<?= base_url('management/warehouse-analytics') ?>"><i class="bi bi-bar-chart"></i> Warehouse Analytics</a>
        <a class="nav-link" href="<?= base_url('management/forecasting') ?>"><i class="bi bi-graph-up-arrow"></i> Forecasting</a>
        <a class="nav-link" href="<?= base_url('management/performance-kpis') ?>"><i class="bi bi-speedometer"></i> Performance KPIs</a>
        <a class="nav-link" href="<?= base_url('management/executive-reports') ?>"><i class="bi bi-file-earmark-text"></i> Executive Reports</a>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-0">
      <!-- Top Bar -->
      <div class="topbar">
        <input type="text" class="form-control w-25" placeholder="Search">
        <div>
          <span class="me-3"><?= date('M d, Y | h:i A') ?> | <?= esc($user['role']) ?> | <strong><?= esc($user['name']) ?></strong></span>
          <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-sm">Logout</a>
        </div>
      </div>

      <!-- Page Container -->
      <div class="page-container">
        <h5><strong>Financial Reports</strong></h5>
        <p class="text-muted mb-4">Comprehensive financial analysis and reporting for WeBuild operations</p>

        <!-- Date Range Filter -->
        <div class="filter-group mb-4">
          <form method="get" action="<?= base_url('management/financial-reports') ?>" class="d-flex gap-2 align-items-center">
            <label class="me-2">Date Range:</label>
            <input type="date" name="start_date" class="form-control form-control-sm" value="<?= $startDate ?>" style="width: 180px;">
            <span>to</span>
            <input type="date" name="end_date" class="form-control form-control-sm" value="<?= $endDate ?>" style="width: 180px;">
            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
            <a href="<?= base_url('management/financial-reports') ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
          </form>
        </div>

        <!-- Financial Summary Cards -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="card border-primary">
              <div class="card-body">
                <h6 class="text-muted">Inventory Value</h6>
                <h4 class="text-primary">₱<?= number_format($summary['inventory_value'], 2) ?></h4>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card border-success">
              <div class="card-body">
                <h6 class="text-muted">Accounts Receivable</h6>
                <h4 class="text-success">₱<?= number_format($summary['receivables_outstanding'], 2) ?></h4>
                <small class="text-muted"><?= number_format($summary['collection_rate'], 1) ?>% collected</small>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card border-danger">
              <div class="card-body">
                <h6 class="text-muted">Accounts Payable</h6>
                <h4 class="text-danger">₱<?= number_format($summary['payables_outstanding'], 2) ?></h4>
                <small class="text-muted"><?= number_format($summary['payment_rate'], 1) ?>% paid</small>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card border-info">
              <div class="card-body">
                <h6 class="text-muted">Net Position</h6>
                <h4 class="<?= $summary['net_position'] >= 0 ? 'text-success' : 'text-danger' ?>">
                  ₱<?= number_format(abs($summary['net_position']), 2) ?>
                  <?= $summary['net_position'] >= 0 ? '▲' : '▼' ?>
                </h4>
              </div>
            </div>
          </div>
        </div>

        <!-- Detailed Breakdown -->
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Receivables Summary</h6>
              </div>
              <div class="card-body">
                <table class="table table-sm">
                  <tr>
                    <td>Total Invoices</td>
                    <td class="text-end"><strong><?= $summary['ar_count'] ?></strong></td>
                  </tr>
                  <tr>
                    <td>Total Amount</td>
                    <td class="text-end">₱<?= number_format($summary['total_receivables'], 2) ?></td>
                  </tr>
                  <tr>
                    <td>Amount Collected</td>
                    <td class="text-end text-success">₱<?= number_format($summary['receivables_collected'], 2) ?></td>
                  </tr>
                  <tr>
                    <td>Outstanding</td>
                    <td class="text-end text-warning">₱<?= number_format($summary['receivables_outstanding'], 2) ?></td>
                  </tr>
                </table>
                <a href="<?= base_url('print/ar-invoices') ?>" class="btn btn-sm btn-outline-primary w-100" target="_blank">
                  📄 Export AR Report
                </a>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-danger text-white">
                <h6 class="mb-0">Payables Summary</h6>
              </div>
              <div class="card-body">
                <table class="table table-sm">
                  <tr>
                    <td>Total Invoices</td>
                    <td class="text-end"><strong><?= $summary['ap_count'] ?></strong></td>
                  </tr>
                  <tr>
                    <td>Total Amount</td>
                    <td class="text-end">₱<?= number_format($summary['total_payables'], 2) ?></td>
                  </tr>
                  <tr>
                    <td>Amount Paid</td>
                    <td class="text-end text-success">₱<?= number_format($summary['payables_paid'], 2) ?></td>
                  </tr>
                  <tr>
                    <td>Outstanding</td>
                    <td class="text-end text-danger">₱<?= number_format($summary['payables_outstanding'], 2) ?></td>
                  </tr>
                </table>
                <a href="<?= base_url('print/ap-invoices') ?>" class="btn btn-sm btn-outline-danger w-100" target="_blank">
                  📄 Export AP Report
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Stock Movement Value -->
        <div class="card mb-4">
          <div class="card-header bg-info text-white">
            <h6 class="mb-0">Inventory Movement Value (<?= date('M d', strtotime($startDate)) ?> - <?= date('M d, Y', strtotime($endDate)) ?>)</h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6 class="text-success">Stock In Value</h6>
                <h3>₱<?= number_format($summary['stock_in_value'], 2) ?></h3>
              </div>
              <div class="col-md-6">
                <h6 class="text-danger">Stock Out Value</h6>
                <h3>₱<?= number_format($summary['stock_out_value'], 2) ?></h3>
              </div>
            </div>
          </div>
        </div>

        <!-- Monthly Trends Chart -->
        <div class="card">
          <div class="card-header bg-dark text-white">
            <h6 class="mb-0">6-Month Financial Trends</h6>
          </div>
          <div class="card-body">
            <canvas id="financialTrendsChart" height="80"></canvas>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Financial Trends Chart
const trendsData = <?= json_encode($monthlyTrends) ?>;

const ctx = document.getElementById('financialTrendsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: trendsData.map(t => t.month),
        datasets: [
            {
                label: 'Receivables',
                data: trendsData.map(t => t.receivables),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            },
            {
                label: 'Payables',
                data: trendsData.map(t => t.payables),
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.4
            },
            {
                label: 'Net Position',
                data: trendsData.map(t => t.net_position),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
</body>
</html>
