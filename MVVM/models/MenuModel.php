<?php
// models/MenuModel.php

class MenuModel {
    private $conn;
    private $table_name = "menu";

    // Properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $category;
    public $is_available;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Mengambil semua menu
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mengambil satu menu berdasarkan ID
    public function getOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch();
        if($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->category = $row['category'];
            $this->is_available = $row['is_available'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Membuat menu baru
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET name = :name, 
                    description = :description, 
                    price = :price, 
                    category = :category, 
                    is_available = :is_available";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitasi input
        $this->name = sanitize($this->name);
        $this->description = sanitize($this->description);
        $this->price = floatval(sanitize($this->price));
        $this->category = sanitize($this->category);
        $this->is_available = $this->is_available ? 1 : 0;
        
        // Binding parameter
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":is_available", $this->is_available);
        
        try {
            if($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }

    // Update menu
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET name = :name, 
                    description = :description, 
                    price = :price, 
                    category = :category, 
                    is_available = :is_available 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitasi input
        $this->name = sanitize($this->name);
        $this->description = sanitize($this->description);
        $this->price = floatval(sanitize($this->price));
        $this->category = sanitize($this->category);
        $this->is_available = $this->is_available ? 1 : 0;
        $this->id = intval(sanitize($this->id));
        
        // Binding parameter
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":is_available", $this->is_available);
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

    // Hapus menu
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
}