<?php
require_once 'viewmodel/OrderItemViewModel.php';
$viewModel = new OrderItemViewModel();
$item = $viewModel->getOrderItemById($_GET['id']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item Pesanan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Edit Item Pesanan</h1>
    
    <form action="?page=order_item&action=update" method="POST">
        <input type="hidden" name="id" value="<?= $item->id ?>">

        <label>ID Order:</label>
        <select name="order_id" required>
            <?php
            // Ambil daftar order yang tersedia
            require_once 'viewmodel/OrderViewModel.php';
            $orderViewModel = new OrderViewModel();
            $orders = $orderViewModel->getAllOrders();
            
            foreach ($orders as $order):
                $selected = ($order->id == $item->order_id) ? 'selected' : '';
            ?>
            <option value="<?= $order->id ?>" <?= $selected ?>>Order #<?= $order->id ?> - <?= $order->nama_pelanggan ?> (<?= date('d/m/Y', strtotime($order->tanggal)) ?>)</option>
            <?php endforeach; ?>
        </select>

        <label>Menu:</label>
        <select name="menu_id" id="menu_select" required>
            <?php
            // Ambil daftar menu yang tersedia
            require_once 'viewmodel/MenuViewModel.php';
            $menuViewModel = new MenuViewModel();
            $menus = $menuViewModel->getAllMenus();
            
            foreach ($menus as $menu):
                $selected = ($menu->nama == $item->nama_menu) ? 'selected' : '';
            ?>
            <option value="<?= $menu->id ?>" data-nama="<?= $menu->nama ?>" data-harga="<?= $menu->harga ?>" <?= $selected ?>><?= $menu->nama ?> - Rp<?= number_format($menu->harga, 0, ',', '.') ?></option>
            <?php endforeach; ?>
        </select>

        <label>Nama Menu:</label>
        <input type="text" name="nama_menu" id="nama_menu" value="<?= $item->nama_menu ?>" readonly>

        <label>Jumlah:</label>
        <input type="number" name="jumlah" id="jumlah" value="<?= $item->jumlah ?>" min="1" required>

        <label>Harga Satuan:</label>
        <input type="number" name="harga_satuan" id="harga_satuan" value="<?= $item->harga_satuan ?>" readonly>

        <div id="total_display" style="margin: 10px 0; padding: 10px; background-color: #f8f9fa; border-radius: 4px;">
            <strong>Total: Rp<span id="total_amount"><?= number_format($item->jumlah * $item->harga_satuan, 0, ',', '.') ?></span></strong>
        </div>

        <button type="submit">Update</button>
        <a href="?page=order_item">Kembali</a>
    </form>

    <script src="assets/js/script.js"></script>
    <script>
        // Auto-fill menu details when menu is selected
        document.getElementById('menu_select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const namaMenu = selectedOption.getAttribute('data-nama');
            const harga = selectedOption.getAttribute('data-harga');
            
            document.getElementById('nama_menu').value = namaMenu || '';
            document.getElementById('harga_satuan').value = harga || '';
            
            calculateTotal();
        });

        // Calculate total when quantity changes
        document.getElementById('jumlah').addEventListener('input', calculateTotal);

        function calculateTotal() {
            const jumlah = parseInt(document.getElementById('jumlah').value) || 0;
            const harga = parseInt(document.getElementById('harga_satuan').value) || 0;
            const total = jumlah * harga;
            
            document.getElementById('total_amount').textContent = total.toLocaleString('id-ID');
        }
    </script>
</body>
</html>