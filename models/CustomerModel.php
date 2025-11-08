<?php

class CustomerModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả customers
    public function getAllCustomers()
    {
        try {
            $sql = 'SELECT * FROM customers ORDER BY id DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Lấy customer theo ID
    public function getCustomerById($id)
    {
        try {
            $sql = 'SELECT * FROM customers WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Thêm customer mới
    public function insertCustomer($name, $phone, $email, $address, $history_notes)
    {
        try {
            $sql = 'INSERT INTO customers (name, phone, email, address, history_notes) 
                    VALUES (:name, :phone, :email, :address, :history_notes)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':phone' => $phone,
                ':email' => $email,
                ':address' => $address,
                ':history_notes' => $history_notes
            ]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Cập nhật customer
    public function updateCustomer($id, $name, $phone, $email, $address, $history_notes)
    {
        try {
            $sql = 'UPDATE customers SET 
                    name = :name,
                    phone = :phone,
                    email = :email,
                    address = :address,
                    history_notes = :history_notes
                    WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':name' => $name,
                ':phone' => $phone,
                ':email' => $email,
                ':address' => $address,
                ':history_notes' => $history_notes
            ]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Xóa customer
    public function deleteCustomer($id)
    {
        try {
            $sql = 'DELETE FROM customers WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Tìm kiếm customer
    public function searchCustomers($keyword)
    {
        try {
            $sql = 'SELECT * FROM customers 
                    WHERE name LIKE :keyword 
                    OR phone LIKE :keyword 
                    OR email LIKE :keyword
                    ORDER BY id DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':keyword' => "%$keyword%"]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Lấy lịch sử booking của customer
    public function getCustomerBookings($customer_id)
    {
        try {
            $sql = 'SELECT * FROM bookings WHERE customer_id = :customer_id ORDER BY booking_date DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':customer_id' => $customer_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}

