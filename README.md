# HRMIS Project

## Setup Instructions

1.  **Database Setup**:
    *   Open MySQL Workbench or phpMyAdmin.
    *   Run the SQL script located at `c:\xampp\htdocs\hrmis\setup.sql`.
    *   This will create the `hrm_project` database, tables, and default users.

2.  **Access the Application**:
    *   Open your browser and go to `http://localhost/hrmis/`.

3.  **Default Login Credentials**:
    *   **HR Head**: `admin` / `password123`
    *   **HR Staff**: `hrstaff` / `password123`
    *   **Employee**: `employee` / `password123`

## Features Implemented

*   **Authentication**: Role-based login (HR Head, HR Staff, Employee).
*   **Employee Management**:
    *   Add New Employee (CSC Form 212 fields).
    *   List Employees with Search.
    *   View Employee Details.
    *   Edit Employee (HR Staff/Head only).
    *   Delete Employee (HR Staff/Head only).
*   **Reports**:
    *   Employees by Department.
    *   Service Report (Date Hired).
*   **Security**:
    *   Password Hashing.
    *   SQL Injection Protection (Prepared Statements).
    *   Role-Based Access Control.
*   **Database**:
    *   ACID Properties (Transactions used in CRUD).
    *   Activity Logging (Triggers implemented in SQL).
