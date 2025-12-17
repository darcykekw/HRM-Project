<?php
class Employee {
    private $conn;
    private $table_name = "employees";
    public $error;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        try {
            // Set user context for triggers
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['user_id'])) {
                $this->conn->exec("SET @current_user_id = " . $_SESSION['user_id']);
                $this->conn->exec("SET @current_username = '" . $_SESSION['username'] . "'");
            }

            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table_name . " 
                (employee_no, surname, firstname, middlename, name_extension, date_of_birth, place_of_birth, sex, civil_status, height_in_meter, weight_in_kg, blood_type, gsis_id, pagibig_id, philhealth_no, sss_no, tin_no, citizenship, residential_address, residential_zip, permanent_address, permanent_zip, telephone_no, mobile_no, email_address, department, position, date_hired,
                birth_city, birth_province, birth_country, contactno,
                res_barangay_address, res_city, res_municipality, res_province, res_zipcode,
                perm_barangay_address, perm_city, perm_municipality, perm_province, perm_zipcode,
                Q34A, Q34B, Q35a, Q35b, Q36, Q37, Q38a, Q38b, Q39a, Q39b, Q40a, Q40b, Q40c) 
                VALUES 
                (:employee_no, :surname, :firstname, :middlename, :name_extension, :date_of_birth, :place_of_birth, :sex, :civil_status, :height_in_meter, :weight_in_kg, :blood_type, :gsis_id, :pagibig_id, :philhealth_no, :sss_no, :tin_no, :citizenship, :residential_address, :residential_zip, :permanent_address, :permanent_zip, :telephone_no, :mobile_no, :email_address, :department, :position, :date_hired,
                'N/A', 'N/A', 'N/A', 'N/A',
                'N/A', 'N/A', 'N/A', 'N/A', 'N/A',
                'N/A', 'N/A', 'N/A', 'N/A', 'N/A',
                0, 0, 0, 0, 'N/A', 0, 0, 0, 0, 0, 0, 0, 0)";

            $stmt = $this->conn->prepare($query);

            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                $this->error = implode(" ", $stmt->errorInfo());
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function readAll($search = "") {
        $query = "SELECT * FROM " . $this->table_name;
        if ($search) {
            $query .= " WHERE surname LIKE :search OR firstname LIKE :search OR employee_no LIKE :search";
        }
        $query .= " ORDER BY surname ASC";
        
        $stmt = $this->conn->prepare($query);
        if ($search) {
            $searchTerm = "%{$search}%";
            $stmt->bindParam(':search', $searchTerm);
        }
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        try {
            // Set user context for triggers
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['user_id'])) {
                $this->conn->exec("SET @current_user_id = " . $_SESSION['user_id']);
                $this->conn->exec("SET @current_username = '" . $_SESSION['username'] . "'");
            }

            $this->conn->beginTransaction();
            
            $fields = [];
            foreach ($data as $key => $value) {
                if ($key != 'id' && $key != 'employee_no') {
                    $fields[] = "$key = :$key";
                }
            }
            $query = "UPDATE " . $this->table_name . " SET " . implode(', ', $fields) . " WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            foreach ($data as $key => $value) {
                if ($key != 'id' && $key != 'employee_no') {
                    $stmt->bindValue(":$key", $value);
                }
            }
            $stmt->bindValue(':id', $id);

            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function delete($id) {
        try {
            // Set user context for triggers
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['user_id'])) {
                $this->conn->exec("SET @current_user_id = " . $_SESSION['user_id']);
                $this->conn->exec("SET @current_username = '" . $_SESSION['username'] . "'");
            }

            $this->conn->beginTransaction();
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>