<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode/QR Scanner | WeBuild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
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
        .main-content { background: #f8f9fa; min-height: 100vh; }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 25px;
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 10px;
        }
        .scanner-container {
            background: #000;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            min-height: 300px;
        }
        #reader {
            width: 100%;
        }
        .scan-result {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
        }
        .item-card {
            border-left: 4px solid #28a745;
            background: white;
            padding: 20px;
            border-radius: 0 10px 10px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .quick-action-btn {
            padding: 15px 25px;
            font-size: 1.1rem;
            border-radius: 10px;
            transition: transform 0.2s;
        }
        .quick-action-btn:hover {
            transform: translateY(-3px);
        }
        .barcode-input {
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            text-align: center;
            letter-spacing: 3px;
        }
        .scan-history {
            max-height: 300px;
            overflow-y: auto;
        }
        .history-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.2s;
        }
        .history-item:hover {
            background: #f8f9fa;
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
                <small class="text-white-50">Warehouse Manager</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= base_url('warehouse-manager/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/inventory') ?>">
                    <i class="bi bi-box-seam"></i> Inventory
                </a>
                <a class="nav-link active" href="<?= base_url('warehouse-manager/barcode-scanner') ?>">
                    <i class="bi bi-qr-code-scan"></i> Barcode Scanner
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/stock-movements') ?>">
                    <i class="bi bi-arrow-left-right"></i> Stock Movements
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/orders') ?>">
                    <i class="bi bi-cart"></i> Orders
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/batch-tracking') ?>">
                    <i class="bi bi-upc-scan"></i> Batch Tracking
                </a>
                <a class="nav-link" href="<?= base_url('warehouse-manager/reports') ?>">
                    <i class="bi bi-file-earmark-bar-graph"></i> Reports
                </a>
                <hr class="mx-3 my-2" style="border-color: rgba(255,255,255,0.2);">
                <a class="nav-link text-danger" href="<?= base_url('logout') ?>">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 px-0 main-content">
            <div class="topbar d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="bi bi-qr-code-scan text-primary"></i> Barcode/QR Code Scanner</h5>
                    <small class="text-muted">Scan items for quick lookup, stock in/out operations</small>
                </div>
                <div>
                    <span class="badge bg-success me-2" id="scannerStatus">
                        <i class="bi bi-circle-fill"></i> Scanner Ready
                    </span>
                </div>
            </div>

            <div class="p-4">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Scanner Section -->
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-camera-video"></i> Camera Scanner</span>
                                <div>
                                    <button class="btn btn-sm btn-primary" id="startScanner">
                                        <i class="bi bi-play-fill"></i> Start
                                    </button>
                                    <button class="btn btn-sm btn-danger" id="stopScanner" style="display:none;">
                                        <i class="bi bi-stop-fill"></i> Stop
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="scanner-container mb-3">
                                    <div id="reader"></div>
                                    <div id="scannerPlaceholder" class="d-flex flex-column align-items-center justify-content-center h-100 text-white py-5">
                                        <i class="bi bi-qr-code-scan display-1 mb-3"></i>
                                        <p class="mb-0">Click "Start" to enable camera scanner</p>
                                        <small class="text-muted">or use manual entry below</small>
                                    </div>
                                </div>
                                
                                <!-- Manual Entry -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="bi bi-upc"></i></span>
                                    <input type="text" class="form-control barcode-input" id="manualBarcode" 
                                           placeholder="Enter barcode/SKU manually" autofocus>
                                    <button class="btn btn-primary" onclick="searchBarcode()">
                                        <i class="bi bi-search"></i> Search
                                    </button>
                                </div>

                                <!-- Scan Mode Selection -->
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="scanMode" id="modeLookup" value="lookup" checked>
                                    <label class="btn btn-outline-primary" for="modeLookup">
                                        <i class="bi bi-search"></i> Lookup Only
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="scanMode" id="modeStockIn" value="stock_in">
                                    <label class="btn btn-outline-success" for="modeStockIn">
                                        <i class="bi bi-box-arrow-in-down"></i> Stock In
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="scanMode" id="modeStockOut" value="stock_out">
                                    <label class="btn btn-outline-danger" for="modeStockOut">
                                        <i class="bi bi-box-arrow-up"></i> Stock Out
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="card mt-4">
                            <div class="card-header"><i class="bi bi-lightning-charge"></i> Quick Actions</div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <button class="btn btn-success w-100 quick-action-btn" data-bs-toggle="modal" data-bs-target="#stockInModal">
                                            <i class="bi bi-box-arrow-in-down"></i><br>Stock In
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-danger w-100 quick-action-btn" data-bs-toggle="modal" data-bs-target="#stockOutModal">
                                            <i class="bi bi-box-arrow-up"></i><br>Stock Out
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-info w-100 quick-action-btn" data-bs-toggle="modal" data-bs-target="#generateBarcodeModal">
                                            <i class="bi bi-upc"></i><br>Generate Barcode
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <a href="<?= base_url('warehouse-manager/batch-tracking') ?>" class="btn btn-warning w-100 quick-action-btn">
                                            <i class="bi bi-collection"></i><br>Batch Tracking
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results Section -->
                    <div class="col-lg-6 mb-4">
                        <!-- Scanned Item Result -->
                        <div class="card" id="resultCard" style="display: none;">
                            <div class="card-header scan-result">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="bi bi-check-circle"></i> Item Found</h5>
                                    <span class="badge bg-light text-dark" id="resultSku">-</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="item-card mb-3">
                                    <h4 id="resultName">-</h4>
                                    <p class="text-muted mb-2" id="resultCategory">-</p>
                                    
                                    <div class="row text-center mt-3">
                                        <div class="col-4">
                                            <h3 class="text-primary mb-0" id="resultQuantity">-</h3>
                                            <small class="text-muted">In Stock</small>
                                        </div>
                                        <div class="col-4">
                                            <h3 class="text-success mb-0" id="resultPrice">-</h3>
                                            <small class="text-muted">Unit Price</small>
                                        </div>
                                        <div class="col-4">
                                            <h3 class="text-warning mb-0" id="resultReorder">-</h3>
                                            <small class="text-muted">Reorder Level</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong><i class="bi bi-geo-alt"></i> Location:</strong>
                                        <p id="resultLocation" class="mb-0">-</p>
                                    </div>
                                    <div class="col-6">
                                        <strong><i class="bi bi-building"></i> Warehouse:</strong>
                                        <p id="resultWarehouse" class="mb-0">-</p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2" id="itemActions">
                                    <button class="btn btn-success" onclick="quickStockIn()">
                                        <i class="bi bi-plus-circle"></i> Add Stock
                                    </button>
                                    <button class="btn btn-danger" onclick="quickStockOut()">
                                        <i class="bi bi-dash-circle"></i> Remove Stock
                                    </button>
                                    <a href="#" id="viewItemLink" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i> View Full Details
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- No Result / Placeholder -->
                        <div class="card" id="noResultCard">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-upc-scan display-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">No Item Scanned</h5>
                                <p class="text-muted">Scan a barcode or enter it manually to see item details</p>
                            </div>
                        </div>

                        <!-- Scan History -->
                        <div class="card mt-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-clock-history"></i> Recent Scans</span>
                                <button class="btn btn-sm btn-outline-secondary" onclick="clearHistory()">Clear</button>
                            </div>
                            <div class="card-body scan-history" id="scanHistory">
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-inbox"></i> No recent scans
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock In Modal -->
<div class="modal fade" id="stockInModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-box-arrow-in-down"></i> Stock In</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockInForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Quantity to Add</label>
                        <input type="number" class="form-control form-control-lg" name="quantity" id="stockInQuantity" min="1" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference Number</label>
                        <input type="text" class="form-control" name="reference" placeholder="PO number, delivery note, etc.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Optional notes"></textarea>
                    </div>
                    <input type="hidden" name="item_id" id="stockInItemId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check"></i> Confirm Stock In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Stock Out Modal -->
<div class="modal fade" id="stockOutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-box-arrow-up"></i> Stock Out</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockOutForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Quantity to Remove</label>
                        <input type="number" class="form-control form-control-lg" name="quantity" id="stockOutQuantity" min="1" value="1" required>
                        <small class="text-muted">Available: <span id="availableStock">-</span></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference Number</label>
                        <input type="text" class="form-control" name="reference" placeholder="Order number, requisition, etc.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <select class="form-select" name="reason" required>
                            <option value="Sale">Sale/Order</option>
                            <option value="Transfer">Transfer to another location</option>
                            <option value="Damage">Damaged goods</option>
                            <option value="Return">Return to supplier</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Optional notes"></textarea>
                    </div>
                    <input type="hidden" name="item_id" id="stockOutItemId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-check"></i> Confirm Stock Out</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Barcode Modal -->
<div class="modal fade" id="generateBarcodeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-upc"></i> Generate Barcode</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="generateBarcodeForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Item</label>
                        <select class="form-select" name="item_id" id="generateItemId" required>
                            <option value="">-- Select Item --</option>
                            <?php foreach ($inventory ?? [] as $item): ?>
                            <option value="<?= $item['id'] ?>"><?= esc($item['product_name'] ?? $item['name'] ?? 'Unknown') ?> (<?= $item['sku'] ?? '' ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Barcode Type</label>
                        <select class="form-select" name="barcode_type">
                            <option value="EAN13">EAN-13 (Standard)</option>
                            <option value="CODE128">Code 128</option>
                            <option value="QR">QR Code</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity to Print</label>
                        <input type="number" class="form-control" name="print_quantity" min="1" max="100" value="1">
                    </div>
                    <div id="barcodePreview" class="text-center border rounded p-3" style="display:none;">
                        <img id="barcodeImage" src="" alt="Barcode Preview" class="img-fluid">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-outline-info" onclick="previewBarcode()"><i class="bi bi-eye"></i> Preview</button>
                    <button type="submit" class="btn btn-info"><i class="bi bi-printer"></i> Generate & Print</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let html5QrCode = null;
let currentItem = null;
let scanHistory = JSON.parse(localStorage.getItem('scanHistory') || '[]');

document.addEventListener('DOMContentLoaded', function() {
    renderHistory();
    
    // Enter key on manual input
    document.getElementById('manualBarcode').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchBarcode();
        }
    });
});

// Start Camera Scanner
document.getElementById('startScanner').addEventListener('click', function() {
    document.getElementById('scannerPlaceholder').style.display = 'none';
    document.getElementById('startScanner').style.display = 'none';
    document.getElementById('stopScanner').style.display = 'inline-block';
    
    html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        onScanSuccess,
        onScanError
    ).catch(err => {
        console.error("Scanner error:", err);
        alert("Could not start camera. Please check permissions or use manual entry.");
        stopScanner();
    });
});

// Stop Camera Scanner
document.getElementById('stopScanner').addEventListener('click', stopScanner);

function stopScanner() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            document.getElementById('scannerPlaceholder').style.display = 'flex';
            document.getElementById('startScanner').style.display = 'inline-block';
            document.getElementById('stopScanner').style.display = 'none';
        });
    }
}

function onScanSuccess(decodedText, decodedResult) {
    // Beep sound
    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleRQVSaHZ2K5lIw0/osfe2bJoJA0+o8fi2rRpJQ0+psjk3LZqJg0/p8vn3rhsJw0/qc7p4bptKA4/qtDr47xvKQ4/rNPt5L5wKg4/rdXv5sBxKw4/r9nw58JzLA4/sdvy6MR0LQ4/s97068Z2Lg4/tOD17Mh3Lw4/tuL27sp5MA4/uOX47sx6MQ4/uuf578x7Mg4/u+n68c58Mw4/vez789B9NA4/vvD79NF+NQ4/wPP99dN/Ng4/wfb/9tSANw4/w/kA99WANw4/xPsB+NaB');
    audio.play();
    
    lookupBarcode(decodedText);
}

function onScanError(errorMessage) {
    // Ignore errors during scanning
}

function searchBarcode() {
    const barcode = document.getElementById('manualBarcode').value.trim();
    if (barcode) {
        lookupBarcode(barcode);
    }
}

function lookupBarcode(barcode) {
    document.getElementById('scannerStatus').innerHTML = '<i class="bi bi-hourglass-split"></i> Searching...';
    document.getElementById('scannerStatus').className = 'badge bg-warning';
    
    fetch('<?= base_url('barcode/scan') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'barcode=' + encodeURIComponent(barcode)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            showResult(data.data);
            addToHistory(data.data);
        } else {
            showNotFound(barcode);
        }
        document.getElementById('scannerStatus').innerHTML = '<i class="bi bi-circle-fill"></i> Scanner Ready';
        document.getElementById('scannerStatus').className = 'badge bg-success';
    })
    .catch(error => {
        console.error('Error:', error);
        showNotFound(barcode);
        document.getElementById('scannerStatus').innerHTML = '<i class="bi bi-circle-fill"></i> Scanner Ready';
        document.getElementById('scannerStatus').className = 'badge bg-success';
    });
}

function showResult(item) {
    currentItem = item;
    
    document.getElementById('resultCard').style.display = 'block';
    document.getElementById('noResultCard').style.display = 'none';
    
    document.getElementById('resultSku').textContent = item.sku || '-';
    document.getElementById('resultName').textContent = item.product_name || item.name || 'Unknown';
    document.getElementById('resultCategory').textContent = item.category || '-';
    document.getElementById('resultQuantity').textContent = item.quantity || 0;
    document.getElementById('resultPrice').textContent = '₱' + parseFloat(item.unit_price || 0).toLocaleString();
    document.getElementById('resultReorder').textContent = item.reorder_level || '-';
    document.getElementById('resultLocation').textContent = item.location || '-';
    document.getElementById('resultWarehouse').textContent = item.warehouse_name || 'Main Warehouse';
    document.getElementById('viewItemLink').href = '<?= base_url('warehouse-manager/inventory/view/') ?>' + item.id;
    
    document.getElementById('stockInItemId').value = item.id;
    document.getElementById('stockOutItemId').value = item.id;
    document.getElementById('availableStock').textContent = item.quantity || 0;
    
    // Clear manual input
    document.getElementById('manualBarcode').value = '';
}

function showNotFound(barcode) {
    document.getElementById('resultCard').style.display = 'none';
    document.getElementById('noResultCard').style.display = 'block';
    document.getElementById('noResultCard').querySelector('.card-body').innerHTML = `
        <i class="bi bi-x-circle display-1 text-danger"></i>
        <h5 class="mt-3 text-danger">Item Not Found</h5>
        <p class="text-muted">No item found for barcode: <code>${barcode}</code></p>
        <a href="<?= base_url('warehouse-manager/inventory/add') ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add New Item
        </a>
    `;
}

function addToHistory(item) {
    const entry = {
        barcode: item.barcode_number || item.sku,
        name: item.product_name || item.name,
        time: new Date().toLocaleTimeString(),
        id: item.id
    };
    
    scanHistory.unshift(entry);
    if (scanHistory.length > 10) scanHistory.pop();
    
    localStorage.setItem('scanHistory', JSON.stringify(scanHistory));
    renderHistory();
}

function renderHistory() {
    const container = document.getElementById('scanHistory');
    
    if (scanHistory.length === 0) {
        container.innerHTML = '<div class="text-center text-muted py-3"><i class="bi bi-inbox"></i> No recent scans</div>';
        return;
    }
    
    container.innerHTML = scanHistory.map(entry => `
        <div class="history-item d-flex justify-content-between align-items-center" onclick="lookupBarcode('${entry.barcode}')">
            <div>
                <strong>${entry.name}</strong><br>
                <small class="text-muted">${entry.barcode}</small>
            </div>
            <small class="text-muted">${entry.time}</small>
        </div>
    `).join('');
}

function clearHistory() {
    scanHistory = [];
    localStorage.removeItem('scanHistory');
    renderHistory();
}

function quickStockIn() {
    if (currentItem) {
        new bootstrap.Modal(document.getElementById('stockInModal')).show();
    }
}

function quickStockOut() {
    if (currentItem) {
        new bootstrap.Modal(document.getElementById('stockOutModal')).show();
    }
}

// Stock In Form Submit
document.getElementById('stockInForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('<?= base_url('barcode/stock-in-scan') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Stock added successfully!');
            bootstrap.Modal.getInstance(document.getElementById('stockInModal')).hide();
            if (currentItem) lookupBarcode(currentItem.sku);
        } else {
            alert(data.message || 'Error adding stock');
        }
    });
});

// Stock Out Form Submit
document.getElementById('stockOutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('<?= base_url('barcode/stock-out-scan') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Stock removed successfully!');
            bootstrap.Modal.getInstance(document.getElementById('stockOutModal')).hide();
            if (currentItem) lookupBarcode(currentItem.sku);
        } else {
            alert(data.message || 'Error removing stock');
        }
    });
});

function previewBarcode() {
    // This would typically call an API to generate a preview
    const itemId = document.getElementById('generateItemId').value;
    if (itemId) {
        document.getElementById('barcodePreview').style.display = 'block';
        document.getElementById('barcodeImage').src = '<?= base_url('barcode/generate-image/') ?>' + itemId;
    }
}
</script>
</body>
</html>
