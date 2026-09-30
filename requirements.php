<?php
require_once '../includes/auth.php';
requireRole('BUYER');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Requirements Board - AGRI-PACT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .card { border-radius: 16px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .card:hover { transform: translateY(-3px); }
        .nav-sidebar { background: white; min-height: 100vh; box-shadow: 2px 0 10px rgba(0,0,0,0.05); }
        .nav-sidebar .nav-link { color: #64748b; font-weight: 600; padding: 15px 20px; border-radius: 8px; margin: 5px 15px; }
        .nav-sidebar .nav-link:hover, .nav-sidebar .nav-link.active { background: #ecfdf5; color: #10b981; }
        .stat-card { border-left: 4px solid #10b981; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold text-success" href="#">AGRI-PACT <span class="badge bg-dark fs-6 ms-2">BUYER PORTAL</span></a>
        <div class="d-flex align-items-center">
            <span class="me-3 fw-bold text-muted"><i class="fas fa-building me-1"></i> Fresh Foods India Pvt Ltd</span>
            <a href="../logout.php" class="btn btn-outline-danger btn-sm rounded-pill fw-bold px-3">Logout</a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Desktop -->
        <div class="col-md-2 d-none d-md-block nav-sidebar py-4 px-0">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-home me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-search me-2"></i> Forward Auctions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="fas fa-bullhorn me-2"></i> My Requirements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-file-contract me-2"></i> Smart Matches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-truck me-2"></i> Logistics Tracker</a>
                </li>
            </ul>
        </div>

        <div class="col-md-10 py-5 px-md-5">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Requirement Board (Reverse Auction)</h3>
                    <p class="text-muted mb-0">Post your demands. Let farmers and FPOs Pool & Bid to fulfill it.</p>
                </div>
                <button class="btn btn-success btn-lg rounded-pill shadow fw-bold px-4"><i class="fas fa-plus me-2"></i> Post New Requirement</button>
            </div>

            <div class="row mb-5">
                <!-- Open Requirement Card -->
                <div class="col-md-6 mb-4">
                    <div class="card stat-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-warning text-dark mb-2 shadow-sm rounded-pill px-3">MATCHING IN PROGRESS</span>
                                    <h4 class="fw-bold mb-0">5,000 kg Tomato (Grade A)</h4>
                                </div>
                                <h4 class="fw-bold text-success mb-0">≤ ₹35/kg</h4>
                            </div>
                            
                            <hr class="opacity-25">
                            
                            <div class="row text-muted small mb-3">
                                <div class="col-6"><i class="fas fa-map-marker-alt me-1 text-danger"></i> Target: Tamil Nadu</div>
                                <div class="col-6 text-end"><i class="fas fa-calendar-alt me-1 text-primary"></i> Exp Delivery: 3 Days</div>
                            </div>
                            
                            <div class="alert bg-success-subtle border-0 mb-3">
                                <span class="fw-bold text-success"><i class="fas fa-users me-2"></i> Farmer Pool Forming!</span><br>
                                <div class="d-flex align-items-center mt-2">
                                    <div class="progress w-100" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: 75%;"></div>
                                    </div>
                                    <span class="ms-3 fw-bold">3,750 kg committed</span>
                                </div>
                            </div>

                            <a href="#" class="btn btn-outline-success w-100 fw-bold rounded-3">Review 4 Farmer Offers</a>
                        </div>
                    </div>
                </div>

                <!-- Another Requirement -->
                <div class="col-md-6 mb-4">
                    <div class="card border-left border-4 border-secondary h-100" style="border-left: 4px solid #94a3b8;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-secondary mb-2 shadow-sm rounded-pill px-3">FULFILLED</span>
                                    <h4 class="fw-bold text-muted mb-0">2,000 kg Onion (Grade B)</h4>
                                </div>
                                <h4 class="fw-bold text-muted mb-0">₹24/kg</h4>
                            </div>
                            
                            <hr class="opacity-25">
                            
                            <div class="row text-muted small mb-3 opacity-75">
                                <div class="col-6"><i class="fas fa-map-marker-alt me-1"></i> Target: Madurai</div>
                                <div class="col-6 text-end"><i class="fas fa-check-circle me-1 text-success"></i> Delivered: Yesterday</div>
                            </div>
                            
                            <div class="alert bg-light border mb-0 text-center text-muted">
                                Completed via single FPO contract.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Smart Matches Section -->
            <h4 class="fw-bold mb-3"><i class="fas fa-magic text-warning"></i> AI Smart Recommendations</h4>
            <p class="text-muted mb-4">Based on your requirement, the Trade Intelligence Engine suggests these aggregated lots:</p>

            <div class="table-responsive bg-white rounded-4 shadow-sm">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-4 py-3">AGGREGATE ORIGIN</th>
                            <th class="py-3">CROP & QUALITY</th>
                            <th class="py-3">QUANTITY</th>
                            <th class="py-3">MATCH SCORE</th>
                            <th class="pe-4 py-3 text-end">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4">
                                <h6 class="fw-bold mb-0">Madurai Farmers Cooperative (FPO)</h6>
                                <small class="text-muted">TN-FPO-99120 • 45 km away</small>
                            </td>
                            <td>
                                <div>Tomato (Hybrid)</div>
                                <span class="badge bg-success">Grade A</span> <span class="badge bg-primary">AI Verified</span>
                            </td>
                            <td><span class="fw-bold">4,100 kg</span></td>
                            <td>
                                <span class="text-success fw-bold"><i class="fas fa-check-circle"></i> 94% Match</span><br>
                                <small class="text-muted" style="font-size:10px;">Location ✓ | Quantity ✗ | Quality ✓</small>
                            </td>
                            <td class="pe-4 text-end">
                                <button class="btn btn-success btn-sm rounded-pill fw-bold px-3">Connect</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
