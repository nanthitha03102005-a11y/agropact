<?php
require_once '../includes/auth.php';
requireRole('FARMER');

// In reality, this data comes from joining produce_listings, ai_quality_results, auctions, etc.
$passport_id = $_GET['id'] ?? 'AGR-TN-2026-000184';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Produce Passport - AGRI-PACT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Noto+Sans+Tamil:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .tamil-text { font-family: 'Noto Sans Tamil', sans-serif; }
        .card { border-radius: 16px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .bottom-nav { position: fixed; bottom: 0; width: 100%; background: white; box-shadow: 0 -2px 10px rgba(0,0,0,0.05); z-index: 100; }
        .bottom-nav a { text-align: center; flex: 1; padding: 12px; color: #6c757d; text-decoration: none; display: flex; flex-direction: column; align-items: center; }
        .bottom-nav a.active { color: #10b981; }
        
        /* Vertical Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 30px;
            margin-top: 20px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 25px;
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.5s forwards;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 5px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #10b981; /* Default active */
            border: 2px solid white;
            box-shadow: 0 0 0 2px #10b981;
            z-index: 2;
        }
        .timeline-item.pending::before {
            background: white;
            border-color: #cbd5e1;
            box-shadow: 0 0 0 2px #cbd5e1;
        }
        .timeline-content {
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        @keyframes slideUp {
            to { opacity: 1; transform: translateY(0); }
        }
        /* Stagger animations */
        .timeline-item:nth-child(1) { animation-delay: 0.1s; }
        .timeline-item:nth-child(2) { animation-delay: 0.2s; }
        .timeline-item:nth-child(3) { animation-delay: 0.3s; }
        .timeline-item:nth-child(4) { animation-delay: 0.4s; }
        .timeline-item:nth-child(5) { animation-delay: 0.5s; }
        .timeline-item:nth-child(6) { animation-delay: 0.6s; }

        .btn-lime { background-color: #84cc16; color: #064e3b; font-weight: 600; }
        .passport-header {
            background: linear-gradient(135deg, #064e3b 0%, #10b981 100%);
            color: white;
            padding: 30px 20px;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }
        .qr-placeholder {
            width: 80px; height: 80px;
            background: white;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: black; font-weight: bold; font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body class="pb-5 mb-5">

<div class="passport-header shadow-sm">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <a href="dashboard.php" class="text-white text-decoration-none mb-2 d-block"><i class="fas fa-arrow-left me-2"></i> Dashboard</a>
            <h5 class="fw-bold mb-1"><i class="fas fa-passport me-2"></i> Digital Produce Passport</h5>
            <div class="badge bg-white text-success fs-6 mt-2"><?= htmlspecialchars($passport_id) ?></div>
        </div>
        <div class="qr-placeholder shadow-sm">
            [ QR CODE ]
        </div>
    </div>
</div>

<div class="container mt-4">
    <!-- Summary Card -->
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="text-muted fw-bold mb-3 lang-text" data-en="Lot Summary" data-ta="தொகுப்பு சுருக்கம்">Lot Summary</h6>
            <div class="row g-2">
                <div class="col-6">
                    <small class="text-muted">Crop</small>
                    <div class="fw-bold fs-5 text-success">Tomato</div>
                    <small>Hybrid Variety</small>
                </div>
                <div class="col-6 text-end">
                    <small class="text-muted">Quantity</small>
                    <div class="fw-bold fs-5">1,200 kg</div>
                </div>
                <div class="col-12 mt-3 text-center">
                    <img src="https://images.unsplash.com/photo-1595856724083-d34e9bdcd2a4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Tomatoes" class="img-fluid rounded-4 shadow-sm" style="max-height: 200px; object-fit: cover; width: 100%;">
                </div>
            </div>
        </div>
    </div>

    <!-- AI Intelligence Card -->
    <div class="card mb-4">
        <div class="card-body border-start border-4 border-primary rounded-end">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-primary mb-0"><i class="fas fa-brain me-2"></i> Trade Intelligence</h6>
                <span class="badge bg-primary">Model: tomato_quality_v2</span>
            </div>
            <div class="row text-center mb-3">
                <div class="col-6 border-end">
                    <small class="text-muted">AI Quality Grade</small>
                    <div class="fs-4 fw-bold text-success">Grade A</div>
                    <small class="text-muted">Confidence: 94.2%</small>
                </div>
                <div class="col-6">
                    <small class="text-muted">Trade Estimate</small>
                    <div class="fs-5 fw-bold text-dark">₹32 - ₹36 / kg</div>
                    <small class="text-muted">High Demand</small>
                </div>
            </div>
            <div class="alert alert-light border border-primary-subtle text-primary mb-0 p-2 text-center" style="font-size: 0.85rem;">
                <i class="fas fa-info-circle"></i> AI estimates form the base values but do not replace final buyer inspection.
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3 ms-2 text-success">Transaction Lifecycle</h5>
    
    <!-- Vertical Timeline -->
    <div class="timeline">
        <div class="timeline-item">
            <div class="timeline-content">
                <div class="d-flex justify-content-between">
                    <h6 class="fw-bold text-success mb-1">Harvested</h6>
                    <small class="text-muted">Today, 08:30 AM</small>
                </div>
                <p class="text-muted mb-0 small">Registered on Madurai Farm. Status: Verified.</p>
            </div>
        </div>
        
        <div class="timeline-item">
            <div class="timeline-content">
                <div class="d-flex justify-content-between">
                    <h6 class="fw-bold text-success mb-1">AI Quality Verified</h6>
                    <small class="text-muted">Today, 09:15 AM</small>
                </div>
                <p class="text-muted mb-0 small">System assigned Grade A with 94.2% confidence.</p>
            </div>
        </div>
        
        <div class="timeline-item">
            <div class="timeline-content">
                <div class="d-flex justify-content-between">
                    <h6 class="fw-bold text-success mb-1">Live Auction</h6>
                    <small class="text-muted">Active</small>
                </div>
                <div class="alert alert-warning mb-0 p-2 mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-bold"><span class="spinner-grow spinner-grow-sm text-danger me-1"></span> Bidding in progress</span>
                        <span class="fw-bold fs-5 text-dark">₹34.50</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="timeline-item pending">
            <div class="timeline-content">
                <div class="d-flex justify-content-between">
                    <h6 class="fw-bold text-muted mb-1">Contract Negotiation</h6>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
        
        <div class="timeline-item pending">
            <div class="timeline-content">
                <div class="d-flex justify-content-between">
                    <h6 class="fw-bold text-muted mb-1">Logistics & Delivery</h6>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
        
        <div class="timeline-item pending">
            <div class="timeline-content">
                <div class="d-flex justify-content-between">
                    <h6 class="fw-bold text-muted mb-1">Payment Released</h6>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Navigation -->
<div class="bottom-nav d-flex justify-content-around">
    <a href="dashboard.php"><i class="fas fa-home"></i><span class="lang-text" data-en="Home" data-ta="முகப்பு">Home</span></a>
    <a href="#"><i class="fas fa-leaf"></i><span class="lang-text" data-en="Crops" data-ta="பயிர்கள்">Crops</span></a>
    <a href="produce-passport.php" class="active"><i class="fas fa-passport"></i><span class="lang-text" data-en="Passport" data-ta="பாஸ்போர்ட்">Passport</span></a>
    <a href="crop-doctor.php"><i class="fas fa-stethoscope"></i><span class="lang-text" data-en="AI Doctor" data-ta="AI மருத்துவர்">AI Doctor</span></a>
    <a href="#"><i class="fas fa-user"></i><span class="lang-text" data-en="Profile" data-ta="சுயவிவரம்">Profile</span></a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
