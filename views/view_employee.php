<?php
require_once '../classes/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/Employee.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$auth->requireLogin();

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');

$employee = new Employee($db);
$row = $employee->readOne($id);

if (!$row) {
    die('ERROR: Employee not found.');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Employee - HRMIS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../dashboard.php">HRMIS</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="list_employees.php">Employees</a>
                </li>
            </ul>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <h2>View Employee Details</h2>
        
        <div class="card">
            <div class="card-header">
                Personal Information
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Employee ID</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($row['employee_no']); ?></dd>

                    <dt class="col-sm-3">Name</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($row['surname'] . ', ' . $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['name_extension']); ?></dd>

                    <dt class="col-sm-3">Date of Birth</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($row['date_of_birth']); ?></dd>

                    <dt class="col-sm-3">Place of Birth</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($row['place_of_birth']); ?></dd>

                    <dt class="col-sm-3">Sex</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($row['sex']); ?></dd>

                    <dt class="col-sm-3">Civil Status</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($row['civil_status']); ?></dd>
                    
                    <dt class="col-sm-3">Department</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($row['department']); ?></dd>

                    <dt class="col-sm-3">Position</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($row['position']); ?></dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($row['email_address']); ?></dd>

                    <dt class="col-sm-3">Mobile</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($row['mobile_no']); ?></dd>
                </dl>
            </div>
        </div>
        
        <a href="list_employees.php" class="btn btn-secondary mt-3">Back to List</a>
    </div>
</body>
</html>