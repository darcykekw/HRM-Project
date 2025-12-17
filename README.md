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

## Recent Updates
*   Fixed database schema inconsistencies (column names and types).
*   Enabled `AUTO_INCREMENT` for employee IDs.
*   Expanded field lengths for phone numbers to accommodate international formats.
*   Resolved "Undefined array key" errors in views.
