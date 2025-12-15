<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-file-earmark-text"></i> Warehouse Reports</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('warehouse-manager/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-file-earmark-bar-graph fs-1 text-primary"></i>
                        <h5 class="mt-3">Inventory Report</h5>
                        <p class="text-muted">Current stock levels and values</p>
                        <button class="btn btn-primary">Generate</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-arrow-left-right fs-1 text-success"></i>
                        <h5 class="mt-3">Movement Report</h5>
                        <p class="text-muted">Stock in/out transactions</p>
                        <button class="btn btn-success">Generate</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-cart fs-1 text-warning"></i>
                        <h5 class="mt-3">Order Report</h5>
                        <p class="text-muted">Order fulfillment status</p>
                        <button class="btn btn-warning">Generate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
