<?php
require_once 'viewmodel/MenuViewModel.php';
$viewModel = new MenuViewModel();
$menu = $viewModel->getMenuById($_GET['id']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Menu</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Hapus Menu</h1>
    
    <div style="background-color: white; padding: 20px; border-radius: 6px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 400px;">
        <p>Apakah kamu yakin ingin menghapus menu <strong><?= $menu->nama ?></strong>?</p>
        
        <form action="?page=menu&action=destroy" method="POST" style="display: inline;">
            <input type="hidden" name="id" value="<?= $menu->id ?>">
            <button type="submit" style="background-color: #dc3545; margin-right: 10px;">Ya, hapus</button>
        </form>
        
        <a href="?page=menu" style="padding: 10px 16px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Batal</a>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>