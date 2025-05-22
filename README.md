**Saya Muhammad Akhtar Rizki Ramadha dengan NIM 2304742 mengerjakan soal Tugas Praktikum 10 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.**

# Restaurant Management System

Sistem manajemen restoran berbasis PHP dengan arsitektur MVVM (Model-View-ViewModel) yang memungkinkan pengelolaan menu, pesanan, dan item pesanan secara efisien.

## 📋 Fitur Utama

- **Manajemen Menu**: Tambah, edit, hapus, dan kelola ketersediaan menu
- **Manajemen Pesanan**: Buat pesanan baru, update status, dan kelola pesanan pelanggan
- **Manajemen Item Pesanan**: Kelola item dalam setiap pesanan dengan kalkulasi otomatis
- **Dashboard Statistik**: Laporan harian pesanan, pendapatan, dan status pesanan
- **Validasi Data**: Validasi input yang komprehensif pada setiap operasi

## 🏗️ Arsitektur

Sistem ini menggunakan arsitektur **MVVM (Model-View-ViewModel)**:

```
├── models/
│   ├── MenuModel.php          # Model untuk data menu
│   ├── OrderModel.php         # Model untuk data pesanan
│   └── OrderItemModel.php     # Model untuk item pesanan
├── viewmodels/
│   ├── MenuViewModel.php      # ViewModel untuk logika menu
│   ├── OrderViewModel.php     # ViewModel untuk logika pesanan
│   └── OrderItemViewModel.php # ViewModel untuk logika item pesanan
└── database.sql              # Struktur database dan data sample
```

## 🗄️ Struktur Database

### Tabel `menu`
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- name (VARCHAR(100), NOT NULL)
- description (TEXT)
- price (DECIMAL(10,2), NOT NULL)
- category (VARCHAR(50))
- is_available (BOOLEAN, DEFAULT TRUE)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
```

### Tabel `orders`
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- customer_name (VARCHAR(100), NOT NULL)
- table_number (INT)
- order_status (ENUM: 'pending', 'preparing', 'served', 'paid')
- total_amount (DECIMAL(10,2), DEFAULT 0.00)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
```

### Tabel `order_items`
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- order_id (INT, FOREIGN KEY)
- menu_id (INT, FOREIGN KEY)
- quantity (INT, NOT NULL, DEFAULT 1)
- item_price (DECIMAL(10,2), NOT NULL)
- subtotal (DECIMAL(10,2), NOT NULL)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
```

## 💻 Penggunaan

### 1. Manajemen Menu

```php
// Inisialisasi
$menuViewModel = new MenuViewModel($pdo);

// Menambah menu baru
$data = [
    'name' => 'Nasi Gudeg',
    'description' => 'Nasi gudeg khas Jogja',
    'price' => 28000,
    'category' => 'Makanan Utama',
    'is_available' => true
];

if($menuViewModel->saveMenu($data)) {
    echo $menuViewModel->success_message;
} else {
    foreach($menuViewModel->errors as $error) {
        echo $error . "<br>";
    }
}

// Mengambil semua menu
$menus = $menuViewModel->getAllMenus();

// Mengambil menu berdasarkan ID
$menu = $menuViewModel->getMenuById(1);
```

### 2. Manajemen Pesanan

```php
// Inisialisasi
$orderViewModel = new OrderViewModel($pdo);

// Membuat pesanan baru
$data = [
    'customer_name' => 'John Doe',
    'table_number' => 5,
    'order_status' => 'pending'
];

$orderId = $orderViewModel->saveOrder($data);

// Mengambil statistik dashboard
$stats = $orderViewModel->getDashboardStats();
echo "Pesanan hari ini: " . $stats['today_orders'];
echo "Pendapatan hari ini: " . $stats['today_revenue_formatted'];
```

### 3. Manajemen Item Pesanan

```php
// Inisialisasi
$orderItemViewModel = new OrderItemViewModel($pdo);

// Menambah item ke pesanan
$data = [
    'order_id' => $orderId,
    'menu_id' => 1,
    'quantity' => 2
];

if($orderItemViewModel->saveItem($data)) {
    echo $orderItemViewModel->success_message;
}

// Mengambil semua item dalam pesanan
$items = $orderItemViewModel->getItemsByOrderId($orderId);
```

## 🔧 Fitur Khusus

### Auto-calculation
- **Subtotal**: Otomatis dihitung dari quantity × item_price
- **Total Order**: Otomatis diupdate saat ada perubahan item pesanan

### Data Validation
- Validasi input pada semua operasi CRUD
- Sanitasi data untuk mencegah XSS
- Error handling yang komprehensif

### Dashboard Statistics
- Jumlah pesanan hari ini
- Total pendapatan hari ini
- Jumlah pesanan pending
- Jumlah menu yang tersedia

## 📊 Status Pesanan

Sistem mendukung 4 status pesanan:
- **pending**: Pesanan baru dibuat
- **preparing**: Pesanan sedang diproses
- **served**: Pesanan sudah disajikan
- **paid**: Pesanan sudah dibayar
