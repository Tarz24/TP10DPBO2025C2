<?php
// views/dashboard.php

// Initialize ViewModels
$orderViewModel = new OrderViewModel($db);
$menuViewModel = new MenuViewModel($db);

// Get dashboard statistics
$stats = $orderViewModel->getDashboardStats();

// Get recent orders (last 5) - with proper formatting
$allOrders = $orderViewModel->getAllOrders();
$recentOrders = [];

if($allOrders) {
    $count = 0;
    foreach($allOrders as $order) {
        if($count >= 5) break;
        
        // Format order data
        $order['total_formatted'] = 'Rp ' . number_format($order['total_amount'], 0, ',', '.');
        
        // Add status badge and text
        switch($order['order_status']) {
            case 'pending':
                $order['status_badge'] = 'badge-warning';
                $order['status_text'] = 'Menunggu';
                break;
            case 'preparing':
                $order['status_badge'] = 'badge-info';
                $order['status_text'] = 'Diproses';
                break;
            case 'served':
                $order['status_badge'] = 'badge-success';
                $order['status_text'] = 'Disajikan';
                break;
            case 'paid':
                $order['status_badge'] = 'badge-secondary';
                $order['status_text'] = 'Dibayar';
                break;
            default:
                $order['status_badge'] = 'badge-light';
                $order['status_text'] = 'Unknown';
        }
        
        $recentOrders[] = $order;
        $count++;
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </h1>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Order Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $stats['today_orders']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pendapatan Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $stats['today_revenue_formatted']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Order Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $stats['pending_orders']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Menu Tersedia
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $stats['available_menus']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Order Terbaru
                    </h6>
                    <a href="index.php?page=order" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($recentOrders)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Belum ada order hari ini</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Pelanggan</th>
                                        <th>Meja</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                            <td><?php echo $order['table_number']; ?></td>
                                            <td>
                                                <span class="badge <?php echo $order['status_badge']; ?>">
                                                    <?php echo $order['status_text']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $order['total_formatted']; ?></td>
                                            <td><?php echo date('H:i', strtotime($order['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt"></i> Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="index.php?page=order&action=create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Order Baru
                        </a>
                        <a href="index.php?page=menu&action=create" class="btn btn-success">
                            <i class="fas fa-plus"></i> Tambah Menu
                        </a>
                        <a href="index.php?page=menu" class="btn btn-info">
                            <i class="fas fa-book"></i> Kelola Menu
                        </a>
                        <a href="index.php?page=order" class="btn btn-warning">
                            <i class="fas fa-receipt"></i> Kelola Order
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Summary -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie"></i> Ringkasan Status
                    </h6>
                </div>
                <div class="card-body">
                    <?php
                    // Get order status counts
                    $statusQuery = "SELECT order_status, COUNT(*) as count FROM orders GROUP BY order_status";
                    $statusStmt = $db->prepare($statusQuery);
                    $statusStmt->execute();
                    $statusCounts = array();
                    while ($row = $statusStmt->fetch(PDO::FETCH_ASSOC)) {
                        $statusCounts[$row['order_status']] = $row['count'];
                    }
                    ?>
                    
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Menunggu</span>
                            <span><?php echo $statusCounts['pending'] ?? 0; ?></span>
                        </div>
                        <div class="progress mb-2" style="height: 5px;">
                            <div class="progress-bar bg-warning" style="width: <?php echo ($statusCounts['pending'] ?? 0) * 10; ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Diproses</span>
                            <span><?php echo $statusCounts['preparing'] ?? 0; ?></span>
                        </div>
                        <div class="progress mb-2" style="height: 5px;">
                            <div class="progress-bar bg-info" style="width: <?php echo ($statusCounts['preparing'] ?? 0) * 10; ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Disajikan</span>
                            <span><?php echo $statusCounts['served'] ?? 0; ?></span>
                        </div>
                        <div class="progress mb-2" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: <?php echo ($statusCounts['served'] ?? 0) * 10; ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Dibayar</span>
                            <span><?php echo $statusCounts['paid'] ?? 0; ?></span>
                        </div>
                        <div class="progress mb-2" style="height: 5px;">
                            <div class="progress-bar bg-secondary" style="width: <?php echo ($statusCounts['paid'] ?? 0) * 10; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.badge-warning {
    background-color: #f6c23e;
    color: #1f2937;
}
.badge-info {
    background-color: #36b9cc;
    color: white;
}
.badge-success {
    background-color: #1cc88a;
    color: white;
}
.badge-secondary {
    background-color: #858796;
    color: white;
}
</style>