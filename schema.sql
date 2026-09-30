-- AGRI-PACT Database Schema v2 (Trade Intelligence Upgrade)

CREATE DATABASE IF NOT EXISTS agripact CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agripact;

-- 1. ROLES AND USERS
CREATE TABLE IF NOT EXISTS roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE IF NOT EXISTS users (
    user_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) UNIQUE NOT NULL,
    role_id INT NOT NULL,
    preferred_language VARCHAR(5) DEFAULT 'en',
    status ENUM('PENDING_KYC', 'ACTIVE', 'SUSPENDED', 'BANNED') DEFAULT 'PENDING_KYC',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

CREATE TABLE IF NOT EXISTS farmer_profiles (
    user_id BIGINT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    address TEXT,
    state VARCHAR(100),
    district VARCHAR(100),
    village VARCHAR(100),
    pincode VARCHAR(20),
    trust_score DECIMAL(5,2) DEFAULT 0.0,
    fpo_id BIGINT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS buyer_profiles (
    user_id BIGINT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    contact_person VARCHAR(255) NOT NULL,
    gst_number VARCHAR(50),
    address TEXT,
    trust_score DECIMAL(5,2) DEFAULT 0.0,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS logistics_profiles (
    user_id BIGINT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    vehicle_types TEXT,
    service_regions TEXT,
    trust_score DECIMAL(5,2) DEFAULT 0.0,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS fpo_profiles (
    user_id BIGINT PRIMARY KEY,
    fpo_name VARCHAR(255) NOT NULL,
    registration_number VARCHAR(100),
    contact_person VARCHAR(255),
    state VARCHAR(100),
    district VARCHAR(100),
    trust_score DECIMAL(5,2) DEFAULT 0.0,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- 2. CROP HIERARCHY
CREATE TABLE IF NOT EXISTS crop_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS crops (
    crop_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    scientific_name VARCHAR(255),
    unit VARCHAR(20) DEFAULT 'kg',
    shelf_life_days INT DEFAULT 7,
    ai_quality_model VARCHAR(100),
    ai_price_model VARCHAR(100),
    ai_disease_model VARCHAR(100),
    FOREIGN KEY (category_id) REFERENCES crop_categories(category_id)
);

CREATE TABLE IF NOT EXISTS crop_translations (
    translation_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    crop_id INT NOT NULL,
    language_code VARCHAR(5) NOT NULL,
    crop_name VARCHAR(255) NOT NULL,
    description TEXT,
    UNIQUE KEY unique_crop_lang (crop_id, language_code),
    FOREIGN KEY (crop_id) REFERENCES crops(crop_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS crop_varieties (
    variety_id INT AUTO_INCREMENT PRIMARY KEY,
    crop_id INT NOT NULL,
    variety_name VARCHAR(255) NOT NULL,
    FOREIGN KEY (crop_id) REFERENCES crops(crop_id) ON DELETE CASCADE
);

-- 3. PRODUCE & DIGITAL PASSPORT
CREATE TABLE IF NOT EXISTS produce_listings (
    produce_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    farmer_id BIGINT NOT NULL,
    crop_id INT NOT NULL,
    variety_id INT,
    quantity DECIMAL(10,2) NOT NULL,
    harvest_date DATE,
    expected_availability DATE,
    passport_id VARCHAR(50) UNIQUE,
    status ENUM('DRAFT', 'AI_ANALYSIS', 'LISTED', 'IN_AUCTION', 'SOLD', 'CANCELLED', 'POOLED') DEFAULT 'DRAFT',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmer_profiles(user_id),
    FOREIGN KEY (crop_id) REFERENCES crops(crop_id)
);

CREATE TABLE IF NOT EXISTS ai_quality_results (
    result_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    produce_id BIGINT,
    model_version VARCHAR(100),
    predicted_grade VARCHAR(50),
    visual_score DECIMAL(5,2),
    confidence DECIMAL(5,4),
    disease_detected VARCHAR(255),
    disease_confidence DECIMAL(5,4),
    analysis_json JSON,
    analyzed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produce_id) REFERENCES produce_listings(produce_id) ON DELETE CASCADE
);

-- 4. REVERSE AUCTION / BUYER REQUIREMENTS
CREATE TABLE IF NOT EXISTS buyer_requirements (
    requirement_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    buyer_id BIGINT NOT NULL,
    crop_id INT NOT NULL,
    variety_id INT,
    quantity DECIMAL(10,2) NOT NULL,
    minimum_grade VARCHAR(50),
    maximum_price DECIMAL(10,2),
    delivery_date DATE,
    delivery_location TEXT,
    status ENUM('OPEN', 'MATCHING', 'FULFILLED', 'CANCELLED') DEFAULT 'OPEN',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES buyer_profiles(user_id),
    FOREIGN KEY (crop_id) REFERENCES crops(crop_id)
);

-- 5. AUCTIONS
CREATE TABLE IF NOT EXISTS auctions (
    auction_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    produce_id BIGINT NULL,
    requirement_id BIGINT NULL,
    auction_type ENUM('FORWARD', 'REVERSE') DEFAULT 'FORWARD',
    starting_bid DECIMAL(10,2) NOT NULL,
    min_increment DECIMAL(10,2) NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    status ENUM('PENDING', 'ACTIVE', 'COMPLETED', 'CANCELLED') DEFAULT 'PENDING',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produce_id) REFERENCES produce_listings(produce_id),
    FOREIGN KEY (requirement_id) REFERENCES buyer_requirements(requirement_id)
);

CREATE TABLE IF NOT EXISTS auction_bids (
    bid_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    auction_id BIGINT NOT NULL,
    bidder_id BIGINT NOT NULL,
    bid_amount DECIMAL(10,2) NOT NULL,
    bid_time DATETIME NOT NULL,
    is_auto_bid BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (auction_id) REFERENCES auctions(auction_id),
    FOREIGN KEY (bidder_id) REFERENCES users(user_id)
);

-- 6. FARMER POOLING
CREATE TABLE IF NOT EXISTS farmer_pools (
    pool_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    requirement_id BIGINT NOT NULL,
    fpo_id BIGINT NULL,
    total_quantity DECIMAL(10,2) NOT NULL,
    status ENUM('FORMING', 'ACTIVE', 'COMPLETED') DEFAULT 'FORMING',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (requirement_id) REFERENCES buyer_requirements(requirement_id),
    FOREIGN KEY (fpo_id) REFERENCES fpo_profiles(user_id)
);

CREATE TABLE IF NOT EXISTS pool_members (
    pool_member_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    pool_id BIGINT NOT NULL,
    farmer_id BIGINT NOT NULL,
    produce_id BIGINT NOT NULL,
    allocated_quantity DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pool_id) REFERENCES farmer_pools(pool_id),
    FOREIGN KEY (farmer_id) REFERENCES farmer_profiles(user_id),
    FOREIGN KEY (produce_id) REFERENCES produce_listings(produce_id)
);

-- 7. CONTRACTS & PAYMENTS
CREATE TABLE IF NOT EXISTS contracts (
    contract_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    auction_id BIGINT NOT NULL,
    farmer_id BIGINT,
    buyer_id BIGINT NOT NULL,
    pool_id BIGINT NULL,
    final_amount DECIMAL(10,2) NOT NULL,
    logistics_cost DECIMAL(10,2) DEFAULT 0.0,
    other_costs DECIMAL(10,2) DEFAULT 0.0,
    status ENUM('PENDING_ACCEPTANCE', 'ACCEPTED', 'REJECTED', 'COMPLETED', 'DISPUTED') DEFAULT 'PENDING_ACCEPTANCE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (auction_id) REFERENCES auctions(auction_id),
    FOREIGN KEY (farmer_id) REFERENCES farmer_profiles(user_id),
    FOREIGN KEY (buyer_id) REFERENCES buyer_profiles(user_id),
    FOREIGN KEY (pool_id) REFERENCES farmer_pools(pool_id)
);

CREATE TABLE IF NOT EXISTS logistics_jobs (
    job_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    contract_id BIGINT NOT NULL,
    logistics_partner_id BIGINT,
    pickup_location TEXT,
    delivery_location TEXT,
    status ENUM('PENDING', 'ASSIGNED', 'PICKED_UP', 'IN_TRANSIT', 'ARRIVED', 'DELIVERED') DEFAULT 'PENDING',
    pickup_otp VARCHAR(6),
    delivery_otp VARCHAR(6),
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id)
);

CREATE TABLE IF NOT EXISTS payments (
    payment_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    contract_id BIGINT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('PENDING', 'HELD', 'RELEASED', 'FAILED', 'REFUNDED', 'DISPUTED') DEFAULT 'PENDING',
    transaction_reference VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id)
);

-- 8. SYSTEM & LOGS (AI Management, Audits, Fraud)
CREATE TABLE IF NOT EXISTS ai_models (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model_name VARCHAR(100) NOT NULL,
    model_type ENUM('CLASSIFICATION', 'REGRESSION', 'ANOMALY') NOT NULL,
    crop_id INT,
    version VARCHAR(50) NOT NULL,
    dataset_version VARCHAR(50),
    accuracy DECIMAL(5,2),
    precision_score DECIMAL(5,2),
    recall DECIMAL(5,2),
    f1_score DECIMAL(5,2),
    status ENUM('TRAINING', 'VALIDATION', 'ACTIVE', 'RETIRED') DEFAULT 'TRAINING',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (crop_id) REFERENCES crops(crop_id)
);

CREATE TABLE IF NOT EXISTS fraud_alerts (
    alert_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT,
    risk_level ENUM('LOW', 'MEDIUM', 'HIGH') NOT NULL,
    rule_triggered VARCHAR(255),
    status ENUM('REVIEW_PENDING', 'RESOLVED', 'DISMISSED') DEFAULT 'REVIEW_PENDING',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS disputes (
    dispute_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    contract_id BIGINT NOT NULL,
    opened_by BIGINT NOT NULL,
    reason VARCHAR(255) NOT NULL,
    status ENUM('OPEN', 'EVIDENCE_COLLECTION', 'UNDER_REVIEW', 'DECISION', 'RESOLVED') DEFAULT 'OPEN',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id),
    FOREIGN KEY (opened_by) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS audit_logs (
    log_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT,
    action VARCHAR(255) NOT NULL,
    entity_type VARCHAR(100),
    entity_id BIGINT,
    old_state JSON,
    new_state JSON,
    ip_address VARCHAR(45),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
