<?php
class DBhelper
{
    private $host = 'localhost';
    private $db_name = 'groupassignment';
    private $db_user = 'root';
    private $db_password = '';
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->db_user, $this->db_password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getStaffCount()
    {
        $result = $this->conn->query("SELECT COUNT(*) AS staff_count FROM staff");
        $row = $result->fetch_assoc();
        return $row['staff_count'];
    }

    public function getCustomerCount()
    {
        $result = $this->conn->query("SELECT COUNT(*) AS customer_count FROM customer");
        $row = $result->fetch_assoc();
        return $row['customer_count'];
    }

    public function validateStaffLogin($username, $password)
    {
        $stmt = $this->conn->prepare("SELECT staffpassword FROM staff WHERE staffusername = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($stored_password);
            $stmt->fetch();
            if ($password === $stored_password) {
                return true;
            }
        }
        return false;
    }

    public function getContactMessages()
    {
        $stmt = $this->conn->prepare("
            SELECT c.id, c.customerid, cu.customerusername, cu.customeremail, c.subject, c.message, c.date
            FROM contact c
            JOIN customer cu ON c.customerid = cu.customerid
            WHERE c.status = 0
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $messages;
    }

    public function getAllContactMessages()
    {
        $stmt = $this->conn->prepare("
            SELECT c.id, c.customerid, cu.customerusername, cu.customeremail, c.subject, c.message, c.date, c.status
            FROM contact c
            JOIN customer cu ON c.customerid = cu.customerid
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $messages;
    }

    public function countUnreadMessages()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS unread_count FROM contact WHERE status = 0");
        $stmt->execute();
        $stmt->bind_result($unread_count);
        $stmt->fetch();
        $stmt->close();
        return $unread_count;
    }

    public function updateMessageStatus($id, $status)
    {
        $stmt = $this->conn->prepare("UPDATE contact SET status = ? WHERE id = ?");
        $stmt->bind_param("ii", $status, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function updateStaffUsername($currentUsername, $newUsername)
    {
        $stmt = $this->conn->prepare("UPDATE staff SET staffusername = ? WHERE staffusername = ?");
        $stmt->bind_param("ss", $newUsername, $currentUsername);
        $stmt->execute();
        $stmt->close();
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function updateStaffPassword($username, $newPassword)
    {
        $stmt = $this->conn->prepare("UPDATE staff SET staffpassword = ? WHERE staffusername = ?");
        $stmt->bind_param("ss", $newPassword, $username);
        $stmt->execute();
        $stmt->close();
    }

    public function getAllStaff()
    {
        $stmt = $this->conn->prepare("SELECT staffid, staffusername FROM staff");
        $stmt->execute();
        $result = $stmt->get_result();
        $staffList = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $staffList;
    }

    public function staffUsernameExists($username)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM staff WHERE staffusername = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    public function insertNewStaff($username, $password)
    {
        $stmt = $this->conn->prepare("INSERT INTO staff (staffusername, staffpassword) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->close();
    }

    public function getAllCustomers()
    {
        $stmt = $this->conn->prepare("SELECT customerid, customerusername, customeremail FROM customer");
        $stmt->execute();
        $result = $stmt->get_result();
        $customers = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $customers;
    }

    public function customerExists($username, $email)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM customer WHERE customerusername = ? OR customeremail = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    public function customerExistsById($customerId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM customer WHERE customerid = ?");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    public function insertNewCustomer($username, $email)
    {
        $hashedPassword = password_hash('123', PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO customer (customerusername, customeremail, customerpassword) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        $stmt->execute();
        $stmt->close();
    }

    public function updateCustomer($id, $username, $email)
    {
        $stmt = $this->conn->prepare("UPDATE customer SET customerusername = ?, customeremail = ? WHERE customerid = ?");
        $stmt->bind_param("ssi", $username, $email, $id);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows > 0;
    }

    public function deleteCustomer($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM customer WHERE customerid = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows > 0;
    }

    public function customerUsernameExistsForOthers($currentUserId, $username)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM customer WHERE customerusername = ? AND customerid != ?");
        $stmt->bind_param("si", $username, $currentUserId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    public function customerEmailExistsForOthers($currentUserId, $email)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM customer WHERE customeremail = ? AND customerid != ?");
        $stmt->bind_param("si", $email, $currentUserId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    public function getAllProducts()
    {
        $stmt = $this->conn->prepare("SELECT id, preview_url, product_name, price_per_unit, total_quantity, update_time, weight_unit FROM product");
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $products;
    }

    public function insertProduct($name, $price, $quantity, $unit, $photo)
    {
        $update_time = date('Y-m-d');

        $result = $this->conn->query("SELECT product_name FROM product WHERE product_name = '$name'");
        if ($result->num_rows > 0) {
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO product (product_name, price_per_unit, total_quantity, weight_unit, preview_url, update_time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdisss", $name, $price, $quantity, $unit, $photo, $update_time);
        $stmt->execute();
        $stmt->close();

        return true;
    }

    public function updateProduct($id, $name, $price, $quantity, $unit, $photo)
    {
        $update_time = date('Y-m-d');

        $result = $this->conn->query("SELECT * FROM product WHERE id = $id");
        if ($result->num_rows === 0) {
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE product SET product_name = ?, price_per_unit = ?, total_quantity = ?, weight_unit = ?, preview_url = ?, update_time = ? WHERE id = ?");
        $stmt->bind_param("sdisssi", $name, $price, $quantity, $unit, $photo, $update_time, $id);
        $stmt->execute();
        $stmt->close();

        return true;
    }

    public function deleteProduct($id)
    {
        $result = $this->conn->query("SELECT * FROM product WHERE id = $id");
        if ($result->num_rows === 0) {
            return false;
        }
        $this->conn->query("DELETE FROM product WHERE id = $id");
        return true;
    }

    public function getAllOrders()
    {
        $stmt = $this->conn->prepare("SELECT id, customer_id, product_id, quantity, order_date, status FROM orders");
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $orders;
    }

    public function getProductPriceById($productId)
    {
        $stmt = $this->conn->prepare("SELECT price_per_unit FROM product WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->bind_result($price_per_unit);
        $stmt->fetch();
        $stmt->close();

        return $price_per_unit;
    }

    public function updateOrderStatus($orderId, $status)
    {
        $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("ii", $status, $orderId);
        $stmt->execute();
        $stmt->close();
    }

    public function getTotalOrders()
    {
        $query = "SELECT COUNT(*) AS total_orders FROM orders";
        $result = $this->conn->query($query);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total_orders'];
        } else {
            return 0;
        }
    }
}
