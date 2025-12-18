<?php
// install.php
// This script initializes the database tables and triggers.

$host = 'localhost';
$user = 'root';
$pass = 'root';
$dbname = 'hrm_project';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL server.<br>";

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    echo "Database `$dbname` created or already exists.<br>";
    
    $pdo->exec("USE `$dbname`");

    $passwordHash = password_hash('password123', PASSWORD_DEFAULT);
    
    $queries = [
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('Employee', 'HR Staff', 'HR Head') NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "INSERT IGNORE INTO users (username, password, role) VALUES 
        ('admin', '$passwordHash', 'HR Head'),
        ('hrstaff', '$passwordHash', 'HR Staff'),
        ('employee', '$passwordHash', 'Employee')",
        "CREATE TABLE IF NOT EXISTS employees (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_id VARCHAR(20) NOT NULL UNIQUE,
            surname VARCHAR(100) NOT NULL,
            firstname VARCHAR(100) NOT NULL,
            middlename VARCHAR(100),
            name_extension VARCHAR(10),
            date_of_birth DATE NOT NULL,
            place_of_birth VARCHAR(255) NOT NULL,
            sex ENUM('Male', 'Female') NOT NULL,
            civil_status ENUM('Single', 'Married', 'Widowed', 'Separated', 'Other') NOT NULL,
            height DOUBLE,
            weight DOUBLE,
            blood_type VARCHAR(5),
            gsis_id VARCHAR(20),
            pagibig_id VARCHAR(20),
            philhealth_no VARCHAR(20),
            sss_no VARCHAR(20),
            tin_no VARCHAR(20),
            citizenship VARCHAR(50),
            residential_address TEXT,
            residential_zip VARCHAR(10),
            permanent_address TEXT,
            permanent_zip VARCHAR(10),
            telephone_no VARCHAR(50),
            mobile_no VARCHAR(50),
            email_address VARCHAR(100),
            department VARCHAR(100),
            position VARCHAR(100),
            date_hired DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            birth_city VARCHAR(100) DEFAULT 'N/A',
            birth_province VARCHAR(100) DEFAULT 'N/A',
            birth_country VARCHAR(100) DEFAULT 'N/A',
            contactno VARCHAR(50) DEFAULT 'N/A',
            
            res_barangay_address VARCHAR(100) DEFAULT 'N/A',
            res_city VARCHAR(100) DEFAULT 'N/A',
            res_municipality VARCHAR(100) DEFAULT 'N/A',
            res_province VARCHAR(100) DEFAULT 'N/A',
            res_zipcode VARCHAR(20) DEFAULT 'N/A',
            
            perm_barangay_address VARCHAR(100) DEFAULT 'N/A',
            perm_city VARCHAR(100) DEFAULT 'N/A',
            perm_municipality VARCHAR(100) DEFAULT 'N/A',
            perm_province VARCHAR(100) DEFAULT 'N/A',
            perm_zipcode VARCHAR(20) DEFAULT 'N/A',
            
            Q34A TINYINT DEFAULT 0,
            Q34B TINYINT DEFAULT 0,
            Q35a TINYINT DEFAULT 0,
            Q35b TINYINT DEFAULT 0,
            Q36 VARCHAR(10) DEFAULT 'No',
            Q37 TINYINT DEFAULT 0,
            Q38a TINYINT DEFAULT 0,
            Q38b TINYINT DEFAULT 0,
            Q39a TINYINT DEFAULT 0,
            Q39b TINYINT DEFAULT 0,
            Q40a TINYINT DEFAULT 0,
            Q40b TINYINT DEFAULT 0,
            Q40c TINYINT DEFAULT 0
        )",
        "CREATE TABLE IF NOT EXISTS work_experience (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_id INT NOT NULL,
            date_from DATE,
            date_to DATE,
            position_title VARCHAR(100),
            department_agency VARCHAR(100),
            monthly_salary DECIMAL(10, 2),
            salary_grade VARCHAR(10),
            status_of_appointment VARCHAR(50),
            gov_service ENUM('Yes', 'No'),
            FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS trainings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_id INT NOT NULL,
            title VARCHAR(255),
            date_from DATE,
            date_to DATE,
            number_of_hours INT,
            type_of_ld VARCHAR(100),
            conducted_by VARCHAR(255),
            FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS eligibilities (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_id INT NOT NULL,
            eligibility_name VARCHAR(255),
            rating VARCHAR(20),
            date_of_examination DATE,
            place_of_examination VARCHAR(255),
            license_number VARCHAR(50),
            date_of_validity DATE,
            FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS activity_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            table_name VARCHAR(50) NOT NULL,
            action_type ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
            record_id INT NOT NULL,
            user_id INT, 
            username VARCHAR(50),
            details TEXT,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    ];

    foreach ($queries as $query) {
        $pdo->exec($query);
    }
    echo "Tables created successfully.<br>";

    $triggers = [
        "DROP TRIGGER IF EXISTS after_employee_insert",
        "CREATE TRIGGER after_employee_insert AFTER INSERT ON employees FOR EACH ROW BEGIN INSERT INTO activity_logs (table_name, action_type, record_id, user_id, username, details) VALUES ('employees', 'INSERT', NEW.id, @current_user_id, @current_username, CONCAT('Added employee: ', NEW.firstname, ' ', NEW.surname)); END",
        
        "DROP TRIGGER IF EXISTS after_employee_update",
        "CREATE TRIGGER after_employee_update AFTER UPDATE ON employees FOR EACH ROW BEGIN INSERT INTO activity_logs (table_name, action_type, record_id, user_id, username, details) VALUES ('employees', 'UPDATE', NEW.id, @current_user_id, @current_username, CONCAT('Updated employee: ', NEW.firstname, ' ', NEW.surname)); END",
        
        "DROP TRIGGER IF EXISTS after_employee_delete",
        "CREATE TRIGGER after_employee_delete AFTER DELETE ON employees FOR EACH ROW BEGIN INSERT INTO activity_logs (table_name, action_type, record_id, user_id, username, details) VALUES ('employees', 'DELETE', OLD.id, @current_user_id, @current_username, CONCAT('Deleted employee: ', OLD.firstname, ' ', OLD.surname)); END"
    ];

    foreach ($triggers as $trigger) {
        $pdo->exec($trigger);
    }
    echo "Triggers created successfully.<br>";
    
    echo "<h3 style='color:green'>Installation Complete!</h3>";
    echo "<a href='index.php'>Go to Login Page</a>";

} catch (PDOException $e) {
    echo "<h3 style='color:red'>Installation Failed</h3>";
    echo "Error: " . $e->getMessage();
}
?>