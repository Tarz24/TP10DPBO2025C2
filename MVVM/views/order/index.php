<?php
// views/order/index.php

// Initialize ViewModel
$orderViewModel = new OrderViewModel($db);

// Handle delete action
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    if ($orderViewModel->deleteOrder($deleteId)) {
        $success_message = $orderViewModel->success_message;
    } else {
        $errors = $orderViewModel->errors;
    }
}

// Handle status update
if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['new_status'];
    
    $orderData = [
        'id' => $orderId,
        'order_status' => $newStatus
    ];
    
    $order = $orderViewModel->getOrderById($orderId);
    if ($order) {
        $orderData['customer_name'] = $order->customer_name;
        $orderData['table_number'] = $order->table_number;
        $orderData['total_amount'] = $order->total_amount;
        
        if ($orderViewModel->saveOrder($orderData)) {
            $success_message = "Status order berhasil diperbarui.";
        } else {
            $errors = $orderViewModel->errors;
        }
    }
}

// Get all orders
$orders = $orderViewModel->getAllOrders();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="fas fa-receipt"></i> Kelola Order
                </h1>
                <a href="index.php?page=order&action=create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Order Baru
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
            <?php foreach ($errors as $error): ?>
                <div><?php echo $error; ?></div>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Orders Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Daftar Order
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($orders)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted">Belum ada order</p>
                    <a href="index.php?page=order&action=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Order Pertama
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Meja</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                    <td><?php echo $order['table_number']; ?></td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <select name="new_status" class="form-select form-select-sm" 
                                                    onchange="this.form.submit()" style="width: auto; display: inline-block;">
                                                <option value="pending" <?php echo ($order['order_status'] == 'pending') ? 'selected' : ''; ?>>Menunggu</option>
                                                <option value="preparing" <?php echo ($order['order_status'] == 'preparing') ? 'selected' : ''; ?>>Diproses</option>
                                                <option value="served" <?php echo ($order['order_status'] == 'served') ? 'selected' : ''; ?>>Disajikan</option>
                                                <option value="paid" <?php echo ($order['order_status'] == 'paid') ? 'selected' : ''; ?>>Dibayar</option>
                                            </select>
                                            <button type="submit" name="update_status" class="btn btn-sm btn-outline-primary ms-1">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td><?php echo formatRupiah($order['total_amount']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="index.php?page=order&action=edit&id=<?php echo $order['id']; ?>" 
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="index.php?page=order&delete=<?php echo $order['id']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus order ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.text-gray-300 {
    color: #dddfeb !important;
}
</style>