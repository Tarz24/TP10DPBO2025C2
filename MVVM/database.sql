-- Membuat database
CREATE DATABASE IF NOT EXISTS restaurant_management;
USE restaurant_management;

-- Membuat tabel menu
CREATE TABLE IF NOT EXISTS menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Membuat tabel order
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    table_number INT,
    order_status ENUM('pending', 'preparing', 'served', 'paid') DEFAULT 'pending',
    total_amount DECIMAL(10, 2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Membuat tabel order_item (relasi many-to-many antara order dan menu)
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    item_price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menu(id) ON DELETE RESTRICT
);

-- Insert beberapa data contoh
INSERT INTO menu (name, description, price, category) VALUES
('Nasi Goreng', 'Nasi goreng dengan telur dan ayam', 25000.00, 'Makanan Utama'),
('Mie Goreng', 'Mie goreng dengan telur dan sayuran', 23000.00, 'Makanan Utama'),
('Es Teh', 'Teh manis dingin', 7000.00, 'Minuman'),
('Es Jeruk', 'Jeruk segar dengan es', 8000.00, 'Minuman'),
('Ayam Bakar', 'Ayam bakar bumbu kecap', 30000.00, 'Makanan Utama');
