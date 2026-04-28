-- Add password reset columns to users table
ALTER TABLE users 
ADD COLUMN password_reset_token VARCHAR(255) DEFAULT NULL,
ADD COLUMN password_reset_expires DATETIME DEFAULT NULL;
