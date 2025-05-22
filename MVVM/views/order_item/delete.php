<?php
require_once 'viewmodel/OrderItemViewModel.php';
$viewModel = new OrderItemViewModel();
$item = $viewModel->getOrderItemById($_GET['id']);

$total = $item->jumlah * $item->harga_satuan;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Item Pesanan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Hapus Item Pesanan</h1>
    
    <div style="background-color: white; padding: 20px; border-radius: 6px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 500px;">
        <p>Apakah kamu yakin ingin menghapus item berikut?</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 4px; margin: 15px 0;">
            <strong>Detail Item:</strong><br>
            <strong>Menu:</strong> <?= $item->nama_menu ?><br>
            <strong>Order ID:</strong> #<?= $item->order_id ?><br>
            <strong>Jumlah:</strong> <?= $item->jumlah ?><br>
            <strong>Harga Satuan:</strong> Rp<?= number_format($item->harga_satuan, 0, ',', '.') ?><br>
            <strong>Total:</strong> Rp<?= number_format($total, 0, ',', '.') ?>
        </div>
        
        <form action="?page=order_item&action=destroy" method="POST" style="display: inline;">
            <input type="hidden" name="id" value="<?= $item->id ?>">
            <button type="submit" style="background-color: #dc3545; margin-right: 10px;">Ya, hapus</button>
        </form>
        
        <a href="?page=order_item" style="padding: 10px 16px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Batal</a>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>