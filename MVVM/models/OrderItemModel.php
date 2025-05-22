<?php
// models/OrderItemModel.php

class OrderItemModel {
    private $conn;
    private $table_name = "order_items";

    // Properties
    public $id;
    public $order_id;
    public $menu_id;
    public $quantity;
    public $item_price;
    public $subtotal;
    public $created_at;

    // Properties tambahan untuk join dengan tabel menu
    public $menu_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Mengambil semua item berdasarkan order_id
    public function getAllByOrderId() {
        $query = "SELECT oi.*, m.name as menu_name 
                FROM " . $this->table_name . " oi
                LEFT JOIN menu m ON oi.menu_id = m.id
                WHERE oi.order_id = :order_id
                ORDER BY oi.id ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mengambil satu item berdasarkan ID
    public function getOne() {
        $query = "SELECT oi.*, m.name as menu_name
                FROM " . $this->table_name . " oi
                LEFT JOIN menu m ON oi.menu_id = m.id
                WHERE oi.id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch();
        if($row) {
            $this->order_id = $row['order_id'];
            $this->menu_id = $row['menu_id'];
            $this->quantity = $row['quantity'];
            $this->item_price = $row['item_price'];
            $this->subtotal = $row['subtotal'];
            $this->created_at = $row['created_at'];
            $this->menu_name = $row['menu_name'];
            return true;
        }
        return false;
    }

    // Membuat item order baru
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET order_id = :order_id, 
                    menu_id = :menu_id, 
                    quantity = :quantity, 
                    item_price = :item_price, 
                    subtotal = :subtotal";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitasi input
        $this->order_id = intval(sanitize($this->order_id));
        $this->menu_id = intval(sanitize($this->menu_id));
        $this->quantity = intval(sanitize($this->quantity));
        $this->item_price = floatval(sanitize($this->item_price));
        $this->subtotal = $this->quantity * $this->item_price;
        
        // Binding parameter
        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->bindParam(":menu_id", $this->menu_id);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":item_price", $this->item_price);
        $stmt->bindParam(":subtotal", $this->subtotal);
        
        try {
            if($stmt->execute()) {
                // Update total order
                $orderModel = new OrderModel($this->conn);
                $orderModel->id = $this->order_id;
                $orderModel->updateTotal();
                
                return true;
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }

    // Update item order
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET menu_id = :menu_id, 
                    quantity = :quantity, 
                    item_price = :item_price, 
                    subtotal = :subtotal 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitasi input
        $this->menu_id = intval(sanitize($this->menu_id));
        $this->quantity = intval(sanitize($this->quantity));
        $this->item_price = floatval(sanitize($this->item_price));
        $this->subtotal = $this->quantity * $this->item_price;
        $this->id = intval(sanitize($this->id));
        
        // Binding parameter
        $stmt->bindParam(":menu_id", $this->menu_id);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":item_price", $this->item_price);
        $stmt->bindParam(":subtotal", $this->subtotal);
        $stmt->bindParam(":id", $this->id);
        
        try {
            if($stmt->execute()) {
                // Update total order
                $orderModel = new OrderModel($this->conn);
                $orderModel->id = $this->order_id;
                $orderModel->updateTotal();
                
                return true;
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }

    // Hapus item order
    public function delete() {
        // Get order_id before deleting
        $this->getOne();
        $order_id = $this->order_id;
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        // Sanitasi input
        $this->id = intval(sanitize($this->id));
        
        // Binding parameter
        $stmt->bindParam(":id", $this->id);
        
        try {
            if($stmt->execute()) {
                // Update total order
                $orderModel = new OrderModel($this->conn);
                $orderModel->id = $order_id;
                $orderModel->updateTotal();
                
                return true;
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }

    // Mendapatkan harga menu
    public function getMenuPrice() {
        $query = "SELECT price FROM menu WHERE id = :menu_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":menu_id", $this->menu_id);
        $stmt->execute();
        
        $row = $stmt->fetch();
        if($row) {
            return $row['price'];
        }
        return 0;
    }
}