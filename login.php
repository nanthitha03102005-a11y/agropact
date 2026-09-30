<?php
require_once 'includes/database.php';
require_once 'includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $db = getDB();
    $stmt = $db->prepare("SELECT u.user_id, u.password_hash, r.role_name, u.preferred_language FROM users u JOIN roles r ON u.role_id = r.role_id WHERE u.email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password_hash'])) {
        loginUser($user['user_id'], $user['role_name'], $user['preferred_language']);
        
        switch($user['role_name']) {
            case 'ADMIN':
                header('Location: admin/dashboard.php');
                break;
            case 'FARMER':
                header('Location: farmer/dashboard.php');
                break;
            case 'BUYER':
                header('Location: buyer/requirements.php'); // Route to new buyer requirements hub
                break;
            case 'LOGISTICS':
                header('Location: logistics/dashboard.php');
                break;
            default:
                header('Location: index.php');
        }
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - AGRI-PACT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        
        .bg-animated {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: url('https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&q=80&w=1920') no-repeat center center;
            background-size: cover;
            animation: zoomInOut 30s infinite alternate;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: rgba(6, 78, 59, 0.7);
        }

        @keyframes zoomInOut {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            border: none;
        }
    </style>
</head>
<body>
    <div class="bg-animated"></div>
    <div class="bg-overlay"></div>
    
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="text-success fw-bold">AGRI-PACT</h2>
                            <p class="text-muted">Welcome back to the marketplace</p>
                        </div>
                        <?php if($error): ?>
                            <div class="alert alert-danger fw-semibold"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-success">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-lg rounded-3 shadow-none border-success-subtle" required placeholder="farmer@agripact.test">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-success">Password</label>
                                <input type="password" name="password" class="form-control form-control-lg rounded-3 shadow-none border-success-subtle" required placeholder="••••••••">
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100 rounded-3 py-3 fw-bold shadow">Secure Login</button>
                        </form>
                        <div class="mt-4 text-center">
                            <p class="text-muted mb-1 fw-bold fs-6">Demo Credentials:</p>
                            <span class="badge bg-light text-dark shadow-sm border border-secondary my-1 fs-6">farmer@agripact.test / password</span><br>
                            <span class="badge bg-light text-dark shadow-sm border border-secondary my-1 fs-6">buyer@agripact.test / password</span>
                            
                            <hr class="mt-4">
                            <a href="index.php" class="text-success fw-semibold text-decoration-none">← Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
