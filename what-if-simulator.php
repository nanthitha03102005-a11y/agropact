<?php
require_once '../includes/auth.php';
requireRole('FARMER');

// Base logic for What-If scenario is done in JS dynamically.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WHAT-IF? Simulator - AGRI-PACT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Noto+Sans+Tamil:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .card { border-radius: 16px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 30px 20px;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }
        .bottom-nav { position: fixed; bottom: 0; width: 100%; background: white; box-shadow: 0 -2px 10px rgba(0,0,0,0.05); z-index: 100; }
        .bottom-nav a { text-align: center; flex: 1; padding: 12px; color: #6c757d; text-decoration: none; display: flex; flex-direction: column; align-items: center; }
        .bottom-nav a.active { color: #10b981; }

        .slider-lime { accent-color: #84cc16; }
        .val-badge { background: #ecfdf5; color: #064e3b; font-weight: bold; padding: 4px 10px; border-radius: 8px; border: 1px solid #10b981; }
        .progress-bar-lime { background-color: #84cc16; }
    </style>
</head>
<body class="pb-5 mb-5">

<div class="header-bg shadow-sm">
    <a href="dashboard.php" class="text-white text-decoration-none mb-2 d-block"><i class="fas fa-arrow-left me-2"></i> Dashboard</a>
    <h4 class="fw-bold mb-1"><i class="fas fa-calculator me-2 text-warning w-auto"></i> "WHAT-IF?" Simulator</h4>
    <p class="mb-0 text-light opacity-75 small">Estimate your net value by simulating different pricing and logistics strategies before locking a contract.</p>
</div>

<div class="container mt-4">
    <!-- Simulator Controls -->
    <div class="card mb-4 border-start border-4 border-warning">
        <div class="card-body">
            <h6 class="fw-bold mb-4 text-warning-emphasis">Adjust Scenario Parameters</h6>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <label class="form-label fw-semibold small mb-0">Expected Bid Price (₹ / kg)</label>
                    <span class="val-badge" id="valPrice">₹ 35</span>
                </div>
                <input type="range" class="form-range slider-lime" id="simPrice" min="20" max="60" value="35">
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <label class="form-label fw-semibold small mb-0">Quantity Available (kg)</label>
                    <span class="val-badge" id="valQty">1000 kg</span>
                </div>
                <input type="range" class="form-range slider-lime" id="simQty" min="100" max="5000" step="100" value="1000">
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <label class="form-label fw-semibold small mb-0">Transport Route Cost (₹ / kg)</label>
                    <span class="val-badge text-danger" id="valTransport" style="border-color: #f87171; background: #fef2f2;">₹ 2.50</span>
                </div>
                <input type="range" class="form-range slider-danger" id="simTransport" min="0" max="10" step="0.5" value="2.5">
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <label class="form-label fw-semibold small mb-0">Estimated Post-Harvest Loss (%)</label>
                    <span class="val-badge text-danger" id="valLoss" style="border-color: #f87171; background: #fef2f2;">2 %</span>
                </div>
                <input type="range" class="form-range" id="simLoss" min="0" max="15" value="2">
            </div>
        </div>
    </div>

    <!-- Simulator Results -->
    <div class="card mb-4 bg-success text-white shadow">
        <div class="card-body">
            <h6 class="fw-bold opacity-75 mb-4">Estimated Trade Outcome</h6>
            
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>Gross Trade Value</span>
                <span class="fw-bold" id="resGross">₹ 35,000</span>
            </div>
            
            <hr class="border-white opacity-25 my-2">

            <div class="d-flex justify-content-between align-items-center mb-1 text-warning">
                <span class="small">- Transport Cost</span>
                <span class="fw-bold small" id="resTransport">₹ 2,500</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2 text-warning">
                <span class="small">- Value Lost (Damage)</span>
                <span class="fw-bold small" id="resLoss">₹ 700</span>
            </div>

            <hr class="border-white opacity-50 my-2">
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <h5 class="fw-bold mb-0">Est. NET VALUE</h5>
                <h3 class="fw-bold mb-0 text-white" id="resNet">₹ 31,800</h3>
            </div>
            
            <!-- Visual Bar -->
            <div class="progress mt-3 bg-dark" style="height: 10px;">
                <div class="progress-bar progress-bar-lime" id="progNet" style="width: 90%;"></div>
                <div class="progress-bar bg-danger opacity-75" id="progCost" style="width: 10%;"></div>
            </div>
            <div class="d-flex justify-content-between mt-1 opacity-75" style="font-size: 10px;">
                <span>Net Retention</span>
                <span>Leakage / Logistics</span>
            </div>
        </div>
    </div>

    <div class="alert alert-light border shadow-sm text-center small text-muted">
        <i class="fas fa-info-circle me-1"></i> This is a simulation. Actual gross and net amounts depend on the final certified live auction bids and verified logistics partner routes.
    </div>
</div>

<!-- Bottom Navigation -->
<div class="bottom-nav d-flex justify-content-around">
    <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
    <a href="what-if-simulator.php" class="active"><i class="fas fa-calculator"></i><span>Simulation</span></a>
    <a href="produce-passport.php"><i class="fas fa-passport"></i><span>Passport</span></a>
    <a href="crop-doctor.php"><i class="fas fa-stethoscope"></i><span>AI Doctor</span></a>
</div>

<script>
    // JS Logic for What-If Simulator
    const simPrice = document.getElementById('simPrice');
    const simQty = document.getElementById('simQty');
    const simTransport = document.getElementById('simTransport');
    const simLoss = document.getElementById('simLoss');

    function calculateSim() {
        const p = parseFloat(simPrice.value);
        const q = parseFloat(simQty.value);
        const t = parseFloat(simTransport.value);
        const l = parseFloat(simLoss.value);

        // Update Labels
        document.getElementById('valPrice').innerText = `₹ ${p.toFixed(2)}`;
        document.getElementById('valQty').innerText = `${q} kg`;
        document.getElementById('valTransport').innerText = `₹ ${t.toFixed(2)}`;
        document.getElementById('valLoss').innerText = `${l} %`;

        // Calculate Outputs
        const gross = p * q;
        const transportCost = t * q;
        const lostQty = q * (l / 100);
        const lostValue = lostQty * p; // Value of the lost/damaged produce
        const platformFee = gross * 0.01; // example 1%
        
        const totalCosts = transportCost + lostValue + platformFee;
        const netValue = gross - totalCosts;

        // Display Outputs
        document.getElementById('resGross').innerText = `₹ ${gross.toLocaleString('en-IN')}`;
        document.getElementById('resTransport').innerText = `₹ ${transportCost.toLocaleString('en-IN')}`;
        document.getElementById('resLoss').innerText = `₹ ${lostValue.toLocaleString('en-IN')}`;
        
        document.getElementById('resNet').innerText = `₹ ${Math.max(0, netValue).toLocaleString('en-IN', {maximumFractionDigits: 0})}`;

        // Width logic
        let netPct = (netValue / gross) * 100;
        if(netPct < 0) netPct = 0;
        let costPct = 100 - netPct;

        document.getElementById('progNet').style.width = `${netPct}%`;
        document.getElementById('progCost').style.width = `${costPct}%`;
    }

    [simPrice, simQty, simTransport, simLoss].forEach(el => el.addEventListener('input', calculateSim));
    calculateSim(); // init
</script>

</body>
</html>
