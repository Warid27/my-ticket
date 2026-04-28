<?php
require_once __DIR__ . '/db.php';
$pdo = getDB();

$sql = "
CREATE TABLE
    IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role enum ('user', 'petugas', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

CREATE TABLE
    IF NOT EXISTS vouchers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(20) NOT NULL UNIQUE,
        discount INT NOT NULL,
        quota INT NOT NULL,
        type enum ('value', 'percentage') DEFAULT 'value',
        status enum ('aktif', 'nonaktif') DEFAULT 'nonaktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

CREATE TABLE
    IF NOT EXISTS venues (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        address TEXT NOT NULL,
        capacity INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

CREATE TABLE
    IF NOT EXISTS events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        date DATETIME NOT NULL,
        venue_id INT NOT NULL,
        FOREIGN KEY (venue_id) REFERENCES venues (id) ON DELETE RESTRICT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

CREATE TABLE
    IF NOT EXISTS tickets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        event_id INT NOT NULL,
        name VARCHAR(50) NOT NULL,
        price INT NOT NULL,
        quota INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE RESTRICT
    );

CREATE TABLE
    IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        voucher_id INT DEFAULT NULL,
        date DATETIME NOT NULL,
        total INT NOT NULL,
        status enum ('pending', 'paid', 'cancel'),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE RESTRICT,
        FOREIGN KEY (voucher_id) REFERENCES vouchers (id) ON DELETE SET NULL
    );

CREATE TABLE
    IF NOT EXISTS order_details (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        ticket_id INT NOT NULL,
        qty INT NOT NULL,
        subtotal INT NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE RESTRICT,
        FOREIGN KEY (ticket_id) REFERENCES tickets (id) ON DELETE RESTRICT
    );

CREATE TABLE
    IF NOT EXISTS attendees (
        id INT AUTO_INCREMENT PRIMARY KEY,
        detail_id INT NOT NULL,
        ticket_code VARCHAR(50) NOT NULL UNIQUE,
        checkin_status enum ('belum', 'sudah') DEFAULT 'belum',
        checkin_time DATETIME DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (detail_id) REFERENCES order_details (id) ON DELETE RESTRICT
    );

CREATE TABLE
    IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        type ENUM('checkin', 'payment', 'order', 'system') DEFAULT 'system',
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
    );

-- Add password reset columns to users table
ALTER TABLE users 
ADD COLUMN password_reset_token VARCHAR(255) DEFAULT NULL,
ADD COLUMN password_reset_expires DATETIME DEFAULT NULL;
";

$pdo->exec($sql);

// ===== SEEDER =====
// users
$users = [
    ['Admin', 'admin@gmail.com', 'password', 'admin'],
    ['Petugas 1', 'petugas1@gmail.com', 'password', 'petugas'],
    ['Petugas 2', 'petugas2@gmail.com', 'password', 'petugas'],
    ['Mada Alvino MR', 'vino@gmail.com', 'password', 'user'],
    ['Irfan', 'irfan@gmail.com', 'password', 'user'],
    ['Al Warid', 'warid@gmail.com', 'password', 'user'],
];

foreach ($users as $user) {
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user[1]]);
    if (!$stmt->fetch()) {
        $name = $user[0];
        $email = $user[1];
        $pass = $user[2];
        $role = $user[3];

        $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)")->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT), $role]);
    }
}

// venues
$venues = [
    ['Lapangan Rindam', 'Rindam', 1000],
    ['Grand Hotel Artos', 'Jl. Artos', 500],
    ['Lapangan SMKN 2 Kota Magelang', 'Jl. Ahmad Yani', 100],
    ['Gedung AH Nasution', 'Akmil', 300],
    ['Gelora Bung Karno', 'Jl. Jenderal Sudirman', 1500],
];

foreach ($venues as $venue) {
    $stmt = $pdo->prepare("SELECT id FROM venues WHERE name = ?");
    $stmt->execute([$venue[0]]);
    if (!$stmt->fetch()) {
        $pdo->prepare("INSERT INTO venues (name, address, capacity) VALUES (?, ?, ?)")->execute($venue);
    }
}

$venueIds = $pdo->query("SELECT id FROM venues ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);

// events
$events = [
    ['Konser .Feast', '2026-9-28'],
    ['GDG Community', '2026-09-28'],
    ['HUT SMEA', '2027-01-28'],
    ['WISUDA SMEA', '2026-10-28'],
    ['Konser Avenged Sevenfold', '2025-12-28'],
];

foreach ($events as $index => $event) {
    $stmt = $pdo->prepare("SELECT id FROM events WHERE name = ?");
    $stmt->execute([$event[0]]);
    if (!$stmt->fetchAll()) {
        $pdo->prepare("INSERT INTO events (name, date, venue_id) VALUES (?, ?, ?)")->execute([$event[0], $event[1], $venueIds[$index % count($venueIds)]]);
    }
}

$eventIds = $pdo->query("SELECT id FROM events ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);

// tickets

$tickets = [
    ["VIP", 10000000, 100],
    ["Reguler", 800000, 500],
    ["Pelajar", 100000, 100],
    ["SMEA", 100000, 300],
];

foreach ($tickets as $index => $ticket) {
    $stmt = $pdo->prepare("SELECT id FROM tickets WHERE name = ? AND event_id = ?");
    $stmt->execute([$ticket[0], $eventIds[$index % count($eventIds)]]);
    if (!$stmt->fetchAll()) {
        $pdo->prepare("INSERT INTO tickets (name, price, quota, event_id) VALUES (?, ?, ?, ?)")->execute([$ticket[0], $ticket[1], $ticket[2], $eventIds[$index % count($eventIds)]]);
    }
}

// voucher
$vouchers = [
    ["DISCOUNT10K", 10000, 100, 'value', 'aktif'],
    ["DISCOUNT100K", 100000, 50, 'value', 'aktif'],
    ["DISCOUNT1JT", 1000000, 10, 'value', 'aktif'],
    ["MYTICKET", 10, 1000, 'percentage', 'aktif'],
    ["BLACKFRIDAY", 20, 500, 'percentage', 'aktif'],
];

foreach ($vouchers as $voucher) {
    $stmt = $pdo->prepare("SELECT id FROM vouchers WHERE code = ?");
    $stmt->execute([$voucher[0]]);
    if (!$stmt->fetchAll()) {
        $pdo->prepare("INSERT INTO vouchers (code, discount, quota, type, status) VALUES (?, ?, ?, ?, ?)")->execute([$voucher[0], $voucher[1], $voucher[2], $voucher[3], $voucher[4]]);
    }
}

echo "Migration & Seeder Done. \n";