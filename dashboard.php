<?php
require_once 'classes/Database.php';
require_once 'classes/Auth.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$auth->requireLogin();

$role = $auth->getUserRole();
?>
<!DOCTYPE html>
<html>
<head>
    <title>HRMIS Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">HRMIS</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <?php if ($role == 'HR Staff' || $role == 'HR Head'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="views/add_employee.php">Add Employee</a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="views/list_employees.php">Employees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="views/reports.php">Reports</a>
                </li>
            </ul>
            <span class="navbar-text mr-3">
                Welcome, <?php echo $_SESSION['username']; ?> (<?php echo $role; ?>)
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Dashboard</h1>
        <p>Welcome to the Human Resources Management Information System.</p>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Employees</div>
                    <div class="card-body">
                        <h5 class="card-title">Manage Records</h5>
                        <p class="card-text">View, search, and manage employee records.</p>
                        <a href="views/list_employees.php" class="btn btn-light">Go to Employees</a>
                    </div>
                </div>
            </div>
            <?php if ($role == 'HR Staff' || $role == 'HR Head'): ?>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">New Entry</div>
                    <div class="card-body">
                        <h5 class="card-title">Add Employee</h5>
                        <p class="card-text">Add a new employee record (CSC Form 212).</p>
                        <a href="views/add_employee.php" class="btn btn-light">Add Employee</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Reports</div>
                    <div class="card-body">
                        <h5 class="card-title">Generate Reports</h5>
                        <p class="card-text">View summary reports and analytics.</p>
                        <a href="views/reports.php" class="btn btn-light">View Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>