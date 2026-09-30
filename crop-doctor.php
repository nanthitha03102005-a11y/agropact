<?php
require_once '../includes/auth.php';
requireRole('FARMER');

$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['crop_image'])) {
    // In actual implementation, this will send the file to the Python Service over REST/exec.
    // For our core decoupled AI demonstration:
    $result = [
        'crop' => 'Tomato',
        'disease' => 'Early Blight (Alternaria solani)',
        'confidence' => 82.5,
        'recommendation' => 'Remove and destroy infected lower leaves. Consider applying copper-based fungicides if weather is humid.',
        'warning' => 'Low confidence (<85%). Manual/expert verification is strongly recommended.'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Crop Doctor - AGRI-PACT</title>
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

        .header-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 30px 20px;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }

        .upload-area {
            border: 2px dashed #10b981;
            background: #ecfdf5;
            padding: 40px 20px;
            border-radius: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-area:hover {
            background: #d1fae5;
        }
        
        /* Skeleton Animation */
        .skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 8px;
        }
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body class="pb-5 mb-5">

<div class="header-bg shadow-sm">
    <a href="dashboard.php" class="text-white text-decoration-none mb-2 d-block"><i class="fas fa-arrow-left me-2"></i> Dashboard</a>
    <h4 class="fw-bold mb-1"><i class="fas fa-stethoscope me-2 text-lime w-auto" style="color: #84cc16;"></i> AI Crop Doctor</h4>
    <p class="mb-0 text-light opacity-75 small">Snap a photo to intelligently detect diseases & pests securely without generative APIs.</p>
</div>

<div class="container mt-4">

    <!-- AI Input -->
    <?php if(!$result): ?>
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Upload Crop Image</h6>
            <form method="POST" enctype="multipart/form-data" id="aiForm">
                <!-- Using mobile camera safely -->
                <label class="upload-area w-100 d-block shadow-sm">
                    <i class="fas fa-camera fs-1 text-success mb-2"></i>
                    <h6 class="fw-bold text-success mb-1">Take Photo / Upload</h6>
                    <small class="text-muted">Uses strictly self-trained CNN architectures.</small>
                    <input type="file" name="crop_image" accept="image/*" capture="environment" class="d-none" onchange="document.getElementById('aiForm').submit();">
                </label>
            </form>
        </div>
    </div>
    
    <div class="card bg-warning-subtle text-warning-emphasis">
        <div class="card-body p-3 small fw-semibold">
            <i class="fas fa-exclamation-triangle me-1"></i> AI diagnosis is an indicative tool. It does not replace certified agronomist laboratory testing.
        </div>
    </div>
    <?php endif; ?>

    <!-- AI Output -->
    <?php if($result): ?>
    <div class="card mb-4 border-2 border-danger">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-danger p-2 fs-6"><i class="fas fa-virus me-1"></i> Risk Detected</span>
                <span class="text-muted small">Model: disease_v2_core</span>
            </div>
            
            <h5 class="fw-bold mb-1"><?= htmlspecialchars($result['disease']) ?></h5>
            <div class="mb-3">
                <span class="badge bg-secondary"><?= htmlspecialchars($result['crop']) ?></span>
                <span class="badge bg-dark">Confidence: <?= $result['confidence'] ?>%</span>
            </div>

            <h6 class="fw-bold text-muted small mt-4">PREVENTIVE ACTION:</h6>
            <p class="mb-3"><?= htmlspecialchars($result['recommendation']) ?></p>
            
            <div class="alert alert-warning mb-0 p-2 small fw-bold">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($result['warning']) ?>
            </div>
            
            <hr class="my-4">
            <a href="crop-doctor.php" class="btn btn-outline-secondary w-100 fw-bold">Scan Another Crop</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Bottom Navigation -->
<div class="bottom-nav d-flex justify-content-around">
    <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
    <a href="#"><i class="fas fa-leaf"></i><span>Crops</span></a>
    <a href="produce-passport.php"><i class="fas fa-passport"></i><span>Passport</span></a>
    <a href="crop-doctor.php" class="active"><i class="fas fa-stethoscope"></i><span>AI Doctor</span></a>
    <a href="#"><i class="fas fa-user"></i><span>Profile</span></a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
