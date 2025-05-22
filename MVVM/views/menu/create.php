<?php
// views/menu/create.php

// Initialize ViewModel
$menuViewModel = new MenuViewModel($db);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($menuViewModel->saveMenu($_POST)) {
        header('Location: index.php?page=menu&success=1');
        exit;
    } else {
        $errors = $menuViewModel->errors;
    }
}

// Default values
$menu = (object) [
    'id' => '',
    'name' => '',
    'description' => '',
    'price' => '',
    'category' => '',
    'is_available' => 1
];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="fas fa-plus"></i> Tambah Menu Baru
                </h1>
                <a href="index.php?page=menu" class="btn btn-secondary">
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
                <i class="fas fa-edit"></i> Form Tambah Menu
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($menu->name); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <select class="form-control" id="category" name="category">
                                <option value="">Pilih Kategori</option>
                                <option value="Makanan" <?php echo ($menu->category == 'Makanan') ? 'selected' : ''; ?>>Makanan</option>
                                <option value="Minuman" <?php echo ($menu->category == 'Minuman') ? 'selected' : ''; ?>>Minuman</option>
                                <option value="Dessert" <?php echo ($menu->category == 'Dessert') ? 'selected' : ''; ?>>Dessert</option>
                                <option value="Appetizer" <?php echo ($menu->category == 'Appetizer') ? 'selected' : ''; ?>>Appetizer</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($menu->description); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="price" name="price" 
                                       value="<?php echo $menu->price; ?>" min="0" step="1000" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status Ketersediaan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" 
                                       <?php echo $menu->is_available ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_available">
                                    Menu Tersedia
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="index.php?page=menu" class="btn btn-secondary me-md-2">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>