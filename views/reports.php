<?php
require_once '../classes/Database.php';
require_once '../classes/Auth.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$auth->requireLogin();

// Report 1: Employees by Department
$query_dept = "SELECT department, COUNT(*) as count FROM employees GROUP BY department";
$stmt_dept = $db->prepare($query_dept);
$stmt_dept->execute();

// Report 2: List of all employees (Service Report simplified)
$query_all = "SELECT employee_no, surname, firstname, position, date_hired FROM employees ORDER BY date_hired DESC";
$stmt_all = $db->prepare($query_all);
$stmt_all->execute();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports - HRMIS</title>
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
                <li class="nav-item">
                    <a class="nav-link" href="list_employees.php">Employees</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="reports.php">Reports</a>
                </li>
            </ul>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Reports</h2>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        Employees by Department
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $stmt_dept->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['department'] ? $row['department'] : 'Unassigned'); ?></td>
                                    <td><?php echo $row['count']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        Service Report (Date Hired)
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Date Hired</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $stmt_all->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['surname'] . ', ' . $row['firstname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date_hired']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>