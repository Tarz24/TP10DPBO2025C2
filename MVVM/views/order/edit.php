<?php
// views/order/edit.php

// Initialize ViewModels
$orderViewModel = new OrderViewModel($db);
$orderItemViewModel = new OrderItemViewModel($db);
$menuViewModel = new MenuViewModel($db);

// Get order ID from URL
$orderId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$orderId) {
    header('Location: index.php?page=order');
    exit;
}

// Get existing order data
$order = $orderViewModel->getOrderById($orderId);

if (!$order) {
    header('Location: index.php?page=order&error=Order tidak ditemukan');
    exit;
}

// Handle form submission for order update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    $_POST['id'] = $orderId;
    if ($orderViewModel->saveOrder($_POST)) {
        $success_message = "Order berhasil diperbarui.";
        // Refresh order data
        $order = $orderViewModel->getOrderById($orderId);
    } else {
        $errors = $orderViewModel->errors;
    }
}

// Handle add item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $_POST['order_id'] = $orderId;
    if ($orderItemViewModel->saveItem($_POST)) {
        $success_message = "Item berhasil ditambahkan.";
    } else {
        $item_errors = $orderItemViewModel->errors;
    }
}

// Handle delete item
if (isset($_GET['delete_item'])) {
    $itemId = $_GET['delete_item'];
    if ($orderItemViewModel->deleteItem($itemId)) {
        $success_message = "Item berhasil dihapus.";
    } else {
        $item_errors = $orderItemViewModel->errors;
    }
}

// Get order items
$orderItems = $orderItemViewModel->getItemsByOrderId($orderId);

// Get available menus
$menus = $menuViewModel->getAllMenus();

// Calculate total from items
$total = 0;
foreach ($orderItems as $item) {
    $total += $item['subtotal'];
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="fas fa-edit"></i> Edit Order #<?php echo $order->id; ?>
                </h1>
                <a href="index.php?page=order" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Error Order:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($item_errors) && !empty($item_errors)): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Error Item:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($item_errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Informasi Order
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                   value="<?php echo htmlspecialchars($order->customer_name); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="table_number" class="form-label">Nomor Meja <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="table_number" name="table_number" 
                                   value="<?php echo $order->table_number; ?>" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="order_status" class="form-label">Status Order</label>
                            <select class="form-control" id="order_status" name="order_status">
                                <option value="pending" <?php echo ($order->order_status == 'pending') ? 'selected' : ''; ?>>Menunggu</option>
                                <option value="preparing" <?php echo ($order->order_status == 'preparing') ? 'selected' : ''; ?>>Diproses</option>
                                <option value="served" <?php echo ($order->order_status == 'served') ? 'selected' : ''; ?>>Disajikan</option>
                                <option value="paid" <?php echo ($order->order_status == 'paid') ? 'selected' : ''; ?>>Dibayar</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="total_amount" name="total_amount" 
                                       value="<?php echo $total > 0 ? $total : $order->total_amount; ?>" min="0" step="1000">
                            </div>
                            <div class="form-text">Total dari items: Rp <?php echo number_format($total, 0, ',', '.'); ?></div>
                        </div>

                        <button type="submit" name="update_order" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Order
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Item -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-plus"></i> Tambah Item
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="menu_id" class="form-label">Pilih Menu <span class="text-danger">*</span></label>
                            <select class="form-control" id="menu_id" name="menu_id" required>
                                <option value="">-- Pilih Menu --</option>
                                <?php foreach ($menus as $menu): ?>
                                    <?php if ($menu['is_available']): ?>
                                        <option value="<?php echo $menu['id']; ?>">
                                            <?php echo htmlspecialchars($menu['name']); ?> - 
                                            Rp <?php echo number_format($menu['price'], 0, ',', '.'); ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   value="1" min="1" required>
                        </div>

                        <button type="submit" name="add_item" class="btn btn-success">
                            <i class="fas fa-plus"></i> Tambah Item
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-shopping-cart"></i> Item Order
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($orderItems)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Belum ada item dalam order ini</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Menu</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderItems as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['menu_name']); ?></td>
                                            <td><?php echo 'Rp ' . number_format($item['item_price'], 0, ',', '.'); ?></td>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td><?php echo 'Rp ' . number_format($item['subtotal'], 0, ',', '.'); ?></td>
                                            <td>
                                                <a href="index.php?page=order&action=edit&id=<?php echo $orderId; ?>&delete_item=<?php echo $item['id']; ?>" 
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Hapus item ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-success">
                                        <th colspan="3">Total</th>
                                        <th><?php echo 'Rp ' . number_format($total, 0, ',', '.'); ?></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-gray-300 {
    color: #dddfeb !important;
}
</style>