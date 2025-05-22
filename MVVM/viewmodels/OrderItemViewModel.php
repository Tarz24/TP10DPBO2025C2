<?php
// viewmodels/OrderItemViewModel.php

class OrderItemViewModel {
    private $model;
    public $errors = [];
    public $success_message = "";

    public function __construct($db) {
        $this->model = new OrderItemModel($db);
    }

    // Data binding untuk form
    public function bindData($data) {
        $this->model->id = isset($data['id']) ? $data['id'] : null;
        $this->model->order_id = isset($data['order_id']) ? $data['order_id'] : null;
        $this->model->menu_id = isset($data['menu_id']) ? $data['menu_id'] : null;
        $this->model->quantity = isset($data['quantity']) ? $data['quantity'] : 1;
        
        // Ambil harga menu
        if(!empty($this->model->menu_id)) {
            $this->model->item_price = $this->model->getMenuPrice();
        }
        
        // Hitung subtotal
        if(!empty($this->model->quantity) && !empty($this->model->item_price)) {
            $this->model->subtotal = $this->model->quantity * $this->model->item_price;
        }
    }

    // Validasi data
    public function validateData() {
        $this->errors = [];
        
        if(empty($this->model->order_id)) {
            $this->errors[] = "ID Order harus diisi.";
        }
        
        if(empty($this->model->menu_id)) {
            $this->errors[] = "Menu harus dipilih.";
        }
        
        if(!is_numeric($this->model->quantity) || $this->model->quantity <= 0) {
            $this->errors[] = "Jumlah harus berupa angka dan lebih dari 0.";
        }
        
        return empty($this->errors);
    }

    // Mengambil semua item berdasarkan order_id
    public function getItemsByOrderId($order_id) {
        $this->model->order_id = $order_id;
        $stmt = $this->model->getAllByOrderId();
        return $stmt;
    }

    // Mengambil item berdasarkan ID
    public function getItemById($id) {
        $this->model->id = $id;
        if($this->model->getOne()) {
            return $this->model;
        }
        return null;
    }

    // Menyimpan item (create atau update)
    public function saveItem($data) {
        $this->bindData($data);
        
        if(!$this->validateData()) {
            return false;
        }
        
        if(empty($this->model->id)) {
            // Create baru
            if($this->model->create()) {
                $this->success_message = "Item order berhasil ditambahkan.";
                return true;
            } else {
                $this->errors[] = "Gagal menambahkan item order.";
                return false;
            }
        } else {
            // Update
            if($this->model->update()) {
                $this->success_message = "Item order berhasil diperbarui.";
                return true;
            } else {
                $this->errors[] = "Gagal memperbarui item order.";
                return false;
            }
        }
    }

    // Menghapus item
    public function deleteItem($id) {
        $this->model->id = $id;
        if($this->model->delete()) {
            $this->success_message = "Item order berhasil dihapus.";
            return true;
        } else {
            $this->errors[] = "Gagal menghapus item order.";
            return false;
        }
    }
}