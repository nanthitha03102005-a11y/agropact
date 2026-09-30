<?php
// includes/auth.php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function loginUser($user_id, $role_name, $language = 'en') {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['role'] = $role_name;
    $_SESSION['lang'] = $language;
}

function logoutUser() {
    session_destroy();
    header("Location: /agripact/login.php");
    exit();
}

function requireRole($role) {
    if (!isLoggedIn() || $_SESSION['role'] !== $role) {
        header("Location: /agripact/login.php");
        exit();
    }
}
?>
