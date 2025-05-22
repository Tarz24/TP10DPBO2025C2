<?php
// viewmodels/OrderViewModel.php

class OrderViewModel {
    private $model;
    public $errors = [];
    public $success_message = "";

    public function __construct($db) {
        $this->model = new OrderModel($db);
    }

    // Data binding untuk form
    public function bindData($data) {
        $this->model->id = isset($data['id']) ? $data['id'] : null;
        $this->model->customer_name = isset($data['customer_name']) ? $data['customer_name'] : null;
        $this->model->table_number = isset($data['table_number']) ? $data['table_number'] : null;
        $this->model->order_status = isset($data['order_status']) ? $data['order_status'] : 'pending';
        $this->model->total_amount = isset($data['total_amount']) ? $data['total_amount'] : 0;
    }

    // Validasi data
    public function validateData() {
        $this->errors = [];
        
        if(empty($this->model->customer_name)) {
            $this->errors[] = "Nama pelanggan harus diisi.";
        }
        
        if(!is_numeric($this->model->table_number) || $this->model->table_number <= 0) {
            $this->errors[] = "Nomor meja harus berupa angka dan lebih dari 0.";
        }
        
        return empty($this->errors);
    }

    // Mengambil semua order
    public function getAllOrders() {
        $stmt = $this->model->getAll();
        return $stmt;
    }

    // Mengambil order berdasarkan ID
    public function getOrderById($id) {
        $this->model->id = $id;
        if($this->model->getOne()) {
            return $this->model;
        }
        return null;
    }

    // Menyimpan order (create atau update)
    public function saveOrder($data) {
        $this->bindData($data);
        
        if(!$this->validateData()) {
            return false;
        }
        
        if(empty($this->model->id)) {
            // Create baru
            $orderId = $this->model->create();
            if($orderId) {
                $this->success_message = "Order berhasil ditambahkan.";
                return $orderId;
            } else {
                $this->errors[] = "Gagal menambahkan order.";
                return false;
            }
        } else {
            // Update
            if($this->model->update()) {
                $this->success_message = "Order berhasil diperbarui.";
                return $this->model->id;
            } else {
                $this->errors[] = "Gagal memperbarui order.";
                return false;
            }
        }
    }

    // Menghapus order
    public function deleteOrder($id) {
        $this->model->id = $id;
        if($this->model->delete()) {
            $this->success_message = "Order berhasil dihapus.";
            return true;
        } else {
            $this->errors[] = "Gagal menghapus order.";
            return false;
        }
    }

    // Method untuk dashboard statistics
    public function getDashboardStats() {
        $db = $this->model->getConnection();
        
        // Today's orders count
        $todayOrdersQuery = "SELECT COUNT(*) as count FROM orders WHERE DATE(created_at) = CURDATE()";
        $stmt = $db->prepare($todayOrdersQuery);
        $stmt->execute();
        $todayOrders = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Today's revenue
        $todayRevenueQuery = "SELECT SUM(total_amount) as revenue FROM orders WHERE DATE(created_at) = CURDATE() AND order_status = 'paid'";
        $stmt = $db->prepare($todayRevenueQuery);
        $stmt->execute();
        $todayRevenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0;

        // Pending orders count
        $pendingOrdersQuery = "SELECT COUNT(*) as count FROM orders WHERE order_status = 'pending'";
        $stmt = $db->prepare($pendingOrdersQuery);
        $stmt->execute();
        $pendingOrders = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Available menus count - FIXED: changed 'menus' to 'menu'
        $availableMenusQuery = "SELECT COUNT(*) as count FROM menu WHERE is_available = 1";
        $stmt = $db->prepare($availableMenusQuery);
        $stmt->execute();
        $availableMenus = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        return [
            'today_orders' => $todayOrders,
            'today_revenue' => $todayRevenue,
            'today_revenue_formatted' => 'Rp ' . number_format($todayRevenue, 0, ',', '.'),
            'pending_orders' => $pendingOrders,
            'available_menus' => $availableMenus
        ];
    }
}