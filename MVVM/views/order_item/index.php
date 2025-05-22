<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Item Pesanan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Daftar Item Pesanan</h1>
    <a href="?page=order_item&action=create">Tambah Item</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Order</th>
                <th>Nama Menu</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once 'viewmodel/OrderItemViewModel.php';
            $viewModel = new OrderItemViewModel();
            $items = $viewModel->getAllOrderItems();

            foreach ($items as $item):
                $total = $item->jumlah * $item->harga_satuan;
            ?>
            <tr>
                <td><?= $item->id ?></td>
                <td><?= $item->order_id ?></td>
                <td><?= $item->nama_menu ?></td>
                <td><?= $item->jumlah ?></td>
                <td>Rp<?= number_format($item->harga_satuan, 0, ',', '.') ?></td>
                <td><strong>Rp<?= number_format($total, 0, ',', '.') ?></strong></td>
                <td>
                    <a href="?page=order_item&action=edit&id=<?= $item->id ?>">Edit</a>
                    <a href="?page=order_item&action=delete&id=<?= $item->id ?>" onclick="return confirm('Yakin ingin menghapus item ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script src="assets/js/script.js"></script>
</body>
</html>