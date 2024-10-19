-- Create the database
CREATE DATABASE IF NOT EXISTS perfume_inventory;

USE perfume_inventory;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Create perfumes table
CREATE TABLE IF NOT EXISTS perfumes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(255) NOT NULL,
    scent_notes TEXT,
    price DECIMAL(10, 2) NOT NULL
);
