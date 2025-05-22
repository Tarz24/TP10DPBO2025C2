<?php
// views/order/create.php

// Initialize ViewModels
$orderViewModel = new OrderViewModel($db);
$menuViewModel = new MenuViewModel($db);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_order'])) {
        $orderId = $orderViewModel->saveOrder($_POST);
        if ($orderId) {
            header('Location: index.php?page=order&action=edit&id=' . $orderId . '&success=Order berhasil dibuat');
            exit;
        } else {
            $errors = $orderViewModel->errors;
        }
    }
}

// Get all available menus
$menus = $menuViewModel->getAllMenus();

// Default values
$order = (object) [
    'id' => '',
    'customer_name' => '',
    'table_number' => '',
    'order_status' => 'pending',
    'total_amount' => 0
];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="fas fa-plus"></i> Order Baru
                </h1>
                <a href="index.php?page=order" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-edit"></i> Form Order Baru
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                   value="<?php echo htmlspecialchars($order->customer_name); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="table_number" class="form-label">Nomor Meja <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="table_number" name="table_number" 
                                   value="<?php echo $order->table_number; ?>" min="1" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="order_status" class="form-label">Status Order</label>
                            <select class="form-control" id="order_status" name="order_status">
                                <option value="pending" <?php echo ($order->order_status == 'pending') ? 'selected' : ''; ?>>Menunggu</option>
                                <option value="preparing" <?php echo ($order->order_status == 'preparing') ? 'selected' : ''; ?>>Diproses</option>
                                <option value="served" <?php echo ($order->order_status == 'served') ? 'selected' : ''; ?>>Disajikan</option>
                                <option value="paid" <?php echo ($order->order_status == 'paid') ? 'selected' : ''; ?>>Dibayar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="total_amount" name="total_amount" 
                                       value="<?php echo $order->total_amount; ?>" min="0" step="1000">
                            </div>
                            <div class="form-text">Kosongkan jika akan diisi otomatis dari item order</div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Info:</strong> Setelah order dibuat, Anda dapat menambahkan item menu pada halaman edit order.
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="index.php?page=order" class="btn btn-secondary me-md-2">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="create_order" class="btn btn-primary">
                        <i class="fas fa-save"></i> Buat Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Available Menus Reference -->
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-book"></i> Menu Tersedia
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($menus)): ?>
                <div class="text-center py-3">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                    <p class="text-muted">Tidak ada menu tersedia</p>
                    <a href="index.php?page=menu&action=create" class="btn btn-warning btn-sm">
                        <i class="fas fa-plus"></i> Tambah Menu
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($menus as $menu): ?>
                        <?php if ($menu['is_available']): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card border-success">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-1"><?php echo htmlspecialchars($menu['name']); ?></h6>
                                        <p class="card-text small text-muted mb-1"><?php echo htmlspecialchars($menu['description']); ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-info"><?php echo htmlspecialchars($menu['category']); ?></span>
                                            <strong class="text-success"><?php echo 'Rp ' . number_format($menu['price'], 0, ',', '.'); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>