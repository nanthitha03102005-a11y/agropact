-- AGRI-PACT Database Seed Data v2
USE agripact;

-- Roles
INSERT IGNORE INTO roles (role_name, description) VALUES 
('ADMIN', 'Platform Administrator'),
('FARMER', 'Agricultural Producer'),
('BUYER', 'Agricultural Buyer'),
('LOGISTICS', 'Logistics Partner'),
('FPO', 'Farmer Producer Organization');

-- Users (assuming password = 'password' hash)
INSERT IGNORE INTO users (email, password_hash, phone_number, role_id, status) VALUES 
('admin@agripact.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9999999999', 1, 'ACTIVE'),
('farmer@agripact.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '8888888888', 2, 'ACTIVE'),
('buyer@agripact.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '7777777777', 3, 'ACTIVE'),
('logistics@agripact.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '6666666666', 4, 'ACTIVE'),
('fpo@agripact.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '5555555555', 5, 'ACTIVE');

-- Profiles
INSERT IGNORE INTO farmer_profiles (user_id, full_name, address, state, district, village, pincode, trust_score) VALUES 
((SELECT user_id FROM users WHERE email='farmer@agripact.test'), 'Murugan K', '12/A West Street', 'Tamil Nadu', 'Madurai', 'Melur', '625106', 95.5);

INSERT IGNORE INTO buyer_profiles (user_id, company_name, contact_person, gst_number, address, trust_score) VALUES 
((SELECT user_id FROM users WHERE email='buyer@agripact.test'), 'Fresh Foods India Pvt Ltd', 'Rajesh Kumar', '33AABCU9603R1ZM', 'Chennai Agro Market', 92.0);

INSERT IGNORE INTO fpo_profiles (user_id, fpo_name, registration_number, state, district) VALUES 
((SELECT user_id FROM users WHERE email='fpo@agripact.test'), 'Madurai Farmers Cooperative', 'TN-FPO-99120', 'Tamil Nadu', 'Madurai');

-- Categories & Crops
INSERT IGNORE INTO crop_categories (category_id, category_name) VALUES 
(1, 'Vegetables'), (2, 'Fruits'), (3, 'Cereals'), (4, 'Spices');

INSERT IGNORE INTO crops (crop_id, category_id, scientific_name, unit, ai_quality_model) VALUES 
(1, 1, 'Solanum lycopersicum', 'kg', 'tomato_quality_v1'),
(2, 1, 'Allium cepa', 'kg', 'onion_quality_v1'),
(3, 2, 'Musa', 'kg', 'banana_quality_v1');

INSERT IGNORE INTO crop_translations (crop_id, language_code, crop_name) VALUES 
(1, 'en', 'Tomato'), (1, 'ta', 'தக்காளி'),
(2, 'en', 'Onion'),  (2, 'ta', 'வெங்காயம்'),
(3, 'en', 'Banana'), (3, 'ta', 'வாழைப்பழம்');
