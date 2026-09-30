<?php
// includes/database.php

// Using SQLite so NO XAMPP MySQL setup or password is required! It "just works" internally!
$db_file = __DIR__ . '/agripact.sqlite';
$is_new = !file_exists($db_file);

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // If database is completely new, generate the SQLite schema instantly
    if ($is_new || filesize($db_file) === 0) {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS roles (
                role_id INTEGER PRIMARY KEY AUTOINCREMENT,
                role_name TEXT NOT NULL UNIQUE,
                description TEXT
            );

            CREATE TABLE IF NOT EXISTS users (
                user_id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT UNIQUE NOT NULL,
                password_hash TEXT NOT NULL,
                phone_number TEXT UNIQUE NOT NULL,
                role_id INTEGER NOT NULL,
                preferred_language TEXT DEFAULT 'en',
                status TEXT DEFAULT 'ACTIVE'
            );

            CREATE TABLE IF NOT EXISTS produce_listings (
                produce_id INTEGER PRIMARY KEY AUTOINCREMENT,
                farmer_id INTEGER NOT NULL,
                crop_id INTEGER NOT NULL,
                quantity REAL NOT NULL,
                status TEXT DEFAULT 'DRAFT'
            );

            -- Initial Base Roles inside SQLite
            INSERT INTO roles (role_name, description) VALUES ('ADMIN', 'Platform Administrator');
            INSERT INTO roles (role_name, description) VALUES ('FARMER', 'Agricultural Producer');
            INSERT INTO roles (role_name, description) VALUES ('BUYER', 'Agricultural Buyer');
            
            -- Insert Demo Users (Password is 'password')
            INSERT INTO users (email, password_hash, phone_number, role_id, status) VALUES 
            ('farmer@agripact.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '8888888888', 2, 'ACTIVE');
            
            INSERT INTO users (email, password_hash, phone_number, role_id, status) VALUES 
            ('buyer@agripact.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '7777777777', 3, 'ACTIVE');
        ");
    }

} catch (\PDOException $e) {
    die("<b>Internal SQLite Creation Failed:</b> " . htmlspecialchars($e->getMessage()));
}

function getDB() {
    global $pdo;
    return $pdo;
}
?>
