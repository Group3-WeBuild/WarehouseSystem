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
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 220px;
            height: 100vh;
            background: linear-gradient(180deg, #17a2b8 0%, #138496 100%);
            overflow-y: auto;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }
        .sidebar .nav-link i { margin-right: 10px; }
        .main-wrapper {
            margin-left: 220px;
            min-height: 100vh;
            background: #f8f9fa;
        }
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
        #reader { width: 100%; }
        .scan-result {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
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
        .barcode-input {
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            text-align: center;
            letter-spacing: 3px;
        }
        .scan-history { max-height: 300px; overflow-y: auto; }
        .history-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.2s;
        }
        .history-item:hover { background: #f8f9fa; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="text-center py-4">
            <h5 class="text-white mb-1">WeBuild</h5>
            <small class="text-white-50">Warehouse Staff</small>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link" href="<?= base_url('warehouse-staff/dashboard') ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a class="nav-link" href="<?= base_url('warehouse-staff/inventory') ?>">
                <i class="bi bi-box-seam"></i> View Inventory
            </a>
            <a class="nav-link active" href="<?= base_url('warehouse-staff/barcode-scanner') ?>">
                <i class="bi bi-qr-code-scan"></i> Barcode Scanner
            </a>
            <a class="nav-link" href="<?= base_url('warehouse-staff/stock-movements') ?>">
                <i class="bi bi-arrow-left-right"></i> Stock Movements
            </a>
            <a class="nav-link" href="<?= base_url('warehouse-staff/orders') ?>">
                <i class="bi bi-cart"></i> Orders
            </a>
            <hr class="mx-3 my-2" style="border-color: rgba(255,255,255,0.2);">
            <a class="nav-link text-danger" href="<?= base_url('logout') ?>">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-wrapper">
        <header class="topbar d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="bi bi-qr-code-scan text-info"></i> Barcode/QR Code Scanner</h5>
                <small class="text-muted">Scan items for quick lookup</small>
            </div>
            <div>
                <span class="badge bg-success me-2" id="scannerStatus">
                    <i class="bi bi-circle-fill"></i> Scanner Ready
                </span>
                <span class="text-muted">Welcome, <strong><?= esc($user['name'] ?? 'User') ?></strong></span>
            </div>
        </header>

        <section class="p-4">
            <div class="row">
                <!-- Scanner Section -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-camera-video"></i> Camera Scanner</span>
                            <div>
                                <button class="btn btn-sm btn-info" id="startScanner">
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
                                <button class="btn btn-info" onclick="searchBarcode()">
                                    <i class="bi bi-search"></i> Search
                                </button>
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
        </section>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let html5QrCode = null;
let scanHistory = JSON.parse(localStorage.getItem('staffScanHistory') || '[]');

document.addEventListener('DOMContentLoaded', function() {
    renderHistory();
    
    document.getElementById('manualBarcode').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchBarcode();
        }
    });
});

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
    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleRQVSaHZ2K5lIw0/osfe2bJoJA0+o8fi2rRpJQ0+psjk3LZqJg0/p8vn3rhsJw0/qc7p4bptKA4/qtDr47xvKQ4/rNPt5L5wKg4/rdXv5sBxKw4/r9nw58JzLA4/sdvy6MR0LQ4/s97068Z2Lg4/tOD17Mh3Lw4/tuL27sp5MA4/uOX47sx6MQ4/uuf578x7Mg4/u+n68c58Mw4/vez789B9NA4/vvD79NF+NQ4/wPP99dN/Ng4/wfb/9tSANw4/w/kA99WANw4/xPsB+NaB');
    audio.play();
    lookupBarcode(decodedText);
}

function onScanError(errorMessage) {}

function searchBarcode() {
    const barcode = document.getElementById('manualBarcode').value.trim();
    if (barcode) lookupBarcode(barcode);
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
    
    document.getElementById('manualBarcode').value = '';
}

function showNotFound(barcode) {
    document.getElementById('resultCard').style.display = 'none';
    document.getElementById('noResultCard').style.display = 'block';
    document.getElementById('noResultCard').querySelector('.card-body').innerHTML = `
        <i class="bi bi-x-circle display-1 text-danger"></i>
        <h5 class="mt-3 text-danger">Item Not Found</h5>
        <p class="text-muted">No item found for barcode: <code>${barcode}</code></p>
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
    
    localStorage.setItem('staffScanHistory', JSON.stringify(scanHistory));
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
    localStorage.removeItem('staffScanHistory');
    renderHistory();
}
</script>
</body>
</html>
