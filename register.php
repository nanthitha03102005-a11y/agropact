<?php
require_once 'includes/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? 'FARMER'; // Default to farmer registration
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $name = $_POST['name'] ?? '';
    
    // Server-side validation
    if (empty($email) || empty($phone) || empty($password) || empty($name)) {
        $error = "All fields are required.";
    } else {
        $db = getDB();
        
        // Ensure email and phone don't exist
        $stmt = $db->prepare("SELECT user_id FROM users WHERE email = ? OR phone_number = ?");
        $stmt->execute([$email, $phone]);
        if ($stmt->fetch()) {
            $error = "Email or Phone already exists.";
        } else {
            // Get role_id
            $stmt = $db->prepare("SELECT role_id FROM roles WHERE role_name = ?");
            $stmt->execute([strtoupper($role)]);
            $roleData = $stmt->fetch();
            
            if (!$roleData) {
                $error = "Invalid role.";
            } else {
                $role_id = $roleData['role_id'];
                $hash = password_hash($password, PASSWORD_DEFAULT);
                
                try {
                    $db->beginTransaction();
                    
                    $stmt = $db->prepare("INSERT INTO users (email, password_hash, phone_number, role_id, status) VALUES (?, ?, ?, ?, 'PENDING_KYC')");
                    $stmt->execute([$email, $hash, $phone, $role_id]);
                    $user_id = $db->lastInsertId();
                    
                    if (strtoupper($role) === 'FARMER') {
                        $stmt = $db->prepare("INSERT INTO farmer_profiles (user_id, full_name) VALUES (?, ?)");
                        $stmt->execute([$user_id, $name]);
                    } else if (strtoupper($role) === 'BUYER') {
                        $stmt = $db->prepare("INSERT INTO buyer_profiles (user_id, company_name, contact_person) VALUES (?, ?, ?)");
                        $stmt->execute([$user_id, $name, $name]);
                    }
                    
                    $db->commit();
                    $success = "Registration successful! Please login.";
                } catch (Exception $e) {
                    $db->rollBack();
                    $error = "Registration failed. Try again.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - AGRI-PACT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-4">
                <div class="card shadow rounded-4 border-0">
                    <div class="card-body p-5">
                        <h3 class="text-center text-success fw-bold mb-4">JOIN AGRI-PACT</h3>
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <?php if($success): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success) ?> <a href="login.php">Login here</a></div>
                        <?php else: ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">I am a</label>
                                <select name="role" class="form-select rounded-3">
                                    <option value="FARMER">Farmer</option>
                                    <option value="BUYER">Buyer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Full Name / Company Name</label>
                                <input type="text" name="name" class="form-control rounded-3" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control rounded-3" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control rounded-3" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control rounded-3" required minlength="6">
                            </div>
                            <button type="submit" class="btn btn-success w-100 rounded-3 py-2 fw-bold">Register</button>
                        </form>
                        <?php endif; ?>
                        <div class="mt-3 text-center">
                            Already have an account? <a href="login.php" class="text-success text-decoration-none">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
