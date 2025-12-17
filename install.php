<?php
// install.php
// This script initializes the database tables and triggers.

$host = 'localhost';
$user = 'root';
$pass = 'root';
$dbname = 'hrm_project';

try {
    // 1. Connect to MySQL Server (without DB name first)
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL server.<br>";

    // 2. Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    echo "Database `$dbname` created or already exists.<br>";
    
    // 3. Select Database
    $pdo->exec("USE `$dbname`");

    // 4. Create Tables
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
            telephone_no VARCHAR(20),
            mobile_no VARCHAR(20),
            email_address VARCHAR(100),
            department VARCHAR(100),
            position VARCHAR(100),
            date_hired DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
            user_id INT,
            action VARCHAR(50),
            description TEXT,
            record_id INT,
            table_name VARCHAR(50),
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    ];

    foreach ($queries as $query) {
        $pdo->exec($query);
    }
    echo "Tables created successfully.<br>";

    // 5. Create Triggers (Drop if exists to avoid errors on re-run)
    $triggers = [
        "DROP TRIGGER IF EXISTS after_employee_insert",
        "CREATE TRIGGER after_employee_insert AFTER INSERT ON employees FOR EACH ROW BEGIN INSERT INTO activity_logs (action, description, record_id, table_name) VALUES ('ADD', CONCAT('Added employee: ', NEW.surname, ', ', NEW.firstname), NEW.id, 'employees'); END",
        
        "DROP TRIGGER IF EXISTS after_employee_update",
        "CREATE TRIGGER after_employee_update AFTER UPDATE ON employees FOR EACH ROW BEGIN INSERT INTO activity_logs (action, description, record_id, table_name) VALUES ('MODIFY', CONCAT('Updated employee: ', NEW.surname, ', ', NEW.firstname), NEW.id, 'employees'); END",
        
        "DROP TRIGGER IF EXISTS after_employee_delete",
        "CREATE TRIGGER after_employee_delete AFTER DELETE ON employees FOR EACH ROW BEGIN INSERT INTO activity_logs (action, description, record_id, table_name) VALUES ('DELETE', CONCAT('Deleted employee ID: ', OLD.employee_id), OLD.id, 'employees'); END"
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