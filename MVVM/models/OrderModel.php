<?php
// models/OrderModel.php

class OrderModel {
    private $conn;
    private $table_name = "orders";

    // Properties
    public $id;
    public $customer_name;
    public $table_number;
    public $order_status;
    public $total_amount;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Mengambil semua order
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mengambil satu order berdasarkan ID
    public function getOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch();
        if($row) {
            $this->customer_name = $row['customer_name'];
            $this->table_number = $row['table_number'];
            $this->order_status = $row['order_status'];
            $this->total_amount = $row['total_amount'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Membuat order baru
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET customer_name = :customer_name, 
                    table_number = :table_number, 
                    order_status = :order_status, 
                    total_amount = :total_amount";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitasi input
        $this->customer_name = sanitize($this->customer_name);
        $this->table_number = intval(sanitize($this->table_number));
        $this->order_status = sanitize($this->order_status);
        $this->total_amount = floatval(sanitize($this->total_amount));
        
        // Binding parameter
        $stmt->bindParam(":customer_name", $this->customer_name);
        $stmt->bindParam(":table_number", $this->table_number);
        $stmt->bindParam(":order_status", $this->order_status);
        $stmt->bindParam(":total_amount", $this->total_amount);
        
        try {
            if($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }

    // Update order
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET customer_name = :customer_name, 
                    table_number = :table_number, 
                    order_status = :order_status, 
                    total_amount = :total_amount 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitasi input
        $this->customer_name = sanitize($this->customer_name);
        $this->table_number = intval(sanitize($this->table_number));
        $this->order_status = sanitize($this->order_status);
        $this->total_amount = floatval(sanitize($this->total_amount));
        $this->id = intval(sanitize($this->id));
        
        // Binding parameter
        $stmt->bindParam(":customer_name", $this->customer_name);
        $stmt->bindParam(":table_number", $this->table_number);
        $stmt->bindParam(":order_status", $this->order_status);
        $stmt->bindParam(":total_amount", $this->total_amount);
        $stmt->bindParam(":id", $this->id);
        
        try {
            if($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }

    // Hapus order
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        // Sanitasi input
        $this->id = intval(sanitize($this->id));
        
        // Binding parameter
        $stmt->bindParam(":id", $this->id);
        
        try {
            if($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }

    // Update total order
    public function updateTotal() {
        $query = "UPDATE " . $this->table_name . " 
                SET total_amount = (
                    SELECT COALESCE(SUM(subtotal), 0) FROM order_items WHERE order_id = :id
                ) 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        try {
            if($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }

    // Get connection for dashboard stats
    public function getConnection() {
        return $this->conn;
    }
}