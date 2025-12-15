<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Movements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-arrow-left-right"></i> Stock Movements</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('warehouse-manager/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Stock Movements</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button class="btn btn-success me-2"><i class="bi bi-arrow-down-circle"></i> Stock In</button>
                <button class="btn btn-danger"><i class="bi bi-arrow-up-circle"></i> Stock Out</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="text-center text-muted py-5">Stock movements tracking page</p>
            </div>
        </div>
    </div>
</body>
</html>
