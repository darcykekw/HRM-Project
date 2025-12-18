# HRMIS Project

## Overview
This is a Human Resource Management Information System (HRMIS) built with PHP and MySQL. It handles employee records, including personal information, employment details, and generates reports.

## Setup & Installation

1.  **Database Configuration**:
    *   The application uses a MySQL database named `hrm_project`.
    *   Ensure your database server is running and the `hrm_project` database is available.
    *   Database credentials are configured in `config/database.php`.

2.  **Access the Application**:
    *   Open your browser and navigate to `http://localhost/hrmis/`.

## Login Credentials

The system comes with pre-configured accounts for different roles:

| Role | Username | Password | Access Level |
| :--- | :--- | :--- | :--- |
| **HR Head** | `admin` | `password123` | Full Access (Add, Edit, Delete, Reports) |
| **HR Staff** | `hrstaff` | `password123` | Manage Employees (Add, Edit, List) |
| **Employee** | `employee` | `password123` | View Own Details (Restricted) |

## Features

*   **Authentication**: Secure login with role-based access control.
*   **Employee Management**:
    *   **Add Employee**: Comprehensive form based on CSC Form 212.
    *   **List Employees**: Searchable list of all employees.
    *   **View Details**: Full profile view.
    *   **Edit Employee**: Update employee records.
*   **Reports**:
    *   Generate reports by Department.
    *   Service reports based on hiring date.
*   **Technical Highlights**:
    *   **Security**: Password hashing (bcrypt), PDO prepared statements for SQL injection protection.
    *   **Architecture**: Object-Oriented PHP (Classes for Auth, Database, Employee).
    *   **Database**: Normalized schema with `AUTO_INCREMENT` IDs and proper data types.

## Database Schema Adjustments

### 1. Critical Fixes (Original Schema Errors)
These changes were necessary to fix fundamental structural issues in the provided database:

*   **Missing `AUTO_INCREMENT`**:
    *   **Issue**: The original schema lacked `AUTO_INCREMENT` on primary keys (`id`).
    *   **Fix**: Enabled auto-increment to allow new records to be inserted without manual ID assignment.
*   **Referential Integrity (Foreign Keys)**:
    *   **Issue**: Deleting an employee caused database errors or left "orphan" records in child tables.
    *   **Fix**: Added `FOREIGN KEY` constraints with `ON DELETE CASCADE` to tables like `work_experience` and `trainings`. This ensures that when an employee is deleted, their related records are automatically removed.

### 2. Project Enhancements
These changes were made to support the specific features required by the HRMIS project:

*   **CSC Form 212 Compliance**:
    *   **Update**: Added missing columns for detailed addresses (`birth_city`, `res_barangay_address`) and questionnaire items (`Q34A`, `Q34B`, etc.) to match the Personal Data Sheet.
*   **Data Constraints**:
    *   **Update**: Increased `telephone_no` and `mobile_no` column lengths to `VARCHAR(50)` to prevent long numbers from being cut off.
*   **Advanced Activity Logging**:
    *   **Update**: Enhanced database triggers to capture the specific *user* performing the action (passing PHP session data to MySQL), which was not supported by the original simple triggers.

## Utility Scripts

*   **`install.php`**:
    *   **Purpose**: Initializes the database structure.
    *   **Usage**: Run this script (`http://localhost/hrmis/install.php`) to create the `hrm_project` database, tables, and default users. Useful for deployment or resetting the database.
*   **`reset_password.php`**:
    *   **Purpose**: Emergency password reset.
    *   **Usage**: Run this script (`http://localhost/hrmis/reset_password.php`) to reset the `admin` password to `password123`.
