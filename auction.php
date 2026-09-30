<?php
require_once '../includes/auth.php';
requireRole('BUYER');

// In reality, this data comes from joining the `auctions` and `produce_listings` tables
$auction_id = $_GET['id'] ?? '104';
$current_bid = 34.50; // Mock current highest bid
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Auction - AGRI-PACT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Manrope:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; overflow-x: hidden; }
        .card { border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
        .bg-dark-accent { background: #0f172a; color: white; }
        
        /* Pulse Animation for Live Bid */
        .live-pulse {
            display: inline-block;
            width: 10px; height: 10px;
            background: #ef4444; border-radius: 50%;
            margin-right: 8px;
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        .bid-btn { background: #10b981; color: white; font-weight: bold; border-radius: 12px; transition: transform 0.2s; }
        .bid-btn:hover { background: #059669; transform: translateY(-2px); }
        .bid-btn:active { transform: translateY(0); }
        
        .timeline-scroll {
            max-height: 250px; overflow-y: auto; scrollbar-width: thin;
        }
        .bid-row { padding: 10px 0; border-bottom: 1px solid #f1f5f9; animation: flashGreen 1s; }
        @keyframes flashGreen { from { background: #ecfdf5; } to { background: transparent; } }
        
        .stat-label { font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .price-text { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold text-success" href="#">AGRI-PACT <span class="badge bg-danger fs-6 ms-2"><i class="fas fa-gavel me-1"></i> AUCTION HUB</span></a>
        <div><a href="dashboard.php" class="btn btn-outline-dark btn-sm rounded-pill fw-bold">Back to Demands</a></div>
    </div>
</nav>

<div class="container py-4">
    <div class="row g-4">
        
        <!-- Left Column: Lot Details -->
        <div class="col-md-5">
            <div class="card h-100">
                <img src="https://images.unsplash.com/photo-1595856724083-d34e9bdcd2a4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     class="card-img-top" alt="Tomatoes" style="height: 220px; object-fit: cover; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary fs-6">#AGR-TN-2026-000184</span>
                        <span class="text-success fw-bold"><i class="fas fa-check-circle"></i> AI Verified</span>
                    </div>
                    
                    <h3 class="fw-bold mb-1">Hybrid Tomato (Grade A)</h3>
                    <p class="text-muted fw-bold mb-4">Madurai Farmers Cooperative</p>
                    
                    <div class="row text-center bg-light rounded-4 p-3 mb-4">
                        <div class="col-4 border-end">
                            <div class="stat-label">Quantity</div>
                            <div class="fw-bold fs-5">1,200 kg</div>
                        </div>
                        <div class="col-4 border-end">
                            <div class="stat-label">Min Increment</div>
                            <div class="fw-bold fs-5 text-warning">₹ 0.50</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-label">Logistics</div>
                            <div class="fw-bold fs-5 text-info">Active</div>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold text-muted"><i class="fas fa-brain me-2 text-primary"></i> Trade Intelligence</h6>
                    <div class="alert bg-primary-subtle border-0 mb-0">
                        <strong>AI Estimate:</strong> ₹32.00 - ₹36.00 / kg<br>
                        <small class="text-muted border-top border-primary mt-2 pt-1 d-block">Indication only. Trade strictly based on live bids.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Live Bidding Engine -->
        <div class="col-md-7">
            
            <!-- Live Status & Price -->
            <div class="card bg-dark-accent mb-4 position-relative overflow-hidden">
                <!-- Cool background watermark -->
                <i class="fas fa-gavel fa-10x position-absolute" style="opacity: 0.03; right: -20px; bottom: -40px;"></i>
                
                <div class="card-body p-5 text-center">
                    <div class="mb-3 d-inline-block px-4 py-2 rounded-pill" style="background: rgba(255,255,255,0.1);">
                        <span class="live-pulse"></span> <span class="fw-bold text-white tracking-widest text-uppercase">Live Auction</span>
                        <span class="ms-3 ps-3 border-start border-white border-opacity-25" id="countdown">Closes in: 04:22</span>
                    </div>
                    
                    <div class="stat-label text-light opacity-75 mb-2">CURRENT HIGHEST BID</div>
                    <div class="display-1 fw-bold text-success price-text mb-2" style="text-shadow: 0 0 20px rgba(16, 185, 129, 0.4);" id="masterBid">₹ 34.50</div>
                    <div class="text-white fw-bold"><i class="fas fa-trophy text-warning me-2"></i> Buyer_412 (Navi Mumbai)</div>
                </div>
            </div>

            <!-- Bidding Actions -->
            <div class="card mb-4 border-2 border-success">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-2">Place Your Bid</h5>
                    <p class="text-muted small mb-4">By bidding, you enter a legally binding contract intention if declared the winner.</p>
                    
                    <div class="d-flex justify-content-center align-items-center mb-4">
                        <button class="btn btn-light shadow-sm fs-4 px-4 fw-bold text-danger border" onclick="adjBid(-0.50)">-</button>
                        <div class="mx-3 input-group w-50">
                            <span class="input-group-text bg-white fw-bold">₹</span>
                            <input type="number" id="bidInput" class="form-control form-control-lg text-center fw-bold fs-4" value="35.00" step="0.50">
                        </div>
                        <button class="btn btn-light shadow-sm fs-4 px-4 fw-bold text-success border" onclick="adjBid(0.50)">+</button>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <button class="btn bid-btn btn-lg w-100" onclick="submitBid()"><i class="fas fa-check-circle me-2"></i> Submit Binding Bid</button>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-outline-primary btn-lg w-100 fw-bold"><i class="fas fa-robot me-1"></i> Auto-Bid</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bid Event Logs -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Live Bid Activity</h6>
                        <span class="badge bg-light text-dark shadow-sm">Secure Audit Log Active</span>
                    </div>
                    
                    <div class="timeline-scroll pe-2" id="bidLog">
                        <!-- Initial items -->
                        <div class="d-flex justify-content-between bid-row text-muted">
                            <div><i class="fas fa-user-circle me-2"></i>Buyer_412</div>
                            <div class="fw-bold text-success">₹ 34.50</div>
                        </div>
                        <div class="d-flex justify-content-between bid-row text-muted opacity-75">
                            <div><i class="fas fa-user-circle me-2"></i>Buyer_88</div>
                            <div class="fw-bold">₹ 34.00</div>
                        </div>
                        <div class="d-flex justify-content-between bid-row text-muted opacity-75">
                            <div><i class="fas fa-user-circle me-2"></i>Buyer_412</div>
                            <div class="fw-bold">₹ 33.50</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Live UI Interactivity Mock
    const bidInput = document.getElementById('bidInput');
    const masterBid = document.getElementById('masterBid');
    const bidLog = document.getElementById('bidLog');
    let currentHigh = 34.50;

    function adjBid(val) {
        let n = parseFloat(bidInput.value) + val;
        if(n > currentHigh) { bidInput.value = n.toFixed(2); }
    }

    function submitBid() {
        let proposed = parseFloat(bidInput.value);
        if(proposed <= currentHigh) {
            alert('Your bid must be higher than the current bid of ₹' + currentHigh.toFixed(2));
            return;
        }

        // Mock AJAX success & UI update
        currentHigh = proposed;
        masterBid.innerText = `₹ ${currentHigh.toFixed(2)}`;
        
        let newEntry = document.createElement('div');
        newEntry.className = "d-flex justify-content-between bid-row text-dark";
        newEntry.innerHTML = `<div><i class="fas fa-user-circle me-2 text-primary"></i>You (Fresh Foods Pvt Ltd)</div><div class="fw-bold text-success">₹ ${currentHigh.toFixed(2)}</div>`;
        
        bidLog.prepend(newEntry);
        
        // Auto increment for next potential bid
        bidInput.value = (currentHigh + 0.50).toFixed(2);
    }
</script>
</body>
</html>
