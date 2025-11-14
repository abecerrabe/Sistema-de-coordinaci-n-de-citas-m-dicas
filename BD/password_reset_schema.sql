-- Add password reset token and expiration columns to usuario table
ALTER TABLE usuario 
ADD COLUMN reset_token VARCHAR(255) NULL,
ADD COLUMN reset_token_expiration DATETIME NULL;