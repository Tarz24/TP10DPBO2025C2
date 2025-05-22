<?php
// views/menu/index.php

// Initialize ViewModel
$menuViewModel = new MenuViewModel($db);

// Handle delete action
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    if ($menuViewModel->deleteMenu($deleteId)) {
        $success_message = $menuViewModel->success_message;
    } else {
        $errors = $menuViewModel->errors;
    }
}

// Get all menus
$menus = $menuViewModel->getAllMenus();
$menuArray = [];
if ($menus) {
    foreach ($menus as $menu) {
        $menuArray[] = $menu;
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="fas fa-book"></i> Kelola Menu
                </h1>
                <a href="index.php?page=menu&action=create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Menu
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

    <!-- Menu Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Daftar Menu
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($menuArray)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted">Belum ada menu yang ditambahkan</p>
                    <a href="index.php?page=menu&action=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Menu Pertama
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama Menu</th>
                                <th>Deskripsi</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menuArray as $menu): ?>
                                <tr>
                                    <td><?php echo $menu['id']; ?></td>
                                    <td><?php echo htmlspecialchars($menu['name']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($menu['description'], 0, 50)); ?><?php echo strlen($menu['description']) > 50 ? '...' : ''; ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo htmlspecialchars($menu['category']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo 'Rp ' . number_format($menu['price'], 0, ',', '.'); ?></td>
                                    <td>
                                        <?php if ($menu['is_available']): ?>
                                            <span class="badge bg-success">Tersedia</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Tidak Tersedia</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="index.php?page=menu&action=edit&id=<?php echo $menu['id']; ?>" 
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="index.php?page=menu&delete=<?php echo $menu['id']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
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