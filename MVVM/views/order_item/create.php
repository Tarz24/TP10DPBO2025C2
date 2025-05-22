<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Item Pesanan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Tambah Item Pesanan</h1>
    
    <form action="?page=order_item&action=store" method="POST">
        <label>ID Order:</label>
        <select name="order_id" required>
            <option value="">Pilih Order</option>
            <?php
            // Ambil daftar order yang tersedia
            require_once 'viewmodel/OrderViewModel.php';
            $orderViewModel = new OrderViewModel();
            $orders = $orderViewModel->getAllOrders();
            
            foreach ($orders as $order):
            ?>
            <option value="<?= $order->id ?>">Order #<?= $order->id ?> - <?= $order->nama_pelanggan ?> (<?= date('d/m/Y', strtotime($order->tanggal)) ?>)</option>
            <?php endforeach; ?>
        </select>

        <label>Menu:</label>
        <select name="menu_id" id="menu_select" required>
            <option value="">Pilih Menu</option>
            <?php
            // Ambil daftar menu yang tersedia
            require_once 'viewmodel/MenuViewModel.php';
            $menuViewModel = new MenuViewModel();
            $menus = $menuViewModel->getAllMenus();
            
            foreach ($menus as $menu):
            ?>
            <option value="<?= $menu->id ?>" data-nama="<?= $menu->nama ?>" data-harga="<?= $menu->harga ?>"><?= $menu->nama ?> - Rp<?= number_format($menu->harga, 0, ',', '.') ?></option>
            <?php endforeach; ?>
        </select>

        <label>Nama Menu:</label>
        <input type="text" name="nama_menu" id="nama_menu" readonly>

        <label>Jumlah:</label>
        <input type="number" name="jumlah" id="jumlah" min="1" required>

        <label>Harga Satuan:</label>
        <input type="number" name="harga_satuan" id="harga_satuan" readonly>

        <div id="total_display" style="margin: 10px 0; padding: 10px; background-color: #f8f9fa; border-radius: 4px; display: none;">
            <strong>Total: Rp<span id="total_amount">0</span></strong>
        </div>

        <button type="submit">Simpan</button>
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
            document.getElementById('total_display').style.display = total > 0 ? 'block' : 'none';
        }
    </script>
</body>
</html>