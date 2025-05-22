<?php
// viewmodels/MenuViewModel.php

class MenuViewModel {
    private $model;
    public $errors = [];
    public $success_message = "";

    public function __construct($db) {
        $this->model = new MenuModel($db);
    }

    // Data binding untuk form
    public function bindData($data) {
        $this->model->id = isset($data['id']) ? $data['id'] : null;
        $this->model->name = isset($data['name']) ? $data['name'] : null;
        $this->model->description = isset($data['description']) ? $data['description'] : null;
        $this->model->price = isset($data['price']) ? $data['price'] : null;
        $this->model->category = isset($data['category']) ? $data['category'] : null;
        $this->model->is_available = isset($data['is_available']) ? 1 : 0;
    }

    // Validasi data
    public function validateData() {
        $this->errors = [];
        
        if(empty($this->model->name)) {
            $this->errors[] = "Nama menu harus diisi.";
        }
        
        if(!is_numeric($this->model->price) || $this->model->price <= 0) {
            $this->errors[] = "Harga harus berupa angka dan lebih dari 0.";
        }
        
        return empty($this->errors);
    }

    // Mengambil semua menu
    public function getAllMenus() {
        $stmt = $this->model->getAll();
        return $stmt;
    }

    // Mengambil menu berdasarkan ID
    public function getMenuById($id) {
        $this->model->id = $id;
        if($this->model->getOne()) {
            return $this->model;
        }
        return null;
    }

    // Menyimpan menu (create atau update)
    public function saveMenu($data) {
        $this->bindData($data);
        
        if(!$this->validateData()) {
            return false;
        }
        
        if(empty($this->model->id)) {
            // Create baru
            if($this->model->create()) {
                $this->success_message = "Menu berhasil ditambahkan.";
                return true;
            } else {
                $this->errors[] = "Gagal menambahkan menu.";
                return false;
            }
        } else {
            // Update
            if($this->model->update()) {
                $this->success_message = "Menu berhasil diperbarui.";
                return true;
            } else {
                $this->errors[] = "Gagal memperbarui menu.";
                return false;
            }
        }
    }

    // Menghapus menu
    public function deleteMenu($id) {
        $this->model->id = $id;
        if($this->model->delete()) {
            $this->success_message = "Menu berhasil dihapus.";
            return true;
        } else {
            $this->errors[] = "Gagal menghapus menu.";
            return false;
        }
    }
}