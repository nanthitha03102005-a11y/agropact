<?php
require_once '../includes/auth.php';
requireRole('LOGISTICS');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logistics HQ - AGRI-PACT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Leaflet Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .card { border-radius: 16px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .map-container { height: 250px; border-radius: 16px; overflow: hidden; background: #e2e8f0; }
        .timeline-vertical { border-left: 2px solid #cbd5e1; padding-left: 20px; position: relative; }
        .timeline-step { position: relative; margin-bottom: 20px; }
        .timeline-step::before {
            content: ''; position: absolute; left: -26px; top: 0; width: 10px; height: 10px;
            background: #cbd5e1; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 0 2px #cbd5e1;
        }
        .timeline-step.active::before { background: #3b82f6; box-shadow: 0 0 0 2px #3b82f6; }
        .timeline-step.completed::before { background: #10b981; box-shadow: 0 0 0 2px #10b981; }
        
        .otp-box { letter-spacing: 0.5em; font-size: 1.5rem; text-align: center; }
    </style>
</head>
<body class="pb-5">

<nav class="navbar navbar-light bg-dark navbar-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-success" href="#">AGRI-PACT <span class="badge bg-primary ms-2" style="font-size: 10px;">LOGISTICS</span></a>
        <div>
            <a href="../logout.php" class="btn btn-outline-light btn-sm fw-bold">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3 class="fw-bold mb-1">Fleet Operations HQ</h3>
    <p class="text-muted">FastAgri Logistics - Manage your digital delivery contracts.</p>

    <div class="row g-4 mt-2">
        <div class="col-md-8">
            <!-- Active Delivery Job -->
            <div class="card shadow-sm mb-4 border-top border-4 border-primary">
                <style>
                    .driver-badge { background: #eff6ff; color: #1e3a8a; font-weight: 600; padding: 5px 12px; border-radius: 20px; }
                </style>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-0">JOB: #LOG-8821 <span class="badge bg-warning text-dark align-middle ms-2">IN TRANSIT</span></h5>
                            <span class="text-muted small">Contract: AGR-TN-2026-000184</span>
                        </div>
                        <span class="driver-badge"><i class="fas fa-truck-moving me-1"></i> Vehicle TN-58-XX-9901</span>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <small class="text-muted fw-bold d-block">Pickup Node</small>
                            <span class="fw-bold">Madurai Farmer Pool</span>
                            <small class="d-block text-muted">12/A West St, Madurai</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted fw-bold d-block">Drop Node</small>
                            <span class="fw-bold text-danger">Fresh Foods Pvt Ltd</span>
                            <small class="d-block text-muted">Chennai Agro Market</small>
                        </div>
                    </div>

                    <div class="map-container mb-4 d-flex align-items-center justify-content-center text-muted fw-bold">
                        <!-- Mock Map Space -->
                        <i class="fas fa-map-marked-alt fa-3x mb-2 d-block w-100 text-center"></i>
                        [ GPS Route Integration Placeholder ]
                    </div>

                    <!-- OTP Workflow -->
                    <div class="bg-light p-3 rounded-4 border">
                        <h6 class="fw-bold mb-3"><i class="fas fa-key text-primary me-2"></i> Delivery Verification Protocol</h6>
                        <div class="row g-2 align-items-center">
                            <div class="col-md-8">
                                <input type="text" class="form-control form-control-lg otp-box fw-bold" placeholder="• • • • • •" maxlength="6">
                                <small class="text-muted d-block mt-1">Ask the buyer for their 6-digit cryptographic Delivery OTP.</small>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary btn-lg w-100 fw-bold shadow"><i class="fas fa-check-circle me-1"></i> Verify & Deliver</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Delivery Timeline Simulator -->
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Logistics Timeline</h5>
                    <div class="timeline-vertical">
                        <div class="timeline-step completed">
                            <h6 class="fw-bold mb-0">Contract Assigned</h6>
                            <small class="text-muted">09:00 AM</small>
                        </div>
                        <div class="timeline-step completed">
                            <h6 class="fw-bold mb-0">Pickup OTP Verified</h6>
                            <small class="text-muted">10:45 AM • Verified by Farmer</small>
                        </div>
                        <div class="timeline-step active">
                            <h6 class="fw-bold mb-0 text-primary">In Transit</h6>
                            <small class="text-muted">ETA: 4 Hours</small>
                        </div>
                        <div class="timeline-step">
                            <h6 class="fw-bold mb-0 text-muted">Arrived at Destination</h6>
                            <small class="text-muted">Pending Delivery OTP</small>
                        </div>
                        <div class="timeline-step">
                            <h6 class="fw-bold mb-0 text-muted">Payment Auto-Released</h6>
                            <small class="text-muted">Locked state</small>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="alert alert-info border-0 p-3 small mb-0">
                        <i class="fas fa-info-circle fw-bold me-1"></i> Upon Delivery OTP verification, the escrow payment will automatically release to the Farmer & Logistics Wallets within minutes.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
